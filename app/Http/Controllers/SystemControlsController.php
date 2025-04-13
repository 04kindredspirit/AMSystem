<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\SectionList;
use App\Models\SchoolYearList;
use App\Models\Payment;
use App\Models\StudentList;

class SystemControlsController extends Controller
{
    public function academicAdvancement()
    {
        $sectionData['data'] = SectionList::orderBy('created_at', 'ASC')->select('id', 'section_name')->get();

        // fetch the most recent payment record for each student
        $latestPayments = Payment::select('studentLrn', DB::raw('MAX(id) as latest_payment_id'))
            ->groupBy('studentLrn')
            ->get();

        // fetch students whose latest payment has a zero balance
        $studentsWithZeroBalance = Payment::whereIn('id', $latestPayments->pluck('latest_payment_id'))
            ->where('balance', 0)
            ->with('student') // Ensure the 'student' relationship is loaded
            ->get();

        // fetch active school years
        $schoolyear = SchoolYearList::where('status', 'Active')
            ->orderBy('created_at', 'ASC')
            ->get();

        return view('SystemControls.AcademicAdvancement', [
            'sectionData' => $sectionData,
            'studentsWithZeroBalance' => $studentsWithZeroBalance,
            'schoolyear' => $schoolyear,
        ]);
    }

    public function updateAcademicAdvancement(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student_lists,id',
            'from_section' => 'required|string',
            'transfer_to_section' => 'required|string',
            'school_year' => 'required|string',
            'tuition_amount' => 'required|numeric',
            'tuition_discount' => 'required|string',
            'custom_discount' => 'nullable|numeric|min:0|max:100',
        ]);

        $student = StudentList::findOrFail($request->student_id);

        // update details
        $student->studentSection = $request->transfer_to_section;
        $student->school_year = $request->school_year;
        $student->studentTuition_amount = $request->tuition_amount;
        $student->studentTuition_discount = $request->tuition_discount;

        // calculation of discount
        $discountPercentage = $request->custom_discount ?? 0;
        if ($request->tuition_discount === 'Academic Discount') {
            $discountPercentage = 10;
        } elseif ($request->tuition_discount === 'Sibling Discount') {
            $discountPercentage = 7;
        } elseif ($request->tuition_discount === 'Parent Discount') {
            $discountPercentage = 7;
        } elseif ($request->tuition_discount === 'Early Discount') {
            $discountPercentage = 5;
        }
            $discountedAmount = $request->tuition_amount - ($request->tuition_amount * ($discountPercentage / 100));
            $student->discountedTuition_amount = $discountedAmount;

            $student->save();

            // create new record for balance adjustment
            $studentFullname = trim(
            $student->studentFirst_name . ' ' .
            $student->studentMiddle_name . ' ' .
            $student->studentLast_name . ' ' .
            $student->studentName_ext
        );

        Payment::create([
            'studentLrn' => $student->studentLRN,
            'studentPayment_section' => $request->from_section,
            'studentFullname' => $studentFullname,
            'paymentDate' => now(),
            'paymentAmount' => 0,
            'paymentDiscountType' => $request->tuition_discount,
            'balance' => $discountedAmount,
            'record_type' => 'balance_adjustment',
        ]);

        return redirect()->route('SystemControls.AcademicAdvancement')->with('success', 'Student advanced successfully!');
    }

    // all access-security controller
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('SystemControls.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('SystemControls.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return redirect()->route('SystemControls.access-security')->with('error', 'User record not found.');
        }

        $data = [
            'role' => $request->role,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('SystemControls.access-security')->with('success', 'User record updated successfully.');
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'paymentDate' => 'required|date',
            'paymentReceipt' => 'required|string',
            'paymentFname' => 'required|string',
            'paymentLrn' => 'required|string',
            'paymentAmount' => 'required|numeric',
            'paymentPeriod' => 'required|string',
        ]);

        $student = StudentList::where('studentLRN', $request->paymentLrn)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        // handle the pay remaining balance option
        if ($request->paymentPeriod === 'Remaining Balance') {
            $paymentAmount = $student->balance;
        } else {
            $paymentAmount = $request->paymentAmount;
        }

        // create new payment record
        Payment::create([
            'studentLrn' => $student->studentLRN,
            'studentFullname' => $request->paymentFname,
            'paymentDate' => $request->paymentDate,
            'paymentOR' => $request->paymentReceipt,
            'paymentAmount' => $paymentAmount,
            'paymentPeriod' => $request->paymentPeriod,
            'balance' => $student->balance - $paymentAmount,
            'record_type' => 'payment',
        ]);

        // uupdate balance
        $student->balance -= $paymentAmount;
        $student->save();

        return redirect()->back()->with('success', 'Payment processed successfully!');
    }

    // all section controller
    public function addSection()
    {
        $sections = SectionList::orderBy('created_at', 'ASC')->get();

        return view('SystemControls.section', compact('sections'));
    }

    public function createSection(Request $request)
    {
        $request->validate([
            'sectionLevel' => 'required|string',
            'sectionName' => 'required|string',
        ]);

        $sections = new SectionList();
        $sections->section_level = $request->sectionLevel;
        $sections->section_name = $request->sectionName;

        $sections->save();

        return redirect()->route('SystemControls.section')->with('success', 'New Section added successfully.');
    }

    public function updateSection(Request $request, $id)
    {
        $request->validate([
            'sectionLevel' => 'required|string',
            'sectionName' => 'required|string',
        ]);

        $section = SectionList::findOrFail($id);
        $section->section_level = $request->sectionLevel;
        $section->section_name = $request->sectionName;
        $section->save();

        return redirect()->route('SystemControls.section')->with('success', 'Section updated successfully.');
    }

    public function deleteSection($id)
    {
        $section = SectionList::findOrFail($id);
        $section->delete();

        return redirect()->route('SystemControls.section')->with('success', 'Section deleted successfully.');
    }

    // all school year controller
    public function schoolYear()
    {
        $schoolyear = SchoolYearList::orderBy('created_at', 'ASC')->get();

        return view('SystemControls.schoolyear', compact('schoolyear'));
    }

    public function createSY(Request $request)
    {
        $request->validate([
            'schoolYear' => 'required|string',
            'syStatus' => 'required|string',
        ]);

        $schoolyear = new SchoolYearList();
        $schoolyear->school_year = $request->schoolYear;
        $schoolyear->status = $request->syStatus;

        $schoolyear->save();

        return redirect()->route('SystemControls.schoolyear')->with('success', 'New School Year added successfully.');
    }

    public function updateSY(Request $request, $id)
    {
        $request->validate([
            'schoolYear' => 'required|string',
            'syStatus' => 'required|string',
        ]);

        $schoolyear = SchoolYearList::findOrFail($id);
        $schoolyear->school_year = $request->schoolYear;
        $schoolyear->status = $request->syStatus;
        $schoolyear->save();

        return redirect()->route('SystemControls.schoolyear')->with('success', 'School Year updated successfully.');
    }

    public function deleteSY($id)
    {
        $schoolyear = SchoolYearList::findOrFail($id);
        $schoolyear->delete();

        return redirect()->route('SystemControls.schoolyear')->with('success', 'School Year deleted successfully.');
    }
}
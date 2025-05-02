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
use App\Models\DiscountList;

class SystemControlsController extends Controller
{
    public function academicAdvancement()
    {
        $section = SectionList::where('status', 'Active')
                                    ->orderBy('created_at', 'ASC')
                                    ->get();

        // fetch the most recent payment record for each student
        $latestPayments = Payment::select('studentLrn', DB::raw('MAX(id) as latest_payment_id'))
            ->groupBy('studentLrn')
            ->get();

        // fetch students whose latest payment has a zero balance
        $studentsWithZeroBalance = Payment::whereIn('id', $latestPayments->pluck('latest_payment_id'))
            ->where('balance', 0)
            ->with('student') // ensure the 'student' relationship is loaded
            ->get();

        // fetch active school years
        $schoolyear = SchoolYearList::where('status', 'Active')
                                        ->orderBy('created_at', 'ASC')
                                        ->get();

        $activeDiscounts = DiscountList::where('status', 'Active')
                                    ->orderBy('discount_type')
                                    ->get();

        return view('SystemControls.AcademicAdvancement', [
            'section' => $section,
            'studentsWithZeroBalance' => $studentsWithZeroBalance,
            'schoolyear' => $schoolyear,
            'activeDiscounts' => $activeDiscounts,
        ]);
    }

    public function updateAcademicAdvancement(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:student_lists,id',
        'student_name' => 'required|string',
        'from_section' => 'required|string',
        'transfer_to_section' => 'required|string',
        'school_year' => 'required|string',
        'tuition_amount' => 'required|numeric',
        'studentDisc' => 'nullable|string', // Changed from tuition_discount to match your form field name
        'custom_discount' => 'nullable|numeric|min:0|max:100',
    ]);

    $student = StudentList::findOrFail($request->student_id);

    // Get the discount type from the form (using studentDisc instead of tuition_discount)
    $discountType = $request->studentDisc;
    $customDiscount = $request->custom_discount;
    $tuitionAmount = $request->tuition_amount;

    // Calculate discount percentage
    $discountPercentage = 0;

    if ($discountType === 'Custom Discount' && $customDiscount !== null) {
        $discountPercentage = $customDiscount;
    } elseif ($discountType && $discountType !== 'No Discount') {
        $discount = DiscountList::where('discount_type', $discountType)
                                ->where('status', 'Active')
                                ->first();
        $discountPercentage = $discount ? $discount->percentage : 0;
    }

    // Calculate discounted amount
    $discountedAmount = $tuitionAmount - ($tuitionAmount * ($discountPercentage / 100));

    // Update student information
    $student->update([
        'studentSection' => $request->transfer_to_section,
        'school_year' => $request->school_year,
        'studentTuition_amount' => $tuitionAmount,
        'studentTuition_discount' => $discountType,
        'discountPercentage' => $discountPercentage,
        'discountedTuition_amount' => $discountedAmount,
    ]);

    // Create payment record for balance adjustment
    $studentFullname = trim(
        $student->studentFirst_name . ' ' .
        $student->studentMiddle_name . ' ' .
        $student->studentLast_name . ' ' .
        $student->studentName_ext
    );

    Payment::create([
        'user_id' => auth()->id(),
        'studentLrn' => $student->studentLRN,
        'studentPayment_section' => $request->from_section,
        'studentFullname' => $studentFullname,
        'paymentDate' => now(),
        'paymentOR' => $request->academic_or,
        'paymentAmount' => 0,
        'paymentDiscountType' => $discountType,
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
            'syStatus' => 'required|string',
        ]);

        $section = SectionList::findOrFail($id);
        $section->section_level = $request->sectionLevel;
        $section->section_name = $request->sectionName;
        $section->status = $request->syStatus;
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

    // all discounts controller
    public function discounts()
    {
        $discounts = DiscountList::orderBy('created_at', 'ASC')->get();
        return view('SystemControls.discounts', compact('discounts'));
    }

    public function storeDiscount(Request $request)
    {
        $request->validate([
            'discount_type' => 'required|string|max:255|unique:discount_lists,discount_type',
            'percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:Active,Inactive',
        ]);

        DiscountList::create($request->only(['discount_type', 'percentage', 'status']));

        return redirect()->route('SystemControls.discounts')->with('success', 'Discount created successfully.');
    }

    public function updateDiscount(Request $request, $id)
    {
        $request->validate([
            'discount_type' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:Active,Inactive',
        ]);

        $discount = DiscountList::findOrFail($id);
        $discount->update($request->only(['discount_type', 'percentage', 'status']));

        return redirect()->route('SystemControls.discounts')->with('success', 'Discount updated successfully.');
    }
}
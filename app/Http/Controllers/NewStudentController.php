<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\StudentList;
use App\Models\SectionList;
use App\Models\SchoolYearList;
use App\Models\DiscountList;

class NewStudentController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index()
    {
        $section = SectionList::where('status', 'Active')
                                    ->orderBy('created_at', 'ASC')
                                    ->get();

        $students = StudentList::orderBy('created_at', 'DESC')->get(['id', 'studentLRN', 'studentFirst_name', 'studentMiddle_name', 'studentLast_name', 'studentName_ext', 'studentSection', 'image']) ?? collect();

        $linkedStudents = DB::table('student_links')
            ->join('student_lists', 'student_links.student_id', '=', 'student_lists.id')
            ->select('student_links.group_id', 'student_lists.id', 'student_lists.studentFirst_name', 'student_lists.studentLast_name')
            ->get();

        $groupedStudents = [];
        foreach ($linkedStudents as $linkedStudent) {
            $groupedStudents[$linkedStudent->group_id][] = $linkedStudent->studentFirst_name . ' ' . $linkedStudent->studentLast_name;
        }

        $schoolyear = SchoolYearList::where('status', 'Active')
                                    ->orderBy('created_at', 'ASC')
                                    ->get();

        $activeDiscounts = DiscountList::where('status', 'Active')
                                    ->orderBy('discount_type')
                                    ->get();

        return view('ManageStudent/StudentDirectory', compact('students', 'groupedStudents', 'section', 'schoolyear', 'activeDiscounts'));
    }

    public function save(Request $request)
    {
        // required inputs validation
        $request->validate([
            'link_to_student' => 'required|string',
            'studentLrn' => 'required|string|unique:student_lists,studentLRN',
            'studentSection' => 'required|string',
            'school_year' => 'required|string',
            'studentFname' => 'required|string',
            'studentLname' => 'required|string',
            'studentGender' => 'required|string',
            'studentBday' => 'required|date',
            'studentBo' => 'required|string',
            'studentAdd' => 'required|string',
            'studentDisc' => 'nullable|string',
            'tuitionAmount' => 'required|numeric',
            'custom_discount' => 'nullable|numeric|min:0|max:100',
        ]);

        // save the new student
        $nstudent = new StudentList();
        $nstudent->studentLRN = $request->studentLrn;
        $nstudent->studentSection = $request->studentSection;
        $nstudent->school_year = $request->school_year;
        $nstudent->studentName_ext = $request->studentNameExt; 
        $nstudent->studentFirst_name = $request->studentFname;
        $nstudent->studentMiddle_name = $request->studentMname;
        $nstudent->studentLast_name = $request->studentLname;
        $nstudent->studentGender = $request->studentGender;
        $nstudent->studentBirthdate = $request->studentBday;
        $nstudent->studentBirthorder = $request->studentBo;
        $nstudent->studentAddress = $request->studentAdd;
        $nstudent->studentHobby = $request->studentHobbies;
        $nstudent->studentFavorite = $request->studentFavorites;
        
        // calculation for discount
        $tuitionAmount = $request->tuitionAmount;
        $discountType = $request->studentDisc;
        $customDiscount = $request->custom_discount;

        $discountPercentage = 0;

        if ($discountType === 'Custom Discount' && $customDiscount !== null) {
            $discountPercentage = $customDiscount;
        } elseif ($discountType && $discountType !== 'No Discount') {
            $discount = DiscountList::where('discount_type', $discountType)
                                ->where('status', 'Active')
                                ->first();
            $discountPercentage = $discount ? $discount->percentage : 0;
        }

        $discountedAmount = $tuitionAmount - ($tuitionAmount * ($discountPercentage / 100));

        $nstudent->studentTuition_amount = $tuitionAmount;
        $nstudent->studentTuition_discount = $discountType;
        $nstudent->discountPercentage = $discountPercentage;
        $nstudent->discountedTuition_amount = $discountedAmount;

        $nstudent->save();

        // handle the linking of students
        if ($request->has('link_to_student')) {
            $linkedStudentId = $request->link_to_student;

            $existingGroup = DB::table('student_links')
                ->where('student_id', $linkedStudentId)
                ->first();

            if ($existingGroup) {
                $group_id = $existingGroup->group_id;
            } else {
                $group_id = $linkedStudentId;
            }

            DB::table('student_links')->insert([
                'student_id' => $nstudent->id,
                'group_id' => $group_id,
            ]);

            $existingLink = DB::table('student_links')
                ->where('student_id', $linkedStudentId)
                ->where('group_id', $group_id)
                ->first();

            if (!$existingLink) {
                DB::table('student_links')->insert([
                    'student_id' => $linkedStudentId,
                    'group_id' => $group_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $parentLinks = DB::table('parent_student_links')
                ->where('student_id', $linkedStudentId)
                ->get();

            foreach ($parentLinks as $parentLink) {
                DB::table('parent_student_links')->insert([
                    'parent_id' => $parentLink->parent_id,
                    'student_id' => $nstudent->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        return redirect()->route('ManageStudent/StudentDirectory')->with('success', 'Student data saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $students = StudentList::findOrFail($id);
        $payments = $students->payments;

        // Fetch Siblings
        $group = DB::table('student_links')->where('student_id', $students->id)->first();
        $siblings = collect();
        if ($group) {
            $siblings = StudentList::join('student_links', 'student_lists.id', '=', 'student_links.student_id')
                ->where('student_links.group_id', $group->group_id)
                ->where('student_lists.id', '!=', $students->id)
                ->select('student_lists.*')
                ->get();
        }

        // Fetch Parents
        $parents = DB::table('parent_student_links')
            ->join('parent_lists', 'parent_student_links.parent_id', '=', 'parent_lists.id')
            ->where('parent_student_links.student_id', $students->id)
            ->get(['parent_lists.*']);

        return view('ManageStudent.show', compact('students', 'payments', 'siblings', 'parents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $students = StudentList::findOrFail($id);
        $activeDiscounts = DiscountList::where('status', 'Active')
                                 ->orderBy('discount_type')
                                 ->get();

        $section = SectionList::all();

        return view('ManageStudent.edit', compact('students', 'activeDiscounts', 'section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = StudentList::findOrFail($id);

        $tuitionAmount = $request->studentTuition_amount;
        $discountType = $request->studentTuition_discount;
        $customDiscount = $request->custom_discount;

        // discount calculation
        $discountPercentage = 0;
        if ($customDiscount !== null && $discountType === 'Custom Discount') {
            $discountPercentage = $customDiscount;
        } elseif ($discountType && $discountType !== 'No Discount') {
            $discount = DiscountList::where('discount_type', $discountType)
                                ->where('status', 'Active')
                                ->first();
            $discountPercentage = $discount ? $discount->percentage : 0;
        }

        $discountedAmount = $tuitionAmount - ($tuitionAmount * ($discountPercentage / 100));

        // update student infos
        $student->update([
            'studentLRN' => $request->studentLRN,
            'studentSection' => $request->studentSection,
            'studentName_ext' => $request->studentName_ext,
            'studentFirst_name' => $request->studentFirst_name,
            'studentMiddle_name' => $request->studentMiddle_name,
            'studentLast_name' => $request->studentLast_name,
            'studentGender' => $request->studentGender,
            'studentBirthdate' => $request->studentBirthdate,
            'studentBirthorder' => $request->studentBirthorder,
            'studentAddress' => $request->studentAddress,
            'studentHobby' => $request->studentHobby,
            'studentFavorite' => $request->studentFavorite,
            'studentTuition_amount' => $tuitionAmount,
            'discountedTuition_amount' => $discountedAmount,
            'studentTuition_discount' => $discountType,
            'discountPercentage' => $discountPercentage,
        ]);

        return redirect()->route('ManageStudent/StudentDirectory')->with('success', 'Student record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $students = StudentList::find($id);

        if (!$students) {
            return redirect()->route('ManageStudent/StudentDirectory')->with('error', 'Student record not found.');
        }

        $students->delete();

        return redirect()->route('ManageStudent/StudentDirectory')->with('success', 'Student record removed successfully.');
    }

    public function studentUploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('../../uploads/student_images'), $imageName);

            // find student and update path
            $students = StudentList::findOrFail($request->student_id);
            if ($students) {
                $students->image = '../../uploads/student_images/' . $imageName;
                $students->save();

                return response()->json(['success' => true, 'imageUrl' => asset($students->image)]);
            }
        }

        return response()->json(['success' => false]);
    }

}

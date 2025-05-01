<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ParentList;
use App\Models\StudentList;
use App\Models\SectionList;
use App\Models\SchoolYearList;
use App\Models\DiscountList;

class AddStudentAndParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $section = SectionList::where('status', 'Active')
                                    ->orderBy('created_at', 'ASC')
                                    ->get();

        $schoolyear = SchoolYearList::where('status', 'Active')
                                    ->orderBy('created_at', 'ASC')
                                    ->get();
        
        $activeDiscounts = DiscountList::where('status', 'Active')
                                    ->orderBy('discount_type')
                                    ->get();

        return view('ManageStudent/addstudent', [
            'section' => $section,
            'schoolyear' => $schoolyear,
            'activeDiscounts' => $activeDiscounts,
        ]);
    }

    public function store(Request $request)
    {
        // inputs validation
        $request->validate([
            'parent_relationship_to_student' => 'required|string',
            'parent_first_name' => 'required|string',
            'parent_last_name' => 'required|string',
            'parent_birthdate' => 'required|date',
            'parent_educational_attainment' => 'required|string',
            'parent_occupation' => 'required|string',
            'parent_mobile_number' => 'required|digits:11',
            'parent_email' => 'required|email|unique:parent_lists,parentEmail',
            'parent_address' => 'required|string',
            'student_LRN' => 'required|string|unique:student_lists,studentLRN',
            'student_section' => 'required|string',
            'school_year' => 'required|string',
            'student_first_name' => 'required|string',
            'student_last_name' => 'required|string',
            'student_gender' => 'required|string',
            'student_birthdate' => 'required|date',
            'student_address' => 'required|string',
            'tuition_amount' => 'required|numeric',
            'tuition_discount' => 'nullable|string',
            'custom_discount' => 'nullable|numeric|min:0|max:100',
        ]);

        // save parent information
        $parent = new ParentList();
        $parent->parentRelationship_to_student = $request->parent_relationship_to_student;
        $parent->parentFirst_name = $request->parent_first_name;
        $parent->parentMiddle_name = $request->parent_middle_name;
        $parent->parentLast_name = $request->parent_last_name;
        $parent->parentBirthdate = $request->parent_birthdate;
        $parent->parentEducational_attainment = $request->parent_educational_attainment;
        $parent->parentOccupation = $request->parent_occupation;
        $parent->parentMobile_number = $request->parent_mobile_number;
        $parent->parentEmail = $request->parent_email;
        $parent->parentAddress = $request->parent_address;

        // save parent info first
        $parent->save();

        // save student information
        $student = new StudentList();
        $student->studentLRN = $request->student_LRN;
        $student->studentSection = $request->student_section;
        $student->school_year = $request->school_year;
        $student->studentName_ext = $request->student_name_ext;
        $student->studentFirst_name = $request->student_first_name;
        $student->studentMiddle_name = $request->student_middle_name;
        $student->studentLast_name = $request->student_last_name;
        $student->studentGender = $request->student_gender;
        $student->studentBirthdate = $request->student_birthdate;
        $student->studentBirthorder = $request->student_birthorder;
        $student->studentAddress = $request->student_address;
        $student->studentHobby = $request->student_hobbies;
        $student->studentFavorite = $request->student_favorite;

        // calculate discounted tuition amount
        $tuitionAmount = $request->tuition_amount;
        $discountType = $request->tuition_discount;
        $customDiscount = $request->custom_discount;

        $discountPercentage = 0;

        if ($customDiscount !== null) {
            $discountPercentage = $customDiscount;
        } else {
            switch ($discountType) {
                case 'Academic Discount':
                    $discountPercentage = 10;
                    break;
                case 'Sibling Discount':
                    $discountPercentage = 7;
                    break;
                case 'Parent Discount':
                    $discountPercentage = 7;
                    break;
                case 'Early Discount':
                    $discountPercentage = 5;
                    break;
                default:
                    $discountPercentage = 0;
                    break;
            }
        }

        $discountedAmount = $tuitionAmount - ($tuitionAmount * ($discountPercentage / 100));
        
        // save tuition information
        $student->studentTuition_amount = $request->tuition_amount;
        $student->studentTuition_discount = $request->tuition_discount;
        $student->discountedTuition_amount = $discountedAmount;

        // save student info
        $student->save();

        DB::table('parent_student_links')->insert([
            'parent_id' => $parent->id,
            'student_id' => $student->id,
        ]);

        return redirect()->route('ManageStudent/StudentDirectory')->with('success', 'Parent and Student data saved successfully!');
    }
}

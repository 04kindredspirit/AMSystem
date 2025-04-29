<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ParentList;
use App\Models\StudentList;

class NewParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parents = ParentList::orderBy('created_at', 'DESC')->get();

        $students = StudentList::orderBy('created_at', 'DESC')->get(['id', 'studentFirst_name', 'studentLast_name']);

        $groupedStudents = DB::table('student_links')
            ->join('student_lists', 'student_links.student_id', '=', 'student_lists.id')
            ->select('student_links.group_id', 'student_lists.id', 'student_lists.studentFirst_name', 'student_lists.studentLast_name')
            ->get()
            ->groupBy('group_id');

        $groupedStudentsMap = [];
        foreach ($groupedStudents as $group_id => $group) {
            $groupedStudentsMap[$group_id] = $group;
        }

        return view('ParentDirectory.parentlist', compact('parents', 'students', 'groupedStudents', 'groupedStudentsMap'));
    }

        /**
         * Show the form for creating a new resource.
         */
        public function create(Request $request)
    {
        // inputs validation
        $request->validate([
            'student_id' => 'required|array',
            'student_id.*' => 'string',
            'pFirstn' => 'required|string',
            'pLastn' => 'required|string',
            'pBirthd' => 'required|date',
            'pHighestEd' => 'required|string',
            'pOccupation' => 'required|string',
            'pMobilen' => 'required|numeric',
            'pEmail' => 'required|email',
            'pAddress' => 'required|string',
        ]);

        // save parent info
        $parent = new ParentList();
        $parent->parentRelationship_to_student = $request->linkToStudent;
        $parent->parentFirst_name = $request->pFirstn;
        $parent->parentMiddle_name = $request->pMiddlen;
        $parent->parentLast_name = $request->pLastn;
        $parent->parentBirthdate = $request->pBirthd;
        $parent->parentEducational_attainment = $request->pHighestEd;
        $parent->parentOccupation = $request->pOccupation;
        $parent->parentMobile_number = $request->pMobilen;
        $parent->parentEmail = $request->pEmail;
        $parent->parentAddress = $request->pAddress;
        $parent->save();

    // student linking
    if ($request->has('student_id')) {
        foreach ($request->student_id as $studentValue) {
            if (str_starts_with($studentValue, 'group_')) {
                $group_id = str_replace('group_', '', $studentValue);

                $studentsInGroup = DB::table('student_links')
                    ->where('group_id', $group_id)
                    ->pluck('student_id');

                // link parent into group of students
                foreach ($studentsInGroup as $studentId) {
                    DB::table('parent_student_links')->insert([
                        'parent_id' => $parent->id,
                        'student_id' => $studentId,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            } elseif (str_starts_with($studentValue, 'student_')) {
                $studentId = str_replace('student_', '', $studentValue);

                // link parent to single student
                DB::table('parent_student_links')->insert([
                    'parent_id' => $parent->id,
                    'student_id' => $studentId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

    return redirect()->route('ParentDirectory')->with('success', 'Parent data saved successfully!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $parent = ParentList::with('students')->findOrFail($id);

        return view('ParentDirectory.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $parent = ParentList::findOrFail($id);

        return view('ParentDirectory.edit', compact('parent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $parent = ParentList::findOrFail($id);

        if (!$parent) {
            return redirect()->route('ParentDirectory')->with('error', 'Parent record not found.');
        }

        $parent->update([
            'parentRelationship_to_student' => $request->parentRelationship_to_student,
            'parentFirst_name' => $request->parentFirst_name,
            'parentMiddle_name' => $request->parentMiddle_name,
            'parentLast_name' => $request->parentLast_name,
            'parentBirthdate' => $request->parentBirthdate,
            'parentEduc' => $request->parentEducational_attainment,
            'parentMobile_number' => $request->parentMobile_number,
            'parentEmail' => $request->parentEmail,
            'parentOccupation' => $request->parentOccupation,
            'parentAddress' => $request->parentAddress,
        ]);

        return redirect()->route('ParentDirectory')->with('success', 'Parent record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $parent = ParentList::find($id);

        if (!$parent) {
            return redirect()->route('ParentDirectory')->with('error', 'Parent record not found.');
        }

        $parent->delete();

        return redirect()->route('ParentDirectory')->with('success', 'Parent record removed successfully.');
    }

    public function parentUploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('../../uploads/parent_images'), $imageName);

            // save image path to database
            $parent = ParentList::find($request->parent_id);
            $parent->image = '../../uploads/parent_images/' . $imageName;
            $parent->save();

            return response()->json(['success' => true, 'imageUrl' => asset($parent->image)]);
        }

        return response()->json(['success' => false]);
    }
    
}

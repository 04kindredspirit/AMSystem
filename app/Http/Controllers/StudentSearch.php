<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentList;
use App\Models\Payment;

class StudentSearch extends Controller
{
    public function searchAjax(Request $request)
    {
        $query = $request->input('search_query');

        $student = StudentList::where('studentFirst_name', 'like', "%{$query}%")->orWhere('studentLast_name', 'like', "%{$query}%")->orWhere('studentLRN', 'like', "%{$query}%")->first();

        if (!$student) {
            return response()->json([], 404);
        }

        $latestPayment = Payment::where('studentLrn', $student->studentLRN)->orderBy('created_at', 'DESC')->first();

        $balance = $latestPayment ? $latestPayment->balance : $student->discountedTuition_amount;

        if ($balance == 0) {
            return response()->json(['error' => 'This student has no remaining balance. Check if the student is ready for Academic Advancement.']);
        }

        return response()->json([
            'full_name' => trim($student->studentFirst_name . ' ' . $student->studentMiddle_name . ' ' . $student->studentLast_name . ' ' . $student->studentName_ext),
            'studentLRN' => $student->studentLRN,
            'paymentAmount' => $student->paymentAmount ?? '',
            'studentSection' => $student->studentSection ?? '',
            'balance' => $balance,
        ]);
    }
}
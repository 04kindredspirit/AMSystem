<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\StudentList;

class PaymentController
{
    /**
     * Display a listing of the resource.
     */
    public function payment()
    {
        return view('Payment.payment');
    }

    public function paymentRecords()
    {
        $payment = Payment::orderBy('created_at', 'DESC')->get();

        return view('PaymentRecords.paymentrecords', compact('payment'));
    }

    public function checkPaymentPeriod(Request $request)
    {
        $studentLrn = $request->input('studentLrn');
        $paymentPeriod = $request->input('paymentPeriod');

        // fetch the student's current grade level
        $student = StudentList::where('studentLRN', $studentLrn)->first();

        if (!$student) {
            return response()->json([
                'alreadyPaid' => false
            ]);
        }

        // check if the student has already paid for this period in the current grade level
        $existingPayment = Payment::where('studentLrn', $studentLrn)
            ->where('studentPayment_section', $student->studentSection) // Scope to current grade level
            ->where('paymentPeriod', $paymentPeriod)
            ->first();

        return response()->json([
            'alreadyPaid' => $existingPayment !== null
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function save(Request $request)
    {
        $request->validate([
            'paymentDate' => 'required|date',
            'paymentReceipt' => 'required|string',
            'paymentFname' => 'required|string',
            'paymentLrn' => 'required|string',
            'paymentAmount' => 'required|numeric',
            'studentPayment_section' => 'required|string',
            'tuitionAmount' => 'required|numeric',
            'paymentPeriod' => 'required|string',
        ]);

        $student = StudentList::where('studentLRN', $request->paymentLrn)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        // Skip validation for "Remaining Balance"
        if ($request->paymentPeriod !== 'Remaining Balance') {
            $existingPayment = Payment::where('studentLrn', $request->paymentLrn)
                ->where('studentPayment_section', $student->studentSection) // Scope to current grade level
                ->where('paymentPeriod', $request->paymentPeriod)
                ->first();

            if ($existingPayment) {
                return redirect()->back()->with('error', 'This student has already paid for the selected transaction period.');
            }
        }

        $paymentAmount = $request->paymentAmount;
        $tuitionAmount = $request->tuitionAmount;

        if ($paymentAmount > $tuitionAmount) {
            return redirect()->back()->with('error', 'Payment amount cannot exceed the tuition amount.');
        }

        $balance = $tuitionAmount - $paymentAmount;

        $payment = new Payment();
        $payment->paymentDate = $request->paymentDate;
        $payment->paymentOR = $request->paymentReceipt;
        $payment->studentFullname = $request->paymentFname; 
        $payment->studentLrn = $request->paymentLrn;
        $payment->paymentAmount = $request->paymentAmount;
        $payment->studentPayment_section = $request->studentPayment_section;
        $payment->paymentTuitionAmount = $request->tuitionAmount;
        $payment->paymentPeriod = $request->paymentPeriod;
        $payment->balance = $balance;

        $payment->save();

        return redirect()->route('PaymentRecords')->with('success', 'Payment data saved successfully!');
    }
}

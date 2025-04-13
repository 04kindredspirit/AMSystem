<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentList;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;

class SuperadminController extends Controller
{
    public function index()
    {
        $totalStudents = StudentList::count();
        $totalAccounts = User::count();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // earnings monthly
        $monthlyEarnings = Payment::whereMonth('paymentDate', Carbon::now()->month)
            ->whereYear('paymentDate', Carbon::now()->year)
            ->sum('paymentAmount');

        // earnings annually
        $annualEarnings = Payment::whereYear('paymentDate', Carbon::now()->year)
            ->sum('paymentAmount');

        // fetch monthly earnings data for the current year
        $monthlyEarningsData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyEarningsData[] = Payment::whereMonth('paymentDate', $month)
                ->whereYear('paymentDate', $currentYear)
                ->sum('paymentAmount');
        }

        return view('superadmin.dashboard', compact('totalStudents', 'totalAccounts', 
        'monthlyEarnings', 'annualEarnings', 'monthlyEarningsData'));
    }
}
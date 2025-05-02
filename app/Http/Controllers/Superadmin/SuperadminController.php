<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentList;
use App\Models\User;
use App\Models\Payment;
use App\Models\ActivityLog;
use App\Models\CategoryAllocation;
use App\Models\ExpenseType;
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

        $expenseTypes = ExpenseType::with(['categoryAllocations', 'expenses'])->get();

        // Initialize array for balances
        $expenseBalances = [];

        foreach ($expenseTypes as $type) {
            // Total allocated amount for this expense type
            $allocated = $type->categoryAllocations->sum('amount');

            // Total spent amount (from expenses relationship)
            $spent = $type->expenses->sum('amount');

            // Remaining balance
            $remaining = $allocated - $spent;

            // Calculate percentage remaining (for progress bars or color coding)
            $percentageRemaining = $allocated > 0 ? ($remaining / $allocated) * 100 : 0;

            // Store in array
            $expenseBalances[$type->name] = [
                'allocated' => $allocated,
                'spent' => $spent,
                'remaining' => $remaining,
                'percentage' => $percentageRemaining
            ];
        }

        $logs = ActivityLog::orderBy('created_at', 'DESC')->get();

        return view('superadmin.dashboard', compact('totalStudents', 'totalAccounts', 
        'monthlyEarnings', 'annualEarnings', 'monthlyEarningsData', 'logs', 'expenseBalances'));
    }
}
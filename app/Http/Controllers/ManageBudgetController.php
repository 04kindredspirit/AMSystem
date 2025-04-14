<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allocation;
use App\Models\CategoryAllocation;
use App\Models\Expense;
use App\Models\ExpenseType;

class ManageBudgetController extends Controller
{
    public function allocate() {
        $allocateRecords = Allocation::with('user')->orderBy('created_at', 'DESC')->get();
        $expenseRecords = CategoryAllocation::with('expenseType', 'user')->orderBy('created_at', 'DESC')->get();

        $expenseTypes = ExpenseType::with('categoryAllocations', 'expenses')->get()->map(function ($type) {
            $allocated = $type->categoryAllocations->sum('amount');
            $spent = $type->expenses->sum('amount');
            $type->balance = $allocated - $spent; 
            return $type;
        });
    
        $totalAllocations = Allocation::sum('amount');
        $totalReplenishments = CategoryAllocation::sum('amount');
        $overallBalance = $totalAllocations - $totalReplenishments;

        return view('ManageBudget.AllocateBudget', compact('expenseTypes', 'overallBalance', 'allocateRecords', 'expenseRecords'));
    }

    public function utilize() {
        $expenseTypes = ExpenseType::with('categoryAllocations', 'expenses')->get()->map(function ($type) {
            $allocated = $type->categoryAllocations->sum('amount');
            $spent = $type->expenses->sum('amount');
            $type->balance = $allocated - $spent; 
            return $type;
        });
    
        $totalAllocations = Allocation::sum('amount');
        $totalReplenishments = CategoryAllocation::sum('amount');
        $overallBalance = $totalAllocations - $totalReplenishments;

        return view('ManageBudget.UtilizeExpense', compact('expenseTypes', 'overallBalance'));
    }

    public function expense() {
        $currentDate = now();
        $lastMonthDate = now()->subMonth();
    
        $expenses = Expense::with('expenseType')
            ->whereBetween('date', [$lastMonthDate, $currentDate])
            ->orderBy('date', 'DESC')
            ->get();
    
        $totalAllocations = Allocation::sum('amount');
        $totalReplenishments = CategoryAllocation::sum('amount');
    
        $groupedExpenses = $expenses->groupBy('expense_type_id')->map(function ($expenses) {
            $allocated = $expenses->first()->expenseType->categoryAllocations->sum('amount');
            $spent = $expenses->sum('amount');
            $remainingBalance = $allocated - $spent;
    
            return [
                'name' => $expenses->first()->expenseType->name,
                'data' => $expenses->map(function ($expense) {
                    return [
                        'description' => $expense->description,
                        'amount' => $expense->amount,
                    ];
                }),
                'remainingBalance' => $remainingBalance,
            ];
        });
    
        $utilities = $groupedExpenses->firstWhere('name', 'Utilities') ?? ['data' => [], 'remainingBalance' => 0];
        $salaries = $groupedExpenses->firstWhere('name', 'Salaries') ?? ['data' => [], 'remainingBalance' => 0];
        $pettyCash = $groupedExpenses->firstWhere('name', 'Petty Cash') ?? ['data' => [], 'remainingBalance' => 0];
        $maintenance = $groupedExpenses->firstWhere('name', 'Maintenance') ?? ['data' => [], 'remainingBalance' => 0];
        $supplies = $groupedExpenses->firstWhere('name', 'Supplies') ?? ['data' => [], 'remainingBalance' => 0];
    
        return view('ManageBudget.ExpenseTracking', compact('utilities', 'salaries', 'pettyCash', 'maintenance', 'supplies'));
    }

    public function exdirectory() {
        $expense = Expense::with('expenseType', 'user')->orderBy('created_at', 'DESC')->get();

        return view('ManageBudget.ExpenseDirectory', compact('expense'));
    } 

    public function storeAllocation(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0'
        ]);

        Allocation::create([
            'date' => $request->date,
            'amount' => $request->amount,
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Budget allocated!');
    }

    public function storeReplenishment(Request $request)
    {
        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'amount' => [
                'required',
                'numeric',
                'min: 0',
                function ($attribute, $value, $fail) {
                    $totalAllocations = Allocation::sum('amount');
                    $totalReplenishments = CategoryAllocation::sum('amount');
                    $available = $totalAllocations - $totalReplenishments;
                    
                    if ($value > $available) {
                        $fail("Amount exceeds available budget of ".number_format($available, 2));
                    }
                }
            ],
            'date' => 'required|date'
        ]);

        CategoryAllocation::create([
            'expense_type_id' => $request->expense_type_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Funds distributed!');
    } 

    public function storeExpense(Request $request)
    {
        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $allocated = CategoryAllocation::where('expense_type_id', $request->expense_type_id)
                        ->sum('amount');
                    $spent = Expense::where('expense_type_id', $request->expense_type_id)
                        ->sum('amount');
                    $available = $allocated - $spent;
                    
                    if ($value > $available) {
                        $fail("Amount exceeds category balance of ".number_format($available, 2));
                    }
                }
            ],
            'description' => 'required|string',
            'date' => 'required|date'
        ]);

        Expense::create([
            'expense_type_id' =>$request->expense_type_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);
        

        return back()->with('success', 'Expense recorded!');
    }

}
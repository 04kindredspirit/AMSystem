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
        $expenseTypes = ExpenseType::all();
        
        $groupedExpenses = collect();
        
        foreach ($expenseTypes as $expenseType) {
            $currentMonth = now()->format('Y-m');

            $latestExpenses = Expense::with('expenseType')
                ->where('expense_type_id', $expenseType->id)
                ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
                ->orderBy('date', 'DESC')
                ->take(15)
                ->get();
            
            $allocated = CategoryAllocation::where('expense_type_id', $expenseType->id)->sum('amount');
            $spent = Expense::where('expense_type_id', $expenseType->id)->sum('amount');
            $remainingBalance = $allocated - $spent;
            $totalAllocated = $allocated;
            
            $groupedExpenses->put($expenseType->name, [
                'name' => $expenseType->name,
                'data' => $latestExpenses->map(function ($expense) {
                    return [
                        'description' => $expense->description,
                        'amount' => $expense->amount,
                        'date' => $expense->date
                    ];
                }),
                'remainingBalance' => $remainingBalance,
                'totalAllocated' => $totalAllocated
            ]);
        }
    
        $utilities = $groupedExpenses->get('Utilities', [
            'data' => [], 
            'remainingBalance' => 0,
            'totalAllocated' => 0
        ]);
        
        $salaries = $groupedExpenses->get('Salaries', [
            'data' => [], 
            'remainingBalance' => 0,
            'totalAllocated' => 0
        ]);
        
        $pettyCash = $groupedExpenses->get('Petty Cash', [
            'data' => [], 
            'remainingBalance' => 0,
            'totalAllocated' => 0
        ]);
        
        $maintenance = $groupedExpenses->get('Maintenance', [
            'data' => [], 
            'remainingBalance' => 0,
            'totalAllocated' => 0
        ]);
        
        $supplies = $groupedExpenses->get('Supplies', [
            'data' => [], 
            'remainingBalance' => 0,
            'totalAllocated' => 0
        ]);
    
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
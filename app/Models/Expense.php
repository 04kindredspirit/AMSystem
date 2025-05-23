<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Expense extends Model
{
    use HasFactory;
    use LogsActivity;

    protected static $logName = 'an Expense';

    protected $fillable = [
        'expense_type_id',
        'amount',
        'description',
        'date',
        'user_id',
    ];

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function totalSpent()
    {
        return self::sum('amount');
    }
}
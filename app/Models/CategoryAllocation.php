<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class CategoryAllocation extends Model
{
    use HasFactory;
    use LogsActivity;

    protected static $logName = 'a Category Allocation';

    protected $fillable = [
        'expense_type_id',
        'amount',
        'date',
        'user_id',
    ];

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function allocation()
    {
        return $this->belongsTo(Allocation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function totalReplenished()
    {
        return self::sum('amount');
    }
}
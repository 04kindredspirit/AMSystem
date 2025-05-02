<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ExpenseType extends Model
{
    use HasFactory;
    use LogsActivity;

    protected static $logName = 'an Expense Type';

    protected $fillable = ['name'];

    public function categoryAllocations()
    {
        return $this->hasMany(CategoryAllocation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
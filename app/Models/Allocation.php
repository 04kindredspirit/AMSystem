<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Allocation extends Model
{
    use LogsActivity;
    use HasFactory;

    protected static $logName = 'an Allocation';

    protected $fillable = [
        'date',
        'amount',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoryAllocations()
    {
        return $this->hasMany(CategoryAllocation::class);
    }

    public static function totalAllocated()
    {
        return self::sum('amount');
    }

    public static function remainingBudget()
    {
        $totalAllocated = self::totalAllocated();
        $totalReplenished = CategoryAllocation::totalReplenished();
        return $totalAllocated - $totalReplenished;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class DiscountList extends Model
{
    use HasFactory;
    use LogsActivity;

    protected static $logName = 'a Discount';

    protected $fillable = [
        'discount_type',
        'percentage',
        'status'
    ];
}

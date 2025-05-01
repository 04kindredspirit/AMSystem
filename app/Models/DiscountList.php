<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountList extends Model
{
    use HasFactory;

    protected $fillable = [
        'discount_type',
        'percentage',
        'status'
    ];
}

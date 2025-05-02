<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class NewStudentList extends Model
{
    use HasFactory;
    use LogsActivity;

    protected static $logName = 'a Student List';

    protected $fillable = [
        'image',
        'studentLRN',
        'studentSection',
        'school_year',
        'studentName_ext',
        'studentFirst_name',
        'studentMiddle_name',
        'studentLast_name',
        'studentGender',
        'studentBirthdate',
        'studentBirthorder',
        'studentAddress',
        'studentHobby',
        'studentFavorite',
        'studentTuition_amount',
        'studentTuition_discount',
        'discountedTuition_amount',
        'discountPercentage',
    ];
}

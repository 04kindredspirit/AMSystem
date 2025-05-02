<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class StudentList extends Model
{
    use LogsActivity;
    use HasFactory;

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

    public function payments()
    {
        return $this->hasMany(Payment::class, 'studentLrn', 'studentLRN');
    }

    public function parents()
    {
        return $this->belongsToMany(ParentList::class, 'parent_student_links', 'student_id', 'parent_id');
    }

    public function siblings()
    {
        $groupIds = $this->studentLinks()->pluck('group_id');
        return StudentList::whereHas('studentLinks', function ($query) use ($groupIds) {
            $query->whereIn('group_id', $groupIds);
        })->where('id', '!=', $this->id);
    }

    public function studentLinks()
    {
        return $this->hasMany(StudentLink::class, 'student_id');
    }
}
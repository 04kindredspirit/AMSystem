<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ParentList extends Model
{
    use LogsActivity;
    use HasFactory;

    protected static $logName = 'a Parent List';

    protected $fillable = [
        'parentRelationship_to_student',
        'parentFirst_name',
        'parentMiddle_name',
        'parentLast_name',
        'parentBirthdate',
        'parentEducational_attainment',
        'parentMobile_number',
        'parentEmail',
        'parentOccupation',
        'parentAddress',
    ];

    public function students()
    {
        return $this->belongsToMany(StudentList::class, 'parent_student_links', 'parent_id', 'student_id');
    }
}

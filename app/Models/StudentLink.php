<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLink extends Model
{
    protected $table = 'student_links';

    public function student()
    {
        return $this->belongsTo(StudentList::class, 'student_id');
    }

    public function group()
    {
        return $this->belongsTo(StudentList::class, 'group_id');
    }
}
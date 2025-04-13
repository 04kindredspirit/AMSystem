<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLink extends Model
{
    protected $table = 'parent_student_link';

    public function student()
    {
        return $this->belongsTo(ParentList::class, 'parent_id');
    }

    public function group()
    {
        return $this->belongsTo(ParentList::class, 'student_id');
    }
}
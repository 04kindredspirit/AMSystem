<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class SectionList extends Model
{
    use LogsActivity;
    use HasFactory;

    protected $fillable = [
        'section_level',
        'section_name',
        'status',
    ];

    protected static $logName = 'a Section';

    protected $table = 'section_lists';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class SchoolYearList extends Model
{
    use LogsActivity;
    use HasFactory;

    protected $fillable = [
        'school_year',
        'status',
    ];

    protected static $logName = 'a School Year';

    protected $table = 'school_year_lists';
}

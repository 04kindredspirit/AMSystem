<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionList extends Model
{
    use HasFactory;

    protected $fillable = [
        'sectionLevel',
        'sectionName',
        'syStatus',
    ];

    protected $table = 'section_lists';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment_records'; // Make sure this matches your table name

    protected $fillable = [
        'paymentDate',
        'paymentOR',
        'studentFullname',
        'studentPayment_section',
        'studentLrn',
        'paymentAmount',
        'paymentTuitionAmount',
        'paymentPeriod',
        'balance',
        'record_type',
    ];

    public function student()
    {
        return $this->belongsTo(StudentList::class, 'studentLrn', 'studentLRN');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByCreatedAt', function (Builder $builder) {
            $builder->orderBy('created_at', 'DESC');
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment_records'; 

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
        'user_id',
    ];

    public function student()
    {
        return $this->belongsTo(StudentList::class, 'studentLrn', 'studentLRN');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByCreatedAt', function (Builder $builder) {
            $builder->orderBy('created_at', 'DESC');
        });
    }
}
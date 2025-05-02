<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\LogsActivity;

class Payment extends Model
{
    use LogsActivity;
    use HasFactory;

    protected static $logName = 'a Payment';

    protected $table = 'payment_records'; 

    protected $fillable = [
        'paymentDate',
        'paymentOR',
        'studentFullname',
        'studentPayment_section',
        'studentLrn',
        'balance',
        'paymentAmount',
        'paymentDiscountType',
        'paymentTuitionAmount',
        'paymentPeriod',
        'record_type',
        'MOP',
        'ReferenceNo',
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
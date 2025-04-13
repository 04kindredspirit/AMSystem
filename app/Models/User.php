<?php

namespace App\Models;

use Illuminate\Contracts\AUth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'image',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = 'web'; 


    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }

    public function categoryAllocations()
    {
        return $this->hasMany(CategoryAllocation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getRole()
    {
        return $this->role;
    }
}
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
// use Tymon\JWTAuth\Contracts\JWTSubject;

class Employee extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes, HasApiTokens;

    protected $fillable = [
        // 'hotel_code',
        'primary_role',
        'name',
        'cnic',
        'phone_no',
        'email',
        'password',
        'salary',
        'status',
        'hotel_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function confirmedReservations()
    {
        return $this->hasMany(ServiceReservation::class, 'confirmed_by');
    }

    public function cancelledReservations()
    {
        return $this->hasMany(ServiceReservation::class, 'cancelled_by');
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }
    public function lastLogin()
    {
        return $this->hasOne(LoginHistory::class)->latest();
    }


    protected $guard_name = 'employee';

    public function createdCampaigns()
    {
        return $this->hasMany(Campaign::class, 'created_by');
    }

    public function approvedCampaigns()
    {
        return $this->hasMany(Campaign::class, 'approved_by');
    }

    public function createdBookings()
    {
        return $this->hasMany(Booking::class, 'created_by');
    }

    public function updatedBookings()
    {
        return $this->hasMany(Booking::class, 'updated_by');
    }

}


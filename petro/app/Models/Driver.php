<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'drivers';

    protected $fillable = [
        'driver_category',
        'first_name',
        'last_name',
        'expiration_date',
        'email',
        'mobile',
        'address',
        'password',
        'upload_valid_id',
        'vehicle_details',
        'license_number'
    ];

    // Ensure you have the proper hidden and casts properties if needed
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}


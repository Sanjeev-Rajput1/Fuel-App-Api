<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelDelivery extends Model
{
   
	 use HasFactory;
	protected $table = 'fuel_delivery';
               
    protected $fillable = [
        'user_id',
        'fuel_type',
        'quantity',
        'location',
        'preferred_time',
        'status',
    ];
	
	
	
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

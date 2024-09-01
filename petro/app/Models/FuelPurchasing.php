<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelPurchasing extends Model
{
    use HasFactory;

    protected $table = 'fuel_purchases';

    protected $fillable = [
        'user_id',
        'fuel_type',
        'quantity',
        'price',
        'supplier_id',
        'date',
    ];
}

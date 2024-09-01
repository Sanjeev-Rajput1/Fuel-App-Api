<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanker extends Model
{
    use HasFactory;


    protected $table = 'tankers';

    protected $primaryKey = 'tanker_id';

    // public $incrementing = false;


    protected $keyType = 'int'; 

    protected $fillable = [
        'tanker_id',
        'capacity',
        'driver_id',
        'status'
    ];


}

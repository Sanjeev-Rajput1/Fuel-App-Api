<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
	protected $table = 'inventory';
	
	protected $fillable = ['user_id', 'fuel_type', 'quantity', 'date', 'source'];

    protected $casts = [
        'quantity' => 'integer',
    ];
	
	
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

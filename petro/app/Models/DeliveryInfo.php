<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DeliveryInfo extends Model
{
    use HasFactory;
	protected $table = 'addresses';

    // Define the fillable attributes  
    protected $fillable = [
        'user_id',
        'type',
        'delivery_address',
        'company_name',
        'contact_number',
        'type_of_industry',
        'storage_type',
        'other_specify',
    ];

    // Optionally, you can define relationships here
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

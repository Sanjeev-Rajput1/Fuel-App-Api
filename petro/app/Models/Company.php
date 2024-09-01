<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

   protected $table = 'company_info';

    // Optionally, specify fillable or guarded attributes if needed
    protected $fillable = [
        'company_name',
        'tin_number',
        'company_address',
        'company_email',
        'company_contact_number',
        'upload_bir',
        'upload_sec',
        'user_id',
    ];

    // Define the inverse relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

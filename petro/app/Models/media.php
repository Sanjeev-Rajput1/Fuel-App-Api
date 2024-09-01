<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class media extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'media';

    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'name',
        'path',
        'url',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

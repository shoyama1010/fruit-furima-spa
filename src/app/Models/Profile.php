<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'img_url',
        'postcode',
        'address',
        'phone_number',
    ];

    // User モデルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{


    protected $fillable = [
        'user_id',
        'business_name',
        'address',
        'category',
        'contact_phone',
        'status'
    ];
}

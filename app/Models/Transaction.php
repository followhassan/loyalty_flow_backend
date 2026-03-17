<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
        'customer_id',
        'merchant_id',
        'amount'
    ];


    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}

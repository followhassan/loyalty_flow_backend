<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashbackHistory extends Model
{
    

    protected $fillable = [
        'user_id',
        'transaction_id',
        'cashback_type',
        'percentage',
        'amount',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cashback belongs to Transaction (nullable)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

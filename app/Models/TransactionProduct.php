<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    //
    protected $fillable = ['transaction_id', 'name', 'price', 'quantity'];
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

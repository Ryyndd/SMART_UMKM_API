<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    //
    use HasFactory;

    protected $table = 'transactions';

    protected $primaryKey = 'transaction_id';

    public $incrementing = false;

    protected $fillable = [
        'transaction_id',
        'transaction_time',
        'transaction_user',
        'transaction_total',
        'transaction_cashback',
    ];

    // public function items()
    // {
    //     return $this->hasMany(TransactionProduct::class);
    // }
    public function products()
    {
        return $this->hasMany(TransactionProduct::class, 'transaction_product_id', 'transaction_id');
    }

}

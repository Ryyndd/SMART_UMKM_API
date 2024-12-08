<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TransactionProduct extends Model
{
    use HasFactory;

    protected $table = 'transaction_products'; // Nama tabel

    protected $fillable = [
        'transaction_id', // ID transaksi
        'name',
        'price',
        'quantity',
    ];

    /**
     * Relasi ke transaksi
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
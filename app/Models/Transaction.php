<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions'; // Nama tabel
    protected $primaryKey = 'id'; // Menentukan primary key
    public $incrementing = false; // Karena kita menggunakan string sebagai primary key
    protected $keyType = 'string'; // Tipe primary key

    protected $fillable = [
        'id', // transaction_id
        'time',
        'user',
        'total',
        'cashback',
    ];

    /**
     * Relasi ke produk transaksi
     */
    public function products(): HasMany
    {
        return $this->hasMany(TransactionProduct::class, 'transaction_id', 'id');
    }
}
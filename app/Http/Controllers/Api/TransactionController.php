<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    //
   
    
    public function index()
    {
        // Fetch transactions with related products
        $transactions = Transaction::with('products')->get();

        // Format response data
        $data = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id, // Menggunakan 'id' sebagai primary key
                'time' => $transaction->time,
                'user' => $transaction->user,
                'total' => $transaction->total,
                'cashback' => $transaction->cashback,
                'products' => $transaction->products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $product->quantity,
                    ];
                }),
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at,
            ];
        });

        return new TransactionResource(true, 'List Data Transaction', $data);
    }

    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'id' => 'required|unique:transactions,id', // Mengubah nama kolom menjadi 'id'
            'time' => 'required|string', // Mengubah nama kolom menjadi 'time'
            'user' => 'required|string', // Mengubah nama kolom menjadi 'user'
            'total' => 'required|string', // Mengubah nama kolom menjadi 'total'
            'cashback' => 'required|string', // Mengubah nama kolom menjadi 'cashback'
            'products' => 'required|array', // Mengubah nama kolom menjadi 'products'
            'products.*.name' => 'required|string',
            'products.*.price' => 'required',
            'products.*.quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create transaction
        $transaction = Transaction::create([
            'id' => $request->id,
            'time' => $request->time,
            'user' => $request->user,
            'total' => $request->total,
            'cashback' => $request->cashback,
        ]);

        // Create transaction products
        foreach ($request->products as $product) {
            TransactionProduct::create([
                'transaction_id' => $transaction->id, // Menggunakan 'transaction_id' untuk relasi
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
            ]);
        }

        return new TransactionResource(true, 'Data Transaction Berhasil Ditambahkan!', $transaction->load('products'));
    }
    
    public function destroy($id)
    {
        // Cari transaksi berdasarkan ID
        $transaction = Transaction::find($id);

        // Jika transaksi tidak ditemukan, kembalikan respons error
        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction not found.'
            ], 404);
        }

        // Hapus produk terkait
        $transaction->products()->delete();

        // Hapus transaksi
        $transaction->delete();

        return response()->json([
            'status' => true,
            'message' => 'Transaction deleted successfully.',
            'data' => null
        ]);
    }
}

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
                'transaction_id' => $transaction->transaction_id,
                'transaction_time' => $transaction->transaction_time,
                'transaction_user' => $transaction->transaction_user,
                'transaction_total' => $transaction->transaction_total,
                'transaction_cashback' => $transaction->transaction_cashback,
                'transaction_product' => $transaction->products->map(function ($product) {
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

        return response()->json([
            'success' => true,
            'message' => 'List Data Transaction',
            'data' => $data,
        ]);
    }

    // Store transaction with products
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|unique:transactions,transaction_id',
            'transaction_time' => 'required',
            'transaction_user' => 'required',
            'transaction_total' => 'required|integer',
            'transaction_cashback' => 'required|integer',
            'transaction_product' => 'required|array',
            'transaction_product.*.name' => 'required|string',
            'transaction_product.*.price' => 'required|numeric',
            'transaction_product.*.quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create transaction
        $transaction = Transaction::create([
            'transaction_id' => $request->transaction_id,
            'transaction_time' => $request->transaction_time,
            'transaction_user' => $request->transaction_user,
            'transaction_total' => $request->transaction_total,
            'transaction_cashback' => $request->transaction_cashback,
        ]);

        // Create transaction products
        foreach ($request->transaction_product as $product) {
            TransactionProduct::create([
                'transaction_product_id' => $transaction->transaction_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
            ]);
        }

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Data Transaction Berhasil Ditambahkan!',
            'data' => $transaction->load('products'),
        ]);
    }
    public function destroy($transaction_id)
    {
        // Find product category by ID
        $transaction = Transaction::find($transaction_id);

        // Check if category exists
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Delete product category
        $transaction->delete();

        // Return response
        return new TransactionResource(true, 'Data Transaction Berhasil Dihapus!', null);
    }
}

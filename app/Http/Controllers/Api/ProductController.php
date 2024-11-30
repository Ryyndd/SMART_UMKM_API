<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        // Get all products
        $product = Product::latest()->get();

        // Return collection of products as a resource
        return new ProductResource(true, 'List Data Products', $product);
    }

    public function store(Request $request)
    {
        // Validate request input
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'category' => 'required|string', // Validate category name
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle image upload
        $image = $request->file('image');
        $namaImage = Str::slug($request->input('title')) . '-' . time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/product', $namaImage, 'public');

        // Create product
        $product = Product::create([
            'image' => $namaImage,
            'name' => $request->name,
            'description' => $request->descproduct,
            'price' => $request->price,
            'category' => $request->category, // Store the category name
        ]);

        // Return response
        return new ProductResource(true, 'Data Product Berhasil Ditambahkan!', $product);
    }

    public function show($id)
    {
        $product =  Product::find($id);

        if (!$product) {
            // Jika produk tidak ditemukan, kembalikan error 404
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        // Mengembalikan data produk
        return new ProductResource(true, 'Detail Data Product!', $product);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Cari produk berdasarkan id_product
        $product = Product::find($id); // Pastikan menggunakan 'id_product'

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Proses pembaruan
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->input('name')) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/product', $imageName, 'public');

            // Update produk dengan gambar baru
            $product->update([
                'image' => $imageName,
                'name' => $request->name,
                'price' => $request->price,
                'descriptiot' => $request->description,
                'category' => $request->category,
            ]);
        } else {
            // Update produk tanpa gambar baru
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'category' => $request->category,
            ]);
        }

        return new ProductResource(true, 'Data Product Berhasil Diubah!', $product);
    }


    public function destroy($id)
    {
        // Temukan produk berdasarkan id_product
        $product =  Product::find($id);

        // Jika produk tidak ditemukan
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Menghapus gambar jika ada
        if ($product->image) {
            Storage::delete('public/product/' . $product->image);
        }

        // Hapus produk dari database
        $product->delete();

        return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
    }

}

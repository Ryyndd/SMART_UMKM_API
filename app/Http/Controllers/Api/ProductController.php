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
            'product_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'product_title' => 'required',
            'product_price' => 'required|numeric',
            'description_product' => 'required|string',
            'category_product' => 'required|string', // Validate category name
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle image upload
        $image = $request->file('product_image');
        $namaImage = Str::slug($request->input('product_title')) . '-' . time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/product', $namaImage, 'public');

        // Create product
        $product = Product::create([
            'product_image' => $namaImage,
            'product_title' => $request->product_title,
            'description_product' => $request->description_product,
            'product_price' => $request->product_price,
            'category_product' => $request->category_product, // Store the category name
        ]);

        // Return response
        return new ProductResource(true, 'Data Product Berhasil Ditambahkan!', $product);
    }

    public function show($id_product)
    {
        $product =  Product::find($id_product);

        if (!$product) {
            // Jika produk tidak ditemukan, kembalikan error 404
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        // Mengembalikan data produk
        return new ProductResource(true, 'Detail Data Product!', $product);
    }

    public function update(Request $request, $id_product)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'product_title' => 'required',
            'product_price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Cari produk berdasarkan id_product
        $product = Product::find($id_product); // Pastikan menggunakan 'id_product'

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Proses pembaruan
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = Str::slug($request->input('product_title')) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/product', $imageName, 'public');

            // Update produk dengan gambar baru
            $product->update([
                'product_image' => $imageName,
                'product_title' => $request->product_title,
                'product_price' => $request->product_price,
                'description_product' => $request->description_product,
                'category_product' => $request->category_product,
            ]);
        } else {
            // Update produk tanpa gambar baru
            $product->update([
                'product_title' => $request->product_title,
                'product_price' => $request->product_price,
                'description_product' => $request->description_product,
                'category_product' => $request->category_product,
            ]);
        }

        return new ProductResource(true, 'Data Product Berhasil Diubah!', $product);
    }


    public function destroy($id_product)
    {
        // Temukan produk berdasarkan id_product
        $product =  Product::find($id_product);

        // Jika produk tidak ditemukan
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Menghapus gambar jika ada
        if ($product->product_image) {
            Storage::delete('public/product/' . $product->product_image);
        }

        // Hapus produk dari database
        $product->delete();

        return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
    }

}

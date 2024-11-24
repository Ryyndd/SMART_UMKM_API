<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    public function index()
    {
        //get all posts
        $product = Product::latest()->paginate(5);

        //return collection of posts as a resource
        return new ProductResource(true, 'List Data Products', $product);
    }

    public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'product_title' => 'required',
                'product_price' => 'required',
            ]);


            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }


            $image = $request->file('product_image');


    // Buat nama file berdasarkan title, waktu unik, dan ekstensi asli
            $namaImage = Str::slug($request->input('product_title')) . '-' . time() . '.' . $image->getClientOriginalExtension();
            
            $image->storeAs('public/product', $namaImage,'public');

            $product = Product::create([
                'product_image' => $namaImage,
                'product_title' => $request->product_title,
                'product_price' => $request->product_price,
            ]);

            return new ProductResource(true, 'Data Product Berhasil Ditambahkan!', $product);
    }

    public function show($id)
    {
        //find post by ID
        $product = Product::find($id);

        //return single post as a resource
        return new ProductResource(true, 'Detail Data Product!', $product);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_title' => 'required',
            'product_price' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $namaImage = Str::slug($request->input('product_title')) . '-' . time() . '.' . $image->getClientOriginalExtension();
    
            $image->storeAs('public/product', $namaImage,'public');

            $product->update([
                'product_image' => $namaImage,
                'product_title' => $request->product_title,
                'product_price' => $request->product_price,
            ]);
        } else {
            $product->update([
                'product_title' => $request->product_title,
                'product_price' => $request->product_price,
            ]);
        }

        return new ProductResource(true, 'Data Product Berhasil Diubah!', $product);
    }

    public function destroy($id)
        {
            // Find product by ID
            $product = Product::find($id);

            // Check if product exists
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Get the image path and delete it if exists
            if ($product->product_image) {
                Storage::delete('public/product/' . $product->product_image);
            }

            // Delete product from the database
            $product->delete();

            return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
        }


}


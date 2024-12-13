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
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // public function index()
    // {
    //     // Get all products
    //     $product = Product::latest()->get();
    //     // Return collection of products as a resource
    //     return new ProductResource(true, 'List Data Products', $product);
    // }
    
    
    public function index(Request $request)
    {
        $query = $request->input('query');
    
        $products = Product::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('name', 'like', "%{$query}%")
                                ->orWhere('category', 'like', "%{$query}%");
        })->latest()->get();
    
        $message = $query ? "Berikut Data Hasil pencarian dari $query" : 'List data Product';
    
        return new ProductResource(true, $message, $products);
    }
    

    public function store(Request $request)
    {
        // Validate request input
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'name' => 'required',
            'price' => 'required',
            'description' => 'required|string',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle image upload
        $image = $request->file('image');
        $imageName = Str::slug($request->input('name')) . '-' . time() . '.' . $image->getClientOriginalExtension();
        // dd($imageName);
        $image->storeAs('product', $imageName, 'public'); // Store in 'storage/app/public/product'

        // Create product
        $product = Product::create([
            'image' => $imageName, // Store the path
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
        ]);


        // Return response with the full URL to the image
        return new ProductResource(true, 'Data Product Berhasil Ditambahkan!',$product);
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
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find product by ID
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Process update
        if ($request->hasFile('image')) {
            // Delete old image if exists
             // Check if the product has an image
            if ($product->image) {
                // Extract the relative path from the full URL
                $imagePath = parse_url($product->image, PHP_URL_PATH); // Get the path from the URL
                
                // Remove the extra 'product/' segment if it exists
                $relativePath = str_replace('public/storage/product/', '/product/', $imagePath); // Adjust the path

                // Check if the file exists and delete it
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                } else {
                    return response()->json(['error' => 'Image not found in storage'], 404);
                }
            }

            $image = $request->file('image');
            $imageName = Str::slug($request->input('name')) . '-' . time() . '.' . $image->getClientOriginalExtension();
            // dd($imageName);
            $image->storeAs('product', $imageName, 'public'); // Store in 'storage/app/public/product'

            // Update product with new image
            $product->update([
                'image' => $imageName,
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'category' => $request->category,
            ]);
        } else {
            // Update product without new image
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'category' => $request->category,
            ]);
        }

        return new ProductResource(true, 'Data Product Berhasil diubah!', $product);
    }


    public function destroy($id)
        {
            // Find the product by ID
            $product = Product::find($id);

            // If the product is not found
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Check if the product has an image
            if ($product->image) {
                // Extract the relative path from the full URL
                $imagePath = parse_url($product->image, PHP_URL_PATH); // Get the path from the URL
                
                // Remove the extra 'product/' segment if it exists
                $relativePath = str_replace('public/storage/product/', '/product/', $imagePath); // Adjust the path

                // Check if the file exists and delete it
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                } else {
                    return response()->json(['error' => 'Image not found in storage'], 404);
                }
            }

            // Delete the product from the database
            $product->delete();

            return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
    }


}

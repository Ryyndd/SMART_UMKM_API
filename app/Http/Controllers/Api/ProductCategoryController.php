<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function index()
    {
        // Get all product categories
        $categories = ProductCategory::latest()->get();

        // Return collection of product categories as a resource
        return new ProductCategoryResource(true, 'List Data Product Categories', $categories);
    }

    public function store(Request $request)
    {
        // Validate request input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create product category
        $category = ProductCategory::create([
            'name' => $request->name
        ]);

        // Return response
        return new ProductCategoryResource(true, 'Data Product Category Berhasil Ditambahkan!', $category);
    }


    public function destroy($id)
    {
        // Find product category by ID
        $category = ProductCategory::find($id);

        // Check if category exists
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        // Delete product category
        $category->delete();

        // Return response
        return new ProductCategoryResource(true, 'Data Product Category Berhasil Dihapus!', null);
    }
}

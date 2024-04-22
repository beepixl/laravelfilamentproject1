<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product; 
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProductsByCategory($category_id)
    {
        try {
            // Fetch products based on the category ID
            $products = Product::where('category_id', $category_id)->get();

            // Check if any products are found
            if ($products->isEmpty()) {
                return response()->json(['error' => 'No products found for the given category'], 404);
            }

            // Return the products in JSON format
            return response()->json(['data' => $products, 'message' => 'Products fetched successfully'], 200);
        } catch (\Exception $e) {
            // Log the error and return a JSON response
            log::error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }
  

    public function getProductsBySubCategory($sub_category_id)
    {
        try {
            // Fetch products based on the category ID
            $products = Product::where('sub_category_id', $sub_category_id)->get();

            // Check if any products are found
            if ($products->isEmpty()) {
                return response()->json(['error' => 'No products found for the given subcategory'], 404);
            }

            // Return the products in JSON format
            return response()->json(['data' => $products, 'message' => 'Products fetched successfully'], 200);
        } catch (\Exception $e) {
            // Log the error and return a JSON response
            log::error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    } 


    public function getProductDetails($product_id)
    {
        try {
            // Fetch products based on the category ID
            $products = Product::with('categories','subcategories','brand')->where('id', $product_id)->get();

            // Check if any products are found
            if ($products->isEmpty()) {
                return response()->json(['error' => 'No products found'], 404);
            }

            // Return the products in JSON format
            return response()->json(['data' => $products, 'message' => 'Products fetched successfully'], 200);
        } catch (\Exception $e) {
            // Log the error and return a JSON response
            log::error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }
}

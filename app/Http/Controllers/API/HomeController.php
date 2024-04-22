<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function brand()
    {
        try {
            $allBrands = Brand::all();

            if ($allBrands->isEmpty()) {
                return response()->json(['error' => 'No brands found'], 404);
            }

            return response()->json(['data' => $allBrands, 'message' => 'Brands fetched successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching brands: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch brands'], 500);
        }
    }


    public function product()
    {
        try {
            $allproduct = Product::take(10)->get();

            if ($allproduct->isEmpty()) {
                return response()->json(['error' => 'No product found'], 404);
            }
            return response()->json(['data' => $allproduct, 'message' => 'product fetched successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch product'], 500);

        }
    }


    public function category()
    {
        try {
            $allcategory = Category::all();

            if ($allcategory->isEmpty()) {
                return response()->json(['error' => 'No category found'], 404);
            }
            return response()->json(['data' => $allcategory, 'message' => 'Category fetched successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching category: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch category'], 500);

        }
    }


    public function home()
    {
        try {
            $brands = Brand::get();
            $products = Product::with('categories')->take(10)->get();
            $categories = Category::get();

            $responseData = ['brands' => $brands,'products' => $products, 'categories' => $categories];


            if ($brands->isEmpty() && $products->isEmpty() && $categories->isEmpty()) {
                return response()->json(['error' => 'No data found'], 404);
            }

            return response()->json(['data' => $responseData, 'message' => 'Data fetched successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }  

    

}

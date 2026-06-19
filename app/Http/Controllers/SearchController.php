<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class SearchController extends Controller
{
    public function __invoke()
    {
        $query = request('q');
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->paginate(12);

        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $maxPrice = Product::where('is_active', true)->max('price');

        return view('frontend.products.index', compact('products', 'categories', 'maxPrice'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->get();

        $newProducts = Product::where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.home', compact('featuredProducts', 'categories', 'newProducts'));
    }
}

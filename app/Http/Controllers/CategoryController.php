<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($category) {
                $q->where('category_id', $category->id)
                  ->orWhereIn('category_id', $category->children->pluck('id'));
            })
            ->paginate(12);

        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $maxPrice = Product::where('is_active', true)->max('price');

        return view('frontend.products.index', compact('products', 'categories', 'category', 'maxPrice'));
    }
}

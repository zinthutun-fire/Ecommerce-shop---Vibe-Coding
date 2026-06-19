<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->when(request('category'), fn($q, $slug) => $q->whereHas('category', fn($q) => $q->where('slug', $slug)))
            ->when(request('search'), fn($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when(request('min_price'), fn($q, $v) => $q->where('price', '>=', $v))
            ->when(request('max_price'), fn($q, $v) => $q->where('price', '<=', $v))
            ->when(request('sort'), function ($q, $sort) {
                match ($sort) {
                    'price_asc' => $q->orderBy('price'),
                    'price_desc' => $q->orderByDesc('price'),
                    'newest' => $q->latest(),
                    'name' => $q->orderBy('name'),
                    default => $q->latest(),
                };
            }, fn($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $maxPrice = Product::where('is_active', true)->max('price');

        return view('frontend.products.index', compact('products', 'categories', 'maxPrice'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();
        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}

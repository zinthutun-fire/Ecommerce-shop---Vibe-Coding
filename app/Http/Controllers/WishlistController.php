<?php

namespace App\Http\Controllers;

use App\Services\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index()
    {
        $wishlists = $this->wishlistService->getUserWishlist(auth()->id());
        return view('frontend.account.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $result = $this->wishlistService->toggle(auth()->id(), $request->product_id);

        if ($request->ajax()) {
            return response()->json($result);
        }

        return back()->with('success', $result['wishlisted'] ? 'Added to wishlist!' : 'Removed from wishlist!');
    }
}

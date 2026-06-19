<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user());
        return view('frontend.cart.index', compact('cart'));
    }

    public function count()
    {
        $count = 0;
        $cart = \App\Models\Cart::where(
            auth()->check() ? 'user_id' : 'session_id',
            auth()->check() ? auth()->id() : session()->getId()
        )->first();
        if ($cart) {
            $count = (int) $cart->items()->sum('quantity');
        }
        return response()->json(['count' => $count]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = $this->cartService->getOrCreateCart(auth()->user());
        $cart = $this->cartService->addItem($cart, $product, $request->quantity);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Product added to cart!',
                'cart_count' => $cart->items->sum('quantity'),
                'cart_total' => $cart->total,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:100']);
        $item = \App\Models\CartItem::findOrFail($itemId);
        $cart = $this->cartService->updateItem($item, $request->quantity);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Cart updated!',
                'cart_count' => $cart->items->sum('quantity'),
                'cart_total' => $cart->total,
                'subtotal' => $item->fresh()?->subtotal ?? 0,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove(Request $request, $itemId)
    {
        $item = \App\Models\CartItem::findOrFail($itemId);
        $cart = $this->cartService->removeItem($item);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Item removed from cart!',
                'cart_count' => $cart->items->sum('quantity'),
                'cart_total' => $cart->total,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $cart = $this->cartService->getOrCreateCart(auth()->user());

        try {
            $cart = $this->cartService->applyCoupon($cart, $request->code);
            return back()->with('success', 'Coupon applied!');
        } catch (\Exception $e) {
            return back()->withErrors(['coupon' => $e->getMessage()]);
        }
    }

    public function removeCoupon()
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user());
        $this->cartService->removeCoupon($cart);
        return back()->with('success', 'Coupon removed!');
    }
}

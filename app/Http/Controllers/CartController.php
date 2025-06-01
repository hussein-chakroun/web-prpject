<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Cart_items;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
 /**
     * Display the current user's cart.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        return view('cart.index', compact('cart'));
    }

    public function viewCart()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
    
        // Eager load the cart items with their products
        $cartItems = $cart ? $cart->cartItems()->with('product')->get() : collect();

        $totalitmes= $cartItems->count();
    
        return view('cart', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'totalitems'=>$totalitmes
        ]);
    }
    
    
    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = Cart_items::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity,
            ]
        );

        return redirect()->back()->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart_items::findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove($id)
    {
        $cartItem = Cart_items::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart successfully.');
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        return redirect()->back()->with('success', 'Cart cleared successfully.');
    }
}

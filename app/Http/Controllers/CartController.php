<?php


namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_session_id', session()->getId())->get();
        $total = $cartItems->sum('total');
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            abort(404);
        }

        $cartItem = Cart::where('product_id', $product->id)
            ->where('user_session_id', session()->getId())
            ->first();

        if ($cartItem) {
            $cartItem->quantity++;
            $cartItem->total = $cartItem->quantity * $cartItem->price;
        } else {
            $cartItem = new Cart([
                'product_id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'quantity' => 1,
                'total' => $product->price,
                'user_session_id' => session()->getId()
            ]);
        }

        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }


    public function remove(Cart $cartItem)
    {
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    public function increaseQuantity(Cart $cartItem)
    {
        $cartItem->quantity += 1;
        $cartItem->total = $cartItem->quantity * $cartItem->price;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Product quantity increased!');
    }

    public function decreaseQuantity(Cart $cartItem)
    {
        if ($cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $cartItem->total = $cartItem->quantity * $cartItem->price;
            $cartItem->save();
        } else {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Product quantity decreased!');
    }
}

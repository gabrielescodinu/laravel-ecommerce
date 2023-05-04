<?php


namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->get();
        } else {
            $cartItems = Cart::where('user_session_id', session()->getId())->get();
        }

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
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhere('user_session_id', session()->getId());
            })
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
                'user_id' => Auth::id(),
                'user_session_id' => session()->getId()
            ]);
        }

        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function remove(Cart $cartItem)
    {
        if (Auth::id() == $cartItem->user_id || session()->getId() == $cartItem->user_session_id) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
        }

        return redirect()->route('cart.index')->with('error', 'Unable to remove the product from the cart.');
    }

    public function increaseQuantity(Cart $cartItem)
    {
        if (Auth::id() == $cartItem->user_id || session()->getId() == $cartItem->user_session_id) {
            $cartItem->quantity += 1;
            $cartItem->total = $cartItem->quantity * $cartItem->price;
            $cartItem->save();

            return redirect()->route('cart.index')->with('success', 'Product quantity increased!');
        }

        return redirect()->route('cart.index')->with('error', 'Unable to increase the product quantity.');
    }

    public function decreaseQuantity(Cart $cartItem)
    {
        if (Auth::id() == $cartItem->user_id || session()->getId() == $cartItem->user_session_id) {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity -= 1;
                $cartItem->total = $cartItem->quantity * $cartItem->price;
                $cartItem->save();
            } else {
                $cartItem->delete();
            }

            return redirect()->route('cart.index')->with('success', 'Product quantity decreased!');
        }

        return redirect()->route('cart.index')->with('error', 'Unable to decrease the product quantity.');
    }
}

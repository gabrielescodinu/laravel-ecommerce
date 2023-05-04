<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\Payment;

class CheckoutController extends Controller
{
    public function showCheckoutForm()
    {
        $cartItems = Cart::where('user_id', Auth::id())
        ->orWhere('user_session_id', session()->getId())
        ->get();

        $total = $cartItems->sum('total');

        return view('checkout', compact('cartItems', 'total'));
    }

    public function processPayment(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $cartItems = Cart::where('user_id', Auth::id())
            ->orWhere('user_session_id', session()->getId())
            ->get();

        $totalAmount = $cartItems->sum('total');

        $charge = Charge::create([
            'amount' => $totalAmount * 100, // Amount in cents
            'currency' => 'usd',
            'source' => $request->stripeToken,
            'description' => 'Payment from ' . Auth::user()->email,
        ]);

        // Save cart items as payments
        foreach ($cartItems as $item) {
            Payment::create([
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'total' => $item->total,
            ]);

            // Clear the cart item after saving as payment
            $item->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Payment successful!');
    }
}

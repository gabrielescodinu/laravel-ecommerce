<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;

class CheckoutController extends Controller
{
    public function showCheckoutForm()
    {
        return view('checkout');
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

        // Clear the cart after successful payment
        foreach ($cartItems as $item) {
            $item->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Payment successful!');
    }
}

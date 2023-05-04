<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        // Store the previous session ID
        $previousSessionId = session()->getId();
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            // Get the new session ID
            $newSessionId = session()->getId();
    
            // Update the cart items with the new session ID
            $cartItems = Cart::where('user_session_id', $previousSessionId)->get();
            foreach ($cartItems as $cartItem) {
                $cartItem->user_id = $user->id;
                $cartItem->user_session_id = $newSessionId;
                $cartItem->save();
            }
    
            return redirect()->intended('/cart');
        }
    
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    
    
    
    
}

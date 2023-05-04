<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function adminPayments()
    {
        // Verifica se l'utente corrente è un amministratore
        if (!Auth::user()->is_admin) {
            return redirect()->route('home')->withErrors(['unauthorized' => 'You are not authorized to view this page.']);
        }
    
        $payments = Payment::with('user', 'product')->orderBy('created_at', 'desc')->get();
        return view('payments.admin_payments', compact('payments'));
    }
    
    
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())->with('product')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($payment) {
                return $payment->created_at->format('Y-m-d H:i:s');
            });

        return view('payments.index', compact('payments'));
    }
}

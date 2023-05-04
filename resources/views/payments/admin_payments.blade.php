@extends('layouts.app')

@section('content')
    <div class="container">
        <a class="btn btn-secondary mb-3" href="{{ route('home') }}">Back to Home</a>
        <h1>All Payments</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <!-- ... -->
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->user->name }}</td>
                        <td>{{ $payment->product->name }}</td>
                        <td>{{ $payment->quantity }}</td>
                        <td>{{ $payment->price }}</td>
                        <td>{{ $payment->total }}</td>
                        <td>{{ $payment->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <!-- ... -->

        </table>
    </div>
@endsection

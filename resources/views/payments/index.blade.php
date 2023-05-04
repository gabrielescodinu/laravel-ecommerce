@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Your Payment History</h1>
        @foreach ($payments as $date => $paymentsGroup)
            <h2>{{ $date }}</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentsGroup as $payment)
                        <tr>
                            <td>{{ $payment->product_id }}</td>
                            <td>{{ $payment->name }}</td>
                            <td>{{ $payment->price }}</td>
                            <td>{{ $payment->quantity }}</td>
                            <td>{{ $payment->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <a class="btn btn-secondary mb-3" href="{{ route('home') }}">Back to Home</a>
        <h1>Your Cart</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                    <tr>
                        <td>{{ $item->product_id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->total }}</td>
                        <td>
                            <form action="{{ route('cart.increase', $item) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">+</button>
                            </form>
                            <form action="{{ route('cart.decrease', $item) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">-</button>
                            </form>
                        </td>                        
                        <td>
                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right">Total:</td>
                    <td>{{ $total }}</td>
                    <td></td>
                    if total is greater than 0, show checkout button
                    <td>
                        @if ($total > 0)
                            <a href="{{ route('checkout') }}" class="btn btn-primary">Checkout</a>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

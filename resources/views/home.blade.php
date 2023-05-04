@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
            <br>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('products.index') }}" class="btn btn-primary">View Products</a>
                <a href="{{ route('categories.index') }}" class="btn btn-primary">View Categories</a>
                <a href="{{ route('admin.payments') }}" class="btn btn-primary">View Payments</a>
                @else
                <a href="{{ route('products.publicView') }}" class="btn btn-primary">View Products</a>
                <a href="{{ route('cart.index') }}" class="btn btn-primary">View Cart</a>
                <a href="{{ route('payments.index') }}" class="btn btn-primary">View Payments</a>
            @endif

        </div>
    </div>
</div>
@endsection

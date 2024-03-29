@extends('layouts.app')

@section('content')
    <div class="container">
        <a class="btn btn-sm btn-info" href="{{ route('products.publicView') }}">Back to products list</a>
        <h1>{{ $product->name }}</h1>
        <p>{{ $product->description }}</p>
        <p>Price: {{ $product->price }}</p>
        <p>Category: {{ $product->category->name }}</p>
        <img class="img-thumbnail" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-height: 200px">
        
        <form method="POST" action="{{ route('cart.add', $product->id) }}">
            @csrf
            <button type="submit" class="btn btn-success">Add to cart</button>
        </form>
    </div>

    
@endsection

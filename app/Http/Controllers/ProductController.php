<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image',
            'category_id' => 'required|exists:categories,id',
        ]);

        $imagePath = $request->file('image')->store('public/images');
        $product = new Product([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'image' => str_replace('public/', '', $imagePath),
            'category_id' => $validatedData['category_id'],
        ]);
        $product->save();

        return redirect('/products')->with('success', 'Product saved!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'image',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            // Elimina l'immagine precedente
            Storage::delete('public/' . $product->image);

            $imagePath = $request->file('image')->store('public/images');
            $product->image = str_replace('public/', '', $imagePath);
        }

        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->category_id = $validatedData['category_id'];
        $product->save();

        return redirect('/products')->with('success', 'Product updated!');
    }



    public function destroy(Product $product)
    {
        // Elimina l'immagine associata al prodotto
        Storage::delete('public/' . $product->image);

        // Elimina il prodotto dal database
        $product->delete();

        return redirect('/products')->with('success', 'Product deleted!');
    }

    public function publicView()
    {
        $products = Product::all();
        return view('products.publicView', compact('products'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Sports;
use App\Models\Product;
use App\Models\Dojo;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index() {
        //fetch all records & pass into the index view
        $products = Product::all();
        return view('sports.index', compact('products'));
    }

    public function welcome() {
        //fetch all records & pass into the welcome view
            $products = Product::all();
            return view('welcome', compact('products'));
        }

    public function show(Product $product) {
        //fetch a single record & pass into the show view
        return view('sports.show', compact('product'));
    }

    public function create() {
        //render a create view (with web form) to admin
        return view('sports.create');
    }

    public function store(Request $request) {
        //handle POST request to store a new product record in table
        $validated = $request->validate([
            'pname' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'bio' => 'required|string|min:20|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('images', 'public');

        $product = new Product();
        $product->pname = $request->pname;
        $product->price = $request->price;
        $product->bio = $request->bio;
        $product->image_path = $imagePath;
        $product->save();

        return redirect()->route('sports.index')->with('success', 'Item added successfully!');
    }

    public function destroy(Product $product) {
        $product->delete();

        return redirect()->route('sports.index')->with('success', 'Product Deleted!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('sports.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pname' => 'required|string|max:255',
            'bio' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);

        // Update product details
        $product->pname = $validated['pname'];
        $product->bio = $validated['bio'];

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image_path = $imagePath;
        }

        $product->save();

        return redirect()->route('sports.show', $product->id)->with('success', 'Product updated successfully.');
    }

}


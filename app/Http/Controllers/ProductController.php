<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\Product;

class ProductController extends Controller
{
    //index
    public function index(){
        $products = \App\Models\Product::paginate(10);
        return view('pages.product.index', compact('products'));
    }

    //create
    public function create(){
        $categories = \App\Models\Category::all();
        return view('pages.product.create', compact('categories'));
    }

    //store
    public function store(Request $request){
        //validate the request
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'stock' => 'required|numeric',
            'is_available' => 'required|boolean',
            'is_favorite' => 'required|boolean',
        ]);

        //store request
        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->stock = $request->stock;
        $product->is_available = $request->is_available;
        $product->is_favorite = $request->is_favorite;

        $product->save();

        //save image
        if($request->hasFile('image')){
            $image = $request->file('image');
            $image->storeAs('public/products', $product->id . '.' . $image->getClientOriginalExtension());
            $product->image = 'storage/products/' . $product->id . '.' . $image->getClientOriginalExtension();
            $product->save();
        }
        return redirect()->route('product.index')->with('success', 'Product created successfully');


        //yg di bawah juga bisa
        // $filename = time() . '.' . $request->image->extension();
        // $request->image->storeAs('public/products', $filename);
        // // $data = $request->all();

        // $product = new \App\Models\Product;
        // $product->name = $request->name;
        // $product->description = $request->description;
        // $product->price = (int) $request->price;
        // $product->stock = (int) $request->stock;
        // $product->category_id = $request->category_id;
        // $product->image = $filename;
        // $product->save();

        // return redirect()->route('product.index');
    }


    // show
    public function show($id)
    {
        return view('pages.product.show');
    }

    // edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = DB::table('categories')->get();
        return view('pages.product.edit', compact('product', 'categories'));
    }

    // update
    public function update(Request $request, $id)
    {
        // validate the request...
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'stock' => 'required|numeric',
            'is_available' => 'required|boolean',
            'is_favorite' => 'required|boolean',
        ]);

        // update the request...
        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->stock = $request->stock;
        $product->is_available = $request->is_available;
        $product->is_favorite = $request->is_favorite;
        $product->save();

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/products', $product->id . '.' . $image->getClientOriginalExtension());
            $product->image = 'storage/products/' . $product->id . '.' . $image->getClientOriginalExtension();
            $product->save();
        }

        return redirect()->route('product.index')->with('success', 'Product updated successfully');
    }

    // destroy
    public function destroy($id)
    {
        // delete the request...
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product deleted successfully');
    }
}

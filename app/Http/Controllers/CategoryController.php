<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CategoryController extends Controller
{

    //index
    public function index(Request $request)
    {
        //get user with pagination with where
        $categories = DB::table('categories')
            ->where('name', 'like', '%' . $request->search . '%')->paginate(10);

        //get category with pagination with when
        // $users = DB::table('categories')
        // ->when($request->input('name'), function ($query, $name){
        //     return $query->where('name', 'like', '%' . $name . '%');
        // } )->paginate(10);
        return view('pages.category.index', compact('categories'));
    }
    //create
    public function create()
    {
        return view("pages.category.create");
    }

    //store
    public function store(Request $request)
    {
        // $data = $request->all();
        // Category::create($data);
        // return redirect()->route('category.index');

        //validate the request...
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //store the request...
        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }

    //show
    public function show($id)
    {
        return view("pages.category.show");
    }

    //edit
    public function edit($id)
    {
        $categories = Category::findOrFail($id);
        return view('pages.category.edit', compact('categories'));
    }

    //update
    public function update(Request $request, $id)
    {

        //validate the request...
        $request->validate([
            'name' => 'required',
            // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //update the request...
        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        return redirect()->route('category.index')->with('success', 'Category updated successfully');
        // $data = $request->all();
        // $category = Category::findOrFail($id);

        // $category->update($data);
        // return redirect()->route('category.index');
    }

    //destroy
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index');
    }
}

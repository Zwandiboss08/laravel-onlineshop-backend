<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CategoryController extends Controller
{
    // //index
    // public function index(){
    //     $categories = \App\Models\Category::paginate(5);
    // return view('pages.category.index', compact('categories'));

    // }


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
        $data = $request->all();
        Category::create($data);
        return redirect()->route('category.index');
    }

    //show
    public function show($id)
    {
        return view("pages.dashboar");
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
        $data = $request->all();
        $category = Category::findOrFail($id);

        $category->update($data);
        return redirect()->route('category.index');
    }

    //destroy
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index');
    }
}

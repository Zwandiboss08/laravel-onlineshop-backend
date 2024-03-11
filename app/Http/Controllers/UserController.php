<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //index
    public function index(Request $request)
    {
        //get user with pagination with where
        // $users = DB::table('users')
        //     ->where('name', 'like', '%' . $request->search . '%')->paginate(10);

        //get user with pagination with when
        $users = DB::table('users')
        ->when($request->input('name'), function ($query, $name){
            return $query->where('name', 'like', '%' . $name . '%');
        } )->paginate(10);
        return view('pages.user.index', compact('users'));
    }
    //create
    public function create()
    {
        return view("pages.user.create");
    }

    //store
    public function store(Request $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));
        User::create($data);
        return redirect()->route('user.index');
    }

    //show
    public function show($id)
    {
        return view("pages.dashboar");
    }

    //edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.edit', compact('user'));
    }

    //update
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user = User::findOrFail($id);
        // check if password is empty
        if($request->input('password')){
            $data['password'] = Hash::make($request->input('password'));
        } else {
            $data['password'] = $user->password;
        }

        $user->update($data);
        return redirect()->route('user.index');
    }

    //destroy
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index');
    }
}

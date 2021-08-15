<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $users = User::with("questions")->get();
        return response()->json($users);
    }
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }
    public function store(Request $req,$id)
    {
        $user = User::create(
            $req->only(
                "first_name",
                "last_name",
                "email",
                "password",
                )
        );
        return response()->json($user,Response::HTTP_CREATED);
    }
    public function update(Request $req,$id)
    {
        $user = User::find($id);
        $user::create(
            $req->only(
                "first_name",
                "last_name",
                "email",
                "password",
            )
        );
        return response()->json($user,Response::HTTP_ACCEPTED);
    }
    public function delete($id)
    {
        User::destroy($id);
        return response(null,Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $req){
        $user = User::create([
            "first_name"=>$req->input("first_name"),
            "last_name"=>$req->input("last_name"),
            "email"=>$req->input("email"),
            "next_assignment"=>date("Y-m-d"),
            "password"=>Hash::make($req->input("password"))
        ]);
        return response($user,Response::HTTP_CREATED);
    }
    public function login(Request $req){
        if(!Auth::attempt($req->only("email","password"))){
            return response([
                "error" => "invalid credentials"
            ],Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $jwt = $user->createToken("token")->plainTextToken;
        $cookie = cookie(
            "jwt",$jwt, 60 * 24
        );
        return response([
            "jwt"=>$jwt,
            "user"=>$user
        ])->withCookie($cookie);
    }
    public function user(Request $req){
        return $req->user();
    }
    public function logout(){
        $cookie = Cookie::forget("jwt");
        return response([
            "message" => "success"
        ])->withCookie($cookie);
    }
    public function updateInfo(Request $req){
        $user = $req->user();
        $user->update(
            $req->only("first_name",
                    "last_name",
                    "email")
        );
        return response($user,Response::HTTP_ACCEPTED);
    }
    public function updatePassword(Request $req){
        $user = $req->user();
        $user->update([
            'password' => Hash::make($req->input('password'))
        ]);
        return response($user,Response::HTTP_ACCEPTED);
    }
}
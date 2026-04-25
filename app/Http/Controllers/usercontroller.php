<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class usercontroller extends Controller
{
    //
    function creater(Request $req){
        $user = User::create([
            "name" => "Abdallah",
            "email"=> "abdallah@example.com",
            "password"=> "123456789",
        ]);
        return $user;
        // return ('createrrrr');
    }

    function register(Request $req){
        $newuser = User::create([
            "name"=> $req -> input ('name'),
            'email'=> $req -> input ('email'),
            "password"=>  $req -> input ('password'),
        ]);
        return $newuser;
    }

    function login(Request $req){
        $user = User::where('email', $req -> input('email'))->first();
        if(! $user){
            return response()->json(['error' => 'User not found'], 404);
        }
        
        if(!Hash::check($req -> input('password'), $user -> password)){
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user -> createToken('auth_token') -> plainTextToken;
        return response()->json(['message' => 'Login successful', 'token' => $token], 200);

        return $user;

}
};
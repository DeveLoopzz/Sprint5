<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' =>'required|unique:users|max:255',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token=$user->createToken('auth_token')->accessToken;

        return response([
            'token' => $token
        ]);
    }

    public function login(Request $request){}

    public function logout(Request $request){}
}

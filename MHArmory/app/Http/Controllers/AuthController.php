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
            'name' => 'max:255',
            'email' =>'required|unique:users|max:255|',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name  ?? 'anónimo',
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' =>'required|max:255|email',
            'password' => 'required|min:6'
        ]);

        $foundUser = User::where('email', $request->email)->first();

        if(isset($foundUser)) {
            $token = $foundUser->createToken('auth_token')->accessToken;

        }
        else{
            return "Invalid user credentials";
        }
    }

    public function logout(Request $request){}
}

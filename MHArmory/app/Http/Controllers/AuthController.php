<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' =>'required|unique:users|max:255',
            'password' => 'required|min:6'
        ]);
    }

    public function login(Request $request){}

    public function logout(Request $request){}
}

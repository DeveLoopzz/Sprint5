<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;

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

        $role = Role::where('name', 'hunter')->first();
        $user->assignRole($role);

        return response()->json([
            'message' => 'User Registered Successfully',
            'user' => $user
            ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' =>'required|max:255|email',
            'password' => 'required|min:6'
        ]);
        $foundUser = User::where('email', $request->email)->first();

        if(isset($foundUser)) {
            if(Hash::check($request->password, $foundUser->password)){ 
                $token = $foundUser->createToken('auth_token')->accessToken;
                return response()->json([
                    'token' => $token,
                    'message' => 'Logged in Successfuly'
                ], 200);
            }
        }
        else{
            return response()->json([
                'message' => 'Invalid credentials'
            ],401);
        }
    }

    public function logout(Request $request)
    {
        if(!$request->user()){
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logged out Successfully'
        ], 200);

    }
}

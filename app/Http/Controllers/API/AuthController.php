<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);
        
        $role = Role::where('name', 'user')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id
        ]);
        

        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User created successfully',
            'token' => $token,
            'user' => $user->load('role')
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) return response()->json(['message' => 'Email or password is incorrect'], 401);

        return response()->json([
            'message' => 'User logged in successfully',
            'token' => $token,
            'user' => auth()->user()->load('role')
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User logged out successfully']);
    }

    public function me()
    {
        $user = auth()->user()->load('role');

        return response()->json([
            "message" => "User details",
            "user" => $user
        ]);
    }

}

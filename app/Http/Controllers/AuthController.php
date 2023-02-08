<?php

namespace App\Http\Controllers;

use Illiminate\Support\Facades\Auth;
// use App\Http\User;
use App\Models\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth as FacadesAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        // validate fields 
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // create user 
        $user = User::create(
            [
                'name' => $attrs['name'],
                'email' => $attrs['email'],
                'password' => bcrypt($attrs['password'])
            ]
        );

        // return user and token in response 
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ]);
    }

    public function login(Request $request)
    {

        // validate fields 
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // attenmt  login
        if (Auth::attempt($attrs)) {
            return response([
                'message' => 'invalide credentials'
            ], 403);
        }


        // return user and token in response 
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout success'
        ], 200);
    }


    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }

    public function updateu(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'profiles');

        auth()->user()->update([
            'name' => $attrs['name'],
            'image' => $image
        ]);
        return response([
            'message' => 'User updated',

            'user' => auth()->user()
        ], 200);
    }
}

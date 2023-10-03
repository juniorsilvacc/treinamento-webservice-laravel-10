<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 10|kvAxijMWMTkoJ8ZhVso3Xs95EYDCCEYIa6BhIiKWa89c5884
// 8|shBoZQOGnz2uodisRCr26p7wut2vlabhAlTew1I67f893e22

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('my-token', ['categories:create'])->plainTextToken;

            return response()->json(['access_token' => $token]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('my-token')->plainTextToken;

        return response()->json(['access_token' => $token]);
    }
}

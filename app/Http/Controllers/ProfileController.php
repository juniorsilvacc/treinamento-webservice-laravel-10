<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // O usuário está autenticado
            return $user;
        } else {
            // O usuário não está autenticado
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }
    }
}

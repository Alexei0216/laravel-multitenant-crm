<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('access')->plainTextToken;

        return response()
            ->json(['user' => $user])
            ->cookie(
                'access_token',
                $token,
                60 * 24,
                '/',
                null,
                true,
                true,
            );
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->attributes->get('accessToken');

        if ($token) {
            $token->delete();
        }

        return response()
            ->json(['message' => 'Logged out'])
            ->cookie('access_token', '', -1);
    }
}

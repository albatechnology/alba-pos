<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function token(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $checkToken = PersonalAccessToken::findToken($request->bearerToken());
        if ($checkToken?->plain_text_token ?? false) {
            return response()->json(['token' => $checkToken->plain_text_token]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->tokens()?->first()?->plain_text_token ?? $user->createToken('default')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}

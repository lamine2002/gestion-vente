<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    private const TOKEN_EXPIRATION = 60; // 60 minutes

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->createToken($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Créez le jeton
        $token = $this->createToken($user);

        // Retournez le jeton et les informations de l'utilisateur
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function refresh(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());

        if (!$token || $token->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'token' => ['The token is invalid or has expired.'],
            ]);
        }

        $user = $token->tokenable;
        $token->delete();

        return $this->createToken($user);
    }

    private function createToken(User $user)
    {
        $expiresAt = now()->addMinutes(self::TOKEN_EXPIRATION);
        $token = $user->createToken('auth_token', ['*'], $expiresAt);

        return response()->json([
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt->toDateTimeString(),
            'user' => $user
        ]);
    }
}

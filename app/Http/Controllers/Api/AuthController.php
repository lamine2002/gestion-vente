<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            Log::info('Tentative de connexion pour l\'email: ' . $request->email);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                Log::info('Utilisateur non trouvé pour l\'email: ' . $request->email);
                return response()->json([
                    'message' => 'The provided credentials are incorrect.',
                    'errors' => [
                        'email' => ['User not found.']
                    ]
                ], 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                Log::info('Mot de passe incorrect pour l\'email: ' . $request->email);
                return response()->json([
                    'message' => 'The provided credentials are incorrect.',
                    'errors' => [
                        'password' => ['Incorrect password.']
                    ]
                ], 401);
            }

            // Créez le jeton
            $token = $this->createToken($user);

            Log::info('Connexion réussie pour l\'utilisateur: ' . $user->email);

            // Retournez le jeton et les informations de l'utilisateur
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la connexion: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during login.',
                'error' => $e->getMessage()
            ], 500);
        }
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

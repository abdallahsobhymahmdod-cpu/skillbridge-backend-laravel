<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private function generateJwtToken(User $user): string
    {
        $secret = env('JWT_SECRET');

        $ttl = (int) env('JWT_TTL', 1440); // minutes

        $now = time();

        $payload = [
            'iss' => config('app.url'),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + ($ttl * 60),
            'sub' => $user->id,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
            ],
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user',
            'status' => 'active',
        ]);

        $sanctumToken = $user->createToken('auth_token')->plainTextToken;
        $jwtToken = $this->generateJwtToken($user);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully.',
            'data' => [
                'user' => $user,
                'token' => $sanctumToken,
                'sanctum_token' => $sanctumToken,
                'jwt_token' => $jwtToken,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        if ($user->status === 'blocked') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is blocked.',
            ], 403);
        }

        $user->update([
            'last_login_at' => now(),
        ]);

        $sanctumToken = $user->createToken('auth_token')->plainTextToken;
        $jwtToken = $this->generateJwtToken($user);

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => $user->fresh(),
                'token' => $sanctumToken,
                'sanctum_token' => $sanctumToken,
                'jwt_token' => $jwtToken,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    public function me(): JsonResponse
    {
        $user = auth()->user()->load('profile');

        return response()->json([
            'status' => true,
            'message' => 'Authenticated user fetched successfully.',
            'data' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $validated['email']],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');

        $resetLink = $frontendUrl . '/reset-password?email=' . urlencode($validated['email']) . '&token=' . $token;

        Mail::raw("Click this link to reset your password: " . $resetLink, function ($message) use ($validated) {
            $message->to($validated['email'])
                ->subject('Reset your password');
        });

        return response()->json([
            'status' => true,
            'message' => 'Password reset link sent successfully.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->where('token', $validated['token'])
            ->first();

        if (!$resetToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid reset token.',
            ], 400);
        }

        $createdAt = strtotime($resetToken->created_at);

        if ($createdAt && now()->timestamp - $createdAt > 3600) {
            DB::table('password_reset_tokens')
                ->where('email', $validated['email'])
                ->delete();

            return response()->json([
                'status' => false,
                'message' => 'Reset token expired.',
            ], 400);
        }

        $user = User::where('email', $validated['email'])->first();

        $user->update([
            'password_hash' => Hash::make($validated['password']),
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully.',
        ]);
    }
}
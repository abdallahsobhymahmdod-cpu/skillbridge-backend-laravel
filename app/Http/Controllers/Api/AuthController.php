<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user',
            'status' => 'active',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password_hash)) {
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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
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
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');

        $resetLink = $frontendUrl . '/reset-password?email=' . urlencode($validated['email']) . '&token=' . urlencode($token);

        Mail::raw("Click this link to reset your password:\n\n" . $resetLink, function ($message) use ($validated) {
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

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (! $resetRecord) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired reset token.',
            ], 400);
        }

        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')
                ->where('email', $validated['email'])
                ->delete();

            return response()->json([
                'status' => false,
                'message' => 'Reset token has expired.',
            ], 400);
        }

        if (! Hash::check($validated['token'], $resetRecord->token)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid reset token.',
            ], 400);
        }

        $user = User::where('email', $validated['email'])->first();

        $user->update([
            'password_hash' => Hash::make($validated['password']),
        ]);

        $user->tokens()->delete();

        DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully.',
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Authenticated user fetched successfully.',
            'data' => auth()->user()->load('profile'),
        ]);
    }
}
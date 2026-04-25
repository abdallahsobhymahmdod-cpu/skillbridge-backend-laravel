<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function profile(): JsonResponse
    {
        $user = auth()->user()->load('profile');

        return response()->json([
            'status' => true,
            'message' => 'Profile fetched successfully.',
            'data' => $user,
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = auth()->user();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => null,
                'avatar_url' => null,
                'country' => null,
                'rating_avg' => 0,
                'total_reviews' => 0,
            ]
        );

        $profile->update([
            'bio' => $validated['bio'] ?? $profile->bio,
            'avatar_url' => $validated['avatar_url'] ?? $profile->avatar_url,
            'country' => $validated['country'] ?? $profile->country,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user->load('profile'),
        ]);
    }
}
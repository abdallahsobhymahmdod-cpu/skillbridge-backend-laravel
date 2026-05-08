<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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

        $avatarUrl = $profile->avatar_url;

        if ($request->hasFile('avatar')) {
            if ($profile->avatar_url && str_contains($profile->avatar_url, '/storage/avatars/')) {
                $oldPath = str_replace('/storage/', '', parse_url($profile->avatar_url, PHP_URL_PATH));

                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $avatarUrl = asset('storage/' . $path);
        } elseif (array_key_exists('avatar_url', $validated)) {
            $avatarUrl = $validated['avatar_url'];
        }

        $profile->update([
            'bio' => $validated['bio'] ?? $profile->bio,
            'avatar_url' => $avatarUrl,
            'country' => $validated['country'] ?? $profile->country,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user->load('profile'),
        ]);
    }
}
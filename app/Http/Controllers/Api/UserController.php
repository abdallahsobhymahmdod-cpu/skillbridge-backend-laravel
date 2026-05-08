<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function addSkill(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'skill_id' => ['required', 'integer', 'exists:skills,id'],
            'type' => ['required', 'in:offer,want'],
            'level' => ['required', 'in:beginner,intermediate,advanced'],
        ]);

        $userSkill = UserSkill::create([
            'user_id' => auth()->id(),
            'skill_id' => $validated['skill_id'],
            'type' => $validated['type'],
            'level' => $validated['level'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Skill added to user successfully.',
            'data' => $userSkill->load('skill'),
        ], 201);
    }

   public function index(): JsonResponse
{
    $users = User::with([
        'profile',
        'userSkills.skill.category',
    ])->get();

    return response()->json([
        'status' => true,
        'message' => 'Users fetched successfully.',
        'data' => $users,
    ]);
}

    public function show($id): JsonResponse
{
    $user = User::with([
        'profile',
        'userSkills.skill.category',
        'reviewsWritten',
        'reviewsReceived',
        'activityLogs',
    ])->find($id);

    if (! $user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found.',
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'User details fetched successfully.',
        'data' => $user,
    ]);
}

    public function allReviews(): JsonResponse
    {
        $reviews = Review::with(['reviewer', 'reviewedUser'])->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Reviews fetched successfully.',
            'data' => $reviews,
        ]);
    }

    public function userReviews(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $reviews = Review::with('reviewer')
            ->where('reviewed_user_id', $id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'User reviews fetched successfully.',
            'data' => $reviews,
        ]);
    }

    public function block(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        if ($user->status === 'blocked') {
            return response()->json([
                'status' => false,
                'message' => 'User is already blocked.',
            ], 400);
        }

        $user->update(['status' => 'blocked']);

        return response()->json([
            'status' => true,
            'message' => 'User blocked successfully.',
            'data' => $user,
        ]);
    }

    public function unblock(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        if ($user->status === 'active') {
            return response()->json([
                'status' => false,
                'message' => 'User is already active.',
            ], 400);
        }

        $user->update(['status' => 'active']);

        return response()->json([
            'status' => true,
            'message' => 'User unblocked successfully.',
            'data' => $user,
        ]);
    }
}
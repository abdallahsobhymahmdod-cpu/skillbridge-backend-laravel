<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matching;
use App\Models\UserSkill;
use Illuminate\Http\JsonResponse;

class MatchController extends Controller
{
    public function index(): JsonResponse
    {
        $matches = Matching::with([
            'userOne',
            'userTwo',
            'userOneSkill',
            'userTwoSkill',
        ])->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Matches fetched successfully.',
            'data' => $matches,
        ]);
    }

    public function generate(): JsonResponse
    {
        $user = auth()->user();

        $userSkills = UserSkill::where('user_id', $user->id)->get();

        $createdMatches = [];

        foreach ($userSkills as $userSkill) {
            $oppositeType = $userSkill->type === 'offer' ? 'want' : 'offer';

            $potentialSkills = UserSkill::where('skill_id', $userSkill->skill_id)
                ->where('type', $oppositeType)
                ->where('user_id', '!=', $user->id)
                ->get();

            foreach ($potentialSkills as $potentialSkill) {
                $exists = Matching::where(function ($query) use ($userSkill, $potentialSkill, $user) {
                    $query->where('user1_id', $user->id)
                        ->where('user2_id', $potentialSkill->user_id)
                        ->where('user1_skill_id', $userSkill->skill_id)
                        ->where('user2_skill_id', $potentialSkill->skill_id);
                })->orWhere(function ($query) use ($userSkill, $potentialSkill, $user) {
                    $query->where('user1_id', $potentialSkill->user_id)
                        ->where('user2_id', $user->id)
                        ->where('user1_skill_id', $potentialSkill->skill_id)
                        ->where('user2_skill_id', $userSkill->skill_id);
                })->exists();

                if (! $exists) {
                    $createdMatches[] = Matching::create([
                        'user1_id' => $user->id,
                        'user2_id' => $potentialSkill->user_id,
                        'user1_skill_id' => $userSkill->skill_id,
                        'user2_skill_id' => $potentialSkill->skill_id,
                        'status' => 'pending',
                    ])->load([
                        'userOne',
                        'userTwo',
                        'userOneSkill',
                        'userTwoSkill',
                    ]);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Matches generated successfully.',
            'data' => $createdMatches,
        ], 201);
    }

    public function suggested(): JsonResponse
    {
        $matches = Matching::with([
            'userOne',
            'userTwo',
            'userOneSkill',
            'userTwoSkill',
        ])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Suggested matches fetched successfully.',
            'data' => $matches,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $match = Matching::with([
            'userOne.profile',
            'userTwo.profile',
            'userOneSkill',
            'userTwoSkill',
        ])->find($id);

        if (! $match) {
            return response()->json([
                'status' => false,
                'message' => 'Match not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Match details fetched successfully.',
            'data' => $match,
        ]);
    }
}
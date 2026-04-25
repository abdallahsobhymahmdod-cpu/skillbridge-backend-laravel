<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Skill\StoreSkillRequest;
use App\Http\Requests\Skill\UpdateSkillRequest;
use App\Models\ActivityLog;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    public function index(): JsonResponse
    {
        $skills = Skill::with('category')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Skills fetched successfully.',
            'data' => $skills,
        ]);
    }

    public function store(StoreSkillRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $skill = Skill::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_skill',
            'description' => 'User created skill: ' . $skill->name,
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Skill created successfully.',
            'data' => $skill->load('category'),
        ], 201);
    }

    public function update(UpdateSkillRequest $request, int $id): JsonResponse
    {
        $skill = Skill::find($id);

        if (! $skill) {
            return response()->json([
                'status' => false,
                'message' => 'Skill not found.',
            ], 404);
        }

        $validated = $request->validated();

        $skill->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'is_active' => $validated['is_active'],
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_skill',
            'description' => 'User updated skill: ' . $skill->name,
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Skill updated successfully.',
            'data' => $skill->load('category'),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $skill = Skill::find($id);

        if (! $skill) {
            return response()->json([
                'status' => false,
                'message' => 'Skill not found.',
            ], 404);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_skill',
            'description' => 'User deleted skill: ' . $skill->name,
            'created_at' => now(),
        ]);

        $skill->delete();

        return response()->json([
            'status' => true,
            'message' => 'Skill deleted successfully.',
        ]);
    }
}
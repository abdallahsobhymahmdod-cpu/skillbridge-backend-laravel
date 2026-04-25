<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    public function index(): JsonResponse
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Recent activity fetched successfully.',
            'data' => $logs,
        ]);
    }

    public function userActions(int $id): JsonResponse
    {
        $logs = ActivityLog::with('user')
            ->where('user_id', $id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'User activity fetched successfully.',
            'data' => $logs,
        ]);
    }
}
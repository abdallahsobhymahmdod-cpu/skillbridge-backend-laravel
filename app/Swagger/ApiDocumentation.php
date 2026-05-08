<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/auth/register',
    summary: 'Register new user',
    tags: ['Auth'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password', 'password_confirmation'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Abdallah Sobhy'),
                new OA\Property(property: 'email', type: 'string', example: 'abdallahsobhymahmdod@gmail.com'),
                new OA\Property(property: 'password', type: 'string', example: '12345678'),
                new OA\Property(property: 'password_confirmation', type: 'string', example: '12345678'),
                new OA\Property(property: 'role', type: 'string', example: 'user'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'User registered successfully'),
        new OA\Response(response: 422, description: 'Validation error'),
    ]
)]
#[OA\Post(
    path: '/api/auth/login',
    summary: 'Login user',
    tags: ['Auth'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', example: 'abdallahsobhymahmdod@gmail.com'),
                new OA\Property(property: 'password', type: 'string', example: '87654321'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Login successful'),
        new OA\Response(response: 401, description: 'Invalid email or password'),
        new OA\Response(response: 403, description: 'Account blocked'),
    ]
)]
#[OA\Post(
    path: '/api/auth/forgot-password',
    summary: 'Send password reset link',
    tags: ['Auth'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email'],
            properties: [
                new OA\Property(property: 'email', type: 'string', example: 'abdallahsobhymahmdod@gmail.com'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Password reset link sent successfully'),
        new OA\Response(response: 422, description: 'Validation error'),
    ]
)]
#[OA\Post(
    path: '/api/auth/reset-password',
    summary: 'Reset password using token',
    tags: ['Auth'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'token', 'password', 'password_confirmation'],
            properties: [
                new OA\Property(property: 'email', type: 'string', example: 'abdallahsobhymahmdod@gmail.com'),
                new OA\Property(property: 'token', type: 'string', example: 'reset_token_from_email'),
                new OA\Property(property: 'password', type: 'string', example: '87654321'),
                new OA\Property(property: 'password_confirmation', type: 'string', example: '87654321'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Password reset successfully'),
        new OA\Response(response: 400, description: 'Invalid or expired reset token'),
        new OA\Response(response: 422, description: 'Validation error'),
    ]
)]
#[OA\Post(
    path: '/api/logout',
    summary: 'Logout authenticated user',
    security: [['bearerAuth' => []]],
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'Logged out successfully'),
        new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Get(
    path: '/api/me',
    summary: 'Get authenticated user',
    security: [['bearerAuth' => []]],
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'Authenticated user fetched successfully'),
        new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]

#[OA\Get(
    path: '/api/users',
    summary: 'Get all users',
    security: [['bearerAuth' => []]],
    tags: ['Users'],
    responses: [
        new OA\Response(response: 200, description: 'Users fetched successfully'),
        new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Get(
    path: '/api/users/{id}',
    summary: 'Get user details',
    security: [['bearerAuth' => []]],
    tags: ['Users'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer'),
            example: 1
        ),
    ],
    responses: [
        new OA\Response(response: 200, description: 'User details fetched successfully'),
        new OA\Response(response: 404, description: 'User not found'),
    ]
)]
#[OA\Patch(
    path: '/api/users/{id}/block',
    summary: 'Block user',
    security: [['bearerAuth' => []]],
    tags: ['Users'],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
    ],
    responses: [
        new OA\Response(response: 200, description: 'User blocked successfully'),
        new OA\Response(response: 404, description: 'User not found'),
    ]
)]
#[OA\Patch(
    path: '/api/users/{id}/unblock',
    summary: 'Unblock user',
    security: [['bearerAuth' => []]],
    tags: ['Users'],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
    ],
    responses: [
        new OA\Response(response: 200, description: 'User unblocked successfully'),
        new OA\Response(response: 404, description: 'User not found'),
    ]
)]

#[OA\Get(
    path: '/api/skills',
    summary: 'Get all skills',
    security: [['bearerAuth' => []]],
    tags: ['Skills'],
    responses: [
        new OA\Response(response: 200, description: 'Skills fetched successfully'),
    ]
)]
#[OA\Post(
    path: '/api/skills',
    summary: 'Create skill',
    security: [['bearerAuth' => []]],
    tags: ['Skills'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['category_id', 'name'],
            properties: [
                new OA\Property(property: 'category_id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'Laravel Backend'),
                new OA\Property(property: 'is_active', type: 'boolean', example: true),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Skill created successfully'),
        new OA\Response(response: 422, description: 'Validation error'),
    ]
)]
#[OA\Put(
    path: '/api/skills/{id}',
    summary: 'Update skill',
    security: [['bearerAuth' => []]],
    tags: ['Skills'],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'category_id', type: 'integer', example: 1),
                new OA\Property(property: 'name', type: 'string', example: 'Laravel Backend'),
                new OA\Property(property: 'is_active', type: 'boolean', example: true),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Skill updated successfully'),
        new OA\Response(response: 404, description: 'Skill not found'),
    ]
)]
#[OA\Delete(
    path: '/api/skills/{id}',
    summary: 'Delete skill',
    security: [['bearerAuth' => []]],
    tags: ['Skills'],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Skill deleted successfully'),
        new OA\Response(response: 404, description: 'Skill not found'),
    ]
)]

#[OA\Post(
    path: '/api/user-skills',
    summary: 'Add skill to authenticated user',
    security: [['bearerAuth' => []]],
    tags: ['User Skills'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['skill_id', 'type', 'level'],
            properties: [
                new OA\Property(property: 'skill_id', type: 'integer', example: 1),
                new OA\Property(property: 'type', type: 'string', example: 'offer'),
                new OA\Property(property: 'level', type: 'string', example: 'beginner'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Skill added to user successfully'),
        new OA\Response(response: 422, description: 'Validation error'),
    ]
)]

#[OA\Get(
    path: '/api/matches',
    summary: 'Get all matches',
    security: [['bearerAuth' => []]],
    tags: ['Matches'],
    responses: [
        new OA\Response(response: 200, description: 'Matches fetched successfully'),
    ]
)]
#[OA\Post(
    path: '/api/matches/generate',
    summary: 'Generate matches',
    security: [['bearerAuth' => []]],
    tags: ['Matches'],
    responses: [
        new OA\Response(response: 201, description: 'Matches generated successfully'),
    ]
)]
#[OA\Get(
    path: '/api/matches/suggested',
    summary: 'Get suggested matches',
    security: [['bearerAuth' => []]],
    tags: ['Matches'],
    responses: [
        new OA\Response(response: 200, description: 'Suggested matches fetched successfully'),
    ]
)]
#[OA\Get(
    path: '/api/matches/{id}',
    summary: 'Get match details',
    security: [['bearerAuth' => []]],
    tags: ['Matches'],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Match details fetched successfully'),
        new OA\Response(response: 404, description: 'Match not found'),
    ]
)]

#[OA\Get(
    path: '/api/reviews',
    summary: 'Get all reviews',
    security: [['bearerAuth' => []]],
    tags: ['Reviews'],
    responses: [
        new OA\Response(response: 200, description: 'Reviews fetched successfully'),
    ]
)]
#[OA\Get(
    path: '/api/users/{id}/reviews',
    summary: 'Get user reviews',
    security: [['bearerAuth' => []]],
    tags: ['Reviews'],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
    ],
    responses: [
        new OA\Response(response: 200, description: 'User reviews fetched successfully'),
    ]
)]

#[OA\Get(
    path: '/api/activity-logs',
    summary: 'Get recent activity logs',
    security: [['bearerAuth' => []]],
    tags: ['Activity Logs'],
    responses: [
        new OA\Response(response: 200, description: 'Recent activity fetched successfully'),
    ]
)]
#[OA\Get(
    path: '/api/users/{id}/activity',
    summary: 'Get user activity logs',
    security: [['bearerAuth' => []]],
    tags: ['Activity Logs'],
    parameters: [
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
    ],
    responses: [
        new OA\Response(response: 200, description: 'User activity fetched successfully'),
    ]
)]

#[OA\Get(
    path: '/api/settings/profile',
    summary: 'Get authenticated user profile',
    security: [['bearerAuth' => []]],
    tags: ['Settings'],
    responses: [
        new OA\Response(response: 200, description: 'Profile fetched successfully'),
        new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Put(
    path: '/api/settings/profile',
    summary: 'Update profile with JSON body',
    security: [['bearerAuth' => []]],
    tags: ['Settings'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Abdallah Sobhy'),
                new OA\Property(property: 'email', type: 'string', example: 'abdallahsobhymahmdod@gmail.com'),
                new OA\Property(property: 'bio', type: 'string', example: 'Backend developer using Laravel'),
                new OA\Property(property: 'avatar_url', type: 'string', example: 'https://example.com/avatar.png'),
                new OA\Property(property: 'country', type: 'string', example: 'Egypt'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Profile updated successfully'),
        new OA\Response(response: 422, description: 'Validation error'),
    ]
)]
#[OA\Post(
    path: '/api/settings/profile',
    summary: 'Update profile with avatar upload',
    security: [['bearerAuth' => []]],
    tags: ['Settings'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['name', 'email'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Abdallah Sobhy'),
                    new OA\Property(property: 'email', type: 'string', example: 'abdallahsobhymahmdod@gmail.com'),
                    new OA\Property(property: 'bio', type: 'string', example: 'Backend developer using Laravel'),
                    new OA\Property(property: 'country', type: 'string', example: 'Egypt'),
                    new OA\Property(property: 'avatar', type: 'string', format: 'binary'),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Profile updated successfully'),
        new OA\Response(response: 422, description: 'Validation error'),
    ]
)]
class ApiDocumentation
{
}
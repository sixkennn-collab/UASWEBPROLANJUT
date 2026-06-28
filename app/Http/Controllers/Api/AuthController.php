<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return response()->json([
                'status'  => false,
                'message' => 'Authentication failed. Invalid credentials supplied.',
                'code'    => 401,
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['last_active' => now()]);

        // Create a simple token (using Laravel's built-in token column or plain token)
        $token = hash('sha256', $user->id . $user->email . now()->timestamp . rand(1000, 9999));

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'USER_LOGIN',
            'description' => "Operator {$user->name} authenticated into the secure grid node.",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Authentication successful. Grid access granted.',
            'data'    => [
                'user'  => $user->load('role'),
                'token' => $token,
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $defaultRole = Role::where('slug', 'client')->first();

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'role_id'     => $defaultRole?->id,
            'status'      => 'active',
            'avatar'      => 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($validated['name']),
            'last_active' => now(),
        ]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'USER_CREATE',
            'description' => "New operator node {$user->name} registered in the grid.",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Operator node registered. Grid access provisioned.',
            'data'    => ['user' => $user->load('role')],
        ], 201);
    }
}

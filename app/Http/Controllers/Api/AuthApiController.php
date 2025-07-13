<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Generate API token for admin users
     */
    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->isAdmin()) {
            return response()->json([
                'message' => 'Access denied. Admin privileges required.'
            ], Response::HTTP_FORBIDDEN);
        }

        // Revoke existing tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('admin-api-token', ['admin:*'])->plainTextToken;

        return response()->json([
            'message' => 'Token generated successfully',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Revoke current API token
     */
    public function revokeToken(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token revoked successfully'
        ]);
    }

    /**
     * Get current authenticated user info
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login via API with Token-Based Authentication
     * Returns API token for subsequent requests
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Attempt authentication
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'errors' => ['email' => ['The provided credentials are invalid.']]
                ], 401);
            }

            $user = Auth::user();

            // Generate API token
            $token = $this->generateApiToken($user);

            return response()->json([
                'success' => true,
                'message' => 'Authenticated successfully',
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 86400, // 24 hours
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'user'
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('API Login Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Authentication error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current authenticated user
     * Requires Bearer token in Authorization header
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user'
            ]
        ], 200);
    }

    /**
     * Logout / Revoke Token
     * Removes the API token for this user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // Revoke the token by clearing it
            $user->update(['api_token' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Check authentication status using token
     * Does not require authentication
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'user'
                ]
            ], 200);
        }

        return response()->json([
            'authenticated' => false,
            'message' => 'Not authenticated'
        ], 401);
    }

    /**
     * Generate API Token for the user
     * Stores token in database for validation
     *
     * @param $user
     * @return string
     */
    private function generateApiToken($user)
    {
        // Generate unique token
        $token = Str::random(60);

        // Store in database
        $user->update(['api_token' => hash('sha256', $token)]);

        return $token;
    }

    /**
     * Refresh API Token
     * Generates a new token and revokes the old one
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        // Generate new token
        $newToken = $this->generateApiToken($user);

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'token' => $newToken,
            'token_type' => 'Bearer',
            'expires_in' => 86400,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user'
            ]
        ], 200);
    }
}

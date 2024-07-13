<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        // Validate request
        $fields = $request->validate([
            'username' => 'required|max:50|unique:users',
            'first_name' => 'required|max:50',
            'email' => 'required|email|max:50|unique:users',
            'password' => 'required|confirmed'
        ]);

        try {
            // create new user
            $user = User::create($fields);

            // create token for user
            $token = $user->createToken($user->username)->plainTextToken;

            // return user and token
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        } catch (Exception) {
            return response()->json([
                'message' => 'Failed to register'
            ], 500);
        }
    }

    /**
     * Login a user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validate request
        $request->validate([
            'username' => 'required|exists:users,username',
            'password' => 'required'
        ]);

        try {
            // check if user exists
            $user = User::where('username', $request->username)->first();

            // check if password is correct
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'The provided credentials are incorrect.'
                ]);
            }

            // create token for user
            $token = $user->createToken($user->username)->plainTextToken;

            // return user and token
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to login'
            ], 500);
        }
    }

    /**
     * Logout a user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // delete token
            $request->user()->tokens()->delete();

            // return success message
            return response()->json([
                'message' => 'Logged out successfully'
            ]);
        } catch (Exception) {
            return response()->json([
                'error' => 'Failed to logout'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Mail\Welcome;
use Illuminate\Support\Facades\Mail;

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
            'password' => 'required|confirmed|min:8', // Ensure strong passwords
        ]);

        try {
            // create new user
            $user = User::create($fields);

            // Create token for user
            $token = $user->createToken($user->username)->plainTextToken;

            // Send welcome email
            $voucher = $user->generateVoucher();

            $data = [
                'title' => 'Welcome to Voucher App',
                'message' => [
                    'user' => ucfirst($user->first_name),
                    'voucher' => $voucher->voucher,
                ],
            ];

            Mail::to($user->email)->send(new Welcome($data));

            // Return user and token
            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to register',
                'error' => $e->getMessage(),
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
            'password' => 'required',
        ]);

        try {
            // Check if user exists
            $user = User::where('username', $request->username)->first();

            // Check if password is correct
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'The provided credentials are incorrect.',
                ], 401);
            }

            // Create token for user
            $token = $user->createToken($user->username)->plainTextToken;

            // Return user and token
            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to login',
                'error' => $e->getMessage(),
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
            // Delete token
            $request->user()->tokens()->delete();

            // Return success message
            return response()->json([
                'message' => 'Logged out successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to logout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

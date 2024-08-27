<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{

    /**
     * register new user
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throw Exception
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->only('name', 'username', 'email', 'password'));
            return response()->json([
                'message' => 'Register successful!',
                'data' => $user
            ],201);
        } catch (Exception $e) {
            Log::error('register failed: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }

    }
    /**
     * user login
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Invalid credentials. Please check your email and password.'
                ], 401);
            }
        } catch (Exception $e) {
            Log::error('Login attempt failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during login. Please try again later.'
            ], 500);
        }

       $user = Auth::user();
       $token = $user->createToken('access-token')->plainTextToken;
       return response()->json([
           'message' => 'Login successful!',
           'data' => $user,
           'token' => $token
       ],200);
    }
    /**
     * user logout
     * delete current access token
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logout successful!',
            ], 200);
        } catch (Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during logout. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * update user profile
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            auth()->user()->update($request->only('name', 'username', 'email'));
            return response()->json([
                'message' => 'Profile update successful!',
            ], 200);
        } catch (Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred during profile update. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * update user password
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request) : JsonResponse
    {
       
       try{
        auth()->user()->update([
            'password' => $request->password
        ]);
        return response()->json([
            'message' => 'Password updated successfully',
            'user' => auth()->user()
        ],200);
       }catch(Exception $e){
           return response()->json([
               'message' => 'Failed to update password',
               'error' => $e->getMessage()
           ], 500);
       }
        
    }
}

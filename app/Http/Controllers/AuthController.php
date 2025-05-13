<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('register'); // resources/views/auth/register.blade.php
    }

    public function showLogPage()
    {
        return view('login'); // resources/views/auth/login.blade.php
    }

    public function homePage()
    {
        return view('home'); // resources/views/auth/login.blade.php
    }

    public function profilePage()
    {
        return view('profile'); // resources/views/auth/login.blade.php
    }

    // Register
    public function postUser(Request $request)
    {
        Log::info("User create/update request received");

        $isUpdate = $request->has('userId');

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('upload/image', $imageName, 'public');
        }

        // Common validation rules
        $rules = [
            'name'     => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'username' => 'required|string|max:255|unique:user,username',
            'email'    => 'required|string|email|max:255|unique:user,email',
        ];

        // If it's an update, ignore current user for unique checks
        if ($isUpdate) {
            $rules['userId'] = 'required|integer|exists:user,userId';

            $rules['username'] .= ',' . $request->userId . ',userId';
            $rules['email']    .= ',' . $request->userId . ',userId';
        } else {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $request->validate($rules);

        try {
            if ($isUpdate) {
                // Fetch the user
                $user = User::where('userId', $request->userId)
                    ->where('is_active', true)
                    ->first();

                if (!$user) {
                    return response()->json(['message' => 'User not found or inactive'], 404);
                }

                // Update fields
                $user->update([
                    'name'          => $request->name,
                    'image'         => $imagePath ?? $user->image,
                    'username'      => $request->username,
                    'email'         => $request->email,
                    'modified_date' => Carbon::now(),
                ]);

                return response()->json(['message' => 'User updated successfully', 'user' => $user]);
            } else {
                // Create new user
                $user = User::create([
                    'name'          => $request->name,
                    'image'         => $imagePath,
                    'username'      => $request->username,
                    'email'         => $request->email,
                    'password'      => Hash::make($request->password),
                    'is_active'     => true,
                    'is_delete'     => false,
                    'created_date'  => Carbon::now(),
                    'modified_date' => Carbon::now(),
                    'deleted_date'  => null,
                ]);

                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message'      => 'User created successfully',
                    'access_token' => $token,
                    'token_type'   => 'Bearer',
                    'user'         => $user,
                ], 201);
            }
        } catch (\Exception $e) {
            Log::error("Error saving user: " . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    // Login Method
    public function login(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'login'    => 'required|string', // Can be email or username
                'password' => 'required|string',
            ]);

            // Log login attempt
            Log::info("Login request received for: " . $request->login);

            // Determine if login input is email or username
            $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            // Fetch the user by email or username
            $user = User::where($loginField, $request->login)->first();

            // Check credentials
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid login credentials'], 401);
            }

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return login success with token
            $loginResponse = [
                'login_type'   => $loginField . ' : ' . $request->login,
                'userData'   => $user,
                'message'      => 'Login successful',
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ];

            // Fetch the workspaces for the user
            $workspace = \App\Models\Workspace::where('userId', $user->userId)
                ->where('is_active', true)
                ->get();

            // Check if workspaces are found
            if ($workspace->isEmpty()) {
                $workspaceResponse = [
                    'workspaces' => [],
                    'message' => 'No active workspaces found for the user'
                ];
            } else {
                $workspacesWithTask = $workspace->map(function ($workspace) {
                    // Fetch tasks that are still marked as active and not deleted
                    $task = $workspace->tasks()
                        ->where('is_active', 1)
                        ->where('is_delete', 0)
                        ->whereNull('status')
                        ->get();

                    $updatedTask = $task->map(function ($task) {
                        if (now()->greaterThanOrEqualTo($task->deadline)) {
                            // Update task to "finished"
                            $task->is_active = false;
                            $task->is_delete = true;
                            $task->status = false;
                            $task->deleted_date = $task->deadline;
                            $task->save();
                        }
                        return $task;
                    });

                    $workspace->task = $updatedTask;
                    return $workspace;
                });

                $workspaceResponse = [
                    'workspaces' => $workspacesWithTask,
                    'message' => 'Active workspaces retrieved successfully'
                ];
            }

            // Return the login response first
            return response()->json(array_merge($loginResponse, $workspaceResponse),200);
            //return view('login', array_merge($loginResponse, $workspaceResponse));


        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    // Get user by userId or get all active users
    public function getUser(Request $request)
    {
        try {
            // Check if userId is provided in the request
            if ($request->has('userId')) {
                $userId = $request->input('userId');

                // Find user by userId and check if user is active
                $user = User::where('userId', $userId)
                    ->where('is_active', true)
                    ->first();

                if (!$user) {
                    return response()->json(['message' => 'User not found or inactive'], 404);
                }

                return response()->json(['user' => $user]);
            } else {
                // If no userId is provided, get all active users
                $users = User::where('is_active', true)->get();

                if ($users->isEmpty()) {
                    return response()->json(['message' => 'No active users found'], 404);
                }

                return response()->json(['users' => $users]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    // Logout Method
    public function logout(Request $request)
    {
        // Delete the current access token
        $request->user()->currentAccessToken()->delete();

        // Return response confirming logout
        return response()->json(['message' => 'Logged out successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class WorkspaceController extends Controller
{
    // public function showWorkspace()
    // {
    //     return view('profile'); // resources/views/auth/login.blade.php
    // }

    public function postWorkspace(Request $request)
    {
        $isUpdate = $request->has('workspaceId');

        // Debugging to check incoming request data
        Log::info('Request Data: ', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'workspaceId' => $isUpdate ? 'required|integer|exists:workspace,workspaceId' : 'nullable',
            'userId' => $isUpdate ? 'nullable' : 'required|integer|exists:user,userId',
        ]);

        try {
            if ($isUpdate) {
                // Update logic
                $workspace = Workspace::where('workspaceId', $request->workspaceId)
                    ->where('is_active', true)
                    ->first();

                if (!$workspace) {
                    return response()->json(['message' => 'Workspace not found or unauthorized'], 404);
                }

                // Update the workspace details
                $workspace->name = $request->name;
                $workspace->description = $request->input('description', null); // Optional field
                $workspace->modified_date = Carbon::now();
                $workspace->save();

                return response()->json(['message' => 'Workspace updated', 'workspace' => $workspace]);
            } else {
                // Create logic
                $userId = $request->input('userId');

                $workspace = Workspace::create([
                    'name' => $request->name,
                    'description' => $request->input('description', null), // Optional
                    'userId' => $userId,
                    'is_active' => true,
                    'is_delete' => false,
                    'created_date' => Carbon::now(),
                    'modified_date' => Carbon::now(),
                    'deleted_date' => null,
                ]);

                return response()->json(['message' => 'Workspace created', 'workspace' => $workspace], 201);
            }
        } catch (\Exception $e) {
            // Log and handle any errors
            Log::error('Workspace save error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }



    public function getWorkspace(Request $request)
    {
        try {
            $user = $request->user();

            if ($request->has('workspaceId')) {
                $workspaceId = $request->input('workspaceId');
                // Get specific workspace
                $workspace = Workspace::where('workspaceId', $workspaceId)
                    ->where('userId', $user->userId)
                    ->where('is_active', true)
                    ->first();

                if (!$workspace) {
                    return response()->json(['message' => 'Workspace not found or unauthorized'], 404);
                }

                return response()->json($workspace);
            } else {
                // Get all workspaces
                $workspaces = Workspace::where('userId', $user->userId)
                    ->where('is_active', true)
                    ->get();

                return response()->json($workspaces);
            }
        } catch (\Exception $e) {
            Log::error('Workspace fetch error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }


    public function deleteWorkspace($workspaceId, Request $request)
    {
        try {
            $user = $request->user();

            // Find the workspace to delete
            $workspace = Workspace::where('workspaceId', $workspaceId)
                ->where('userId', $user->userId)
                ->where('is_active', true)
                ->first();

            if (!$workspace) {
                return response()->json(['message' => 'Workspace not found or have been deleted'], 404);
            }

            // Set is_active, is_delete, deleted_date for soft delete
            $workspace->is_active = false;
            $workspace->is_delete = true;
            $workspace->deleted_date = Carbon::now();
            $workspace->save();

            return response()->json(['message' => 'Workspace deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Workspace delete error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log; 

class WorkspaceController extends Controller
{
    public function postWorkspace(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'workspaceId' => 'nullable|integer|exists:workspace,workspaceId',
        ]);

        try {
            $user = $request->user();

            if ($request->has('workspaceId')) {
                // Update
                $workspace = Workspace::where('workspaceId', $request->workspaceId)
                    ->where('userId', $user->userId)
                    ->where('is_active', true)
                    ->first();

                if (!$workspace) {
                    return response()->json(['message' => 'Workspace not found or unauthorized'], 404);
                }

                $workspace->name = $request->name;
                $workspace->modified_date = Carbon::now();
                $workspace->save();

                return response()->json(['message' => 'Workspace updated', 'workspace' => $workspace]);
            } else {
                // Create
                $workspace = Workspace::create([
                    'name' => $request->name,
                    'userId' => $user->userId,
                    'is_active'     => true,
                    'is_delete'     => false,
                    'created_date' => Carbon::now(),
                    'modified_date' => Carbon::now(),
                    'deleted_date' => null,
                ]);

                return response()->json(['message' => 'Workspace created', 'workspace' => $workspace], 201);
            }
        } catch (\Exception $e) {
            Log::error('Workspace save error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }

  public function getWorkspace(Request $request, $workspaceId = null)
    {
        try {
            $user = $request->user();

            if ($workspaceId) {
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

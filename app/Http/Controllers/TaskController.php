<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function postTask(Request $request)
    {
        $request->validate([
            'workspaceId'  => 'required|integer|exists:workspace,workspaceId',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'deadline'     => 'nullable|date',
            'taskId'       => 'nullable|integer|exists:task,taskId',
        ]);

        try {
            if ($request->has('taskId')) {
                // Update task
                $task = Task::where('taskId', $request->taskId)
                            ->where('is_active', true)
                            ->where('status', null)
                            ->first();

                if (!$task) {
                    return response()->json(['message' => 'Task not found'], 404);
                }

                $task->update([
                    'workspaceId'   => $request->workspaceId,
                    'title'         => $request->title,
                    'description'   => $request->description,
                    'deadline'      => $request->deadline,
                    'status'        => null,
                    'is_active'     => true,
                    'is_delete'     => false,
                    'modified_date' => Carbon::now(),
                ]);

                return response()->json(['message' => 'Task updated successfully', 'task' => $task]);
            } else {
                // Create task
                $task = Task::create([
                    'workspaceId'   => $request->workspaceId,
                    'title'         => $request->title,
                    'description'   => $request->description,
                    'deadline'      => $request->deadline,
                    'status'        => null,
                    'is_active'     => true,
                    'is_delete'     => false,
                    'created_date'  => Carbon::now(),
                    'modified_date' => Carbon::now(),
                    'deleted_date'  => null,
                ]);

                return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
            }
        } catch (\Exception $e) {
            Log::error('Task save error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    // Get all active tasks or a specific task
    public function getTask(Request $request, $taskId = null)
    {
        try {
            if ($taskId) {
               
                // Get specific active task
                $task = Task::where('taskId', $taskId)
                            ->where('is_active', true)
                            ->first();

                if (!$task) {
                    return response()->json(['message' => 'Task not found or inactive'], 404);
                }

                return response()->json($task);
            } else {
                // Get all active tasks
                $tasks = Task::where('is_active', true)->get();
                return response()->json($tasks);
            }
        } catch (\Exception $e) {
            Log::error('Task fetch error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    // update task status
    public function postComplete(Request $request)
    {
        $request->validate([
            'taskId' => 'required|integer|exists:task,taskId',
        ]);

        try {
            $task = Task::where('taskId', $request->taskId)
                        ->where('is_active', 1)
                        ->where('is_delete', 0)
                        ->whereNull('status') // Only allow update if status is NULL
                        ->first();

            if (!$task) {
                return response()->json(['message' => 'Task not found, inactive, deleted, or already completed'], 404);
            }

            $task->update([
                'status'        => true,
                'is_active'     => false,
                'is_delete'     => true,
                'deleted_date' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Task status updated successfully', 'task' => $task]);

        } catch (\Exception $e) {
            Log::error('Task status update error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }


    public function deleteTask($taskId)
    {
        try {
            $task = Task::where('taskId', $taskId)
                        ->where('is_active', true)
                        ->where('is_delete', false)
                        ->whereNull('status')
                        ->first();

            if (!$task) {
                return response()->json(['message' => 'Task not found or already deleted'], 404);
            }

            $task->update([
                'is_active'    => false,
                'is_delete'    => true,
                'deleted_date' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Task soft deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Task delete error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/postUser', [AuthController::class, 'postUser']);

//Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post('/user/getUser', [AuthController::class, 'getUser']);



Route::middleware('auth:sanctum')->post('/workspace/postWorkspace', [WorkspaceController::class, 'postWorkspace']);

// Route::middleware('auth:sanctum')->get('/workspace/{workspaceId?}', [WorkspaceController::class, 'getWorkspace']);

// // // dynamic parameter
// // Route::middleware('auth:sanctum')->get('/workspace/{workspaceId}', [WorkspaceController::class, 'getById']);
// // Route::middleware('auth:sanctum')->get('/workspace', [WorkspaceController::class, 'getAll']);

// Route::middleware('auth:sanctum')->delete('/workspace/{workspaceId?}', [WorkspaceController::class, 'deleteWorkspace']);

// Route::middleware('auth:sanctum')->post('/task/postTask', [TaskController::class, 'postTask']);

// Route::middleware('auth:sanctum')->get('/task/getTask/{taskId?}', [TaskController::class, 'getTask']);

// Route::middleware('auth:sanctum')->post('/complete/postComplete', [TaskController::class, 'postComplete']);

// Route::middleware('auth:sanctum')->delete('/task/delete/{taskId}', [TaskController::class, 'deleteTask']);



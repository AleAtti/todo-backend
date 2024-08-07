<?php

use App\Http\Controllers\ToDoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/todos', [ToDoController::class, 'index']);
Route::post('/todos', [ToDoController::class, 'store']);
route::get('/todos/{id}', [ToDoController::class, 'show']);
route::put('/todos/{id}/edit', [ToDoController::class, 'update']);
route::put('/todos/{id}/complete', [ToDoController::class, 'updateMarkToggle']);
route::delete('/todos/{id}/delete', [ToDoController::class, 'destroy']);

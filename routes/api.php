<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Guarded Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/threads', [\App\Http\Controllers\ThreadController::class, 'create']);
    Route::post('/threads/{thread}/replies', [\App\Http\Controllers\ReplyController::class, 'create']);
    Route::delete('/threads/{thread}', [\App\Http\Controllers\ThreadController::class, 'delete']);
    Route::delete('/threads/{thread}/replies/{reply}', [\App\Http\Controllers\ReplyController::class, 'delete']);
});

Route::get('/threads', [\App\Http\Controllers\ThreadController::class, 'getAll']);
Route::get('/threads/{thread}/replies', [\App\Http\Controllers\ReplyController::class, 'getAll']);


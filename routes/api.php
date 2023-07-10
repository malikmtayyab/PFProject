<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;

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

// Authentication Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Protected Routes (Require Authentication)
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::get('me', [AuthController::class, 'me'])->name('me');
});

// // Email Verification Routes
// Route::group(['middleware' => ['auth', 'throttle:6,1']], function () {
//     Route::get('/email/verify', function () {
//         return response()->json(['message' => 'Please verify your email']);
//     })->name('verification.notice');

//     Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
//         ->middleware(['signed'])
//         ->name('verification.verify');
    
//     Route::post('/email/resend', [VerificationController::class, 'resend'])
//         ->middleware(['throttle:6,1'])
//         ->name('verification.resend');
// });

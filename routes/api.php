<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Work_Space_Controller;
use App\Http\Controllers\InvitationTableController;
use App\Http\Controllers\WorkspaceAdminsController;
use App\Http\Controllers\ProjectSpaceController;





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
Route::post('check_authorization', [AuthController::class, 'authorizeJWT_Session']);


//work_space route

Route::post('/createworkspace', [Work_Space_Controller::class, 'store']);
Route::post('/inviteby', [InvitationTableController::class, 'store']);

Route::post('/workspaceadmin', [WorkspaceAdminsController::class, 'store']);

//ProjectSpaceController
Route::post('/projectspace', [ProjectSpaceController::class, 'store']);



// Protected Routes (Require Authentication)
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::get('me', [AuthController::class, 'me'])->name('me');
});


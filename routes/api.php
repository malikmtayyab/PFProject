<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Work_Space_Controller;
use App\Http\Controllers\InvitationTableController;
use App\Http\Controllers\WorkspaceAdminsController;
use App\Http\Controllers\ProjectSpaceController;
use App\Http\Controllers\ProjectMembersController;
use App\Http\Controllers\ProjectTasksController;
use App\Http\Controllers\UserController;








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

Route::group(['middleware' => ['api.authentication']], function(){
    Route::post('check_authorization', [AuthController::class, 'authorizeJWT_Session']);
    Route::get('verifyCookie',[ApiAuthenticationController::class, 'checkAPICookie']);
    //work_space route

    Route::post('/createworkspace', [Work_Space_Controller::class, 'store']);
    Route::post('/inviteby', [InvitationTableController::class, 'store']);

    Route::post('/workspaceadmin', [WorkspaceAdminsController::class, 'store']);

    //ProjectSpaceController
    Route::post('/projectspace', [ProjectSpaceController::class, 'store']);

    //projectmemer
    Route::post('/projectmember', [ProjectMembersController::class, 'store']);

    //project task

    Route::post('/projecttask', [ProjectTasksController::class, 'store']);
    Route::get('/getWorkSpaceProjects', [UserController::class, 'getUserInitialData']);
    Route::post('/changePassword', [UserController::class, 'changePassword']);
    Route::post('/addUserName', [UserController::class, 'addUserName']);

    // Worked when no task and members available
    Route::post('/getProject', [ProjectSpaceController::class, 'getSpecificProjectInfo']);

    Route::get('/getWorkspaceMembers', [InvitationTableController::class, 'show']);

    Route::post('/changeProject_status', [ProjectSpaceController::class, 'update_status']);
    Route::post('/changeProject_deadline', [ProjectSpaceController::class, 'update_deadline']);
    Route::post('/changeProject_completionDate', [ProjectSpaceController::class, 'update_deadline']);

    Route::post('/deleteProjectMember', [ProjectMembersController::class, 'delete']);

});


// Protected Routes (Require Authentication)
// Route::group(['middleware' => 'auth:api'], function () {
//     Route::post('logout', [AuthController::class, 'logout'])->name('logout');
//     Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
//     Route::get('me', [AuthController::class, 'me'])->name('me');
// });


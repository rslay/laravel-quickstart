<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\user\UserController;
use App\Http\Controllers\api\user\NotificationController;

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

/* USER ROUTES */

Route::prefix('/user')->group(function()
{
    // User login, validated
    Route::post('/login', [AuthController::class, 'login']);

    // User registration, validated
    Route::post('/register', [AuthController::class, 'register']);

    // Self User information, protected
    Route::middleware('auth:api')->get('/', [UserController::class, 'index']);

    /* USER NOTIFICATION ROUTES */

    Route::prefix('/notification')->group(function()
    {
        // Get notifications with status of not yet read, protected
        Route::middleware('auth:api')->get('/unread', [NotificationController::class, 'unread']);
        Route::middleware('auth:api')->get('/',       [NotificationController::class, 'unread']);

        // Get notifications with status of already read, protected
        Route::middleware('auth:api')->get('/read', [NotificationController::class, 'read']);

        // Get all notifications, protected
        Route::middleware('auth:api')->get('/all', [NotificationController::class, 'all']);

        // Get notification with specified id, protected
        Route::middleware('auth:api')->get('/mark_as_read/{id}', [NotificationController::class, 'markAsRead']);

        // Make notification unread, protected
        Route::middleware('auth:api')->get('/mark_as_unread/{id}', [NotificationController::class, 'markAsUnread']);

        // Create notification, protected
        Route::middleware('auth:api')->post('/create', [NotificationController::class, 'create']);

        // Delete notification, protected
        Route::middleware('auth:api')->delete('/delete/{id}', [NotificationController::class, 'delete']);

        // Delete notification, protected
        Route::middleware('auth:api')->delete('/delete_all', [NotificationController::class, 'deleteAll']);
    });
});

/* USER NOTIFICATION ROUTES */

// Yelp External API, protected
Route::middleware('auth:api')->get('/yelp_external', [UserController::class, 'yelpExternal']);

// Forbidden route
Route::any('/forbidden', [UserController::class, 'forbidden'])->name('forbidden');

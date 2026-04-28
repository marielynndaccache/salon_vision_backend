<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\PersonalizedPagesController;
use App\Http\Controllers\UserColumnViewsController;


Route::group([
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
    Route::post('/refresh', [UserAuthController::class, 'refresh']);
    Route::get('/user-profile', [UserAuthController::class, 'userProfile']);
    Route::get('/getNotifications', [UserAuthController::class, 'getNotifications']);
    Route::get('/getLocationTree', [UserAuthController::class, 'getLocationTree']);
    Route::post('/notificationSeen', [UserAuthController::class, 'notificationSeen']);
    Route::post('/notificationIgnored', [UserAuthController::class, 'notificationIgnored']);
    Route::post('/setFirebaseToken', [UserAuthController::class, 'setFirebaseToken']);
    
});

Route::group([
    'prefix' => 'PersonalizedPages'

], function ($router) {
    Route::get('/get', [PersonalizedPagesController::class, 'get']);
    Route::get('/getById-{id}', [PersonalizedPagesController::class, 'getById']);
    Route::post('/add', [PersonalizedPagesController::class, 'add']);
    Route::post('/update', [PersonalizedPagesController::class, 'update']);
    Route::post('/delete', [PersonalizedPagesController::class, 'delete']);
});

Route::group([
    'prefix' => 'UserColumnViews'

], function ($router) {
    Route::get('/get', [UserColumnViewsController::class, 'get']);
    Route::post('/save', [UserColumnViewsController::class, 'save']);
    Route::post('/delete', [UserColumnViewsController::class, 'delete']);
});
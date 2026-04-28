<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MasterData\ProfilesController;
use App\Http\Controllers\MasterData\UsersController;

Route::group([
    'prefix' => 'MasterData'

], function ($router) {
    Route::group(
        [
            'prefix' => 'users'

        ],
        function ($router) {
            Route::get('/get', [UsersController::class, 'get']);
            Route::get('/getById-{id}', [UsersController::class, 'getById']);
            Route::post('/add', [UsersController::class, 'add']);
            Route::post('/update', [UsersController::class, 'update']);
            Route::post('/delete', [UsersController::class, 'delete']);
            Route::post('/changePassword', [UsersController::class, 'changePassword']);
            Route::get('/getDataBySearchQuery', [UsersController::class, 'getDataBySearchQuery']);
        }
    );

    Route::group(
        [
            'prefix' => 'profiles'

        ],
        function ($router) {
            Route::get('/get', [ProfilesController::class, 'get']);
            Route::get('/getById-{id}', [ProfilesController::class, 'getById']);
            Route::post('/add', [ProfilesController::class, 'add']);
            Route::post('/update', [ProfilesController::class, 'update']);
            Route::post('/delete', [ProfilesController::class, 'delete']);
            Route::get('permissionsTree/get/{serial}', [ProfilesController::class, 'getPermissionsTree']);
            Route::post('permissionsTree/updatePermissions', [ProfilesController::class, 'updatePermissions']);
        }
    );
});
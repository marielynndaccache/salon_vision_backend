<?php

use App\Http\Controllers\AI\Operations\AIEmployeeInputsController;
use App\Http\Controllers\AI\Reports\AIDashboardController;
use App\Http\Controllers\AI\Reports\AIModelResultsReportController;
use App\Http\Controllers\AI\Settings\AIServicesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'AI',
], function ($router) {
    Route::group(['prefix' => 'Settings'], function ($router) {
        Route::group(['prefix' => 'Services'], function ($router) {
            Route::get('/get', [AIServicesController::class, 'get']);
            Route::get('/getById/{id}', [AIServicesController::class, 'getById']);
            Route::post('/add', [AIServicesController::class, 'add']);
            Route::post('/update', [AIServicesController::class, 'update']);
            Route::post('/delete', [AIServicesController::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'Operations'], function ($router) {
        Route::group(['prefix' => 'EmployeeInputs'], function ($router) {
            Route::get('/get', [AIEmployeeInputsController::class, 'get']);
            Route::get('/getById/{id}', [AIEmployeeInputsController::class, 'getById']);
            Route::post('/add', [AIEmployeeInputsController::class, 'add']);
            Route::post('/update', [AIEmployeeInputsController::class, 'update']);
            Route::post('/delete', [AIEmployeeInputsController::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'Reports'], function ($router) {
        Route::group(['prefix' => 'ModelResultsVsInputs'], function ($router) {
            Route::get('/get', [AIModelResultsReportController::class, 'get']);
        });
    });

    Route::group(['prefix' => 'Dashboard'], function ($router) {
        Route::get('/summary', [AIDashboardController::class, 'summary']);
    });
});

<?php

use FaithGen\Sermons\Http\Controllers\SermonController;
use Illuminate\Support\Facades\Route;

/**
 * Handles sermons details.
 */
Route::name('sermons.')
    ->prefix('sermons/')
    ->middleware('source.site')
    ->group(function () {
        Route::post('', [SermonController::class, 'create']);
        Route::delete('{sermon}', [SermonController::class, 'delete']);
        Route::post('{sermon}/update-picture', [SermonController::class, 'updatePicture']);
        Route::post('/update', [SermonController::class, 'update']);
    });

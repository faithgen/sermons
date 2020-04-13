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
        Route::post('create', [SermonController::class, 'create']);
        Route::delete('delete', [SermonController::class, 'delete']);
        Route::post('/update-picture', [SermonController::class, 'updatePicture']);
        Route::post('/update', [SermonController::class, 'update']);
    });

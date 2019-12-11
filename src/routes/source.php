<?php

use FaithGen\Sermons\Http\Controllers\SermonController;
use Illuminate\Support\Facades\Route;

/**
 * Handles sermons details
 */
Route::name('sermons.')->prefix('sermons/')->group(function () {
    Route::post('create', [SermonController::class, 'create'])->middleware('source.site');
    Route::delete('delete', [SermonController::class, 'delete'])->middleware('source.site');
    Route::post('/update-picture', [SermonController::class, 'updatePicture'])->middleware('source.site');
    Route::post('/update', [SermonController::class, 'update'])->middleware('source.site');
});

<?php

use FaithGen\Sermons\Http\Controllers\SermonController;
use Illuminate\Support\Facades\Route;

/**
 * Handles sermons details
 */
Route::name('sermons.')->prefix('sermons/')->group(function () {
    Route::get('/', [SermonController::class, 'index']);
    Route::get('/{sermon}', [SermonController::class, 'view']);
    Route::post('comment', [SermonController::class, 'comment']);
});

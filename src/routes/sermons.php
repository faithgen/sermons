<?php
/**
 * Handles sermons details
 */
Route::name('sermons.')->prefix('sermons/')->group(function () {
    Route::get('/', 'SermonController@index');
    Route::get('/{sermon}', 'SermonController@view');
});

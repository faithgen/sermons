<?php
/**
 * Handles sermons details
 */
Route::name('sermons.')->prefix('sermons/')->group(function () {
    Route::get('/', 'SermonController@index');
    Route::get('/{sermon}', 'SermonController@view');
    Route::post('create', 'SermonController@create');
    Route::delete('delete', 'SermonController@delete');
    Route::post('/update-picture', 'SermonController@updatePicture');
    Route::post('/update', 'SermonController@update');
});

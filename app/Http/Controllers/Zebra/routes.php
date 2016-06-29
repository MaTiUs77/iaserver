<?php
Route::group(array('prefix' => 'zebra'), function() {
    Route::get('/', [
        'as' => 'zebra.print',
        'uses' => 'Zebra\Zebra@print'
    ]);
});

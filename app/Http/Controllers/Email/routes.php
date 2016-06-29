<?php
Route::group(array('prefix' => 'email'), function() {
    Route::post('/send', [
        'as' => 'email.send',
        'uses' => 'Email\EmailView@send'
    ]);

    Route::get('/send/{to}/{toEmail}/{subject}/{data}', [
        'as' => 'email.get.send',
        'uses' => 'Email\EmailView@sendByGet'
    ]);
});

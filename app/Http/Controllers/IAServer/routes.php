<?php
// IASERVER HOME
Route::get('/', ['as' =>'iaserver.home', 'uses' => 'IAServer\IAServerController@index']);

Route::get('/home', 'IAServer\IAServerController@index');

Route::get('/logo', ['as' =>'iaserver.logo', 'uses' => 'IAServer\IAServerController@logo']);

Route::group(['prefix' => 'auth'], function() {

    // LOGIN
    Route::get('/login', 'Auth\AuthController@showLoginForm');
    Route::post('/login', 'Auth\AuthController@login');
//    Route::get('/logout', 'Auth\AuthController@logout');

    Route::post('/atlogin',  ['as' =>'iaserver.atlogin', 'uses' => 'IAServer\IAServerController@attemptLogin']);
    Route::get('/logout',  ['as' =>'iaserver.logout', 'uses' => 'IAServer\IAServerController@logout']);

    // REGISTER
    Route::get('/register', 'Auth\AuthController@showRegistrationForm');
    Route::post('/register', 'Auth\AuthController@register');

    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');
});


// ABM
Route::resource('/abm', 'IAServer\Abm\AbmController');

// Custom Profile
/*
Route::post('/profile/search', function() {
    $buscar = Input::get('search');

    $words = explode(' ',$buscar);

    $nombre = head($words);
    $apellido = last($words);

    if(count($words)==2 && !empty($nombre) && !empty($apellido))
    {
        $output = \IAServer\Http\Controllers\Auth\Entrust\Profile::where('nombre','like','%'.$nombre.'%')
            ->where('apellido','like','%'.$apellido.'%')
            ->get();

        if(count($output)==0)
        {
            $output = array('error'=>'No se encontraron resultados');
        }
    } else
    {
        $output = array('error'=>'Debe ingresar nombre y apellido');
    }

    return Response::multiple($output);
});
*/

// FORMS
Route::group(['prefix' => 'forms'], function() {
    Route::get('/prompt', ['as'=>'iaserver.forms.prompt', 'uses' =>'IAServer\IAServerController@prompter']);
});

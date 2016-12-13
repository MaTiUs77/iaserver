<?php

Route::group(['prefix' => 'inventario'], function()
{
    Route::get('/configuracion', 'inventario\configController@configuracion');
    Route::get('/impresoras/{id?}', 'inventario\configController@index');
    Route::post('/impresoras', 'inventario\configController@addPrinter');
    Route::post('/impresoras/{id}', 'inventario\configController@updatePrinter');
    Route::delete('/impresoras/{id}', 'inventario\configController@deletePrinter');
    Route::get('/impresoras/tipo','inventario\configController@getPrinterType');
    Route::get('/unidad_medida','inventario\impresionController@getUnit');
    Route::post('/agregar_material','inventario\impresionController@insertMaterial');
    Route::get('/import','inventario\importController@import');

    Route::match(['get','post'],'/consultar/etiqueta/info/{id}',[
        'as'=>'inventario.etiqueta',
        'uses'=>'Inventario\invController@findlabel'
    ]);
    Route::match(['get','post'],'/consultar/etiqueta/info/update/{id}/{qty}/{qty2}/{qty3}',[
        'as'=>'inventario.update',
        'uses'=>'Inventario\invController@updateLabel'
    ]);
    Route::match(['get','post'],'/imprimir',[
        'as'=>'inventario.imprimir',
        'uses'=>'Inventario\viewController@vistaImprimir'
    ]);

    Route::match(['get','post'],'/reimprimir/{id}/{pn}/{pcant}/{scant}/{tcant}',[
        'as'=>'inventario.reimprimir',
        'uses'=>'Inventario\impresionController@rePrint'
    ]);

    Route::match(['get','post'],'/consultar',[
        'as'=>'consultar',
        'uses'=>'Inventario\viewController@vistaConsultar'
    ]);

    /*** IMPRESION ***/

    Route::post('/impresion/imprimir',[
        'as'=>'inventario.impresion.imprimir',
        'uses'=>'Inventario\impresionController@toPrint'
    ]);

    Route::get('/getPN/{pn?}',[
        'as'=>'inventario.impresion.getpn',
        'uses'=>'Inventario\impresionController@show'
    ]);

    /****************/

    /**** Configuracion *****/

    Route::match(['get','post'],'/configurar/impresoras',[
        'as'=>'inventario.configurar.impresoras',
        'uses'=>'Inventario\viewController@vistaConfiguracion'
    ]);

    /*USUARIOS*/

    Route::get('/configurar/perfil/get',[
        'as'=>'inventario.configurar.perfil.get',
        'uses'=>'Inventario\usersController@getSessionData'
    ]);

    Route::get('/configurar/usuarios',[
        'as'=>'inventario.configurar.usuarios',
        'uses'=>'Inventario\usersController@showUsers'
    ]);

    Route::get('/configurar/usuarios/getUsers',[
        'as'=>'inventario.configurar.usuarios.getUsers',
        'uses'=>'Inventario\usersController@index'
    ]);

    Route::post('/configurar/usuarios/getUsers',[
        'as'=>'inventario.configurar.usuarios.getUsers',
        'uses'=>'Inventario\usersController@create'
    ]);

    Route::get('/configurar/usuarios/getUsers/{id}/edit',[
        'as'=>'inventario.configurar.usuarios.getUsers.edit',
        'uses'=>'Inventario\usersController@show'
    ]);

    Route::get('/configurar/usuarios/getUsers/fromIAServer',[
        'as'=>'inventario.configurar.usuarios.getUsers.fromiaserver',
        'uses'=>'Inventario\usersController@showiaserver'
    ]);

    Route::get('/configurar/usuarios/getProfile/{id}',[
        'as'=>'inventario.configurar.usuarios.getProfile',
        'uses'=>'Inventario\usersController@getProfileData'
    ]);

    Route::post('/configurar/usuarios/getUsers/update','Inventario\usersController@update');

    Route::get('/configurar/usuarios/getRoles/{id}',[
        'as'=>'inventario.configurar.usuarios.getRoles',
        'uses'=>'Inventario\usersController@getRoles'
    ]);

    Route::delete('/configurar/usuarios/getUsers/{id}/delete','Inventario\usersController@destroy');
    /*********/

    Route::get('/configurar/getPlants',[
        'as'=>'inventario.configurar.getplants',
        'uses'=>'Inventario\invController@getPlants'
    ]);

    Route::get('/configurar/getSectors',[
        'as'=>'inventario.configurar.getsectors',
        'uses'=>'Inventario\invController@getSectors'
    ]);

    Route::get('/configurar/usuarios/profile',[
        'as'=>'inventario.configurar.usuarios.profile',
        'uses'=>'Inventario\usersController@showProfile'
    ]);


    /*******************/

    /****** REPORTE DE IMPRESIONES ******/

    Route::get('/consultar/reporte',[
        'as'=>'inventario.consultar.reporte',
        'uses'=>'Inventario\viewController@vistaReportes'
    ]);

    Route::get('/consultar/reporte/get',[
        'as'=>'inventario.consultar.reporte.get',
        'uses'=>'Inventario\reportController@index'
    ]);

    /************************************/

});
<?php
Route::group([
    'prefix' => 'api',
    'namespace'=>'Aoicollector\Api'], function() {

    Route::get('/verify/placa/{barcode}/{stage}', 'CollectorClient\CollectorClientApi@verifyBarcode');

    /*
        NOTA
    -------------
            Seria bueno aplicar un KEY de acceso a los service de cada planta, para evitar
        problemas futuros, como por ejemplo que planta5 utilice el service de planta6 seteando
        la ultima ruta de la placa de forma erronea.

        Esto se solucionaria modificando la ruta con una clave fija para cada planta por ej:

            Route::get('/{barcode}/ClaveSuperSecretaParaPlanta4/{stage}', 'Planta4\Planta4Api@estadoDePlaca');
            Route::get('/{barcode}/Clave512PepePizzaParaPlanta5/{stage}', 'Planta5\Planta5Api@estadoDePlaca');

        Luego enviarle la clave respectiva a la gente de sistema de cada planta, para que consuman el service.
    */
    //=============================== PLANTA 4 ======================================
    Route::group([
        'prefix' => 'planta4',
        'middleware' => 'responselog:planta4'], function() {

        Route::get('/{barcode}/{stage}', 'Planta4\Planta4Api@estadoDePlaca');
    });

    //=============================== PLANTA 5 ======================================
    Route::group([
        'prefix' => 'planta5',
        'middleware' => 'responselog:planta5'], function() {

        Route::get('/{barcode}/{stage}', 'Planta5\Planta5Api@estadoDePlaca');
    });

    //=============================== PLANTA 6 ======================================
    Route::group([
        'prefix' => 'planta6',
        'middleware' => 'responselog:planta6'], function() {

        Route::get('/{barcode}/{stage}', 'Planta6\Planta6Api@estadoDePlaca');
    });

    //=========================== CLIENTE AOICOLLECTOR ==============================
    Route::group([
        'prefix' => 'aoicollector'], function() {

        Route::get('/placa/{barcode}/{verifyDeclared?}', 'CollectorClient\CollectorClientApi@findBarcode');
        Route::get('/prodinfo/{aoibarcode}', 'CollectorClient\CollectorClientApi@prodInfo');
        Route::get('/prodinfoall', 'CollectorClient\CollectorClientApi@prodInfoAll');
        Route::get('/prodlist', 'CollectorClient\CollectorClientApi@prodList');
        Route::get('/declarar/{panelBarcode}', 'CollectorClient\CollectorClientApi@declararPanel');
    });

    //============================ CONTROL DE PLACAS ===============================
    Route::group([
        'prefix' => 'controldeplacas'], function() {

        Route::get('/setroute/{stkbarcode}', 'ControldePlacas\ControlDePlacasApi@setroute');
        Route::get('/verifystocker/{stkbarcode}', 'ControldePlacas\ControlDePlacasApi@verifyStocker');
        Route::get('/infostocker/{stkbarcode}', 'ControldePlacas\ControlDePlacasApi@infoStocker');
        Route::get('/opinfo/{op}', 'ControldePlacas\ControlDePlacasApi@opinfo');
    });
});

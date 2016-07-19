<?php

Route::resource('/abmordentrabajo', 'SMTDatabase\AbmOrdenTrabajo\AbmOrdenTrabajo');

Route::post('/abmordentrabajo/find', [
    'as' => 'smtdatabase.abmordentrabajo.find',
    'uses' => 'SMTDatabase\AbmOrdenTrabajo\AbmOrdenTrabajo@find'
]);
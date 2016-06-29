<h3>
    Resumen de IAServer
</h3>

<small>Cantidad de lote: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de lote en SMTDatabase"></span></small> {{ $smt->qty }}
<small>Paneles en Aoi: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de paneles con {{ $smt->op }}, si se cambio manualmente la OP por otra, la diferencia se veria reflejada con el contador incremental"></span></small> {{ $smt->registros }}
<small>Bloques en Aoi: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cada vez que se inspecciona un bloque, este contador se incrementa"></span></small> {{ $smt->prod_aoi }}
<small>Control de placas: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="En control de placas, el modelo,lote y panel, debe coincidir para tener el dato correcto de salida"></span></small>
@if(isset($controldeplacas->salidas))
    {{ $controldeplacas->salidas }}
@else
    Sin datos
@endif



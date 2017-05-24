<h3>
    Resumen de IAServer
</h3>

<small>Cantidad de lote: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de lote en SMTDatabase"></span></small> {{ $smt->qty }}
<small>Paneles en Aoi: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de paneles con {{ $smt->op }}"></span></small> {{ $smt->registros }}
<small>Placas en Aoi: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cada vez que se inspecciona un nuevo panel, este contador se incrementa"></span></small>
{{ $smt->prod_aoi }}

<small>Control de placas: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Placas que salieron por el sistema, y la diferencia para cerrar la OP"></span></small>
@if(isset($controldeplacas->error))
    {{ $controldeplacas->error }}
@else
    {{ $controldeplacas->scalar }}

    <?php
        //$diferenciaDeclarada = $wip->wip_ot->quantity_completed - $controldeplacas->scalar;

        $allstocker = \IAServer\Http\Controllers\Aoicollector\Model\Stocker::vista()
            ->where('op', $op)
            ->where('paneles','>',0)
            ->where('id_stocker_route', 1)
            ->orderBy('created_at','desc')
            ->get();

    ?>
    @if(count($allstocker)>0)
        <span style="color: #5CB85C;font-size:14px;">
            +{{ $allstocker->sum('paneles') * $allstocker->first()->bloques }}
        </span>

        @if(isset($wip))
        <small>Stockers</small>
            <a class="btn btn-sm btn-success" ng-click="openModal('{{ route('trazabilidad.form.allprodstocker',$wip->wip_ot->nro_op) }}','Stockers en produccion','success')">Ver stockers en produccion</a>
        @endif
    @endif
@endif



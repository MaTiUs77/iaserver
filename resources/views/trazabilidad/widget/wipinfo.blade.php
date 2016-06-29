<h3>
    {{ $wip->wip_ot->nro_op }}
</h3>
<small>Estado:</small> <span style="padding: 5px;" class="label label-{{ $wip->active ? 'success' : 'danger' }}">{{ $wip->active ? 'ACTIVA' : 'CERRADA' }}</span>

@if($wip->active && isAdmin())
    <a class="btn btn-sm btn-success" tooltip="Declarar OPs" ng-click="openModal('{{ route('trazabilidad.form.declarar',$wip->wip_ot->nro_op) }}','Declarar OP','success')"><span class="glyphicon glyphicon-plus"></span> Declarar</a>
@endif

<small>Modelo:</small>
@if(isset($smt))
     {{ $smt->modelo }} - {{ $smt->panel }} - {{ $smt->lote }}
@else
    <span class="label label-danger">No hay datos en SMTDatabase</span>
@endif

<small>Semielaborado:</small> {{ $wip->wip_ot->codigo_producto }}
@if($wip->active )
    <small>Descripcion:</small>
    <span style="font-size: 12px;">{{ $wip->wip_ot->description }}</span>
    <small>Cantidad de lote: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de lote segun WIP_OT"></span></small> {{ $wip->wip_ot->start_quantity }}
    <small>Cantidad declarada: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Declaraciones segun WIP_OT"></span></small> {{ $wip->wip_ot->quantity_completed }}
    <small>Restante:</small> {{ $wip->wip_ot->restante }}
@endif
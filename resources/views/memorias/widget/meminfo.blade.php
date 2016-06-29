    <h3>{{ str_replace('MEM-','',$smt->panel) }}</h3>
    <span class="theop">{{ $smt->op }}</span>

    <small>Estado</small>
    @if($smt->wip->active)
        <span style="padding: 5px;" class="label label-success">ABIERTA</span>
        @if(!isset($btnDeclarar))
            <a class="btn btn-xs btn-success" tooltip="Declarar" ng-click="openModal('{{  route('memorias.form.declarar',$smt->op )}}','Declarar OP','success')"><span class="glyphicon glyphicon-plus"></span></a>
        @endif
    @else
        <span style="padding: 5px;" class="label label-danger">CERRADA</span>
        @if(!isset($btnDetalle))
            <a class="btn btn-xs btn-default" tooltip="Ver detalles" ng-click="openModal('{{  route('memorias.form.declarar',$smt->op )}}','Detalle de OP','info')"><span class="glyphicon glyphicon-eye-open"></span></a>
        @endif
    @endif

    @if($smt->wip->wip_ot)
        <small>Semielaborado</small>
        {{ $smt->wip->wip_ot->codigo_producto }}
    @endif

    @if($smt->wip->active)
        <small>Cantidad de lote</small> {{ $smt->wip->wip_ot->start_quantity }}
        <small>Cantidad declarado</small> {{ $smt->wip->wip_ot->quantity_completed }}
        @if(( $smt->wip->wip_ot->quantity_completed - $smt->wip->wip_ot->start_quantity) > 0)
            <small>Cantidad excedente</small> {{ ( $smt->wip->wip_ot->quantity_completed - $smt->wip->wip_ot->start_quantity) }}
        @else
            <small>Cantidad restante</small> {{ ( $smt->wip->wip_ot->quantity_completed - $smt->wip->wip_ot->start_quantity) }}
        @endif

        <small>Progreso</small>

        @include('iaserver.common.progressbar',[
            'type' => 'success',
            'active' => true,
            'percent' => true,
            'now' => $smt->wip->wip_ot->quantity_completed,
            'max' => $smt->wip->wip_ot->start_quantity
        ])
    @else

        <small>Cantidad de lote</small> {{ $smt->qty }}
        <small>Cantidad declarado</small> {{ $smt->wip->transactions->declaradas }}

        @if(( $smt->wip->transactions->declaradas - $smt->qty) > 0)
            <small>Cantidad excedente</small> {{ ( $smt->wip->transactions->declaradas - $smt->qty) }}
        @else
            <small>Cantidad restante</small> {{ ( $smt->wip->transactions->declaradas - $smt->qty) }}
        @endif

        <small>Progreso</small>

        @if($smt->qty>0)
             @include('iaserver.common.progressbar',[
               'type' => (($smt->wip->transactions->declaradas == $smt->qty) ? 'info' : 'danger'),
               'percent' => true,
               'now' => $smt->wip->transactions->declaradas,
               'max' => $smt->qty
            ])
        @endif
    @endif

@extends('adminlte/theme')
@section('title','AMR - Pedido de Materiales')
@section('mini',false)
@section('collapse',false)
@section('menuaside')
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">

                <!-- TREEMENU -->
                <li class="treeview">
                    <a href="#"><i class="glyphicon glyphicon-qrcode"></i>
                        <span>EBS</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('amr/parciales') }}">PEDIDOS PARCIALES/ERROR</a></li>
                        <li><a href="{{ url('amr/pedidos/nuevos') }}">PEDIDOS NUEVOS</a></li>
                        <li><a href="{{ url('amr/pedidos/procesados') }}">PEDIDOS PROCESADOS</a></li>
                    </ul>
                </li>
                <!-- END TREEMENU -->
                <li><a href="{{ url('amr/parciales/almacen') }}">PISO PRODUCCION</a></li>
                <li><a href="{{ url('amr/pedidos/transito') }}">RESERVA TRANSITO</a></li>
                <li><a href="{{ url('amr/consultar') }}">TRAZABILIDAD</a></li>
            </ul>
        </section>
    </aside>

@endsection
@section('body')
<?php
$self = url('amr/parciales'); //Obtenemos la página en la que nos encontramos
header("refresh:300; url=$self"); //Refrescamos cada 400 segundos
?>
<div class="container-fluid">
    <div class="row">
        <?php
        $carbon = new \Carbon\Carbon();
        $carbon = $carbon->today();


        $estadoAmr = \IAServer\Http\Controllers\MonitorPedidos\Model\amr_heartbeat::where('id','>','3')
        ->orderBy('id','desc')
        ->take(1)
        ->get();
        foreach($estadoAmr as $status)
            {
                $status2 =  \IAServer\Http\Controllers\MonitorOp\GetWipOtInfo::statusAmr($status->timeStamp);
            }

        $rechazos = \IAServer\Http\Controllers\MonitorPedidos\Model\XXE_WMS_COGISCAN_PEDIDOS::WHERE('STATUS','<>','NEW')
            ->WHERE('STATUS','<>','PROCESSED')
            ->WHERE('LAST_UPDATE_DATE','>',$carbon)
            ->GET();
            \IAServer\Http\Controllers\MonitorPedidos\CogiscanPedidos::pedidosRechazados($rechazos);

        ?>

        <div class="col-lg-12" align="left">
            <div>

           <label>ESTADO AMR: </label>
                @if($status2 < 10)
                    <i class="fa fa-check fa-2x" style="color: green" aria-hidden="true"></i><br>
                    <span>Ultima actualizacion hace <b>@if($status2 < 1)algunos segundos @elseif($status2 < 2) {{$status2}}</b> minuto  @elseif($status2 >=2)<b>{{$status2}}</b> minutos @endif</span>
                @else
            <i class="fa fa-times fa-2x" style="color: red" aria-hidden="true"></i><br>
                    <span>No se registra actividad del sistema de AMR hace mas de <b>{{$status2}}</b> minutos</span>
                @endif
            </div>
        </div>

    </div><br>

</div>
@endsection

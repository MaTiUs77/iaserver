@extends('angular')
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand">@yield('title','')</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="{{ url('amr/parciales') }}">EBS</a></li>
                <li><a href="{{ url('amr/parciales/almacen') }}">PISO PRODUCCION</a></li>
            </ul>
        </div>
    </nav>
<div class="container-fluid">
    <div class="row">
        <?php
        $estadoAmr = \IAServer\Http\Controllers\MonitorPedidos\Model\amr_heartbeat::where('id','>','3')
        ->orderBy('id','desc')
        ->take(1)
        ->get();
        foreach($estadoAmr as $status)
            {
                $status2 =  \IAServer\Http\Controllers\MonitorOp\GetWipOtInfo::statusAmr($status->timeStamp);
            }
        ?>

        <div class="col-lg-2" align="left">
            <div>

           <label>ESTADO AMR: </label>
                @if($status2 < 10)
                    <i class="fa fa-check fa-2x" style="color: green" aria-hidden="true"></i><br>
                {{--{{dd($status2)}}--}}
                    <span>Ultima actualizacion hace <b>@if($status2 < 1)algunos segundos @elseif($status2 < 2) {{$status2}}</b> minuto  @elseif($status2 >=2)<b>{{$status2}}</b> minutos @endif</span>
                @else
            <i class="fa fa-times fa-2x" style="color: red" aria-hidden="true"></i><br>

                    <span>No se registra actividad del sistema de AMR hace mas de <b>{{$status2}}</b> minutos</span>
                @endif
            </div>
        </div>

    </div>

</div>
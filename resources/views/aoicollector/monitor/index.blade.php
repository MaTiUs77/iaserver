@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector Monitor')
@section('body')
    <div class="container" ng-controller="aoicollectorController">
        <h3>Monitor de Aoicollector <small>Tiempo de ejecucion en segundos</small></h3>

        <hr>
        <!-- EN CASO DE NO TENER CONEXION A NODE -->
        <div class="callout callout-danger" ng-show="nodejserror">
            <h4>AdonisJS esta OFFLINE</h4>
            <p>No fue posible obtener datos del servidor, verifique el servidor node</p>
        </div>

        <!-- CONEXION ESTABLECIDA -->
        <div class="row" ng-hide="nodejserror">

            <div class="col-md-12" ng-hide="aoiList.length">
                <h4>Esperando datos...</h4>
            </div>

            <div class="col-md-12" ng-show="aoiList.length">
                <h4>@{{ runtime }}</h4>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12" ng-repeat="item in aoiList">
                <div class="box box-success box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">@{{ item.smd }} </h3>
                        <small class="pull-right" style="color: #f8f8ff">@{{ item.aoibarcode }}</small>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="padding: 0px;">
                        <ul class="nav nav-stacked">
                            <li dynchart data="item.runtimeHistory">
                                Cargando...
                            </li>
                            <li style="padding:5px;">
                                <div class="col-xs-6">
                                    <h4>Actual <span class="label label-primary">@{{ item.tiempoEjecucion }}</span></h4>
                                </div>
                                <div class="col-xs-6">
                                    <h4>Max <span class="label" ng-class="item.tiempoEjecucionMax > 6 ? 'label-warning ' : 'label-primary'">@{{ item.tiempoEjecucionMax }}</span></h4>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

   {!! IAScript('assets/adonis/ws.min.js') !!}
   <!--    {!! IAScript('assets/socket.io/socket.io.js') !!} -->

    @include('iaserver.common.footer')
    {!! IAScript('adminlte/plugins/sparkline/jquery.sparkline.min.js') !!}
    {!! IAScript('adminlte/plugins/knob/jquery.knob.js') !!}
    {!! IAScript('vendor/aoicollector/monitor/aoicollectormonitor.js') !!}

@endsection
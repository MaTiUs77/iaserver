@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Monitor de servidores')
@section('body')
    <div class="container" ng-controller="servidorMonitorController">
        <h3>Monitor de servidores</h3>
        <hr>
        <!-- EN CASO DE NO TENER CONEXION A NODE -->
        <div class="callout callout-danger" ng-show="nodejserror">
            <h4>AdonisJS esta OFFLINE</h4>
            <p>No fue posible obtener datos del servidor, verifique el servidor node</p>
        </div>

        <!-- CONEXION ESTABLECIDA -->
        <div class="row" ng-hide="nodejserror">
            <div class="col-md-3 col-sm-6 col-xs-12" ng-repeat="item in serverList">
                <div class="box box-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-green">
                        <!-- /.widget-user-image -->
                        <h3>@{{ item.nombre }}</h3>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <li dynchart data="item.chartCpu">
                                Cargando...
                            </li>
                            <li>
                                <a href="#">CPU <span class="pull-right badge bg-blue">@{{ item.cpu}}%</span></a>
                            </li>
                            <li dynchart data="item.chartMemoriaFisicaPorcentaje" data-min="0" data-max="80">
                                Cargando...
                            </li>
                            <li>
                                <a href="#">Memoria <span class="badge bg-aqua">@{{ item.memoriaFisicaTotal}} GB</span> <span class="pull-right badge bg-blue">@{{ item.memoriaFisicaPorcentaje}}%</span></a>
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
    {!! IAScript('vendor/servermonitor/servermonitor.js') !!}

@endsection
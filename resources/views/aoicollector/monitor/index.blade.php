@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector Monitor')
@section('head')
    <style>
        .bg-warning {
            background-color: #fbff96;
        }
    </style>
@endsection
@section('body')
    <div class="container" ng-controller="aoicollectorController">
        <small class="pull-right">@{{ socketserver  }}</small>

        <h3>Monitor de Aoicollector <small>Tiempo de ejecucion en segundos</small></h3>

        <div class="callout callout-warning" ng-if="totalruntime>10">
            <h4>Atencion</h4>
            <p>Hay demaciados archivos a procesar!, el sistema esta lento?</p>
        </div>

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


           {{-- <table class="table table-striped">
                <thead>
                <tr>
                    <th>Linea</th>
                    <th>Maquina</th>
                    <th>OP</th>
                    <th>Modelo</th>
                    <th>Lote</th>
                    <th>Panel</th>
                    <th>Produccion</th>
                </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in aoiList">
                        <td>@{{ item.smd }}</td>
                        <td>@{{ item.aoibarcode }}</td>
                        <td>@{{ item.prodinfo.op }}</td>
                        <td>@{{ item.prodinfo.smt.modelo }}</td>
                        <td>@{{ item.prodinfo.smt.lote }}</td>
                        <td>@{{ item.prodinfo.smt.panel }}</td>
                        <td>
                            <div>
                                @{{ item.prodinfo.smt.registros }} de @{{ item.prodinfo.smt.qty }}
                            </div>
                            <div class="progress" style="margin-bottom: 5px; ">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="@{{ item.prodinfo.smt.porcentaje }}" aria-valuemin="0" aria-valuemax="@{{ item.prodinfo.smt.qty }}" style="width: @{{ item.prodinfo.smt.porcentaje }}%; min-width: 4em;">
                                    @{{ item.prodinfo.smt.porcentaje }}%
                                </div>
                            </div>
                        </td>
                        <td style="width: 200px;">
                            <div dynchart data="item.runtimeHistory">
                                Cargando...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>--}}

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
                                    <h5>Actual <span class="label label-primary">@{{ item.tiempoEjecucion }}</span></h5>
                                </div>
                                <div class="col-xs-6">
                                    <h5>Max <span class="label" ng-class="item.tiempoEjecucionMax > 6 ? 'label-warning ' : 'label-primary'">@{{ item.tiempoEjecucionMax }}</span></h5>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="box-footer" style="padding: 0px;">
                        <blockquote>
                            <h4><code>@{{ item.prodinfo.op }}</code></h4>
                            <h5>
                                @{{ item.prodinfo.smt.modelo }}
                                @{{ item.prodinfo.smt.lote }}
                                @{{ item.prodinfo.smt.panel }}
                            </h5>

                            <div>
                                @{{ item.prodinfo.smt.prod_aoi }} de @{{ item.prodinfo.smt.qty }}
                            </div>
                            <div class="progress" style="margin-bottom: 5px; ">
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="@{{ item.prodinfo.smt.porcentaje }}" aria-valuemin="0" aria-valuemax="@{{ item.prodinfo.smt.qty }}" style="width: @{{ item.prodinfo.smt.porcentaje }}%; min-width: 4em;">
                                    @{{ item.prodinfo.smt.porcentaje }}%
                                </div>
                            </div>
                            @{{ item.lastUpdateText }}
                        </blockquote>

                    </div>
                </div>
            </div>
        </div>
    </div>

   {!! IAScript('assets/adonis/ws.min.js') !!}

    @include('iaserver.common.footer')
    {!! IAScript('adminlte/plugins/sparkline/jquery.sparkline.min.js') !!}
    {!! IAScript('adminlte/plugins/knob/jquery.knob.js') !!}
    {!! IAScript('vendor/aoicollector/monitor/aoicollectormonitor.js') !!}
@endsection
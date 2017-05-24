@extends('adminlte/theme')
@section('ng','app')
@section('mini',true)
@section('title','Trazabilidad - Stocker')
@section('body')
    <div ng-controller="trazaStockerController">

        <table style="width: 100%">
            <tr>
                <td style="vertical-align: top;">
                    @include('trazabilidad.partial.header')

                    @{{ modal }}
                    <div style="padding: 5px;">
                        <!-- will be used to show any messages -->
                        @if (Session::has('message'))
                            <div class="alert alert-info">{{ Session::get('message') }}</div>
                        @endif

                        @if(isset($find->error))
                            <h3>{{ $find->error }}</h3>
                        @else
                            <div class="row">
                                <!-- SIDE LEFT -->
                                <div class="col-lg-3">
                                    <blockquote>
                                        <small>Declaracion</small>
                                        @if($contenido->declaracion->declarado)
                                            <span class="label label-success">Declarado</span>
                                        @else
                                            @if($contenido->declaracion->error)
                                                <span class="label label-danger">Error en declaraciones</span>
                                            @endif

                                            @if($contenido->declaracion->pendiente)
                                                <span class="label label-warning">Pendiente</span>
                                            @endif

                                            @if($contenido->declaracion->parcial)
                                                <span class="label label-warning">Declaracion parcial</span>
                                            @endif

                                            @if(!$contenido->declaracion->parcial && !$contenido->declaracion->pendiente && !$contenido->declaracion->error)
                                                <span class="label label-danger">Sin declarar</span>
                                            @endif
                                        @endif

                                        <small>Stocker ID</small>
                                        {{ $find->stocker->barcode }}

                                        <small>Linea de produccion</small>
                                        @if(isset($find->linea ))
                                            {{ $find->linea }}
                                            <small>Op</small>
                                            {{ $find->stocker->op }}

                                            @if(isAdmin())
                                                <button class="btn btn-xs btn-info" ng-hide="cambiarOp" ng-click="cambiarOp=true">Cambiar</button>
                                                <form ng-show="cambiarOp" method="POST" action="{{ route('aoicollector.stocker.trazabilidad.changeop') }}"  >
                                                    <div class="input-group" >
                                                        <input style="display: none;" type="text" name="stockerBarcode" value="{{ $find->stocker->barcode }}"/>
                                                        <input type="text" name="toOp" class="form-control" placeholder="Nueva OP" value=""/>
                                                        <span class="input-group-btn">
                                                            <button type="submit" class="btn btn-info">Aceptar</button>
                                                            <button type="button" class="btn btn-default" ng-click="cambiarOp=false">Cancelar</button>
                                                        </span>
                                                    </div>
                                                </form>
                                            @endif

                                            <small>Semielaborado</small>
                                            {{ $find->stocker->semielaborado }}

                                            <small>Unidades</small>
                                            {{ $find->stocker->unidades }}
                                        @else
                                            <span class="label label-danger">Sin definir</span>
                                        @endif

                                        @if(isset($find->trazabilidad) && count($find->trazabilidad)>0)
                                            <small>Trazabilidad</small>
                                            <ul class="list-group">
                                                @foreach($find->trazabilidad as $tstocker)
                                                    <li class="list-group-item">
                                                        <div style="font-size: 10px;">{{ $tstocker->created_at }}</div>
                                                        <div style="font-size: 14px;">{{ $tstocker->joinRoute->name }}</div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </blockquote>
                                </div>
                                <!-- SIDE RIGHT -->
                                <div class="col-lg-9">
                                    @if(isAdmin() && !$contenido->declaracion->declarado)
                                        <div class="pull-right">
                                            <a href="javascript:;" ng-hide="reDeclareWithErrorExcecuted" ng-click="reDeclareWithError('{{ route('aoicollector.stocker.view.redeclarewitherror', $find->stocker->barcode) }}')" class="btn btn-sm bg-purple" target="_blank" tooltip-placement="left" tooltip="Re declara todas las placas con errores">
                                                <i class="fa fa-retweet" ></i>
                                                Re declarar stocker</a>

                                            <a href="javascript:;" ng-show="reDeclareWithErrorExcecuted" class="btn btn-sm btn-warning" >
                                                <i class="fa fa-refresh fa-spin fa-fw"></i>
                                                Espere...</a>
                                        </div>
                                    @endif

                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Panel</th>
                                            <th>Programa</th>
                                            <th>AOI</th>
                                            <th>INS</th>
                                            <th>Bloques</th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <!-- <th><a href="" class="btn btn-xs btn-block btn-info">Verificar</a></th> -->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($contenido->paneles as $item)
                                            <?php
                                            $panel = $item->panel;
                                            $declaracion = $item->declaracion;
                                            ?>
                                            <tr>
                                                <td>{{ $panel->panel_barcode }}</td>
                                                <td>{{ $panel->programa }}</td>
                                                <td>{{ $panel->revision_aoi }}</td>
                                                <td>{{ $panel->revision_ins }}</td>
                                                <td>{{ $panel->bloques }}</td>
                                                <td>{{ $panel->created_date }}</td>
                                                <td>{{ $panel->created_time }}</td>
                                                <td>
                                                    <?php
                                                    //$cogiscanService= new \IAServer\Http\Controllers\Cogiscan\Cogiscan();
                                                    //$cogiscan = $cogiscanService->queryItem($panel->panel_barcode);
                                                    ?>
                                                    <?php
                                                    /*
                                                    @if(isset($cogiscan['attributes']['message']))
                                                        <i class="fa fa-exclamation-triangle fa-2x text-danger" tooltip="Cogiscan: {{ $cogiscan['attributes']['message'] }}"></i>
                                                    @else
                                                        @if(isset($cogiscan['Product']['attributes']['operation']))
                                                            @if($cogiscan['Product']['attributes']['operation'] == 'Depanelization')
                                                                <i class="fa fa-send fa-2x text-info" tooltip="Ruta: {{ $cogiscan['Product']['attributes']['operation'] }}, Estado: {{ $cogiscan['Product']['attributes']['status'] }}"></i>
                                                            @else
                                                                <i class="fa fa-road fa-2x text-success" tooltip="Ruta: {{ $cogiscan['Product']['attributes']['operation'] }}, Estado: {{ $cogiscan['Product']['attributes']['status'] }}"></i>
                                                            @endif

                                                            @if(isset($cogiscan['attributes']['quarantineLocked']) && $cogiscan['attributes']['quarantineLocked'] == "true")
                                                                <i class="fa fa-bombtext-danger" tooltip="Placa en cuarentena"></i>
                                                            @endif
                                                        @endif
                                                    @endif
                                                    */
                                                    ?>
                                                    @if($declaracion->declarado)
                                                        <i class="fa fa-thumbs-o-up fa-2x text-success" tooltip-placement="left" tooltip="Declarado"></i>
                                                    @else
                                                        @if($declaracion->error)
                                                            <i class="fa fa-thumbs-o-down fa-2x text-danger" tooltip-placement="left" tooltip="Declarado con errores"></i>
                                                        @endif

                                                        @if($declaracion->pendiente)
                                                            <i class="fa fa-clock-o fa-2x text-info" tooltip-placement="left" tooltip="Pendiente"></i>
                                                        @endif

                                                        @if($declaracion->parcial)
                                                            <i class="fa fa-exclamation-circle fa-2x text-warning" tooltip-placement="left" tooltip="Faltan declarar: {{ $declaracion->parcial_total }} unidades"></i>
                                                        @endif

                                                        @if(!$declaracion->parcial && !$declaracion->pendiente && !$declaracion->error)
                                                            <i class="fa fa-exclamation-circle fa-2x text-danger" tooltip-placement="left" tooltip="Sin declarar"></i>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td ng-controller="reDeclareController">
                                                    @if(isAdmin())
                                                        <a href="javascript:;" ng-hide="executed || error" ng-click="reDeclare('{{ route('aoicollector.stocker.panel.view.declare', $panel->panel_barcode) }}')" class="btn btn-sm btn-info" tooltip-placement="top" tooltip="Re declarar">
                                                            <i class="fa fa-retweet"></i>
                                                        </a>
                                                        <a href="javascript:;" ng-show="executed && !error" class="btn btn-sm btn-warning" ><i class="fa fa-refresh fa-spin fa-fw"></i></a>
                                                        <a href="javascript:;" ng-show="error" ng-click="reDeclare('{{ route('aoicollector.stocker.panel.view.declare', $panel->panel_barcode) }}')" class="btn btn-sm btn-danger" tooltip-placement="left" tooltip="@{{ error }}" ><i class="fa fa-exclamation-circle"></i></a>

                                                        <a href="{{ route('aoicollector.stocker.panel.view.declare.force', $panel->panel_barcode) }}" class="btn btn-sm btn-primary" target="_blank" tooltip-placement="top" tooltip="Forzar envio"><i class="fa fa-truck"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if(isAdmin())
                                                @if(!$declaracion->declarado && ($declaracion->error || $declaracion->parcial))
                                                    <tr>
                                                        <td colspan="8">
                                                            <h5>Placas declaradas</h5>
                                                            @foreach($item->bloques as $placaDet)
                                                                <div>{{ $placaDet->bloque->referencia_1 }}</div>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div>
                </td>
            </tr>
        </table>
    </div>

    @include('iaserver.common.footer')

    <script>
        app.controller("trazaStockerController",function($scope, $rootScope, $http, $q)
        {
            $scope.reDeclareWithErrorExcecuted = false;

            $scope.reDeclareWithError = function(route) {
                $scope.reDeclareWithErrorExcecuted = true;

                $http.get(route).then(function(response){

                    $scope.reDeclareWithErrorExcecuted = false;

                    location.reload();

/*                    var rta = response.data.find;
                    if(rta!=undefined) {

                    }*/
                });
            };
        });


        app.controller("reDeclareController",function($scope, $rootScope, $http)
        {
            $scope.executed = false;
            $scope.error = "";

            $scope.reDeclare = function(route) {
                $scope.executed = true;
                $scope.error = "";

                $http.get(route).then(function(response){
                    if(response.data.error) {
                        $scope.error = response.data.error;
                    }

                    $scope.executed = false;

                });
            };
        });
    </script>
@endsection
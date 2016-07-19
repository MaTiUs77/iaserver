@extends('angular')
@section('ng','app')
@section('title','Reparacion')
@section('body')

<style>
    html,
    body {
        height: 100%;
        width: 100%;
    }
    body {
        margin: 0;
    }

    .table {
        font-size: 11px;
    }
    .table-responsive {
        height: 100%;
        width: 100%;
        overflow: scroll;
    }
</style>

<div class="well">
    <form method="GET" action="">
        <div class="row">
            <div class="col-xs-2">
                <div class="form-group">
                    <select class="form-control" name="id_sector">
                        <option value="" selected="selected">- Seleccionar sector -</option>
                        @foreach(\IAServer\Http\Controllers\Reparacion\Model\Sector::where('visible',1)->get() as $value)
                            <option value="{{ $value->id }}">{{ $value->sector }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-8">
                <div class="row">
                    <div class="col-sm-2" ng-init="from_session = '{{ Session::get('from_session') }}';" ng-controller="datapickerController">
                        <input type="text" name="from_session" placeholder="Desde fecha" class="form-control" ng-model="from_session" datepicker-popup="dd-MM-yyyy" is-open="datepickerOpened" ng-required="true" show-button-bar="false" ng-click="open($event)"/>
                    </div>

                    <div class="col-sm-2" ng-init="to_session = '{{ Session::get('to_session') }}';" ng-controller="datapickerController">
                        <input type="text" name="to_session" placeholder="Hasta fecha" class="form-control" ng-model="to_session" datepicker-popup="dd-MM-yyyy" is-open="datepickerOpened" ng-required="true" show-button-bar="false" ng-click="open($event)"/>
                    </div>

                    <div class="col-sm-2">
                        <span>
                            <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Filtrar</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php

    $defaultValues = [
            [
                'rechazos' =>   ['color' => '#9bafff'],
            ],[
                'pendientes' => ['color' => '#fdff9b','visible'=> false],
            ],[
                'scrap' =>      ['color' => '#ff0202','visible'=> false],
            ],[
                'reparados' =>  ['color' => '#25db11','visible'=> false],
            ]
    ];

    $top = 10;
    $causasChart = [
        'id' => 'CausasChart',
        'titulo' => 'Causas',
        'top' => $top,
        'height' => '300',
        'collection' => $stats->causas,
        'values' => $defaultValues,
        'total' => 'rechazos'
    ];

    $defectosChart = [
        'id' => 'DefectosChart',
        'titulo' => 'Defectos',
        'top' => $top,
        'height' => '300',
        'collection' => $stats->defectos,
        'values' => $defaultValues,
        'total' => 'rechazos'
    ];

    $referenciasChart = [
        'id' => 'ReferenciasChart',
        'titulo' => 'Referencias',
        'top' => $top,
        'height' => '300',
        'collection' => $stats->referencias,
        'values' => $defaultValues,
        'total' => 'rechazos'
    ];

    $accionesChart = [
        'id' => 'CorrectivasChart',
        'titulo' => 'Correctivas',
        'top' => $top,
        'height' => '300',
        'collection' => $stats->acciones,
        'values' => $defaultValues,
        'total' => 'rechazos'
    ];

    $origenesChart = [
        'id' => 'OrigenChart',
        'titulo' => 'Origen',
        'top' => $top,
        'height' => '300',
        'collection' => $stats->origenes,
        'values' => $defaultValues,
        'total' => 'rechazos'
    ];

    $reparadoresChart = [
        'id' => 'ReparadoresChart',
        'titulo' => 'Reparadores',
        'top' => $top,
        'height' => '300',
        'collection' => $stats->reparadores,
        'values' => $defaultValues,
        'total' => 'rechazos'
    ];

    $turnosChart = [
        'id' => 'TurnosChart',
        'titulo' => 'Turno',
        'top' => $top,
        'height' => '300',
        'collection' => $stats->turnos,
        'values' => $defaultValues,
        'total' => 'rechazos'
    ];

?>

<div class="container">

    <h2>{{ \IAServer\Http\Controllers\Reparacion\Model\Sector::find(Session::get('id_sector'))->sector }}</h2>
    <div class="row">
        <div class="col-xs-6">
            @include('reparacion.chart',['opt'=>$causasChart])
        </div>

        <div class="col-xs-6">
            @include('reparacion.chart',['opt'=>$defectosChart])
        </div>

        <div class="col-xs-6">
            @include('reparacion.chart',['opt'=>$referenciasChart])
        </div>
        <div class="col-xs-6">
            @include('reparacion.chart',['opt'=>$accionesChart])
        </div>

        <div class="col-xs-6">
            @include('reparacion.chart',['opt'=>$origenesChart])
        </div>
        <div class="col-xs-6">
            @include('reparacion.chart',['opt'=>$reparadoresChart])

        </div>

        <div class="col-xs-6">
            @include('reparacion.chart',['opt'=>$turnosChart])
        </div>
    </div>

    <div class="table-responsive" style="height: 500px;">
        <table class="table table-hover" >
            <thead>
            <tr>
                <th>Estado</th>
                <th>Codigo</th>
                <th>Modelo</th>
                <th>Lote</th>
                <th>Panel</th>
                <th>Defecto</th>
                <th>Causa</th>
                <th>Referencia</th>
                <th>Correctiva</th>
                <th>Observacion</th>
                <th>Origen</th>
                <th>Reparador</th>
                <th>Turno</th>
                <th>Area</th>
                <th>Fecha</th>
                <th>Hora</th>
            </tr>
            </thead>
            <tbody>
                @foreach( $reparacion as $rep )
                    <tr
                            @if( $rep->historico == 'log')
                                style="color: #c4c4c4;background-color: #efefef"
                            @endif
                            @if( $rep->estado== 'P' && $rep->reparaciones == 0)
                                style="background-color: #fdff9b"
                            @endif
                    >
                        <td>
                            @if( $rep->historico == 'log')
                                Cerrado:
                            @endif
                            @if( $rep->estado== 'P')
                                Pendiente
                            @elseif($rep->estado== 'R')
                                Reparado
                            @endif

                        </td>
                        <td>{{ $rep->codigo }}</td>
                        <td>{{ $rep->modelo }}</td>
                        <td>{{ $rep->lote }}</td>
                        <td>{{ $rep->panel }}</td>
                        <td>{{ $rep->defecto }}</td>
                        <td>{{ $rep->causa }}</td>
                        <td>{{ $rep->referencia }}</td>
                        <td>{{ $rep->accion }}</td>
                        <td>{{ $rep->correctiva }}</td>
                        <td>{{ $rep->origen }}</td>
                        <td>{{ $rep->nombre_completo }}</td>
                        <td>{{ $rep->turno }}</td>
                        <td>{{ $rep->area }}</td>
                        <td>{{ $rep->fecha }}</td>
                        <td>{{ $rep->hora }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@include('iaserver.common.footer')
{!! IAScript('assets/highchart/js/highcharts.js') !!}

@endsection


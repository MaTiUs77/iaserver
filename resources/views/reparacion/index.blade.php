@extends('adminlte/theme')
@section('ng','app')
@section('mini',true)
@section('title','Reparacion - Reporte')
@section('body')

<div class="well">

    <form method="GET" action="?">
        <div class="row">
            <div class="col-xs-2">
                <div class="form-group">
                    <select class="form-control" name="id_sector">
                        <option value="" selected="selected">- Seleccionar sector -</option>
                        @foreach(\IAServer\Http\Controllers\Reparacion\Model\Sector::where('visible',1)->get() as $value)
                            <option value="{{ $value->id }}" {{ $value->id == Session::get('id_sector') ? 'selected="selected"' : '' }}>{{ $value->sector }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-2">
                <input type="text" name="reparacion_fecha" value="{{ Session::get('reparacion_fecha') }}" placeholder="Seleccionar fecha" class="form-control defaultdatarangepicker"/>
            </div>

            <div class="col-xs-2">
                <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
            </div>
        </div>
    </form>

</div>

    @if(isset($reparacion) && count($reparacion))
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
            <table class="table table-hover datatable" >
                <thead>
                <tr>
                    <th>Estado</th>
                    <th>Codigo</th>
                    <th>OP</th>
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
                        <td>{{ $rep->op }}</td>
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
    @else
        @if($id_sector==null)
            <h3>No definio el sector</h3>
        @else
            <h3>No se registraron reparaciones en la fecha solicitada</h3>
        @endif
    @endif



@include('iaserver.common.footer')
{!! IAScript('assets/highchart/js/highcharts.js') !!}
@endsection


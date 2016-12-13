@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector - Estadisticas')
@section('body')
    <style>
        .f15 {
            font-size: 15px;
        }
    </style>
    <div ng-controller="statController">
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
                @include('aoicollector.stat.partial.header')

                <div class="dropdown pull-right ">

                    <button ng-show="statExporting" class="btn btn-sm btn-warning" type="button">
                        Procesando datos, espere...
                    </button>

                    <button ng-hide="statExporting" class="btn btn-sm btn-danger dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Exportar datos desde historial
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu" ng-hide="statExporting">
                        <li><a href="{{ route('aoicollector.stat.export',[$maquina->linea, $turno, $fecha, 'first'] ) }}" ng-click="statExporting = true">Primer estado de cada placa</a></li>
                        <li><a href="{{ route('aoicollector.stat.export',[$maquina->linea, $turno, $fecha, 'last'] ) }}" ng-click="statExporting = true">Ultimo estado de cada placa</a></li>
                    </ul>
                </div>

                <div style="padding: 5px">
                    <!-- Lista programas ejecutados en la jornada -->
                    <h3>
                        <label class="label label-primary">
                            <b>SMD-{{ $maquina->linea }}</b>
                            |
                            @if($maquina->tipo=='R') VT-RNS @endif
                            @if($maquina->tipo=='V') VT-S500 @endif
                            @if($maquina->tipo=='W') VT-WIN @endif
                        </label>
                    </h3>

                    @if( isset($programas) && count($programas)>0)
                        <h3>Inspecciones del dia <b>{{ $fecha }}</b> | Turno <b>{{ $turno == 'M' ? 'Mañana' : 'Tarde' }}</b></h3>

                    <div class="row">


                        @foreach( $programas as $p )
                            <?php
                                $smt = \IAServer\Http\Controllers\SMTDatabase\SMTDatabase::findOp($p->inspected_op);
                            ?>

                            <div class="col-sm-6 col-md-4 col-lg-4">
                                <blockquote style="font-size: 16px;">
                                    <div class="dropdown" style="padding-right:5px;">
                                        <button class="btn btn-sm dropdown-toggle {{ isset($resume) && ($p->programa == $resume->programa)  && ($p->inspected_op == $resume->inspected_op)  ? 'btn-default active' : 'btn-default' }}" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span style="font-size: 16px;">{{ $p->inspected_op }}</span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                                            <li><a href="{{ route('aoicollector.stat.show',[$p->id_maquina, $turno, $fecha, 'first', $p->programa, $p->inspected_op ] ) }}">Primer estado</a></li>
                                            <li><a href="{{ route('aoicollector.stat.show',[$p->id_maquina, $turno, $fecha, 'last', $p->programa, $p->inspected_op ] ) }}">Ultimo estado</a></li>
                                        </ul>
                                    </div>

                                    <small>Modelo</small>
                                    {{ $smt->modelo  }} - {{ $smt->lote  }} - {{ $smt->panel  }}
                                    <small>Programa</small>
                                    {{ $p->programa  }}

                                </blockquote>

                            </div>

                        @endforeach
                    </div>
                    @else
                        <h3>No se detectaron inspecciones en el dia <b>{{ $fecha }}</b> | Turno <b>{{ $turno == 'M' ? 'Mañana' : 'Tarde' }}</b></h3>
                        @endif
                                <!-- Fin -->

                        @include('aoicollector.stat.partial.resume')
                </div>

            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2">
                @include('aoicollector.stat.partial.sidebar')
            </div>
        </div>


    </div>

    {!! IAScript('assets/gauge/dist/gauge.min.js') !!}
    @include('iaserver.common.footer')
    {!! IAScript('vendor/aoicollector/stat/stat.js') !!}
@endsection
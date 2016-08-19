@extends('angular')
@section('ng','app')
@section('title','Aoicollector - Inspecciones')
@section('body')
    <div ng-controller="inspectionController">
        <table style="width: 100%">
            <tr>
                <td style="vertical-align: top;">
                    @include('aoicollector.inspection.partial.header')

                    @if( isset($timeline) )
                        <h3>Resultado de busqueda: <b>{{ $barcode }}</b></h3>
                    @endif

                    @if( isset($search_reference) )
                        <h3>Resultado de busqueda: <b>{{ $search_reference }}</b></h3>
                    @endif

                    @if(!isset($timeline) && !isset($search_reference) )

                        <div class="dropdown pull-right" style="margin:10px;">

                            <button ng-show="statExporting" class="btn btn-sm btn-warning" type="button">
                                Procesando datos, espere...
                            </button>

                            <button ng-hide="statExporting" class="btn btn-sm btn-danger dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Exportar datos desde historial
                                <span class="caret"></span>
                            </button>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu" ng-hide="statExporting">
                                <li><a href="{{ route('aoicollector.inspection.export',[$maquina->id,Session::get('date_session'),'MIN']) }}" ng-click="statExporting = true">Primer estado de cada placa</a></li>
                                <li><a href="{{ route('aoicollector.inspection.export',[$maquina->id,Session::get('date_session'),'MAX']) }}" ng-click="statExporting = true">Ultimo estado de cada placa</a></li>
                            </ul>
                        </div>

                        <div class="dropdown pull-right" style="margin:10px;">

                            <button class="btn btn-sm btn-primary" ng-click="detalledeop=!detalledeop">
                                Ver detalle de OP
                            </button>
                        </div>

                        <h3>Inspecciones en <b>SMD-{{ $maquina->linea }}</b> - Paneles en Total: <b>{{ $total  }}</b></h3>
                        <hr>

                        @foreach( $programas as $p )
                            <?php
                            $smt = \IAServer\Http\Controllers\SMTDatabase\SMTDatabase::findOp($p->inspected_op);
                            ?>

                            <div class="col-lg-4" ng-show="detalledeop">
                                <blockquote style="font-size: 16px;">
                                    <div class="dropdown" style="padding-right:5px;">
                                        <button class="btn btn-sm dropdown-toggle {{ isset($resume) && ($p->programa == $resume->programa)  && ($p->inspected_op == $resume->inspected_op)  ? 'btn-default active' : 'btn-default' }}" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span style="font-size: 16px;">{{ $p->inspected_op }}</span>
                                        </button>
                                    </div>

                                    <small>Modelo</small>
                                    {{ $smt->modelo  }} - {{ $smt->lote  }} - {{ $smt->panel  }}
                                    <small>Programa</small>
                                    {{ $p->programa  }}
                                    <?php
                                        $pcbData = \IAServer\Http\Controllers\Aoicollector\Model\PcbData::where('nombre',$p->programa)->where('tipo_maquina',$maquina->tipo)->first();
                                    ?>
                                    @if(isset($pcbData))
                                        <small>Etiquetas</small>
                                        {{ $pcbData->etiquetas }}
                                        <small>Placa secundaria</small>
                                         {{ $pcbData->secundaria == '1' ? 'Si' : 'No' }}
                                    @endif
                                </blockquote>

                            </div>
                            <a style="display: none;" href="#{{ route('aoicollector.inspection.showop',[$p->inspected_op ] ) }}" class="btn btn-sm btn-default" type="button" >
                                <span class="label label-default active" style="font-size: 12px;text-shadow: none;">{{ $p->inspected_op }}</span>
                                {{ $p->programa  }}
                            </a>
                        @endforeach
                    @endif

                    <!-- OP INSPECCIONADAS EN LA JORNADA -->
                    <div style="padding: 5px;">
                        @if( isset($op_list) )
                            @foreach( $op_list as $op )
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default">{{ $op->op  }}</button>
                                    <button type="button" class="btn btn-success">{{ $op->total  }}</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- END -->

                    @if(isset($timeline))
                        @include('aoicollector.inspection.partial.timeline')
                    @endif

                    @if(isset($search_reference))
                        @include('aoicollector.inspection.partial.search_reference')
                    @endif

                    @if(!isset($timeline) && !isset($search_reference) )
                        @include('aoicollector.inspection.partial.list')
                    @endif
                </td>
                <td style="vertical-align: top;width: 180px;padding-left:5px;">
                    @include('aoicollector.inspection.partial.sidebar')
                </td>
            </tr>
        </table>
    </div>

    @include('iaserver.common.footer')
    {!! IAScript('vendor/aoicollector/inspection/inspection.controller.js') !!}
@endsection
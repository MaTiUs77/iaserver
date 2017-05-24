@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector - Busqueda de multiples inspecciones')
@section('head')
    <style>
        .table tbody tr td {
            text-align: center;
        }

        thead.panel th {
            background-color: #2D6CA2;
            color: white;
            text-align: center;
        }

        thead.bloque th {
            background-color: rgb(31, 70, 107);
            color: white;
            text-align: center;
        }
        thead.detail th {
            background-color: rgb(88, 88, 88);
            color: white;
            text-align: center;
        }

    </style>
@endsection
@section('body')
    <div ng-controller="inspectionController">
        <table style="width: 100%">
            <tr>
                <td style="vertical-align: top;">
                    @include('aoicollector.inspection.partial.header')

                    <div class="clearfix"></div>
                    <h3>Resultado de busqueda multiple</h3>

                    @if (count($barcodes) == 0)
                        <h3>No se encontraron resultados</h3>
                    @else
                        <table class="table table-bordered">
                            <thead class="panel">
                            <tr>
                                <th></th>
                                <th>Linea</th>
                                <th>Panel</th>
                                <th>Modelo</th>
                                <th>Panel</th>
                                <th>AOI</th>
                                <th>INS</th>
                                <th>Errores</th>
                                <th>Falsos</th>
                                <th>Reales</th>
                                <th>Bloques</th>
                                <th>OP</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach( $barcodes as $inspection)
                                    @if(isset($inspection->error))
                                        <tr>
                                            <td></td>
                                            <td>{{ $inspection->barcode }}</td>
                                            <td colspan="13">{{ $inspection->error }}</td>
                                        </tr>
                                    @else
                                        <!-- Si hay un error los muestro -->
                                        <tr class="{{ ($inspection->panel->revision_aoi == 'OK' && $inspection->panel->revision_ins == 'OK' ) ? 'success' : '' }} {{ ($inspection->panel->revision_ins == 'NG' ) ? 'danger' : '' }} {{ ($inspection->panel->revision_ins == 'SCRAP' ) ? 'warning' : '' }}">
                                            <td style="width:50px;">
                                                <button id_panel="{{ $inspection->panel->id_panel_history }}" route="{{ route('aoicollector.inspection.blocks',$inspection->panel->id_panel_history) }}" ng-click="getInspectionBlocks($event);" class="btn btn-xs btn-default">Bloques</button>
                                            </td>
                                            <td>
                                                @if(isset($inspection->panel->linea))
                                                    SMD-{{ $inspection->panel->linea }}
                                                @else
                                                    SMD-{{ $inspection->panel->maquina->linea }}
                                                @endif
                                            </td>
                                            <td>{{ $inspection->panel->panel_barcode }}</td>
                                            <td>{{ $inspection->smt->modelo }}</td>
                                            <td>{{ $inspection->smt->panel }}</td>
                                            <td>{{ $inspection->panel->revision_aoi }}</td>
                                            <td>{{ $inspection->panel->revision_ins }}</td>
                                            <td>{{ $inspection->panel->errores }}</td>
                                            <td>{{ $inspection->panel->falsos }}</td>
                                            <td>{{ $inspection->panel->reales }}</td>
                                            <td>{{ $inspection->panel->bloques }}</td>
                                            <td>{{ $inspection->panel->inspected_op }}</td>
                                            <td>{{ dateToEs($inspection->panel->created_date) }}</td>
                                            <td>{{ $inspection->panel->created_time }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </td>
                <td style="vertical-align: top;width: 180px;padding-left:5px;">
                    @include('aoicollector.inspection.partial.sidebar')
                </td>
            </tr>
        </table>
    </div>

    @include('iaserver.common.footer')
    {!! IAScript('vendor/aoicollector/inspection/inspection.js') !!}
@endsection
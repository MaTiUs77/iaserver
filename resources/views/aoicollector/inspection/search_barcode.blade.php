@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector - Busqueda de inspeccion')
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

                    <h3>Resultado de busqueda: <b>{{ $barcode }}</b></h3>

                    @if (count($insp_by_date) == 0)
                        <h3>No se encontraron resultados</h3>
                    @else

                        @if(isAdmin())
                            <a href="{{ route('aoicollector.inspection.admin.forceok',$barcode) }}" class="btn btn-success">Forzar estado OK</a>
                        @endif

                        @foreach( $insp_by_date as $date => $panels)
                            <h3>{{ $date }}</h3>
                            <table class="table table-bordered">
                                <thead class="panel">
                                <tr>
                                    <th></th>
                                    <th>Linea</th>
                                    <th>Panel</th>
                                    <th>Programa</th>
                                    <th>AOI</th>
                                    <th>INS</th>
                                    <th>Errores</th>
                                    <th>Falsos</th>
                                    <th>Reales</th>
                                    <th>Bloques</th>
                                    {{--<th>Etiqueta</th>--}}
                                    <th>OP</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Ruta</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach( $panels as $inspection)
                                        <?php
                                        $wip = $inspection->wip;
                                        ?>

                                        <!-- Si hay un error los muestro -->
                                        @if ( isset($insp->error))
                                            <tr>
                                                <td colspan="13">
                                                    {{ $insp->error }}
                                                </td>
                                            </tr>
                                        @else
                                            <tr class="{{ ($inspection->panel->revision_aoi == 'OK' && $inspection->panel->revision_ins == 'OK' ) ? 'success' : '' }} {{ ($inspection->panel->revision_ins == 'NG' ) ? 'danger' : '' }}">
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
                                                <td>{{ $inspection->panel->programa }}</td>
                                                <td>{{ $inspection->panel->revision_aoi }}</td>
                                                <td>{{ $inspection->panel->revision_ins }}</td>
                                                <td>{{ $inspection->panel->errores }}</td>
                                                <td>{{ $inspection->panel->falsos }}</td>
                                                <td>{{ $inspection->panel->reales }}</td>
                                                <td>{{ $inspection->panel->bloques }}</td>
                                                <td>{{ $inspection->panel->inspected_op }}</td>
                                                <td>{{ dateToEs($inspection->panel->created_date) }}</td>
                                                <td>{{ $inspection->panel->created_time }}</td>
                                                <td>
                                                    @if(isset($wip->twip))
                                                        @if(isset($wip->twip->joinRoute->name))
                                                            {{ $wip->twip->joinRoute->name  }}
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($wip->twip))
                                                        @if($wip->declaracion->declarado)
                                                            <i class="fa fa-thumbs-o-up text-success" tooltip-placement="left" tooltip="Declarado"></i>
                                                        @else
                                                            @if($wip->declaracion->error)
                                                                <i class="fa fa-thumbs-o-down text-danger" tooltip-placement="left" tooltip="Declarado con errores"></i>
                                                            @endif

                                                            @if($wip->declaracion->pendiente)
                                                                <i class="fa fa-clock-o text-info" tooltip-placement="left" tooltip="Pendiente"></i>
                                                            @endif

                                                            @if(!$wip->declaracion->error && !$wip->declaracion->pendiente)
                                                                <i class="fa fa-exclamation-circle text-warning" tooltip-placement="left" tooltip="Declaracion parcial"></i>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <i class="fa fa-exclamation-circle text-danger" tooltip-placement="left" tooltip="Sin declarar"></i>
                                                    @endif

                                                    @if(isset($inspection->panel->joinProduccion->cogiscan_traza) && $inspection->panel->joinProduccion->cogiscan_traza==1)
                                                        @if(isset($inspection->cogiscan['attributes']['message']))
                                                            <i class="fa fa-exclamation-triangle text-danger" tooltip="Cogiscan: {{ $inspection->cogiscan['attributes']['message'] }}"></i>
                                                        @else
                                                            @if(isset($inspection->cogiscan['Product']['attributes']['operation']))
                                                                @if($inspection->cogiscan['Product']['attributes']['operation'] == 'Depanelization')
                                                                    <i class="fa fa-send text-info" tooltip="Ruta: {{ $inspection->cogiscan['Product']['attributes']['operation'] }}, Estado: {{ $inspection->cogiscan['Product']['attributes']['status'] }}"></i>
                                                                @else
                                                                    <i class="fa fa-road text-success" tooltip="Ruta: {{ $inspection->cogiscan['Product']['attributes']['operation'] }}, Estado: {{ $inspection->cogiscan['Product']['attributes']['status'] }}"></i>
                                                                @endif

                                                                @if(isset($inspection->cogiscan['attributes']['quarantineLocked']) && $inspection->cogiscan['attributes']['quarantineLocked'] == "true")
                                                                    <i class="fa fa-bomb text-danger" tooltip="Placa en cuarentena"></i>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>

                                            <!-- Muestro paginacion, solo si hay mas de una pagina-->
                                            @if( isset($total) && $paginas > 1)
                                                <tr>
                                                    <td colspan="14">
                                                        @include('aoicollector.inspection.partial.pagination')
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
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


            <!-- Include Date Range Picker -->
    {!! IAScript('assets/moment.min.js') !!}
    {!! IAScript('assets/moment.locale.es.js') !!}
    {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
    {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}
    <script type="text/javascript">
        moment.locale("es");

        $(function() {
            $('input[name="inspection_date_session"]').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    customRangeLabel: 'Definir rango'
                },
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()]
                },
                autoApply: true,
                singleDatePicker: true
            });
        });
    </script>
@endsection
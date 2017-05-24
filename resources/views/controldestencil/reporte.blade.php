@extends('controldestencil.index')
@section('head')
    {!! IAScript('adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
    {!! IAScript('adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! IAStyle('adminlte/plugins/datatables/dataTables.bootstrap.css') !!}
    {!! IAScript('assets/moment.min.js') !!}
    {!! IAScript('assets/moment.locale.es.js') !!}
    {{--Angular DataTables--}}
    {!! IAScript('assets/angular-datatables/angular-datatables.min.js') !!}
    {{----}}
@endsection
@section('body')
    <div class="container" ng-controller="lavadoController">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Registrar Lavado de Placa</h3>
                    <div class="box-body">
                        <div class="col-lg-3">
                            <select id="selectLinea" ng-model="_linea" class="form-control select2">
                                <option value = 'NULL' selected="selected">Seleccione Linea</option>
                                <option value = '1' disabled="disabled">1 (deshabilitada)</option>
                                <option value = '2'>2</option>
                                <option value = '3'>3</option>
                                <option value = '4'>4</option>
                                <option value = '5'>5</option>
                                <option value = '6'>6</option>
                                <option value = '7' disabled="disabled">7 (deshabilitada)</option>
                                <option value = '8' disabled="disabled">8 (deshabilitada)</option>
                                <option value = '9'>9</option>
                                <option value = '10'>10</option>
                                <option value = '11' disabled="disabled">11 (deshabilitada)</option>
                                <option value = '12'>12</option>
                                <option value = '13' disabled="disabled">13 (deshabilitada)</option>
                                <option value = '14' disabled="disabled">14 (deshabilitada)</option>
                                <option value = '15' disabled="disabled">15 (deshabilitada)</option>
                                <option value = '16'>16</option>
                                <option value = '17'>17</option>
                                <option value = '18' disabled="disabled">18 (deshabilitada)</option>
                                <option value = '19'>19</option>
                                <option value = '20'>20</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            {{--<input id="checkbox" type="checkbox" class="icheckbox_flat-blue" checked>--}}
                            {{--<label>Tiene Código</label>--}}
                            <input id="_codigo" type="text" ng-keypress="validar($event)" class="form-control" ng-model="_codigo" ng-disabled="codDisabled" placeholder="Código de Panel">
                        </div>
                            <button class="btn btn-social btn-vk" ng-click="registrar()"><i class="fa fa-database"></i> Registrar Lavado</button>
                        <div class="pull-right">
                            <form method="POST" action="{{url('/lavado/placas/export')}}">
                                <button type="submit" class="btn btn-social btn-success" ><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
                                <input type="text" style="display: none;" name="export" value=true>
                            </form>
                        </div>
                    </div>
                    <div class="box-footer"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Historial de Lavado</h3>
                    <div class="col-lg-6 pull-right">
                        <form method="GET" ng-submit="filtrar($event)" class="navbar-form navbar-left">
                            <div class="form-group">
                                <input type="text" name="lavados_fecha" value="{{ Session::get('lavados_fecha') }}" placeholder="Seleccionar fecha" class="form-control"/>
                            </div>
                            <select id="selectLineaHistory" ng-model="_lineaHistory" class="form-control select2">
                                <option value = 'NULL' selected="selected"> -- Todas -- </option>
                                <option value = '1' disabled="disabled">1 (deshabilitada)</option>
                                <option value = '2'>2</option>
                                <option value = '3'>3</option>
                                <option value = '4'>4</option>
                                <option value = '5'>5</option>
                                <option value = '6'>6</option>
                                <option value = '7' disabled="disabled">7 (deshabilitada)</option>
                                <option value = '8' disabled="disabled">8 (deshabilitada)</option>
                                <option value = '9'>9</option>
                                <option value = '10'>10</option>
                                <option value = '11' disabled="disabled">11 (deshabilitada)</option>
                                <option value = '12'>12</option>
                                <option value = '13' disabled="disabled">13 (deshabilitada)</option>
                                <option value = '14' disabled="disabled">14 (deshabilitada)</option>
                                <option value = '15' disabled="disabled">15 (deshabilitada)</option>
                                <option value = '16'>16</option>
                                <option value = '17'>17</option>
                                <option value = '18' disabled="disabled">18 (deshabilitada)</option>
                                <option value = '19'>19</option>
                                <option value = '20'>20</option>
                            </select>
                            <button type="submit" class="btn btn-info"><i class="fa  fa-search-plus"></i> Buscar</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="text" style="display: none;" name="export" value=false>
                        </form>
                    </div>

                    <!-- DATE RANGE PICKER -->
                    <script type="text/javascript">
                        $(function() {
                            $('input[name="lavados_fecha"]').daterangepicker({
                                locale: {
                                    format: 'DD/MM/YYYY',
                                    customRangeLabel: 'Definir rango'
                                },
                                ranges: {
                                    'Hoy': [moment(), moment()],
                                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                    'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()],
                                    'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
                                    'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                                    'Ultimo Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                                },
                                autoApply: true
                            });
                        });

                        moment.locale("es");

                    </script>
                    <!-- Include Date Range Picker -->

                    {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
                    {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}

                    <div class="box-body">
                        <hr>
                        <table datatable="ng" dt-options="dtOptions" id="tablaHistorial" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">Linea</th>
                                <th class="text-center">Codigo</th>
                                <th class="text-center">Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="h in history">
                                    <td class="text-center">@{{h.linea}}</td>
                                    <td class="text-center ">@{{h.codigo}}</td>
                                    <td class="text-center">@{{h.timestamp}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('iaserver.common.footer')
    {!! IAScript('vendor/controldestencil/controldestencil.js') !!}
@endsection
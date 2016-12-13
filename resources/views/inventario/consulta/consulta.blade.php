@extends('inventario.index')
@section('ng','app')
@section('head')
    {!! IAScript('adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
    {!! IAScript('adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! IAStyle('adminlte/plugins/datatables/dataTables.bootstrap.css') !!}
    {{--{!! IAStyle('assets/bootswatch/paper/bootstrap.min.css') !!}--}}
@endsection
@section('body')
    <h2>Reporte de Impresiones</h2>
    <div  ng-controller="reportController">

        <div class="col-lg-12">
            <div class="btn-group col-lg-12">
                <div class="col-lg-12">
                    <form method="GET" action="{{url('/inventario/consultar/reporte/find')}}" class="navbar-form navbar-left">
                        <div class="form-group">
                            <input type="text" name="partNumber" placeholder="Part Number" class="form-control"/>
                            <select name="ddUsuarios" class="form-control">
                                <option value="todasZonas">- Todos los Usuarios -</option>
                                {{--@foreach($lineas as $linea)--}}
                                {{--<option value="{{$linea->linea}}">Linea {{$linea->linea}}</option>--}}
                                {{--@endforeach--}}
                            </select>
                            <select name="ddZona" class="form-control">
                                <option value="todasZonas">- Todas las Zonas -</option>
                                {{--@foreach($lineas as $linea)--}}
                                {{--<option value="{{$linea->linea}}">Linea {{$linea->linea}}</option>--}}
                                {{--@endforeach--}}
                            </select>
                            <select name="ddPlanta" class="form-control">
                                <option value="todasPlantas">- Todas las Plantas -</option>
                                {{--@foreach($lineas as $linea)--}}
                                    {{--<option value="{{$linea->linea}}">Linea {{$linea->linea}}</option>--}}
                                {{--@endforeach--}}
                            </select>



                            <input type="text" name="pizarra_fecha" value="{{ Session::get('pizarra_fecha') }}" placeholder="Seleccionar fecha" class="form-control"/>
                        </div>
                        <button type="button" class="btn btn-info"><i class="fa fa-search"></i> Buscar</button>
                    </form>


                </div>
                <!-- AngularJS Application Scripts -->
                {!! IAScript('vendor/iaserver/iaserver.js') !!}
                {!! IAScript('vendor/inventario/consulta/consulta.factory.js') !!}
                {!! IAScript('vendor/inventario/consulta/reportes.controller.js') !!}
                {!! IAScript('assets/moment.min.js') !!}
                        <!-- Include Date Range Picker -->
                {!! IAScript('assets/moment.locale.es.js') !!}
                {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
                {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}

                {{--@if(!empty($datos))--}}
                    <div class="col-lg-12">
                        <table id="tablaRecupero" class="table table-striped">
                            <thead>
                            <tr>
                                <th data-sortable="true" class="text-center">ID</th>
                                <th data-sortable="true" class="text-center">PN</th>
                                <th data-sortable="true" class="text-center">LPN</th>
                                <th data-sortable="true" class="text-center">Cantidad</th>
                                <th data-sortable="true" class="text-center">Planta</th>
                                <th data-sortable="true" class="text-center">Zona</th>
                                <th data-sortable="true" class="text-center">Usuario</th>
                                <th data-sortable="true" class="text-center">Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="impresion in impresiones">
                                    <td class="text-center">@{{impresion.id}}</td>
                                    <td class="text-center">@{{impresion.pn}}</td>
                                    <td class="text-center">@{{impresion.lpn}}</td>
                                    <td class="text-center">@{{impresion.cant}}</td>
                                    <td class="text-center">@{{impresion.planta}}</td>
                                    <td class="text-center">@{{impresion.zona}}</td>
                                    <td class="text-center">@{{impresion.user}}</td>
                                    <td class="text-center">@{{impresion.fecha}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <script>
                    $(document).ready(function() {
                        $('#tablaRecupero').DataTable( {
                            "language": {
                                "search":"Buscar",
                                "lengthMenu":"Ver _MENU_ resultados",
                                "info":"Ver _START_ a _END_ de _TOTAL_ resultados",
                                "zeroRecords":"No hay resultados",
                                "paginate": {
                                    "first":"Primero",
                                    "last":"Ultimo",
                                    "next":"Siguiente",
                                    "previous":"Anterior"
                                }
                            }
                        } );
                    } );
                </script>
                {{--@endif--}}

                <script type="text/javascript">
                    $(function() {
                        $('input[name="pizarra_fecha"]').daterangepicker({
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
                @if(hasRole('smtdatabase_operator') || isAdmin())
                    <div class="pull-right">
                        <a href="{{url('/amr/recupero/reporte/exportar')}}"><button type="button" class="btn btn-success"><span class="fa fa-file-excel-o"></span> Exportar a Excel</button></a>
                    </div>
                @endif


            </div>
        </div>

    </div>



@endsection


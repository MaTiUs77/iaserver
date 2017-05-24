@extends('adminlte/theme')
@section('title','Recupero de Materiales')
@section('mini',false)
@section('collapse',false)
@section('menuaside')
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ url('amr/recupero/') }}">RECUPERAR</a></li>
                <li><a href="{{ url('amr/recupero/reporte') }}">REPORTE</a></li>
            </ul>
        </section>
    </aside>

@endsection
@section('body')
    @if(hasRole('smtdatabase_operator') || isAdmin())
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}">
    @endif
<div class="container-fluid">
    <h2>Reporte de Recuperación de Materiales</h2>

        <div class="col-lg-12">
            <div class="btn-group col-lg-12">
                <div class="col-lg-6">
                    <form method="GET" action="{{url('/amr/recupero/reporte/find')}}" class="navbar-form navbar-left">
                        <div class="form-group">
                        <select name="ddLinea" class="form-control">
                            <option value="todas">- Todas las Líneas -</option>
                            @foreach($lineas as $linea)
                                <option value="{{$linea->linea}}">Linea {{$linea->linea}}</option>
                            @endforeach
                        </select>

                            <input type="text" name="pizarra_fecha" value="{{ Session::get('pizarra_fecha') }}" placeholder="Seleccionar fecha" class="form-control fulldatarangepicker"/>
                        </div>
                        <button type="submit" class="btn btn-info"><i class="fa fa-calendar"></i> Buscar</button>
                    </form>

                </div>

                @if(hasRole('smtdatabase_operator') || isAdmin())
                    <div class="pull-right">
                        <a href="{{url('/amr/recupero/reporte/exportar')}}"><button type="button" class="btn btn-success"><span class="fa fa-file-excel-o"></span> Exportar a Excel</button></a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(!empty($datos))
        <div class="col-lg-12">
            <table id="tablaRecupero" class="table table-striped">
                <thead>
                <tr>
                    <th data-sortable="true" class="text-center">LPN</th>
                    <th data-sortable="true" class="text-center">Part Number</th>
                    <th data-sortable="true" class="text-center">Cantidad Recuperada</th>
                    <th data-sortable="true" class="text-center">Contenido En</th>
                    <th data-sortable="true" class="text-center">Ubicación en el Contenedor</th>
                    <th data-sortable="true" class="text-center">OP</th>
                    <th data-sortable="true" class="text-center">Linea</th>
                    <th data-sortable="true" class="text-center">usuario</th>
                    <th data-sortable="true" class="text-center">Fecha de Recuperación</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datos as $dato)
                    <tr>
                        <td class="text-center">{{$dato->item_id}}</td>
                        <td class="text-center">{{$dato->part_number}}</td>
                        <td class="text-center">{{$dato->cantidad_recuperada}}</td>
                        <td class="text-center">{{$dato->container_id}}</td>
                        <td class="text-center">{{$dato->location_in_container}}</td>
                        <td class="text-center">{{$dato->op}}</td>
                        <td class="text-center">{{$dato->linea}}</td>
                        <td class="text-center">{{$dato->user}}</td>
                        <td class="text-center">{{$dato->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
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
@stop

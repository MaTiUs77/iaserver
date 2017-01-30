@extends('inventario.index')
@section('ng','app')
@section('head')
    {!! IAScript('adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
    {!! IAScript('adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! IAStyle('adminlte/plugins/datatables/dataTables.bootstrap.css') !!}
    {{--{!! IAStyle('assets/bootswatch/paper/bootstrap.min.css') !!}--}}
@endsection
@section('body')
    @if(hasRole('smtdatabase_operator') || isAdmin())
    <h2>Reporte de Impresiones</h2>
    <div  ng-controller="reportController">

        <div class="col-lg-12">
            <div class="btn-group col-lg-12">
                <div class="col-lg-12">
                    <form method="get" action="{{url('inventario/excel')}}" class="navbar-form navbar-left">
                        <div class="form-group">
                        <select class="form-control" name="plantas">
                            <option value="all">Seleccionar Todo</option>
                            @foreach($pl as $planta)
                            <option value="{{$planta->id_planta}}">{{$planta->descripcion}}</option>
                            @endforeach
                        </select>
                        <input type="text" name="pizarra_fecha" value="{{ Session::get('pizarra_fecha') }}" placeholder="Seleccionar fecha" class="form-control"/>

                                <div class="pull-right">
                                <button type="submit" class="btn btn-success"><span class="fa fa-file-excel-o"></span> Exportar a Excel</button>
                                </div>

                        </div>
                    </form>
                </div>
                @endif
                <!-- AngularJS Application Scripts -->
                {!! IAScript('vendor/iaserver/iaserver.js') !!}
                {!! IAScript('vendor/inventario/consulta/consulta.factory.js') !!}
                {!! IAScript('vendor/inventario/consulta/reportes.controller.js') !!}
                {!! IAScript('assets/moment.min.js') !!}
                        <!-- Include Date Range Picker -->
                {!! IAScript('assets/moment.locale.es.js') !!}
                {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
                {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}

                <script type="text/javascript">
                    $(function() {
                        $('input[name="pizarra_fecha"]').daterangepicker({
                            locale: {
                                format: 'YYYY-MM-DD',
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

            </div>
        </div>

    </div>

@endsection


@extends('scrap.index')
@section('head')
    {!! IAScript('adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
    {!! IAScript('adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! IAStyle('adminlte/plugins/datatables/dataTables.bootstrap.css') !!}
    {{--{!! IAStyle('assets/bootswatch/paper/bootstrap.min.css') !!}--}}
@endsection
@section('body')
    <div class="container">
        <h2>Reporte de Scrap IA <small>(Inserción Automática)</small></h2>
        <div class="col-lg-12">
            <div class="col-lg-4">
                <form method="GET" action="{{url('/scrap')}}" class="navbar-form navbar-left">
                    <div class="form-group">
                        <input type="text" name="pizarra_fecha" value="{{ Session::get('pizarra_fecha') }}" placeholder="Seleccionar fecha" class="form-control"/>
                    </div>
                    <button type="submit" class="btn btn-info"><i class="fa fa-calendar"></i> Buscar</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="text" style="display: none;" name="export" value=false>
                </form>
            </div>
            @if(!empty($items))
                <div class="pull-right">
                    <form method="POST" action="{{url('/scrap/export')}}">
                        <button type="submit" class="btn btn-success" ><span class="glyphicon glyphicon-save-file"></span>Exportar Datos</button>
                        <input type="text" style="display: none;" name="export" value=true>
                    </form>
                </div>
            @endif
            <!-- DATE RANGE PICKER -->
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
            <!-- Include Date Range Picker -->
            {!! IAScript('assets/moment.min.js') !!}
            {!! IAScript('assets/moment.locale.es.js') !!}
            {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
            {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}
        </div>

        <h4>Resultados:</h4>
        <div class="panel panel-default col-lg-6" style="padding-top:2em;">
            @if (!empty($items))
            <div class="col-lg-12">
                @foreach($lineas as $linea)
                    <div class="panel-group">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a data-toggle="collapse" href="#{{$linea}}"><span class="fa fa-plus"></span> Linea {{$linea}}</a>
                                </h3>
                            </div>
                            <div id="{{$linea}}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    @foreach($ops as $op)
                                        @if($op["linea"] === $linea)
                                            <div class="panel-group">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title">
                                                            <a data-toggle="collapse" href="#{{$linea.$op['op']}}">{{$op["op"]}}</a>
                                                        </h3>
                                                    </div>
                                                    <div id="{{$linea.$op['op']}}" class="panel-collapse collapse">
                                                        <table id="tablaScrap" class="table table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th data-sortable="true" class="text-center">Número de Parte</th>
                                                                <th data-sortable="true" class="text-center">Cantidad</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($items as $item)
                                                                    @if(($item->id_linea === $linea)&&($item->op === $op["op"]))
                                                                        <tr>
                                                                            <td class="text-center">{{$item->partnumber}}</td>
                                                                            <td class="text-center">{{$item->total_error}}</td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
        <div class="panel panel-default col-lg-5" style="margin-left:1em;">
            <div class="panel panel-group" style="display:block;float:right;padding-top:0.5em;">
                <h3>Cantidad de Códigos: <label style="color:blue;">{{$cantMat}}</label></h3>
                <h3>Sumatoria total de Cantidad de Materiales: <label style="color:blue;">{{$qtyTotalMat}}</label></h3>
            </div>
        </div>
    </div>
@endsection

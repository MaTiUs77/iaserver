@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Cogiscan - Grafico de carga')
@section('body')

    <div ng-controller="inspectionController">

        <div class="well">
            <div class="pull-right">
                <form method="GET" action="{{ route('cogiscan.graficos.carga') }}" class="navbar-form navbar-left" style="margin: 0;">
                    <div class="form-group">
                        <input type="text" name="date_session" value="{{ Session::get('date_session') }}" placeholder="Seleccionar fecha" class="form-control"/>
                    </div>

                    <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
                </form>
            </div>

            <script type="text/javascript">
                $(function() {
                    $('input[name="date_session"]').daterangepicker({
                        //timePicker: true,
                        //timePicker24Hour: true,
                        //timePickerIncrement: 10,
                        locale: {
                            //format: 'DD/MM/YYYY H:mm',
                            format: 'DD/MM/YYYY',
                            customRangeLabel: 'Definir rango'
                        },
                        ranges: {
                            //'Hoy': [moment().set({hour:0,minute:0,second:0,millisecond:0}), moment().set({hour:23,minute:59,second:0,millisecond:0})],
                            'Hoy': [moment(), moment()],
                            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()]
                        },
                        autoApply: true
                    });
                });
            </script>

            <a href="{{ route('aoicollector.inspection.index') }}" class="btn btn-info">WDSL Service</a>
            <a href="{{ route('aoicollector.stat.index') }}" class="btn btn-info">DB2 Service</a>


        </div>

        @foreach($byUser as $user => $info)
            <div class="col-sm-6" >
                @include('cogiscan.chart.drilldown',[
                    'user' => $user,
                    'info' => $info
                ])
            </div>
        @endforeach
    </div>

    @include('iaserver.common.footer')
    {!! IAScript('vendor/aoicollector/inspection/inspection.js') !!}

    {!! IAScript('assets/highchart/js/highcharts.js') !!}
    {!! IAScript('assets/highchart/js/modules/drilldown.js') !!}

    <!-- Include Date Range Picker -->
    {!! IAScript('assets/moment.min.js') !!}
    {!! IAScript('assets/moment.locale.es.js') !!}
    {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
    {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}
    <script>
        moment.locale("es");
    </script>


@endsection
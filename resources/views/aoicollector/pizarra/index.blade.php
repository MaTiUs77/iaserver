@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector - Pizarra')
@section('body')
@section('bodytag','ng-controller="pizarraController"')

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Ver menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Pizarra</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="{{ route('aoicollector.pizarra.linea',1) }}">Resumen por linea</a>
                </li>
                <li>
                    <a href="{{ route('aoicollector.pizarra.general') }}">Resumen general</a>
                </li>
            </ul>
            <form method="GET" action="?" class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" name="pizarra_fecha" value="{{ Session::get('pizarra_fecha') }}" placeholder="Seleccionar fecha" class="form-control"/>
                </div>
                <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
            </form>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
        @if(!isset($resume->produccionLine))
            <h3  style="padding-left: 10px;">La linea solicitada no existe</h3>
        @else
            @if($resume->produccion->aoi->total==0 && $resume->produccion->cone->total==0)
                <h3 style="padding-left: 10px;">{{ $resume->produccionLine->linea  }} | No se detecto produccion el dia {{ Session::get('pizarra_fecha') }}</h3>
            @else
                <h3>{{ $resume->produccionLine->linea  }}</h3>
                <h4>Resumen <small>{{ Session::get('pizarra_fecha') }}</small></h4>


                <div class="row">
                    <div class="col-md-6">
                        @include('aoicollector.pizarra.partial.chart.pie',[
                            'turno' => 'M'
                        ])

                        @include('aoicollector.pizarra.partial.chart.produccion_x_hora',[
                            'turno' => 'M'
                        ]);
                    </div>
                    <div class="col-md-6">
                        @include('aoicollector.pizarra.partial.chart.pie',[
                            'turno' => 'T'
                        ])

                        @include('aoicollector.pizarra.partial.chart..produccion_x_hora',[
                            'turno' => 'T'
                        ]);
                    </div>
                </div>

                {{ dump($resume) }}
               {{-- @include('aoicollector.pizarra.partial.panel')--}}
            @endif
        @endif
    </div>
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2">
        <div class="list-group ">
           @include('aoicollector.common.sidebar',[
                'current' => Request::segment(count(Request::segments())),
                'url_before' => url('aoicollector/pizarra/linea')
           ])
        </div>
    </div>
</div>

    @include('iaserver.common.footer')

    {!! IAScript('assets/highstock/js/highstock.js') !!}
    {!! IAScript('assets/moment.min.js') !!}


    <script>
        function chartController(title, renderTo, series, tooltip, legend, enableNavigator)
        {
            var interfaz = {};
            interfaz.chart = null;
            interfaz.toggle = false;
            interfaz.options = {
                chart: {
                    renderTo: renderTo,
                    type: 'column'
                },
                rangeSelector: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                navigator: {
                    enabled: enableNavigator
                },
                title: {
                    text: title
                },
                xAxis: {
                    type: "datetime",
                   /* dateTimeLabelFormats: {
                        day: '%H'
                    },*/
                    tickInterval: moment.duration(1, 'hour').asMilliseconds(),
                    range:  moment.duration(1, 'day').asMilliseconds(),
                    useHtml: true,
                    labels: {
                        formatter: function() {
                            var currdate = Highcharts.dateFormat('%d', this.value);
                            if(currdate != interfaz.lastDate)
                            {
                                interfaz.lastDate = currdate;
                                return '<b>'+Highcharts.dateFormat('%d/%m', this.value)+'</b> '+Highcharts.dateFormat('%H:%M', this.value);
                            } else
                            {
                                return Highcharts.dateFormat('%H:%M', this.value);
                            }
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Total'
                    },
                    min: 0
                },
                legend: {
                    enabled: legend

                },
                plotOptions: {
                    column: {
                        stacking: 'normal'
                    }
                },
                tooltip: tooltip,
                series: series
            };

            interfaz.draw = function()
            {
                interfaz.chart = new Highcharts.StockChart(interfaz.options);
            };

            return interfaz;
        }

        app.controller("pizarraController",function($scope,$rootScope,$http,$timeout,$interval, IaCore, toasty)
        {
        });
    </script>

<!-- Include Date Range Picker -->
{!! IAScript('assets/moment.locale.es.js') !!}
{!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
{!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}
<script type="text/javascript">
    moment.locale("es");

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


</script>

@endsection



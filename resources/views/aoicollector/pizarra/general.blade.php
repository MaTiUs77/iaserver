@extends('angular')
@section('ng','app')
@section('title','Aoicollector - Pizarra general')
@section('body')
@section('bodytag','ng-controller="pizarraController"')


<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Pizarra de produccion</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <a href="{{ route('aoicollector.pizarra.linea',1) }}" class="btn btn-default navbar-btn navbar-left">Detalle por linea</a>

            <form method="GET" action="?" class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" name="pizarra_fecha" value="{{ Session::get('pizarra_fecha') }}" placeholder="Seleccionar fecha" class="form-control"/>
                </div>
                <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
            </form>

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
            </script>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container">


@include('aoicollector.pizarra.partial.eficienciageneral')

@foreach($pizarra as $resume)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ route('aoicollector.pizarra.linea',$resume->produccionLine->numero_linea) }}" class="btn btn-info" target="_blank">
                        Detalles de {{ $resume->produccionLine->linea  }}
                    </a>
                </div>
                <div class="panel-body">
                    @if($resume->produccion->aoi->total==0 && $resume->produccion->cone->total==0)
                        No se detecto produccion el dia {{ Session::get('pizarra_fecha') }}
                    @else
                        <h3>Turno Mañana</h3>
                        @include('aoicollector.pizarra.partial.panelgeneral',[
                            'turno' => 'M',
                            'produccion_aoi' => $resume->produccion->aoi->M,
                            'proyectado_cone' => $resume->proyectado->cone->M,
                            'reportes_incompletos' => $resume->proyectado->cone->reporteIncompleto->M
                        ])

                        @if($resume->produccion->aoi->T>0 || $resume->produccion->cone->T>0)
                            <h3>Turno Tarde</h3>
                            @include('aoicollector.pizarra.partial.panelgeneral',[
                                'turno' => 'T',
                                'produccion_aoi' => $resume->produccion->aoi->T,
                                'proyectado_cone' => $resume->proyectado->cone->T,
                                'reportes_incompletos' => $resume->proyectado->cone->reporteIncompleto->T
                            ])
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
</div>


@include('iaserver.common.footer')

{!! IAScript('assets/highstock/js/highstock.js') !!}
{!! IAScript('assets/moment.min.js') !!}

        <!-- Include Date Range Picker -->
{!! IAScript('assets/moment.locale.es.js') !!}
{!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
{!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}


<script>
    moment.locale("es");

    function chartController(title, renderTo, series, legend, enableNavigator, category)
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
                categories: category,
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Total'
                },
                min: 0,
            },
            legend: {
                enabled: legend

            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.x + '</b><br> ' + this.y + ' %';
                }
            },
            series: series
        };

        interfaz.draw = function()
        {
            interfaz.chart = new Highcharts.chart(interfaz.options);
        };

        return interfaz;
    }

    app.controller("pizarraController",function($scope,$rootScope,$http,$timeout,$interval, IaCore, toasty)
    {
    });
</script>

@endsection





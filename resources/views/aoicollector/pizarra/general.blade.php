@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector - Pizarra general')
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
                <li>
                    <a href="{{ route('aoicollector.pizarra.linea',1) }}">Resumen por linea</a>
                </li>
                <li class="active">
                    <a href="{{ route('aoicollector.pizarra.general') }}">Resumen general</a>
                </li>
            </ul>

            <form method="GET" action="?" class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" name="pizarra_fecha_range" value="{{ Session::get('pizarra_fecha_range') }}" placeholder="Seleccionar fecha" class="form-control defaultdatapicker"/>
                </div>
                <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
            </form>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container">
    @include('aoicollector.pizarra.partial.chart.eficiencia_general')

    @foreach($pizarra as $resume)
    <div class="col-md-12">
        @if($resume->produccion->aoi->total==0)
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $resume->produccionLine->linea  }}</h3>
                </div>
                <div class="box-body">
                        No se han detectado inspecciones nuevas.
                </div>
            </div>
        @else
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h1 class="box-title">{{ $resume->produccionLine->linea  }}</h1>
                    <a href="{{ route('aoicollector.pizarra.linea',$resume->produccionLine->numero_linea) }}" target="_blank" class="btn btn-sm btn-info pull-right">Ver detalle</a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_m_{{ $resume->produccionLine->linea  }}" data-toggle="tab">Turno Mañana</a></li>
                                    @if($resume->produccion->aoi->T>0 || $resume->produccion->cone->T>0)
                                        <li><a href="#tab_t_{{ $resume->produccionLine->linea  }}" data-toggle="tab">Turno Tarde</a></li>
                                    @endif
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_m_{{ $resume->produccionLine->linea  }}">
                                        @include('aoicollector.pizarra.partial.panelgeneral',[
                                            'turno' => 'M',
                                            'produccion_aoi' => $resume->produccion->aoi->M,
                                            'proyectado_cone' => $resume->proyectado->cone->M,
                                            'reportes_incompletos' => $resume->proyectado->cone->reporteIncompleto->M
                                        ])
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="tab_t_{{ $resume->produccionLine->linea  }}">
                                        @if($resume->produccion->aoi->T>0 || $resume->produccion->cone->T>0)
                                            @include('aoicollector.pizarra.partial.panelgeneral',[
                                                'turno' => 'T',
                                                'produccion_aoi' => $resume->produccion->aoi->T,
                                                'proyectado_cone' => $resume->proyectado->cone->T,
                                                'reportes_incompletos' => $resume->proyectado->cone->reporteIncompleto->T
                                            ])
                                        @endif
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- nav-tabs-custom -->
                        </div>
                        <!-- /.col -->
                    </div>

                </div>
            </div>
        @endif
    </div>
{{--
        <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ route('aoicollector.pizarra.linea',$resume->produccionLine->numero_linea) }}" class="btn btn-info" target="_blank">
                        Detalles de {{ $resume->produccionLine->linea  }}
                    </a>
                </div>
                <div class="panel-body">
                    @if($resume->produccion->aoi->total==0 && $resume->produccion->cone->total==0)
                        No se detecto produccion el dia {{ Session::get('pizarra_fecha_range') }}
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
            </div>--}}
        @endforeach
</div>

@include('iaserver.common.footer')
{!! IAScript('assets/highstock/js/highstock.js') !!}

<script>
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





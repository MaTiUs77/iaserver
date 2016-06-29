@extends('angular')
@section('ng','app')
@section('title','Aoicollector - Pizarra general')
@section('body')
@section('bodytag','ng-controller="pizarraController"')


<div class="well">
    <a href="{{ route('aoicollector.pizarra.linea',1) }}" class="btn btn-info">Ver detalle por linea</a>

    <div class="pull-right">
        @include('iaserver.common.datepicker',[
            'button' => 'Aplicar',
            'custom_session'=>'pizarra_fecha',
            'route'=>route('aoicollector.pizarra.general')
        ])
    </div>
</div>

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





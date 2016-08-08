@extends('angular')
@section('ng','app')
@section('title','Aoicollector - Pizarra')
@section('body')
@section('bodytag','ng-controller="pizarraController"')

<div class="well">
    <a href="{{ route('aoicollector.pizarra.general') }}" class="btn btn-info" target="_blank">Ver detalle general</a>

    @if(isset($resume->produccionLine))
        <div class="pull-right">
            @include('iaserver.common.datepicker',[
                'button' => 'Aplicar',
                'custom_session'=>'pizarra_fecha',
                'route'=>route('aoicollector.pizarra.linea',$resume->produccionLine->numero_linea)
            ])
        </div>
    @endif
</div>
<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
        @if(!isset($resume->produccionLine))
            <h3  style="padding-left: 10px;">La linea solicitada no existe</h3>
        @else
            @if($resume->produccion->aoi->total==0 && $resume->produccion->cone->total==0)
                <h3 style="padding-left: 10px;">{{ $resume->produccionLine->linea  }} | No se detecto produccion el dia {{ Session::get('pizarra_fecha') }}</h3>
            @else
                <h3 style="padding-left: 10px;">{{ $resume->produccionLine->linea  }} | Produccion del dia {{ Session::get('pizarra_fecha') }}</h3>

                @include('aoicollector.pizarra.partial.panel')
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
                    dateTimeLabelFormats: {
                        day: '%H'
                    },
                    tickInterval: 3600 * 1000
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

@endsection



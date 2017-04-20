@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector - Defectos por periodo')
@section('body')

    <div ng-controller="inspectionController">

        <div class="well">
            <div class="pull-right">
                <form method="GET" action="{{ route('aoicollector.inspection.defectos.periodo') }}" class="navbar-form navbar-left" style="margin: 0;">
                    <div class="form-group">
                        <input type="text" name="periodo_date_session" value="{{ Session::get('periodo_date_session') }}" placeholder="Seleccionar fecha" class="form-control"/>
                    </div>

                    <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
                </form>
            </div>

            <script type="text/javascript">
                $(function() {
                    $('input[name="periodo_date_session"]').daterangepicker({
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

            <a href="{{ route('aoicollector.inspection.index') }}" class="btn btn-info">Ir a Inspecciones</a>
            <a href="{{ route('aoicollector.stat.index') }}" class="btn btn-info">Ir a Estadisticas</a>


        </div>

        @foreach($defectChart->groupBy('id_maquina') as $id_maquina => $item)
                <div class="col-sm-4" >
                    @include('aoicollector.inspection.chart.defecto_x_periodo',[
                        'headofchota' => $item,
                        'maquina' => $maquinas->where('id',$id_maquina)->first()
                    ])
                </div>
            @endforeach
    </div>

    @include('iaserver.common.footer')
    {!! IAScript('vendor/aoicollector/inspection/inspection.js') !!}

    {!! IAScript('assets/highstock/js/highstock.js') !!}

    <!-- Include Date Range Picker -->
    {!! IAScript('assets/moment.min.js') !!}
    {!! IAScript('assets/moment.locale.es.js') !!}
    {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
    {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}
    <script>
        moment.locale("es");
    </script>

    <style>
        .highcharts-button {
            display: none;
        }
    </style>
    <script>
        Highcharts.setOptions({
            lang:{
                rangeSelectorZoom: ''
            }
        });

        function chartController(title, subtitle, renderTo, series, tooltip, legend, enableNavigator)
        {
            var interfaz = {};
            interfaz.chart = null;
            interfaz.toggle = false;
            interfaz.options = {
                chart: {
                    renderTo: renderTo,
                    type: 'column'
                },
                credits: {
                    enabled: false
                },

                navigator: {
                    enabled: true
                },

                rangeSelector: {
                    buttons: [{
                        type: 'hour',
                        count: 2,
                        text: '1h'
                    }],
                    inputEnabled: false, // it supports only days
                    selected: 0
                },

                title: {
                    text: title
                },
                subtitle: {
                    text: subtitle
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
                        text: 'Defectos reales'
                    },
                    min: 0
                },
                legend: {
                    enabled: legend

                },
                plotOptions: {
                    column: {
                        stacking: 'normal'
                    },
                    line: {
                        marker: {
                            enabled : true,
                            radius: 5,
                            fillColor: '#FFFFFF',
                            lineWidth: 2,
                            lineColor: null // inherit from series
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                "fontSize": "20px",
                                "fontWeight": "normal"
                            },
                            formatter: function() {
                                if (this.y != 0) {
                                    return this.y;
                                } else {
                                    return null;
                                }
                            }
                        }
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

        setTimeout("window.location.reload();", (60 * 1000) * 5 );
    </script>
@endsection
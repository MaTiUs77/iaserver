<?php
    $prodchart = 'drillchart'.rand(0,99999);
?>


<div style="border:2px solid #efefef;margin-bottom:2px;">
   {{-- <button id="{{ $prodchart }}drillUp" tooltip="Subir nivel" class="btn btn-xs btn-default"><span class="fa fa-mail-reply"></span></button>--}}
    <div id="{{ $prodchart }}container" style="width: 95%;height:{{ isset($height) ? $height : '300' }}px;"></div>
</div>


<script>
    $(function () {
        Highcharts.setOptions({
            lang: {
                drillUpText: 'Volver',
                weekdays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
            },
            global: {
                useUTC: false
            }
        });

        var chart =  new Highcharts.Chart({
            chart: {
                renderTo: '{{ $prodchart }}container',
                type: 'column'
            },
            title: {
                text: '{{ $user }}'
            },
            subtitle: {
                text: '{{ isset($info['totalCargado']) ? "Total: ".$info['totalCargado'] : 'desconocido' }}'
            },
            xAxis: {
                type: "datetime"
                /* dateTimeLabelFormats: {
                 day: '%H'
                 },*/
                //tickInterval: moment.duration(1, 'hour').asMilliseconds()
            },
            yAxis: {
                title: {
                    text: 'Cargas'
                }
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },

            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        style: {
                            "fontSize": "20px",
                            "fontWeight": "bold",
                            "color": "contrast",
                            "textOutline": "1px 1px contrast"
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

            series: [
                {
                    name: 'Cargas',
                    data: [
                        @foreach($info['porFecha'] as $fecha => $total)
                        {
                            x:  moment("{{ $fecha }}", "YYYY-MM-DD").valueOf(),
                            y: {{ $total }},
                            drilldown: 'drill{{ $fecha }}'
                        },
                        @endforeach

                    ]
                }
            ],

            drilldown: {
                series: [
                    <?php
                        $lastDrill = null;
                    ?>
                    @foreach($info['detalle'] as $fechaConHora => $data)

                        <?php
                            list($fecha,$hora) = explode(" ",$fechaConHora);
                        ?>
                        @if($lastDrill != $fecha)

                            @if($lastDrill != null)
                                ]}, // FIN FECHA
                            @endif
                            // ************  NUEVA FECHA ***********
                            {
                                name: 'Cargas',
                                id: 'drill{{ $fecha }}',
                            data: [
                            <?php $lastDrill = $fecha; ?>
                        @endif
                            {
                                x:  moment("{{ $fechaConHora }}", "YYYY-MM-DD HH").valueOf(),
                                y: {{ count($data) }}
                            },
                    @endforeach

                            ]} // FIN FECHA
                ]
            }
        });

        $('#{{ $prodchart }}drillUp').click(function() {
            if (chart.drilldownLevels.length > 0) {
                chart.drillUp();
            }
        });
    });


</script>
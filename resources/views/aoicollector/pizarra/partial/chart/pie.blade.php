<?php
    $prodchart = 'pie_chart'.rand(0,999999);
?>
<div id="{{ $prodchart }}" style="height: 500px"></div>

<script>
        $(function () {

            var series = [{
                type: 'pie',
                data: [
                    @if(count($resume->byOp))
                        @foreach($resume->byOp as $op => $data)
                            <?php $total = $data->where('turno',$turno)->count();?>
                            @if($total>0)
                                ['{{ $op }}', {{ $total }}],
                            @endif
                        @endforeach
                    @endif

                    @if($resume->proyectado->cone->faltanteM>0 && $turno =='M')
                        {
                            name: 'Produccion faltante',
                            y: {{ $resume->proyectado->cone->faltanteM }},
                            color: '#FF0000',
                            sliced: true,
                            selected: true
                        }
                    @endif

                    @if($resume->proyectado->cone->faltanteT>0 && $turno =='T')
                    {
                        name: 'Produccion faltante',
                        y: {{ $resume->proyectado->cone->faltanteT }},
                        color: '#FF0000',
                        sliced: true,
                        selected: true
                    }
                    @endif
                ]

            }];

            $('#{{ $prodchart }}').highcharts({
                chart: {
                    type: 'pie'
                },
                credits: {
                    enabled: false
                },
                title: {
                    @if($turno=='M')
                        text: 'Resumen turno Mañana'
                    @else
                        text: 'Resumen turno Tarde'
                    @endif
                },
                subtitle: {
                    @if($turno=='M')
                        text: 'Producido: {{ $resume->produccion->aoi->M  }} / Proyectado: {{ $resume->proyectado->cone->M   }}'
                    @else
                        text: 'Producido: {{ $resume->produccion->aoi->T  }} / Proyectado: {{ $resume->proyectado->cone->T   }}'
                    @endif
                },
                tooltip: {
                    pointFormat: '<b>{point.y} Placas / {point.percentage:.1f}% </b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.y} Placas</b>  <b>{point.percentage:.1f} %</b>',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        },
                        showInLegend: true
                    }
                },
                series: series
            });
        });
    </script>


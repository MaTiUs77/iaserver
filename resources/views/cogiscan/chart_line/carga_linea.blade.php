<?php
$prodchart = 'columnchart'.rand(0,99999);
?>
<div id="{{ $prodchart }}container" style="border:2px solid #efefef;margin-bottom:2px;width: 95%;height:{{ isset($height) ? $height : '300' }}px;"></div>
<script>

    $(function () {
        Highcharts.chart('{{ $prodchart }}container', {
            chart: {
                type: 'column'
            },
            title: {
                text: '{{$linea}}'

            },
            subtitle: {

            },
            xAxis: {
                categories: [
                    '06',
                    '07',
                    '08',
                    '09',
                    '10',
                    '11',
                    '12',
                    '13',
                    '14',
                        {{--@foreach($tiempo as $hora)--}}
                            {{--{{$hora}},--}}
                        {{--@endforeach--}}
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Rollos cargados'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.0f} rollos</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },

            series: [
                @foreach($info as $name => $datos)
                {
                    name: '{{$name}} = {{$datos['totalCargado']}}',
                    data:
                        [
                                @foreach($datos['detalle'] as $fechaConHora => $cantidad)
                                        {{count($cantidad)}},
                                @endforeach
                        ]
                },
                @endforeach
            ]
    })
    });
</script>
<?php
    $chartpiegeneral = 'chartpiegeneral_'.rand(0,99999);
?>
<div id="{{ $chartpiegeneral }}" style="height: 400px"></div>
<script>
        $(function () {
            $('#{{ $chartpiegeneral }}').highcharts({
                chart: {
                    type: 'pie',
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Eficiencia'
                },
                subtitle: {
                    text: 'Producido: {{ $resume->produccion->aoi->total  }} / Proyectado: {{ $resume->proyectado->cone->total   }}'
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
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    data: [
                        ['Produccion', {{ $resume->produccion->aoi->total }}],
                        ['Proyectado', {{ $resume->produccion->cone->total }}]
                    ]
                }]
            });
        });
    </script>


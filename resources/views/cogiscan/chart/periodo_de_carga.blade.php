<?php
    $prodchart = 'columnchart'.rand(0,99999);
?>

    <div id="{{ $prodchart }}container" style="border:2px solid #efefef;margin-bottom:2px;width: 95%;height:{{ isset($height) ? $height : '300' }}px;"></div>

<script>
    $(function () {
        var tooltip_a = {
            useHTML: true,
            backgroundColor: null,
            borderWidth: 0,
            shadow: false,
            formatter: function () {
                var s = '<b>' + Highcharts.dateFormat('%e/%m', this.x) + ' a las ' + Highcharts.dateFormat('%H:%M', this.x) + '</b> ';

                var div = '<div style="background-color:#fffef2; padding: 5px; border-radius: 5px; box-shadow: 2px 2px 2px;" > ' + s + '</div>';
                return div;
            }
        };

        var option_series = [
            {
                name: 'Carga',
                type: 'column',
                lineWidth: 1,
                dashStyle: 'longdash',
                data: [
                    @foreach($info['detalle'] as $fechaConHora => $data)
                    {
                        x: moment("{{ $fechaConHora }}", "YYYY-MM-DD HH").valueOf(),
                        y: {{ count($data) }}
                    },
                    @endforeach
                ]/*,
                zones: [{
                    value: 0,
                    color: '#000000'
                }, {
                    value: 5,
                    color: '#7cb5ec'
                }, {
                    color: '#ff0000'
                }]*/
            }

        ];

        var chart = chartController('{{ $user }} ','Total: {{ $info['totalCargado'] }} ','{{ $prodchart }}container',option_series,tooltip_a, false, false);
        chart.draw();
    });
</script>


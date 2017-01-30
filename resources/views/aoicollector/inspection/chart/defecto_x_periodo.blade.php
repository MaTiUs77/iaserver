<?php
    $prodchart = 'columnchart'.rand(0,99999);
?>

    <div id="{{ $prodchart }}container" style="margin-bottom:2px;background-color:#FF0000; border:10px solid #{{ ($headofchota->last()->errores > 10 ) ? 'FF0000' : 'a9a9a9'}};width: 95%;height:{{ isset($height) ? $height : '300' }}px;"></div>


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
                    name: 'Real',
                    type: 'line',
                    lineWidth: 1,
                    dashStyle: 'longdash',
                    data: [
                        @foreach($headofchota as $data)
                            {
                                x: moment("{{ $data->created_date }} {{ $data->periodo }} -0000", "YYYY-MM-DD HH:mm:ss Z").valueOf(),
                                y: {{ $data->errores }},
                                @if($data->errores==0)
                                    marker : {
                                        enabled: false
                                    }
                                @endif
                            },
                        @endforeach
                    ],
                    zones: [{
                        value: 0,
                        color: '#000000'
                    }, {
                        value: 5,
                        color: '#7cb5ec'
                    }, {
                        color: '#ff0000'
                    }]
                }

            ];

            var chart = chartController('SMD-{{ $maquina->linea }} {{ $maquina->inf }}','Total: {{ $headofchota->sum('errores') }}','{{ $prodchart }}container',option_series,tooltip_a, false, true);
            chart.draw();
        });
    </script>


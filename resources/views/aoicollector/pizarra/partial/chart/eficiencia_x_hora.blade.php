<?php
    $prodchart = 'eficiencia_chart'.rand(0,99999);
?>

    <div id="{{ $prodchart }}container" style="width: 95%;height:{{ isset($height) ? $height : '400' }}px;"></div>

    <script>
        $(function () {
            var tooltip_a = {
                useHTML: true,
                backgroundColor: null,
                borderWidth: 0,
                shadow: false,
                formatter: function () {
                    var s = '<b>' + Highcharts.dateFormat('%b %e', this.x) + '</b> ';
                    var hora = parseInt(Highcharts.dateFormat('%H', this.x));
                    s += ' de ' + hora + ':00 a ' + (hora + 1)+':00' ;

                    $.each(this.points, function () {
                        s += '<br/><span style="color:'+this.series.color+'" class="glyphicon glyphicon glyphicon-record"></span> '+this.series.name+': <b>' + this.y + '</b>';
                    });

                    var div = '<div style="background-color:#fffef2; padding: 5px; border-radius: 5px; box-shadow: 2px 2px 2px;" > ' + s + '</div>';
                    return div;
                }
            };

            var option_series = [
                // Obtengo todas las OP producidas
                @foreach($resume->byOp as $op => $opvalue)
                    @if($turno=='M')
                        @if(isset($opvalue->produccion->M) && $opvalue->produccion->M > 0)
                            {
                                name: '{{ $op }}',
                                type: 'column',
                                dataLabels: {
                                    enabled: true,
                                    borderRadius: 2,
                                    backgroundColor: 'rgba(252, 255, 197, 0.7)',
                                    borderWidth: 1,
                                    borderColor: '#AAA',
                                    y: -6
                                },
                                data: [
                                    @if(isset($opvalue->result))
                                        @foreach($opvalue->result->groupBy('created_date') as $fecha => $period)
                                            @foreach($period as $data)
                                                @if($data->turno == $turno)
                                                    {
                                                        x: moment("{{ $data->created_date }} {{ $data->periodo }} -0000", "YYYY-MM-DD HH:mm:ss Z").valueOf(),
                                                        y: {{ $data->placas }}
                                                    },
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                ]
                            },
                        @endif
                    @else
                        @if(isset($opvalue->produccion->T) && $opvalue->produccion->T > 0)
                            {
                                name: '{{ $op }}',
                                type: 'column',
                                dataLabels: {
                                    enabled: true,
                                    borderRadius: 2,
                                    backgroundColor: 'rgba(252, 255, 197, 0.7)',
                                    borderWidth: 1,
                                    borderColor: '#AAA',
                                    y: -6
                                },
                                data: [
                                    @if(isset($opvalue->result))
                                        @foreach($opvalue->result->groupBy('created_date') as $fecha => $period)
                                            @foreach($period as $data)
                                                @if($data->turno == $turno)
                                                    {
                                                        x: moment("{{ $data->created_date }} {{ $data->periodo }} -0000", "YYYY-MM-DD HH:mm:ss Z").valueOf(),
                                                        y: {{ $data->placas }}
                                                    },
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                ]
                            },
                        @endif
                    @endif
                @endforeach
            ];

            var chart = chartController('Produccion','{{ $prodchart }}container',option_series,tooltip_a, true, true);
            chart.draw();
        });
    </script>


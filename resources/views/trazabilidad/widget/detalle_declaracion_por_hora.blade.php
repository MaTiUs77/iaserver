@if(isset($period))
    <?php
        $prodchart = 'prodchart_'.rand(0,99999);
    ?>
    <div id="{{ $prodchart }}container" style="width: 95%;height:{{ isset($height) ? $height : '300' }}px;"></div>

    <script>
        $(function () {
            var {{ $prodchart }} = null;

            var {{ $prodchart }}options = {
                chart: {
                    renderTo: '{{ $prodchart }}container',
                    type: 'column'
                },

                rangeSelector: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                navigator: {
                    @if(isset($navigator))
                        enabled: true
                    @else
                        enabled: false
                    @endif
                },
                title: {
                    text: '{{ $title }}'
                },
                xAxis: {
                    type: 'datetime',
                    tickInterval: 3600 * 1000,
                    title: {
                        text: 'Fecha'
                    },
                    range: 24 * 3600 * 1000 // six months
                },
                yAxis: {
                    title: {
                        text: 'Total'
                    },
                    min: 0
                },
                legend: {
                    enabled: true

                },
                tooltip: {
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
                },
                series: [
                    @if(isset($proyectado))
                        {
                            name: 'Proyectado',
                            type: 'area',
                            color: '#ffb6b5',
                            marker: {
                                enabled: true,
                                radius: 4,
                                lineColor: '#FF0000',
                                lineWidth: 1
                            },
                            data: [
                                @foreach($proyectado as $point)
                                    [moment("{{ $point->anio }}-{{ $point->mes }}-{{ $point->dia }} {{ $point->horario }}:00 -0000", "YYYY-MM-DD HH:mm Z").valueOf(),{{ $point->proyectado }}],
                                @endforeach
                            ]
                        },
                        {
                            name: 'Operador',
                            type: 'area',
                            color: '#ffead6',
                            data: [
                                @foreach($proyectado as $point)
                                    [moment("{{ $point->anio }}-{{ $point->mes }}-{{ $point->dia }} {{ $point->horario }}:00 -0000", "YYYY-MM-DD HH:mm Z").valueOf(),{{ $point->p_real }}],
                                @endforeach
                            ]
                        },
                    @endif

                    @if(isset($wip_op) && $wip_op != null)
                        {
                        name: '{{ $wip_op }}',
                        dataLabels: {
                            enabled: true,
                            borderRadius: 2,
                            backgroundColor: 'rgba(252, 255, 197, 0.7)',
                            borderWidth: 1,
                            borderColor: '#AAA',
                            y: -6
                        },
                        data: [
                            @foreach($period as $point)
                                [moment("{{ $point->anio }}-{{ $point->mes }}-{{ $point->dia }} {{ $point->periodo }}:00 -0000", "YYYY-MM-DD HH:mm Z").valueOf(),{{ $point->total }}],
                            @endforeach
                        ]},
                    @endif

                    @if(isset($ops))
                        @foreach($ops as $insop)
                            <?php
                                $opval = $period->where('op',$insop);
                            ?>
                            {
                            name: '{{ $insop }}',
                            dataLabels: {
                                enabled: true,
                                borderRadius: 2,
                                backgroundColor: 'rgba(252, 255, 197, 0.7)',
                                borderWidth: 1,
                                borderColor: '#AAA',
                                y: -6
                            },
                            data: [
                                @foreach($opval as $point)
                                    [moment("{{ $point->anio }}-{{ $point->mes }}-{{ $point->dia }} {{ $point->periodo }}:00 -0000", "YYYY-MM-DD HH:mm Z").valueOf(),{{ $point->total }}],
                                @endforeach
                            ]},
                        @endforeach
                    @endif
                ]
            };

            {{ $prodchart }} = new Highcharts.StockChart({{ $prodchart }}options);
        });
    </script>
@endif

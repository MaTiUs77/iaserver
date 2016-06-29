<?php
    $charteficienciageneral = 'charteficienciageneral_'.rand(0,99999);
?>
    <div class="pull-right">
        <small>Factor Planta:</small> <label class="label" id="factorPlanta">0%</label>
    </div>
    <div class="clearfix"></div>
    <div id="{{ $charteficienciageneral }}container2" style="width: 95%;height:300px;"></div>
    <script>
        $(function () {
            var option_series2 = [
                {
                    name: 'Eficiencia',
                    type: 'column',
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}%', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    },
                    data: [
                        <?php
                            $totalAoi = 0;
                            $totalCone = 0;
                            $total = 0 ;
                            $totalFactor = 0 ;
                        ?>
                        @foreach($pizarra as $resume)
                            @if(isset($resume->produccion->aoi->porcentaje))
                                {
                                <?php
                                    $category[] = $resume->produccionLine->linea;
                                    $totalAoi += $resume->produccion->aoi->total;
                                    $totalCone += $resume->proyectado->cone->total;

                                    if($resume->produccion->aoi->porcentaje < 65) {
                                        $color = '#ff0202'; // rojo
                                        $mode='danger';
                                    } else  {
                                        if($resume->produccion->aoi->porcentaje > 85) {
                                            $color = '#6af260'; // verde
                                            $mode='success';
                                        } else {
                                            $color = '#ffff77'; // amarillo
                                            $mode = 'warning';
                                        }
                                    }
                                ?>
                                    color: '{!! $color !!}',
                                    y: {{ $resume->produccion->aoi->porcentaje }}
                                },
                            @endif
                        @endforeach
                        <?php
                            $category = "['".join("','",$category)."']";
                            if($totalCone>0) {
                                $total = number_format((($totalAoi / $totalCone) * 100), 1, '.', '');
                                $totalFactor = number_format($total * 0.85, 1, '.', '');
                            }
                        ?>
                    ]
                }
            ];

            var chart2 = chartController('Eficiencia de area: {{ $total }}%','{{ $charteficienciageneral }}container2',option_series2, false, false,<?php echo $category; ?>);
            chart2.draw();

            $('#factorPlanta').text('{{ $totalFactor }} %').addClass('label-{{ $mode }}');

        });
    </script>


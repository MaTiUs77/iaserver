<?php
    $charteficienciageneral = 'charteficienciageneral_'.rand(0,99999);
?>

    <div class="row">
        <form method="post" action="{{ route('aoicollector.pizarra.general.filter') }}">
            {{-- <div class="col-sm-2 col-md-2 col-lg-2" style="border: 1px solid #efefef;">
                 <div style="padding: 5px;">
                     <button type="submit" class="btn btn-xs btn-success" tooltip="Oculta lineas seleccionadas">Aplicar filtro</button>
                     <a href="{{ route('aoicollector.pizarra.general.filter',['remove']) }}" class="btn btn-xs btn-default">Quitar filtro</a>
                </div>

                <div style="height:250px;overflow: auto;">
                    @foreach(\IAServer\Http\Controllers\Aoicollector\Model\Produccion::vista()->where('id_maquina','<>',null)->groupBy('linea')->orderBy('numero_linea')->get()  as $prod)
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="{{ $prod->numero_linea }}" {{ (key_exists($prod->numero_linea,$filterGeneral)) ? 'checked' : ''}} >
                                {{ $prod->linea }}
                            </label>
                        </div>
                    @endforeach
                </div>

            </div>
--}}
            <div class="col-md-12">
                <div class="pull-right">
                    <small>Factor Planta:</small> <label class="label" id="factorPlanta">0%</label>
                </div>
                <div class="clearfix"></div>
                <div id="{{ $charteficienciageneral }}container2" style="width: 95%;height:300px;"></div>

                <small><i class="fa fa-refresh" aria-hidden="true"></i> Proxima actualizacion en {{ $ttl }} seg.</small>

            </div>
        </form>
    </div>

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
                            @if(isset($resume->produccion->aoi->total))
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


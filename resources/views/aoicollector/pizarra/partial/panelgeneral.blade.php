<div class="row">
    <div class="col-lg-3 col-md-3">
        <blockquote>
            <?php
            $max = $proyectado_cone;
            if($max <= 0) { $max = 1; }
            $porcentaje =  (($produccion_aoi  / $max) * 100);

            if( $porcentaje > 85 ) {
                $type = 'success';
            } else {
                if($porcentaje > 65)  {
                    $type = 'warning';
                } else {
                    $type = 'danger';
                }
            }
            ?>

            @include('iaserver.common.progressbar',[
              'type' => $type,
              'percent' => true,
              'leyend' => true,
              'now' => $produccion_aoi,
              'max' => $proyectado_cone
           ])

            @if($reportes_incompletos > 0)
                @if($reportes_incompletos == 1)
                    <span class="label label-danger" style="display:block;"><b>({{  $reportes_incompletos }})</b> reporte incompleto</span>
                @else
                    <span class="label label-danger" style="display:block;"><b>({{  $reportes_incompletos }})</b> reportes incompletos</span>
                @endif
            @endif
        </blockquote>
    </div>
    <div class="col-lg-9 col-md-9">
        <table class="table">
            <thead>
            <tr>
                <th>OP</th>
                <th>Modelo</th>
                <th>Lote</th>
                <th>Panel</th>
                <th>Produccion</th>
            </tr>
            </thead>
            <tbody>
            @foreach($resume->byOp as $op => $item)
                @if(count($item->periodo[$turno]))
                    <tr>
                        <td>
                            {{ $op }}
                        </td>
                        <td>
                            {{ (isset($item->smt)) ? $item->smt->modelo : 'Desconocido '}}
                        </td>
                        <td>
                            {{ (isset($item->smt)) ? $item->smt->lote : 'Desconocido '}}
                        </td>
                        <td>
                            {{ (isset($item->smt)) ? $item->smt->panel : 'Desconocido '}}
                        </td>
                        <td>
                            @if($turno=='M')
                                {{ $item->produccionM }}
                            @else
                                {{ $item->produccionT }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
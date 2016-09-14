<table class="table table-bordered block">
    <thead class="bloque">
        <tr>
            <th></th>
            <th>Barcode</th>
            <th>AOI</th>
            <th>INS</th>
            <th>Errores</th>
            <th>Falsos</th>
            <th>Reales</th>
            <th>Etiqueta</th>
            <th>Declarado</th>
        </tr>
    </thead>
    <tbody>
    @foreach( $bloques as $bloque )
        <tr class="{{ ($bloque->revision_aoi == 'OK' && $bloque->revision_ins == 'OK' ) ? 'success' : '' }} {{ ($bloque->revision_ins == 'NG' ) ? 'danger' : '' }}">
            <td style="width:50px;">
                @if($bloque->errores)
                    <button id_bloque="{{ $bloque->id_bloque_history }}" route="{{ route('aoicollector.inspection.detail',$bloque->id_bloque_history) }}" ng-click="getInspectionDetail($event);" class="btn btn-xs btn-default">Detalle</button>
                @endif
            </td>
            <td>{{ $bloque->barcode }}</td>
            <td>{{ $bloque->revision_aoi }}</td>
            <td>{{ $bloque->revision_ins }}</td>
            <td>{{ $bloque->errores }}</td>
            <td>{{ $bloque->falsos }}</td>
            <td>{{ $bloque->reales }}</td>
            <td>{{ $bloque->etiqueta == 'E' ? 'Fisica' : 'Virtual' }}</td>

            <td>
                <?php
                    $verify = new \IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion();
                    $twip = (object) $verify->bloqueEnTransaccionWip($bloque->barcode);
                ?>
                    @if(isset($twip) && isset($twip->last))
                        @if($twip->declarado)
                            <i class="fa fa-thumbs-o-up text-success" tooltip-placement="left" tooltip="Declarado"></i>
                        @else
                            @if($twip->errores)
                                <i class="fa fa-thumbs-o-down text-danger" tooltip-placement="left" tooltip="Declarado con errores"></i>
                            @endif

                            @if($twip->pendiente)
                                <i class="fa fa-clock-o text-info" tooltip-placement="left" tooltip="Pendiente"></i>
                            @endif

                            @if(!$twip->errores && !$twip->pendiente)
                                <i class="fa fa-exclamation-circle text-warning" tooltip-placement="left" tooltip="Declaracion parcial"></i>
                            @endif
                        @endif
                    @else
                        <i class="fa fa-eye text-info" tooltip-placement="left" tooltip="Sin verificar"></i>
                    @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
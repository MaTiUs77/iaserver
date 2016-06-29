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
                @if(isset($bloque->twip->trans_code))
                    @if($bloque->twip->trans_code==0)
                        <button class="btn btn-xs btn-warning" tooltip-placement="left" tooltip="En proceso..."><span class="glyphicon glyphicon-hand-right"></span></button>
                    @endif
                    @if($bloque->twip->trans_code==1)
                        <button class="btn btn-xs btn-success" tooltip-placement="left" tooltip="{{ $bloque->twip->trans_det }}"><span class="glyphicon glyphicon-thumbs-up"></span></button>
                    @endif
                    @if($bloque->twip->trans_code>1)
                        <button class="btn btn-xs btn-danger" tooltip-placement="left" tooltip="{{ $bloque->twip->trans_det }}"><span class="glyphicon glyphicon-thumbs-down"></span></button>
                    @endif
                @else
                    <button class="btn btn-xs btn-default" tooltip-placement="left" tooltip="Sin declarar"><span class="glyphicon glyphicon-thumbs-down"></span></button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
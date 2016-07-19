@if(isset($error))
   <h3>{{ $error }}</h3>
@else
    <div class="row">
        <div class="col-lg-3">
            <blockquote>
                <small>Linea de produccion</small>
                {{ $linea }}

                <small>Op</small>
                {{ $stocker->op }}

                <small>Semielaborado</small>
                {{ $stocker->semielaborado }}

                <small>Unidades</small>
                {{ $stocker->unidades }}

                <small>Trazabilidad</small>
                <ul class="list-group">
                    @foreach($stockerTraza as $tstocker)
                        <li class="list-group-item">
                            <div style="font-size: 10px;">{{ $tstocker->created_at }}</div>
                            <div style="font-size: 14px;">{{ $tstocker->joinRoute->name }}</div>
                        </li>
                    @endforeach
                </ul>


            </blockquote>
        </div>
            <div class="col-lg-9">
            <table class="table table-bordered table-striped">
                <thead class="panel">
                <tr>
                    <th>Panel</th>
                    <th>Programa</th>
                    <th>AOI</th>
                    <th>INS</th>
                    <th>Errores</th>
                    <th>Falsos</th>
                    <th>Reales</th>
                    <th>Bloques</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($stockerDetalle as $detalle)
                        @if(isset($detalle->joinPanel))
                            @if(isset($panel) && $panel->panel_barcode == $detalle->joinPanel->panel_barcode)
                                <tr class="info">
                            @else
                                <tr>
                            @endif
                            <td>{{ $detalle->joinPanel->panel_barcode }}</td>
                            <td>{{ $detalle->joinPanel->programa }}</td>
                            <td>{{ $detalle->joinPanel->revision_aoi }}</td>
                            <td>{{ $detalle->joinPanel->revision_ins }}</td>
                            <td>{{ $detalle->joinPanel->errores }}</td>
                            <td>{{ $detalle->joinPanel->falsos }}</td>
                            <td>{{ $detalle->joinPanel->reales }}</td>
                            <td>{{ $detalle->joinPanel->bloques }}</td>
                            <td>{{ $detalle->joinPanel->created_date }}</td>
                            <td>{{ $detalle->joinPanel->created_time }}</td>
                            <td>
                                @if($detalle->joinPanel->twip != null)
                                    @if($detalle->joinPanel->twip->CountOk() == $detalle->joinPanel->bloques)
                                        <i class="fa fa-thumbs-o-up fa-2x "  tooltip-placement="left" tooltip="Procesado Correctamente"></i>
                                    @else
                                        <i class="fa fa-thumbs-o-down fa-2x "  tooltip-placement="left" tooltip="Error total o parcial en declaracion"></i>
                                    @endif
                                @else
                                    <i class="fa fa-thumbs-o-down fa-2x " tooltip-placement="left" tooltip="Sin declarar"></i>
                                @endif

                                <?php
                                $cogiscanService= new \IAServer\Http\Controllers\Cogiscan\Cogiscan();
                                $cogiscan = $cogiscanService->queryItem($detalle->joinPanel->panel_barcode);
                                ?>
                                @if(isset($cogiscan['attributes']['message']))
                                    <i class="fa fa-exclamation-triangle fa-2x text-danger" tooltip="Cogiscan: {{ $cogiscan['attributes']['message'] }}"></i>
                                @else
                                    @if(isset($cogiscan['Product']['attributes']['operation']))
                                        @if($cogiscan['Product']['attributes']['operation'] == 'Depanelization')
                                            <i class="fa fa-send fa-2x text-info" tooltip="Ruta: {{ $cogiscan['Product']['attributes']['operation'] }}, Estado: {{ $cogiscan['Product']['attributes']['status'] }}"></i>
                                        @else
                                            <i class="fa fa-road fa-2x text-success" tooltip="Ruta: {{ $cogiscan['Product']['attributes']['operation'] }}, Estado: {{ $cogiscan['Product']['attributes']['status'] }}"></i>
                                        @endif

                                        @if(isset($cogiscan['attributes']['quarantineLocked']) && $cogiscan['attributes']['quarantineLocked'] == "true")
                                            <i class="fa fa-bombtext-danger" tooltip="Placa en cuarentena"></i>
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @else
                            <tr>
                                <td colspan="10">
                                    El panel no fue lozalizado en la base de datos
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
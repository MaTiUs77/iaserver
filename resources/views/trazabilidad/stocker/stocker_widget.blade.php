@if(isset($find->error))
   <h3>{{ $find->error }}</h3>
@else
    <div class="row">
        <div class="col-lg-3">
            <blockquote>
                <small>Declarado</small>
                @if($detalle->stocker_declarado)
                    <span class="label label-success">Si</span>
                @endif

                @if($detalle->stocker_errores)
                    <span class="label label-danger">Error en declaraciones</span>
                @endif

                @if($detalle->stocker_pendiente)
                    <span class="label label-warning">Pendiente</span>
                @endif

                <small>Stocker ID</small>
                {{ $find->stocker->barcode }}

                <small>Linea de produccion</small>
                {{ $find->linea }}

                <small>Op</small>
                {{ $find->stocker->op }}

                <small>Semielaborado</small>
                {{ $find->stocker->semielaborado }}

                <small>Unidades</small>
                {{ $find->stocker->unidades }}

                <small>Trazabilidad</small>
                <ul class="list-group">
                    @foreach($find->trazabilidad as $tstocker)
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
                <thead>
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
                    @foreach($detalle->paneles as $item)
                        <?php
                            $panel = $item->panel;
                        ?>
                        @if(isset($panel))
                            <tr>
                                <td>{{ $panel->panel_barcode }}</td>
                                <td>{{ $panel->programa }}</td>
                                <td>{{ $panel->revision_aoi }}</td>
                                <td>{{ $panel->revision_ins }}</td>
                                <td>{{ $panel->errores }}</td>
                                <td>{{ $panel->falsos }}</td>
                                <td>{{ $panel->reales }}</td>
                                <td>{{ $panel->bloques }}</td>
                                <td>{{ $panel->created_date }}</td>
                                <td>{{ $panel->created_time }}</td>

                                <td>
                                    <?php
                                        $cogiscanService= new \IAServer\Http\Controllers\Cogiscan\Cogiscan();
                                        $cogiscan = $cogiscanService->queryItem($panel->panel_barcode);
                                    ?>
                                    <?php
                                    /*
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
                                    */
                                    ?>
                                    @if(!isset($item->bloques) || head($item->bloques) == null)
                                            <i class="fa fa-exclamation-circle fa-2x text-danger" tooltip-placement="left" tooltip="Sin declarar"></i>
                                    @else
                                        @if($item->panel_declarado)
                                            <i class="fa fa-thumbs-o-up fa-2x text-success" tooltip-placement="left" tooltip="Declarado"></i>
                                        @else
                                            @if($item->panel_errores)
                                                <i class="fa fa-thumbs-o-down fa-2x text-danger" tooltip-placement="left" tooltip="Declarado con errores"></i>
                                            @endif

                                            @if($item->panel_pendiente)
                                                <i class="fa fa-clock-o fa-2x text-info" tooltip-placement="left" tooltip="Pendiente"></i>
                                            @endif

                                            @if(!$item->panel_errores && !$item->panel_pendiente)
                                                <i class="fa fa-exclamation-circle fa-2x text-warning" tooltip-placement="left" tooltip="Declaracion parcial: {{ $item->panel_declarado_total }} unidades"></i>
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
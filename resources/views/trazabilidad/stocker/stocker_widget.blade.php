@if(isset($find->error))
   <h3>{{ $find->error }}</h3>
@else
    <div class="row">
        <div class="col-lg-3">
            <blockquote>
                <small>Declarado</small>
                @if($contenido->declaracion->declarado)
                    <span class="label label-success">Si</span>
                @else
                    @if($contenido->declaracion->error)
                        <span class="label label-danger">Error en declaraciones</span>
                    @endif

                    @if($contenido->declaracion->pendiente)
                        <span class="label label-warning">Pendiente</span>
                    @endif

                    @if($contenido->declaracion->parcial)
                        <span class="label label-warning">Declaracion parcial</span>
                    @endif
                @endif

                <small>Stocker ID</small>
                {{ $find->stocker->barcode }}

                <small>Linea de produccion</small>
                @if(isset($find->linea ))
                    {{ $find->linea }}
                    <small>Op</small>
                    {{ $find->stocker->op }}

                    <small>Semielaborado</small>
                    {{ $find->stocker->semielaborado }}

                    <small>Unidades</small>
                    {{ $find->stocker->unidades }}
                @else
                    <span class="label label-danger">Sin definir</span>
                @endif

                @if(isset($find->trazabilidad) && count($find->trazabilidad)>0)
                    <small>Trazabilidad</small>
                    <ul class="list-group">
                        @foreach($find->trazabilidad as $tstocker)
                            <li class="list-group-item">
                                <div style="font-size: 10px;">{{ $tstocker->created_at }}</div>
                                <div style="font-size: 14px;">{{ $tstocker->joinRoute->name }}</div>
                            </li>
                        @endforeach
                    </ul>
                @endif
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
                    @foreach($contenido->paneles as $item)
                        <?php
                            $panel = $item->panel;
                            $declaracion = $item->declaracion;
                        ?>
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
                                    //$cogiscanService= new \IAServer\Http\Controllers\Cogiscan\Cogiscan();
                                    //$cogiscan = $cogiscanService->queryItem($panel->panel_barcode);
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
                                @if($declaracion->declarado)
                                    <i class="fa fa-thumbs-o-up fa-2x text-success" tooltip-placement="left" tooltip="Declarado"></i>
                                @else
                                    @if($declaracion->error)
                                        <i class="fa fa-thumbs-o-down fa-2x text-danger" tooltip-placement="left" tooltip="Declarado con errores"></i>
                                    @endif

                                    @if($declaracion->pendiente)
                                        <i class="fa fa-clock-o fa-2x text-info" tooltip-placement="left" tooltip="Pendiente"></i>
                                    @endif

                                    @if($declaracion->parcial)
                                        <i class="fa fa-exclamation-circle fa-2x text-warning" tooltip-placement="left" tooltip="Faltan declarar: {{ $declaracion->parcial_total }} unidades"></i>
                                    @endif

                                    @if(!$declaracion->parcial && !$declaracion->pendiente && !$declaracion->error)
                                        <i class="fa fa-exclamation-circle fa-2x text-danger" tooltip-placement="left" tooltip="Sin declarar"></i>
                                        @if(isAdmin())
                                            <a href="{{ route('aoicollector.stocker.panel.view.declare', $panel->panel_barcode) }}" target="_blank">Declarar</a>
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
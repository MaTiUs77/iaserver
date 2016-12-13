<style>
    .table tbody tr td {
        text-align: center;

    }

    thead.panel th {
        background-color: #2D6CA2;
        color: white;
        text-align: center;
    }

    thead.bloque th {
        background-color: rgb(31, 70, 107);
        color: white;
        text-align: center;
    }
    thead.detail th {
        background-color: rgb(88, 88, 88);
        color: white;
        text-align: center;
    }

    .tooltip-inner {
        white-space:pre;
        max-width: none;
    }

    .icosize
    {
        font-size:20px;
    }

</style>

<table class="table table-bordered">
    <thead class="panel">
       <tr>
            <th></th>
            <th>Panel</th>
            <th>Programa</th>
            <th>AOI</th>
            <th>INS</th>
            <th>Errores</th>
            <th>Falsos</th>
            <th>Reales</th>
            <th>Bloques</th>
            <th>Etiqueta</th>
            <th>OP</th>
            <th>Fecha</th>
            <th>Hora</th>
        </tr>
    </thead>
    <tbody>
    <!-- Si hay un error los muestro -->
    @if ( isset($inspectionList->inspecciones ->error))
        <tr>
            <td colspan="13">
                {{ $insp->error }}
            </td>
        </tr>
    @else
        @if (count($inspectionList->inspecciones ) == 0)
            <tr>
                <td colspan="13">
                    No se registraron inspecciones
                </td>
            </tr>
        @else
            <!-- Muestro resultados de paneles inspeccionados -->
            @foreach( $inspectionList->inspecciones as $panel)
                <tr class="{{ ($panel->revision_aoi == 'OK' && $panel->revision_ins == 'OK' ) ? 'success' : '' }} {{ ($panel->revision_ins == 'NG' ) ? 'danger' : '' }}">
                    <td style="width:50px;">
                        <button id_panel="{{ $panel->id_panel_history }}" route="{{ route('aoicollector.inspection.blocks',$panel->id_panel_history) }}" ng-click="getInspectionBlocks($event);" class="btn btn-xs btn-default">Bloques</button>
                    </td>
                    <td>{{ $panel->panel_barcode }}</td>
                    <td>{{ $panel->programa }}</td>
                    <td>{{ $panel->revision_aoi }}</td>
                    <td>{{ $panel->revision_ins }}</td>
                    <td>{{ $panel->errores }}</td>
                    <td>{{ $panel->falsos }}</td>
                    <td>{{ $panel->reales }}</td>
                    <td>{{ $panel->bloques }}</td>
                    <td>
                        @if ($panel->etiqueta === 'E')
                            Fisica
                        @else
                            Virtual
                        @endif
                    </td>
                    <td>{{ $panel->inspected_op }}</td>
                    <td>{{  \IAServer\Http\Controllers\IAServer\Util::dateToEs($panel->created_date) }}</td>
                    <td>{{ $panel->created_time }}</td>
                    <td>
                        <?php
                       // $verify = new \IAServer\Http\Controllers\Aoicollector\Inspection\VerificarDeclaracion();
                        //$twip = (object) $verify->bloqueEnTransaccionWip($panel->panel_barcode);
                        ?>

                            @if($panel->trans_ok == null)
                                <i class="fa fa-exclamation-circle text-danger icosize" tooltip-placement="left" tooltip="Sin declarar"></i>
                            @endif
                            @if($panel->trans_ok==1)
                                <i class="fa fa-thumbs-o-up text-success icosize" tooltip-placement="left" tooltip="Declarado"></i>
                            @endif
<?php
                            /*
                        @if(isset($twip) && isset($twip->last))
                            @if($twip->declarado)
                                <i class="fa fa-thumbs-o-up text-success" tooltip-placement="left" tooltip="Declarado"></i>
                            @else
                                @if($twip->error)
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
                            */ ?>

                        @if($maquina->cogiscan=='T')
                            <?php
                                $cogiscan = $panel->cogiscan();
                            ?>
                            @if(isset($cogiscan['attributes']['message']))
                                <i class="fa fa-exclamation-triangle text-danger" tooltip="Cogiscan: {{ $cogiscan['attributes']['message'] }}"></i>
                            @else
                                @if(isset($cogiscan['Product']['attributes']['operation']))
                                    @if($cogiscan['Product']['attributes']['operation'] == 'Depanelization')
                                        <i class="fa fa-send text-info" tooltip="Ruta: {{ $cogiscan['Product']['attributes']['operation'] }}, Estado: {{ $cogiscan['Product']['attributes']['status'] }}"></i>
                                    @else
                                        <i class="fa fa-road text-success" tooltip="Ruta: {{ $cogiscan['Product']['attributes']['operation'] }}, Estado: {{ $cogiscan['Product']['attributes']['status'] }}"></i>
                                    @endif

                                    @if(isset($cogiscan['attributes']['quarantineLocked']) && $cogiscan['attributes']['quarantineLocked'] == "true")
                                        <i class="fa fa-bomb text-danger" tooltip="Placa en cuarentena"></i>
                                    @endif
                                @endif
                            @endif
                        @endif

                        @if(isset($panel->first_history_inspeccion_panel))
                            @if($panel->id_panel_history != $panel->first_history_inspeccion_panel)
                                <?php
                                    $firstApparition = $panel->joinFirstInspection;
                                ?>

                                    <!-- Misma maquina -->
                                @if($maquina->id == $firstApparition->id_maquina)
                                    @if($panel->created_date == $firstApparition->created_date)
                                        <i class="fa fa-history text-info icosize" tooltip-placement="left" tooltip="Primera inspeccion a las {{ $firstApparition->created_time }}"></i>
                                    @else
                                        <i class="fa fa-history text-danger icosize" tooltip-placement="left" tooltip="Primera inspeccion {{ \IAServer\Http\Controllers\IAServer\Util::dateToEs($firstApparition->created_date) }} a las {{ $firstApparition->created_time }}"></i>
                                    @endif
                                @else
                                    <!-- Maquina diferente -->
                                    <i class="fa fa-code-fork text-danger icosize" tooltip-placement="left" tooltip="Primera inspeccion en
{{ $maquinas->where('id',$firstApparition->id_maquina)->first()->maquina }}
{{ \IAServer\Http\Controllers\IAServer\Util::dateToEs($firstApparition->created_date) }} a las {{ $firstApparition->created_time }}"></i>
                                @endif
                            @endif
                        @endif
                    </td>

                </tr>
            @endforeach


            @if($inspectionList->inspecciones instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <tr>
                    <td colspan="14">
                        {!! $inspectionList->inspecciones->appends([
                            'date_session' => Input::get('date_session'),
                            'listMode' => Input::get('listMode'),
                            'filterPeriod' => Input::get('filterPeriod')
                            ])->links()
                        !!}
                    </td>
                </tr>
            @endif

            <!-- Muestro paginacion, solo si hay mas de una pagina-->
            @if( $inspectionList->filas > 0 && $inspectionList->paginas > 1)
                <tr>
                    <td colspan="14">
                        @include('aoicollector.inspection.partial.pagination')
                    </td>
                </tr>
            @endif
        @endif
    @endif
    </tbody>
</table>

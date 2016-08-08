<style>
   /* .table-striped tbody tr:nth-child(2n+1) > td {
        background-color: #F7F7F7;
    }

    .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
        background-color: #FFFDD1;
    }*/

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
    @if ( isset($insp->error))
        <tr>
            <td colspan="13">
                {{ $insp->error }}
            </td>
        </tr>
    @else
        @if (count($insp) == 0)
            <tr>
                <td colspan="13">
                    No se registraron inspecciones
                </td>
            </tr>
        @else
            <!-- Muestro resultados de paneles inspeccionados -->
            @foreach( $insp as $panel)
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

                        @if($panel->twip)
                            @if($panel->twip->trans_code == 1)
                                <i style="color: #49bc00;" class="fa fa-thumbs-o-up fa-2x" tooltip-placement="left" tooltip="Declaracion: {{ $panel->twip->trans_det }}"></i>
                            @else
                                <i class="fa fa-thumbs-o-down"  tooltip-placement="left" tooltip="{{ $panel->twip->trans_code }}:{{ $panel->twip->trans_det }}"></i>
                            @endif
                        @else
                            <i class="fa fa-thumbs-o-down" tooltip-placement="left" tooltip="Sin declarar"></i>
                        @endif

                        <!-- Deshabilito la opcion de verificar el barcode declarado en la interfaz -->
                        @if(true==false)
                            @if($panel->wip()->last->trans_ok == 1 && empty($panel->wip()->last->ebs_error_trans))
                                <i style="color: #49bc00;" class="fa fa-thumbs-o-up fa-2x" tooltip-placement="left" tooltip="Declarado en la interfaz"></i>
                            @else
                                <i style="color: #ec0006;" class="fa fa-thumbs-o-down fa-2x" tooltip-placement="left" tooltip="{{ $panel->wip()->last->trans_ok }}:{{ $panel->wip()->last->ebs_error_trans }}"></i>
                            @endif
                        @else
                            <!--
                                <i class="fa fa-thumbs-o-down" tooltip-placement="left" tooltip="Sin declarar"></i>
                            -->
                        @endif

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

                        @if($panel->created_date != $panel->firstime)
                            <i class="fa fa-clock-o" tooltip-placement="left" tooltip="Primera inspeccion: {{ $panel->firstime }}"></i>
                        @endif
                    </td>

                </tr>
            @endforeach

            <!-- Muestro paginacion, solo si hay mas de una pagina-->
            @if( isset($total) && $paginas > 1)
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
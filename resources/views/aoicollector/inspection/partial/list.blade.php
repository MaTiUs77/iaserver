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
            <th>Wip</th>
            <th>First</th>
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
            @foreach( $insp as $panel )
                <tr class="{{ ($panel->revision_aoi == 'OK' && $panel->revision_ins == 'OK' ) ? 'success' : '' }} {{ ($panel->revision_ins == 'NG' ) ? 'danger' : '' }}">
                    <td style="width:50px;">
{{--
                        @if($panel->bloques==1)
                            <button id_bloque="{{ $id_bloque }}" route="{{ route('aoicollector.inspection.detail', $id_bloque) }}" ng-click="getInspectionDetail($event);" class="btn btn-xs btn-default">Detalle</button>
                        @else
--}}
                            <button id_panel="{{ $panel->id_panel_history }}" route="{{ route('aoicollector.inspection.blocks',$panel->id_panel_history) }}" ng-click="getInspectionBlocks($event);" class="btn btn-xs btn-default">Bloques</button>
{{--
                        @endif
--}}
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
                    <td>{{ \IAServer\Http\Controllers\IAServer\Util::dateToEs($panel->created_date) }}</td>
                    <td>{{ $panel->created_time }}</td>
                    <td>
                        @if($panel->twip != null)
                            @if($panel->twip->CountOk() == $panel->bloques)
                                <button class="btn btn-xs btn-success" tooltip-placement="left" tooltip="Procesado Correctamente"><span class="glyphicon glyphicon-thumbs-up"></span></button>
                            @else
                                <button class="btn btn-xs btn-danger" tooltip-placement="left" tooltip="Error total o parcial en declaracion"><span class="glyphicon glyphicon-thumbs-down"></span></button>
                            @endif
                        @else
                            <button class="btn btn-xs btn-default" tooltip-placement="left" tooltip="Sin declarar"><span class="glyphicon glyphicon-thumbs-down"></span></button>
                        @endif
                    </td>
                    <td>
                        @if($panel->created_date != $panel->firstime)
                            <button class="btn btn-xs btn-default" tooltip-placement="left" tooltip="Primera inspeccion: {{ $panel->firstime }}">
                                <span class="glyphicon glyphicon-time"></span>
                            </button>
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
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

@if (count($insp) == 0)
    <h3>No se encontraron resultados</h3>
@else
    @foreach( $insp as $panel_barcode => $panels)
        <h3>{{ $panel_barcode }}</h3>
        <table class="table table-bordered">
            <thead class="panel">
                <tr>
                    <th></th>
                    <th>Linea</th>
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
                @foreach( $panels as $panel)
                    <!-- Si hay un error los muestro -->
                    @if ( isset($insp->error))
                        <tr>
                            <td colspan="13">
                                {{ $insp->error }}
                            </td>
                        </tr>
                    @else
                        <tr class="{{ ($panel->revision_aoi == 'OK' && $panel->revision_ins == 'OK' ) ? 'success' : '' }} {{ ($panel->revision_ins == 'NG' ) ? 'danger' : '' }}">
                            <td style="width:50px;">
                                <button id_panel="{{ $panel->id_panel_history }}" route="{{ route('aoicollector.inspection.blocks',$panel->id_panel_history) }}" ng-click="getInspectionBlocks($event);" class="btn btn-xs btn-default">Bloques</button>
                            </td>
                            <td>SMD-{{ $panel->linea }}</td>
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
                            <td>{{ $panel->created_date }}</td>
                            <td>{{ $panel->created_time }}</td>
                        </tr>

                        <!-- Muestro paginacion, solo si hay mas de una pagina-->
                        @if( isset($total) && $paginas > 1)
                            <tr>
                                <td colspan="14">
                                    @include('aoicollector.inspection.partial.pagination')
                                </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
            </tbody>
        </table>
    @endforeach
@endif

<table class="table table-bordered table-striped table-hover">
        <thead>
            <tr style="text-align: center;">
                <th>DB</th>
                <th>Solicitudes</th>
                <th>Trans_Ok</th>
                <th>Detalle</th>
                <th>Ebs Trans Error</th>
            </tr>
        </thead>
        <tbody>
        @if(isset($wip_serie))
            @foreach($wip_serie as $serie)
                <tr style="text-align: center;">
                    <td>WIP_SERIE</td>
                    <td>{{ $serie->total }}<td>
                        <a class="btn btn-xs btn-{{ ($serie->trans_ok > 1) ? 'danger' : 'success'  }}" tooltip="Ver detalle" href="{{ route('trazabilidad.form.trans_ok',[$nro_op,'serie',$serie->trans_ok]) }}" target="_blank">{{ $serie->trans_ok }}</a>
                    </td>
                    <td>{{ $serie->description }}</td>
                    <td>{{ $serie->ebs_error_trans }}</td>
                </tr>
            @endforeach
        @endif

        @if(isset($wip_history))
            @foreach($wip_history as $history)
                <tr style="text-align: center;">
                    <td>WIP_SERIE_HISTORY</td>
                    <td>{{ $history->total }}</td>
                    <td>
                        <a class="btn btn-xs btn-{{ ($history->trans_ok > 1) ? 'danger' : 'success'  }}" tooltip="Ver detalle" href="{{ route('trazabilidad.form.trans_ok',[$nro_op,'history',$history->trans_ok]) }}" target="_blank">{{ $history->trans_ok }}</a>
                    </td>
                    <td>{{ $history->description }}</td>
                    <td>{{ $history->ebs_error_trans }}</td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>

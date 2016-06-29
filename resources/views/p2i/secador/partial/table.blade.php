<table class="table table-bordered">
    <thead class="panel">
    <tr>
        <th></th>
        <th>Secador</th>
        <th>Modelo</th>
        <th>Fecha</th>
        <th>Hora entrada</th>
        <th>Hora salida</th>
        <th>Tiempo de Proceso</th>
        <th>Jigs cargados</th>
        <th>Operador</th>
    </tr>
    </thead>
    <tbody>
    @if(count($secador)==0)
        <tr>
            <td colspan="14">
                <a href="{{ url('p2i/secador/create') }}" class="btn btn-info">Ingresar nuevo registro</a>
            </td>
    @endif
    @foreach($secador as $key => $value)
        <tr>
            <td style="width: 50px;">
                {!! IABtnDelete(route('p2i.secador.destroy', $value->id))  !!}
            </td>
            <td>{{ $value->secador }}</td>
            <td>{{ $value->modelo->nombre }}</td>
            <td style="min-width: 100px;">
                {{ \Carbon\Carbon::createFromFormat('Y-m-d',$value->fecha)->format('d/m/Y') }}
            </td>
            <td>{{ $value->hora_entrada }}</td>
            <td>
                @if($value->hora_salida)
                    {{ $value->hora_salida }}
                @else
                    <a href="{{ route('p2i.secador.terminar',$value->id) }}" class="btn btn-sm btn-default">Terminar</a>
                @endif
            </td>
            <td>
                @if($value->tiempo_proceso)
                    {{ $value->tiempo_proceso }}
                @else
                    <span hour-ago="{{ $value->hora_entrada }}"></span>
                @endif
            </td>

            <td>
                @if($value->jigs_cargados) <span class="glyphicon glyphicon-ok"></span> @endif
            </td>
            <td>{{ $value->operador->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

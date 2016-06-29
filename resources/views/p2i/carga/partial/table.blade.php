<table class="table table-bordered">
    <thead class="panel">
    <tr>
        <th></th>
        <th>Camara</th>
        <th>Fecha</th>
        <th>Hora Entrada</th>
        <th>Hora Salida</th>
        <th>Tiempo de Proceso</th>
        <th>Limpieza de Camara</th>
        <th>Verificacion y Limpieza de Laminas Laterales</th>
        <th>Limpieza de Burlete y Puerta</th>
        <th>Jigs Cargados Correctamente</th>
        <th>Nivel de Monomero</th>
        <th>Verificacion de Filtros</th>
        <th>Ciclo Numero (Run Number)</th>
        <th>Operador</th>
        <th>Codigo Monomero</th>
        <th>Conjunto de Jigs</th>
        <th>Observacion</th>
    </tr>
    </thead>
    <tbody>
    @if(count($carga)==0)
        <tr>
            <td colspan="14">
                <a href="{{ url('p2i/carga/create') }}" class="btn btn-info">Ingresar nuevo registro</a>
            </td>
        </tr>
    @endif
    @foreach($carga as $key => $value)
        <tr>
            <td style="width: 50px;">
                {!! IABtnDelete(route('p2i.carga.destroy', $value->id)) !!}
            </td>
            <td>{{ $value->camara }}</td>
            <td style="min-width: 100px;">
                {{ \IAServer\Http\Controllers\IAServer\Util::dateToEs($value->fecha) }}
            </td>
            <td>{{ $value->hora_entrada }}</td>
            <td>
                @if($value->hora_salida)
                    {{ $value->hora_salida }}
                    @else
                    <a href="{{ route('p2i.carga.terminar',$value->id) }}" class="btn btn-sm btn-default">Terminar</a>
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
                @if($value->limp_camara) <span class="glyphicon glyphicon-ok"></span> @endif
            </td>
            <td>
                @if($value->limp_laminas_laterales) <span class="glyphicon glyphicon-ok"></span> @endif
            </td>
            <td>
                @if($value->limp_burlete_puerta) <span class="glyphicon glyphicon-ok"></span> @endif
            </td>
            <td>
                @if($value->jigs_cargados) <span class="glyphicon glyphicon-ok"></span> @endif
            </td>
            <td>
                @if($value->nivel_monomero) <span class="glyphicon glyphicon-ok"></span> @endif
            </td>
            <td>
                @if($value->verif_filtros) <span class="glyphicon glyphicon-ok"></span> @endif
            </td>
            <td>{{ $value->ciclo }}</td>
            <td>{{ $value->operador->name }}</td>
            <td>{{ $value->monomero }}</td>
            <td>{{ $value->conjunto_jigs }}</td>
            <td>{{ $value->observacion }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

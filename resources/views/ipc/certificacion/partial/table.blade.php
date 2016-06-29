<table class="table table-bordered">
    <thead class="panel">
    <tr>
        <th></th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Legajo</th>
        <th>Certificacion Norma</th>
        <th>ID</th>
        <th>Alta</th>
        <th>Caduca</th>
        <th>Instructor</th>
    </tr>
    </thead>
    <tbody>
    @if(count($certificacion)==0)
        <tr>
            <td colspan="14">
                <a href="{{ url('ipc/certificacion/create') }}" class="btn btn-info">Agregar nueva certificacion</a>
            </td>
    @endif
    @foreach($certificacion as $cert)
        <tr>
            <td style="width: 50px;">
                {!! IABtnDelete(route('ipc.certificacion.destroy', $cert->id_certificacion)) !!}
            </td>
            <td>{{ $cert->profile->nombre}}</td>
            <td>{{ $cert->profile->apellido }}</td>
            <td>{{ $cert->profile->legajo }}</td>
            <td>{{ $cert->norma->norma }}</td>
            <td>{{ $cert->codigo_certificado }}</td>
            <td style="min-width: 100px;">
                {{ \Carbon\Carbon::createFromFormat('Y-m-d',$cert->fecha_alta)->format('d/m/y') }}
            </td>
            <td style="min-width: 100px;">
                {{ \Carbon\Carbon::createFromFormat('Y-m-d',$cert->fecha_baja)->format('m/y') }}
            </td>
            <td>
                {{ $cert->user->profile->nombre }}
                {{ $cert->user->profile->apellido}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

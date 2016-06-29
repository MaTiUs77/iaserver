1<table class="table table-bordered">
    <thead class="panel">
    <tr>
        <th></th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Legajo</th>
        <th>Sector</th>
        <th>Categoria</th>
    </tr>
    </thead>
    <tbody>
    @if(count($personas)==0)
        <tr>
            <td colspan="14">
                <a href="{{ url('ipc/personas/create') }}" class="btn btn-info">Agregar nueva persona</a>
            </td>
    @endif
    @foreach($personas as $persona)
        <tr>
            <td></td>
            <td>{{ $persona->nombre }}</td>
            <td>{{ $persona->apellido }}</td>
            <td>{{ $persona->legajo }}</td>
            <td>{{ $persona->sector->sector }}</td>
            <td>{{ $persona->categoria->categoria }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

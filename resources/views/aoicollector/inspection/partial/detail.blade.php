<table class="table table-bordered block">
    <thead class="detail">
    <tr>
        <th>Referencia</th>
        <th>Descripcion</th>
        <th>Estado</th>
        <th>Faultcode</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $detalle as $detail )
        <tr>
            <td>{{ $detail->referencia }}</td>
            <td>{{ ($detail->descripcion == null) ? 'Descripcion desconocida' : $detail->descripcion  }}</td>
            <td>{{ $detail->estado }}</td>
            <td>{{ $detail->faultcode }}</td>
        </tr>
    @endforeach
    </tbody>
</table>





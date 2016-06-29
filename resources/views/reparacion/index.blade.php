@extends('angular')
@section('title','Reparacion')
@section('body')

<style>
    .table {
        font-size: 11px;
    }
</style>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Codigo</th>
            <th>Modelo</th>
            <th>Lote</th>
            <th>Panel</th>
            <th>Defecto</th>
            <th>Causa</th>
            <th>Referencia</th>
            <th>Correctiva</th>
            <th>Observacion</th>
            <th>Origen</th>
            <th>Reparador</th>
            <th>Turno</th>
            <th>Area</th>
            <th>Fecha</th>
            <th>Hora</th>
        </tr>
        </thead>
        <tbody>
            @foreach( $reparacion as $rep )
                <tr
                        @if( $rep->historico == 'log')
                            style="background-color: #efefef"
                        @endif
                        @if( $rep->estado== 'P' && $rep->reparaciones == 0)
                            style="background-color: #fdff9b"
                        @endif
                >
                    <td>{{ $rep->codigo }}</td>
                    <td>{{ $rep->modelo }}</td>
                    <td>{{ $rep->lote }}</td>
                    <td>{{ $rep->panel }}</td>
                    <td>{{ $rep->defecto }}</td>
                    <td>{{ $rep->causa }}</td>
                    <td>{{ $rep->referencia }}</td>
                    <td>{{ $rep->accion }}</td>
                    <td>{{ $rep->correctiva }}</td>
                    <td>{{ $rep->origen }}</td>
                    <td>{{ $rep->reparador }}</td>
                    <td>{{ $rep->turno }}</td>
                    <td>{{ $rep->area }}</td>
                    <td>{{ $rep->fecha }}</td>
                    <td>{{ $rep->hora }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

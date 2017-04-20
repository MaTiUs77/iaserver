@extends('adminlte/theme')
@section('title','Monitor de molinete')
@section('body')

    <div class="container">
        <!-- will be used to show any messages -->
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <h3>Monitor de molinetes</h3>
        <hr>

        <div class="row" >
            <h4>Resultados de la jornada</h4>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="7"></th>
                        <th colspan="2">Resultado de pies</th>
                    </tr>
                    <tr>
                        <th>Apellido</th>
                        <th>Nombre</th>
                        <th>Legajo</th>
                        <th>Departamento</th>
                        <th>Puesto</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Izquierdo</th>
                        <th>Derecho</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($molinete as $item)
                        <?php
                            $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s.u', $item->Fecha);
                        ?>
                        <tr class="{{ ($item->Resultado == 1) ? 'success' : '' }}">
                            <td>{{ $item->Apellido }}</td>
                            <td>{{ $item->Nombres }}</td>
                            <td>{{ $item->Legajo }}</td>
                            <td>{{ $item->Departamento }}</td>
                            <td>{{ $item->Puesto }}</td>
                            <td>{{ $carbonDate->format('d-m-Y') }}</td>
                            <td>{{ $carbonDate->format('H:i') }} </td>
                            @if($item->Resultado == 0)
                                <td class="danger">NG</td>
                                <td class="danger">NG</td>
                            @endif
                            @if($item->Resultado == 1)
                                <td class="success">OK</td>
                                <td class="success">OK</td>
                            @endif
                            @if($item->Resultado == 2)
                                <td class="success">OK</td>
                                <td class="danger">NG</td>
                            @endif
                            @if($item->Resultado == 3)
                                <td class="danger">NG</td>
                                <td class="success">OK</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('iaserver.common.footer')
@endsection
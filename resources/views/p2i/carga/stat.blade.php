@extends('angular')
@section('ng','app')
@section('title','P2i - Estadisticas')
@section('body')
    @include('p2i.common.header')
    @include('p2i.common.bread',['bread'=>['Carga','Estadisticas']])

    @if(count($stat)==0)
        <h3>No hay registros cargados</h3>
    @else
        <table class="table table-bordered">
            <thead class="panel">
            <tr>
                <th style="width: 200px;"><input type="text" ng-model="filter_monomero" ng-init="filter_monomero=''" class="form-control" placeholder="Filtrar monomero"/></th>
                <th>Usos</th>
                <th style="width: 200px;"><input type="text" ng-model="filter_camara" ng-init="filter_camara=''" class="form-control" placeholder="Filtrar camara"/></th>
                <th>Fecha primer uso</th>
                <th>Fecha ultimo uso</th>
            </tr>
            </thead>
            <tbody>
            @foreach($stat as $s)
                <tr ng-hide="(('{{ $s->monomero }}').indexOf(filter_monomero.toLowerCase()) == -1) || ( ('{{ $s->camara }}' != filter_camara) && filter_camara != '')">
                    <td>{{ $s->monomero }}</td>
                    <td>{{ $s->monomero_usos }}</td>
                    <td>{{ $s->camara }}</td>
                    <td>{{ \IAServer\Http\Controllers\IAServer\Util::dateToEs($s->monomero_start_date) }}</td>
                    <td>{{ \IAServer\Http\Controllers\IAServer\Util::dateToEs($s->monomero_end_date) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @include('p2i.common.footer')
@endsection

@extends('angular')
@section('ng','app')
@section('title','Controldeplacas')
@section('body')
@section('bodytag','ng-controller="placasController"')

    <nav class="navbar navbar-default" style="padding-bottom:5px;margin-bottom:1px;" role="navigation">
        <div class="navbar-form">
            <div class="navbar-left">
                <button type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span> Paletizar
                </button>

                <button class="btn btn-sm btn-default" ng-click="openModal('{{  route('controldeplacas.filtrar.form') }}','Formulario de filtro','info')">
                    <span class="glyphicon glyphicon-search"></span> Filtrar
                </button>

                <button type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Recepcionar
                </button>
                <button type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Lotes
                </button>
                <button type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span> Reproceso
                </button>
            </div>

            @if(Auth::user())
                <div class="navbar-right">
                    <a href="" class="btn btn-info">
                        @if (Auth::user()->hasProfile())
                            {{ Auth::user()->profile->fullname() }}
                        @else
                            {{ Auth::user()->name }}
                        @endif
                    </a>
                </div>
            @endif

        </div>
    </nav>

    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th></th>
            <th>Codigo</th>
            <th>Op</th>
            <th>Modelo</th>
            <th>Lote</th>
            <th>Placa</th>
            <th>Cantidad</th>
            <th>Salidas</th>
            <th>Cant. Lote</th>
            <th>Diferencia</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Turno</th>
            <th>Sector</th>
            <th>Destino</th>
        </tr>
        </thead>
        <tbody>
            @foreach( $datos as $row )
                @if(!empty($row->op))
                <tr>
                    <th> <button type="button" class="btn btn-danger btn-xs">Borrar</button></th>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->op }}</td>
                    <td>{{ $row->modelo }}</td>
                    <td>{{ $row->lote }}</td>
                    <td>{{ $row->panel }}</td>
                    <td>{{ $row->cantidad }}</td>
                    <td>{{ $row->salidas }}</td>
                    <td>{{ $row->qty }}</td>
                    <td>
                        {{ ($row->salidas - $row->qty) }}
                    </td>
                    <td>{{ $row->fecha }}</td>
                    <td>{{ $row->hora }}</td>
                    <td>{{ $row->turno }}</td>
                    <td>{{ $row->sector }}</td>
                    <td>{{ $row->destino }}</td>
                </tr>
                @else
                    @if(isset($row->id))
                    <tr>
                        <td> <button type="button" class="btn btn-danger btn-xs">Borrar</button> </td>
                        <td>{{ $row->id }}</td>
                        <td><span class="label label-default">Desconocida</span></td>
                        <td>{{ $row->modelo }}</td>
                        <td>{{ $row->lote }}</td>
                        <td>{{ $row->panel }}</td>
                        <td>{{ $row->cantidad }}</td>
                        <td><span class="label label-default">Desconocida</span></td>
                        <td><span class="label label-default">Desconocida</span></td>
                        <td><span class="label label-default">Desconocida</span></td>
                        <td>{{ $row->fecha }}</td>
                        <td>{{ $row->hora }}</td>
                        <td>{{ $row->turno }}</td>
                        <td>{{ $row->sector }}</td>
                        <td>{{ $row->destino }}</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="14">{{ $row }}</td>
                    </tr>
                    @endif
                @endif
            @endforeach
        </tbody>
    </table>

    @include('iaserver.common.footer')
    <script>
        app.controller("placasController",function($scope,$rootScope,$http,$interval,$q,IaCore)
        {
            $scope.openModal = function(route, title, type) {
                IaCore.modal({
                    scope: $scope,
                    route:route,
                    title: title,
                    type: type,
                    ignoreloadingbar: false
                });
            }
        });
    </script>
@endsection

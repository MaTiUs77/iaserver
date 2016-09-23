@extends('adminlte/theme')
@section('ng','app')
@section('title','SMTDatabase')
@section('body')
    @include('smtdatabase.partial.header')

    <div class="container">
        <!-- will be used to show any messages -->
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <form class="form-horizontal" role="form" method="post" action="{{ route('smtdatabase.componentes.buscar') }}">
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <h3>Buscar componente</h3>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <input ng-required="true"  type="text" class="form-control" placeholder="Ej: EAG63530103" name="componente">
                </div>
                <div class="col-sm-4">
                    <input type="submit" value="Buscar" class="btn btn-primary">
                </div>
            </div>
        </form>

        <form class="form-horizontal" role="form" method="post" action="{{ route('smtdatabase.componentes.buscar.semielaborado') }}">
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <h3>Buscar semielaborado</h3>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="input-group">
                        <div class="input-group-addon">4-651-</div>
                        <input type="text" class="form-control" placeholder="Resto del codigo" name="componente" ng-required="true">
                    </div>
                </div>
                <div class="col-sm-4">
                    <input type="submit" value="Buscar" class="btn btn-primary">
                </div>
            </div>
        </form>

        <form class="form-horizontal" role="form" method="post" action="{{ route('smtdatabase.componentes.buscar.semielaborado.modelo') }}">
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <h3>Buscar semielaborado por modelo</h3>
                </div>
            </div>

            <div class="form-group">
                <div  ng-controller="TypeaheadCtrl" class="col-sm-4 col-sm-offset-1 ng-scope">
                    <input autocomplete="off" type="text" placeholder="Ej: 19EN33" name="modelo" ng-model="selected" typeahead="item for item in items | filter:$viewValue | limitTo:8" class="form-control" ng-required="true" >
                </div>
                <div class="col-sm-4">
                    <input type="submit" value="Buscar" class="btn btn-primary">
                </div>
            </div>
        </form>
    </div>

    @include('iaserver.common.footer')

    <?php
    $modelos = \IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo::select('modelo')->groupBy('modelo')->get();

    ?>

    <script>
        app.controller("TypeaheadCtrl",function($scope, $rootScope, $http)
        {
            $scope.selected = undefined;
            $scope.items = ['{!! join("','",array_flatten($modelos->toArray()))  !!}'];
        });

    </script>
@endsection
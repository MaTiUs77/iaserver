@extends('angular')
@section('ng','app')
@section('title','SMTDatabase - Transportador')
@section('body')

@include('smtdatabase.partial.header')
<div class="container">
    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <form class="form-horizontal" role="form" method="post" action="{{ route('smtdatabase.transport.form') }}">
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <h3>SMTDatabase</h3>
            </div>
        </div>

        <div class="form-group" ng-hide="searchPerfilFlag">
            <div class="col-sm-4 col-sm-offset-1">
                <input ng-required="true"  type="text" class="form-control" placeholder="Ingresar OP" name="op">
            </div>
            <div class="col-sm-4">
                <input type="submit" value="Aceptar" class="btn btn-primary">
            </div>
        </div>
    </form>

    <hr>
    @if(isset($postMode) && !isset($smt))
        <h4>Sin resultados: {{ $op }}</h4>
    @endif

    @if(isset($smt))

        <div class="row">
            <div class="col-lg-6">
                <blockquote>
                    <h3>{{ $smt->op }}</h3>
                    <small>Modelo</small>
                    {{ $smt->modelo }} - {{ $smt->lote }} - {{ $smt->panel }}

                    <small>Paneles en AOI</small>
                    {{ $transport }}

                    @if($transport>0)
                        <form class="form-inline" role="form" method="post" action="{{ route('smtdatabase.transport.submit') }}">
                            <input type="hidden" name="transport_from" value="{{$op}}">

                            <small>Custom OP ?</small>
                            <input type="checkbox" ng-model="customop" class="form-control">

                            <small>Transportar a</small>

                            <input type="text" class="form-control" placeholder="Ingresa OP manualmente" name="transport_to" ng-show="customop">

                            <select class="form-control" name="transport_to" style="font-size: 15px;"  ng-hide="customop" ng-disabled="customop">
                                @foreach($panels as $panel)
                                    @if($panel->op != $op)
                                        <option value="{{ $panel->op }}">{{ $panel->op }} / {{ $panel->panel }}</option>
                                    @endif
                                @endforeach
                            </select>

                            <input type="submit" class="btn btn-info" value="Transportar">

                        </form>
                    @endif
                </blockquote>
            </div>
            @if(isset($smtTo))
            <div class="col-lg-6">
                <blockquote>
                    <h3>{{ $smtTo->op }}</h3>
                    <small>Modelo</small>
                    {{ $smtTo->modelo }} - {{ $smtTo->lote }} - {{ $smtTo->panel }}

                    <small>Paneles en AOI</small>
                    {{ $quantity }}
                </blockquote>
            </div>
            @endif
        </div>

    @endif

    @if(isset($smtTo))
    <div ng-controller="SMTDatabaseController">
        Auto transporte: @{{ intervalo }}
    </div>
    @endif

</div>

@include('iaserver.common.footer')

    <script>
        app.controller("SMTDatabaseController",function ($scope,$http, $compile, $interval)
        {
            $scope.intervalo = 100;
            var stop = $interval(function() {
                $scope.intervalo--;

                if($scope.intervalo==0)
                {
                    location.reload();
                    $interval.cancel(stop);
                    $scope.intervalo="Transportando placas...";
                }
            }, 600);
        });
    </script>
@endsection

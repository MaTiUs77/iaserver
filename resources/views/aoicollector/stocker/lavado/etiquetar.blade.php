@extends('angular')
@section('ng','app')
@section('title','Stocker - Re etiquetar')
@section('body')
    <div class="container" ng-controller="etiquetadoController" ng-init="lavadoIndex = '{{ route('aoicollector.stocker.lavado.index') }}'">

        @include('aoicollector.stocker.lavado.menu')

        @if(Input::get('stk'))

            <h3>{{ Input::get('stk') }}</h3>
            <button ng-show="printing" class="btn btn-info">Imprimiendo...</button>
            <button ng-hide="printing" class="btn btn-success" ng-click="imprimirEtiqueta('{{ route('aoicollector.stocker.lavado.imprimir.etiqueta',Input::get('stk')) }}')">Imprimir 4 etiquetas</button>
            <hr>

            <h3>Validar etiquetas</h3>
            <p>Una vez impresas las etiquetas, peguelas en el stocker y luego verifique con el scanner cada una de ellas.</p>

            <div class="row">
                <div class="col-md-4">
                    <div ng-show="stkerror" class="alert alert-danger" role="alert">
                        @{{ stkerror }}
                    </div>

                    <div ng-show="stkdone" class="alert alert-info" role="info">
                        Operacion completa!, redireccionando...
                    </div>

                    <form method="GET" action="?">
                        <input type="text" class="form-control" placeholder="Verificar etiqueta"  ng-keydown="enterEvent($event)" ng-init="stk = '{{ Input::get('stk') }}'" />
                    </form>

                    <ul class="list-group">
                        <li class="list-group-item list-group-item-warning">
                            @{{ current }} de @{{ limit }}
                        </li>
                        <li class="list-group-item" ng-repeat="item in stkbarcodes">
                            <span class="badge">@{{ item.qty}}</span>
                            @{{ item.barcode }}
                        </li>
                    </ul>
                </div>
            </div>
        @else
            @if(hasRole('stocker_lavado') || isAdmin())
                <div class="row">
                    <div class="col-lg-4">
                        <form method="POST" action="{{ route('aoicollector.stocker.lavado.etiquetar') }}" >
                            <div class="input-group" >
                                <input type="text" name="stk" class="form-control" placeholder="Ingresar codigo de stocker" />
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-info"> Re etiquetar</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endif
    </div>

    @include('iaserver.common.footer')

    <script>
            app.controller("etiquetadoController",function($scope, $rootScope, $http)
            {
                $scope.stk = '';
                $scope.limit = 4;
                $scope.current = 0;
                $scope.stkbarcodes = [];

                $scope.stkerror = false;
                $scope.stkdone = false;
                $scope.printing = false;

                $scope.enterEvent = function(e)
                {
                    var texto = e.target.value;

                    var code = (e.keyCode ? e.keyCode : e.which);
                    switch (code) {
                        case 13:
                            if(texto.toUpperCase() == $scope.stk.toUpperCase()) {

                                if (texto != '') {
                                    texto = texto.toUpperCase();

                                    var stkExist = false;
                                    var addStk = {barcode: texto, qty: 1};

                                    $scope.stkbarcodes.forEach(function (el) {
                                        if (el.barcode == texto) {
                                            el.qty++;
                                            $scope.current++;
                                            stkExist = true;
                                        }
                                    });

                                    if (!stkExist) {
                                        $scope.stkbarcodes.push(addStk);
                                        $scope.current++;
                                    }

                                    e.target.value = "";
                                }

                                if ($scope.current >= $scope.limit) {

                                    $scope.finishRoute();

                                    e.target.disabled = true;
                                }
                            } else
                            {
                                $scope.stkerror = "Se escaneo una etiqueta con codigo diferente";
                            }

                            e.preventDefault();

                        break;
                    }
                };

                $scope.finishRoute = function()
                {
                    $scope.stkdone = true;
                    window.location.href = $scope.lavadoIndex;
                }

                $scope.imprimirEtiqueta = function(route)
                {
                    $scope.printing = true;

                    $http.get(route)
                    .success(function(response){
                        $scope.printing = false;
                    });
                }
        });
    </script>
@endsection
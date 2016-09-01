@extends('angular')
@section('ng','app')
@section('title','Stocker - Re etiquetar')
@section('body')
    <div class="container">

        @include('aoicollector.stocker.lavado.menu')

        <h3>Re-etiquetado: <b>{{ Input::get('stk') }}</b></h3>

        <br>
        <div class="row">
            <div class="col-md-4" ng-controller="enterEventController">
                <form method="GET" action="?">
                    <input type="text" class="form-control" placeholder="Stocker a reetiquetar"  ng-keydown="enterEvent($event)" />
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

            <div class="col-md-4" ng-controller="enterEventController">
                <form method="GET" action="?">
                    <input type="text" class="form-control" placeholder="Stocker a reetiquetar"  ng-keydown="enterEvent($event)" />
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

    </div>

    @include('iaserver.common.footer')

    <script>
            app.controller("enterEventController",function($scope, $rootScope)
            {
                $scope.limit = 4;
                $scope.current = 0;
                $scope.stkbarcodes = [];
                $scope.enterEvent = function(e)
                {
                    var code = (e.keyCode ? e.keyCode : e.which);
                    switch(code) {
                        case 13:

                            var texto = e.target.value;

                            if(texto!='')
                            {
                                texto = texto.toUpperCase();

                                var stkExist=false;
                                var addStk = {barcode: texto, qty: 1};

                                $scope.stkbarcodes.forEach(function(el){
                                    if(el.barcode == texto)
                                    {
                                        el.qty++;
                                        $scope.current++;
                                        stkExist = true;
                                    }
                                });

                                if(!stkExist)
                                {
                                    $scope.stkbarcodes.push(addStk);
                                    $scope.current++;
                                }

                                e.target.value = "";
                            }

                            if($scope.current >= $scope.limit)
                            {
                                e.target.disabled = true;
                            }

                            e.preventDefault();
                        break;
                    }
                };
        });
    </script>
@endsection
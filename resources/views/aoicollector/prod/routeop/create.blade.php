@extends('adminlte/theme')
@section('ng','app')
@section('title','Aoicollector - Route OP')
@section('body')

    <div class="container" ng-controller="createOpController">
        <form class="form-horizontal" role="form" method="post" action="{{ url('aoicollector/prod/routeop') }}">
            <div class="form-group">
                <div class="col-md-4 ">
                    <h3>Configurar: {{  ($op!="") ? $op : Input::old('op') }}</h3>
                </div>
            </div>

            <!-- will be used to show any messages -->
            @if (Session::has('message'))
                <div class="form-group" id="message">
                    <div class="col-md-4 ">
                        <div class="alert alert-info">{{ Session::get('message') }}</div>
                    </div>
                </div>
            @endif

            <!-- ERROR -->
            @if (Session::has('errors'))
                <div class="form-group">
                    <div class="col-md-4 ">
                        <div class="alert alert-warning" role="alert">
                            <ul>
                                <strong>Oops! algo salio mal: </strong>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <!-- FIN -->
            <div class="form-group">
                <div class="col-md-4 ">
                    <input type="text" class="form-control" name="puesto" placeholder="Puesto ej: SMT" value="{{  Input::old('puesto')  }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 ">
                    <select class="form-control" name="regex">
                        <option value="">- Seleccionar expresion regular -</option>
                        <option value="\d{10}">
                            Permite solo 10 digitos
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 ">
                    <input type="number" class="form-control" name="qty_etiquetas" placeholder="Cantidad de etiquetas" value="{{  Input::old('qty_etiquetas')  }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 ">
                    <input type="number" class="form-control" name="qty_bloques" placeholder="Cantidad de bloques" value="{{  Input::old('qty_bloques')  }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 ">
                    <input type="hidden" name="op" value="{{  ($op!="") ? $op : Input::old('op') }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 ">
                    <div class="col-sm-6 ">
                        <input id="checkbox-declare" type="checkbox" name="declare">
                        <label for="checkbox-declare">Declarar</label>
                    </div>
                    <div class="col-sm-6">
                        <input id="checkboxCogiscan" type="checkbox" name="cogiscan">
                        <label for="checkboxCogiscan">Traza Cogiscan</label>
                    </div>
                </div>
            </div>

            <div class="form-group" ng-class="{'has-error' : cogiscan_partnumberError,'has-success' : cogiscan_partnumberSuccess}" ng-show="checkboxCogiscan">
                <div class="col-md-4">

                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="cogiscan_partnumber" ng-model="cogiscan_partnumber" placeholder="Cogiscan PartNumber " value="{{  Input::old('cogiscan_partnumber')  }}">
                        <span class="input-group-btn">
                          <button ng-click="validarCogiscanPartNumber()" type="button" class="btn btn-default btn-flat">Validar</button>
                        </span>
                    </div>
                    <span class="help-block" ng-show="cogiscan_partnumberError">@{{ cogiscan_partnumberError }}</span>

                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 ">
                    <input id="submit" name="submit" type="submit" value="Guardar configuracion" class="btn btn-block btn-primary">
                </div>
            </div>
        </form>
    </div>

    @include('iaserver.common.footer')

    <script>
        $(document).ready(function(){
            $('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-green',
                increaseArea: '20%' // optional
            });
        });
    </script>

    <script>
        app.controller('createOpController',function($scope,$http){
            $scope.checkboxCogiscan = false;
            $scope.cogiscan_partnumberError = false;
            $scope.cogiscan_partnumberSuccess = false;
            $scope.cogiscan_partnumber = "";


            $('input[name="cogiscan"]').on('ifToggled', function(event){
                $scope.checkboxCogiscan = !$scope.checkboxCogiscan;
                $scope.$apply();
            });

            $scope.validarCogiscanPartNumber = function()
            {
                $scope.cogiscan_partnumberError = false;
                $scope.cogiscan_partnumberSuccess = false;

                if($scope.cogiscan_partnumber!="")
                {
                    $http.get('{{ url('cogiscan/queryPartNumberProduct') }}/'+$scope.cogiscan_partnumber).then(function(rta){
                        var attr = rta.data.attributes;

                        if(attr.message)
                        {
                            $scope.cogiscan_partnumberError = attr.message;
                        }

                        if(attr.partNumber)
                        {
                            $scope.cogiscan_partnumberError = false;
                            $scope.cogiscan_partnumberSuccess = true;
                        }
                    });
                }

            }
        });
    </script>
@endsection

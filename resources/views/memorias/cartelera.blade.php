@extends('angular')
@section('ng','app')
@section('title','Memorias - Cartelera')
@section('body')

    <div class="well" style="height: 70px;">


        <div class="row">
            <div class="col-sm-4 col-md-6 col-lg-6">
                @if(\Illuminate\Support\Facades\Input::get('search'))
                    <a href="{{ route('memorias.index') }}" class="btn btn-info">Ver cartelera completa</a>
                @endif
            </div>

            <div class="col-sm-4 col-md-3 col-lg-3">
                <!-- BUSQUEDA EN TRAZABILIDAD-->
                <form method="POST" action="{{ route('trazabilidad.find.op') }}" class="pull-right">
                    <div class="input-group">
                        <input type="text" name="op" class="form-control" placeholder="OP" value="{{ Input::get('op')  }}"/>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Buscar en Traza</button>
                        </span>
                    </div>
                </form>
                <!-- END BUSQUEDA -->
            </div>

            <div class="col-sm-4 col-md-3 col-lg-3">
                <!-- BUSQUEDA EN PLAN -->
                <form method="POST" action="{{ route('memorias.search') }}" class="pull-right">
                    <div class="input-group" >
                        <input type="text" name="search" class="form-control" placeholder="Modelo/Op" value="" ng-model="buscador" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Buscar en plan</button>
                        </span>
                    </div>
                </form>
                <!-- END BUSQUEDA -->
            </div>
        </div>

    </div>

    <div class="row" ng-controller="carteleraController">
        <div class="container">

            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif

            <?php $autoIncrement = 0; ?>
            @foreach($programa as $programaTitle => $programaValues )
                    <?php $autoIncrement++; ?>
                    <div class="panel panel-primary" style="margin:2px;">

                        <!-- Nombre de programa ej LCD2 -->
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ $programaTitle }}</h3>
                        </div>

                        <!-- Contenido de programa -->
                        <div class="panel-body">
                            @foreach($programaValues as $item)
                                <?php if(!is_array($item->memorias->ingenieria)) { $errorIngenieria = true;} else { $errorIngenieria = false; }?>
                                <div class="panel panel-{{ ($errorIngenieria) ? 'danger':'default' }} col-sm-12 col-md-12 col-lg-12">

                                    <!-- Mostrar modelo y lote -->
                                    <div class="panel-heading">
                                        <a href="#" class="btn btn-{{ ($errorIngenieria) ? 'danger':'default' }}">{{ $item->plan->modelo }} - {{ $item->plan->lote }}</a>

                                        @if($errorIngenieria)
                                            <!-- Muestra error de ingenieria -->
                                            <span class="label label-danger" style="font-size: 14px;">
                                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                {{ $item->memorias->ingenieria }}</span>
                                        @else
                                            <!-- Muestra posiciones en Ingenieria -->
                                            <span class="label label-default">Posiciones:</span>
                                            @foreach($item->memorias->ingenieria as $ing)
                                                <span class="label label-danger" style="font-size: 12px;">{{ $ing['posicion'] }}</span>
                                            @endforeach
                                        @endif
                                    </div>

                                    @if(count($item->memorias->smt))
                                        <!-- Puede haber mas de una OP por memoria, imprime todas -->
                                        @foreach($item->memorias->smt as $smt)
                                            <div class="col-sm-6 col-md-6 col-lg-3">
                                                <blockquote>
                                                    @include('memorias.widget.meminfo',$smt)
                                                </blockquote>
                                            </div>
                                        @endforeach
                                    @else
                                        <h4>No existen OP creadas</h4>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
    @include('iaserver.common.footer')
    <script>
        app.controller("carteleraController",function($scope,$rootScope,$http,$interval,$q,IaCore)
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

        setTimeout('window.location.reload();', (60 * 1000) * 2);
    </script>
@endsection


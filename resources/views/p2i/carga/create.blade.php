@extends('angular')
@section('ng','app')
@section('title','P2i - Nuevo registro de carga')
@section('body')
    @include('p2i.common.header')
    @include('p2i.common.bread',['bread'=>['Carga','Nuevo registro']])

    <form class="form-horizontal" style="margin:20px;" role="form" method="post" action="{{ url('p2i/carga') }}" ng-controller="p2iController" ng-init="route = '{{ url('p2i/carga/') }}'">
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <h3>Registro de carga</h3>
            </div>
        </div>

        <!-- ERROR -->
        @if (Session::has('errors'))
            <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-1">
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
            <div class="col-sm-4 col-sm-offset-1">
                <select class="form-control" name="camara" ng-model="camara" ng-change="onSelectCamara()" ng-init="autoCamara('{{  Input::old('camara')  }}')">
                    <option value="" selected="selected">- Seleccionar camara -</option>
                    @for ($i = 1; $i <= $cantidad_camaras; $i++)
                        <option value="{{ $i }}">Camara {{ $i }}</option>
                    @endfor

                </select>
            </div>
        </div>

        <div ng-show="last_monomero_loading">
            <div class="form-group">

                <div class="col-sm-3 col-sm-offset-1">
                    Verificando ultimo monomero cargado en Camara-@{{ camara  }}...
                </div>

                <div class="col-sm-1">
                    <div class="loader_mini">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                </div>

            </div>
        </div>

        <div ng-hide="last_monomero_loading">
            <div ng-show="showCreateForm">
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <input type="text" class="form-control" name="ciclo" placeholder="Numero de ciclo" value="{{  Input::old('ciclo')  }}">
                </div>
            </div>

            <div class="form-group" ng-show="last_monomero">
                <div class="col-sm-4 col-sm-offset-1">
                    <input type="text" disabled class="form-control" name="last_monomero" placeholder="Codigo de monomero" ng-model="monomero" value="{{  Input::old('last_monomero')  }}">
                    <button type="button" class="btn btn-block btn-info" ng-click="changeMonomero()">Cambiar monomero</button>
                </div>
            </div>

            <div class="form-group" ng-hide="last_monomero">
                <div class="col-sm-4 col-sm-offset-1">
                    <input type="text" class="form-control focus" name="new_monomero" ng-model="monomero"  placeholder="Codigo de monomero" value="{{  Input::old('new_monomero')  }}">
                </div>
            </div>

            <div style="display:none;">
                <input type="text" name="monomero" ng-model="monomero">
                <input type="text" name="monomero_start" ng-model="monomero_start">
            </div>

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <input type="text" class="form-control" name="conjunto_jigs" placeholder="Conjunto de Jigs" value="{{  Input::old('conjunto_jigs')  }}">
                </div>
            </div>

            @foreach([
                ['limp_camara','Limpieza de Camara'],
                ['limp_laminas_laterales','Verificacion y Limpieza de Laminas Laterales'],
                ['limp_burlete_puerta','Limpieza de Burlete y Puerta'],
                ['jigs_cargados','Jigs cargados correctamente'],
                ['nivel_monomero','Nivel de Monomero'],
                ['verif_filtros','Verificacion de Filtros']
            ] as $v)
                @include('p2i.common.checkbox',[
                    'name'      => $v[0],
                    'name_desc' => $v[1]
                ])
            @endforeach

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <textarea class="form-control" rows="4" name="observacion" placeholder="Observaciones"></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <input id="submit" name="submit" type="submit" value="Guardar" class="btn btn-primary">
                </div>
            </div>
        </div>
        </div>

    </form>

    @include('p2i.common.footer')
@endsection

@extends('angular')
@section('ng','app')
@section('title','IPC - Registrar nueva certificacion')
@section('body')
    @include('ipc.common.header')
    @include('ipc.common.bread',['bread'=>['Certificacion','Nueva certificacion']])

    <form class="form-horizontal" role="form" method="post" action="{{ url('ipc/certificacion') }}" ng-controller="ipcController" ng-init="profile_search_path = '{{ url('profile/search') }}'">
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <h3>Nueva certificacion</h3>
            </div>
        </div>


        <!-- will be used to show any messages -->
        @if (Session::has('message'))
            <div class="form-group" id="message">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                </div>
            </div>
        @endif

                <script>
                    $(function() {
                        var el = $('#message');

                        $('#profile_search').focus(function() {
                            el.hide();
                        });
                    });
                </script>

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


        <div class="form-group" ng-hide="searchPerfilFlag">
            <div class="col-sm-4 col-sm-offset-1">
                <input ng-required="true"  type="text" class="form-control" placeholder="Ingresar nombre y apellido" ng-model="profile_search" id="profile_search">
            </div>
            <div class="col-sm-4">
                <input type="button" value="Buscar" class="btn btn-primary" ng-click="searchPerfil();">
            </div>
        </div>

         <div class="form-group" ng-show="searchPerfilFlag">
            <div class="col-sm-4 col-sm-offset-1">
                <select name="id_perfil" class="form-control">
                    <option ng-repeat="item in autocompleteSearch" value="@{{ item.id }}">@{{ item.nombre }}, @{{ item.apellido }}</option>
                </select>
            </div>
            <div class="col-sm-4">
                <input type="button" value="Cambiar busqueda" class="btn btn-default" ng-click="profile_search='';searchPerfilFlag=false;autocompleteSearch = [];">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <select ng-required="true"  class="form-control" name="id_norma">
                    <option value="" selected="selected">- Seleccionar norma -</option>
                    @foreach(\IAServer\Http\Controllers\Ipc\Model\Norma::all() as $value)
                        <option value="{{ $value->id_norma }}">{{ $value->norma }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input ng-required="true"  type="text" class="form-control" name="certificado" placeholder="Codigo de certificado" value="{{  Input::old('certificado')  }}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2 col-sm-offset-1" ng-controller="datapickerController">
                <input type="text" name="fecha_alta" placeholder="Fecha de alta" class="form-control" ng-model="date_session" datepicker-popup="dd-MM-yyyy" is-open="datepickerOpened" ng-required="true" show-button-bar="false" ng-click="open($event)" ng-change="dateChanged(date_session)"/>
            </div>
            <div class="col-sm-2">
                <h4>Caducacion: @{{ fecha_caducidad }}</h4>
            </div>
        </div>


            <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                @if(Auth::user()->hasRole('admin'))
                    <select class="form-control" name="id_instructor">
                        <option value="" selected="selected">- Seleccionar instructor -</option>
                        @foreach($instructores as $instructor)
                            <option value="{{ $instructor->id }}">{{ $instructor->profile->fullname() }}</option>
                        @endforeach
                    </select>
                @else
                    <h4>Instructor</h4>
                    <input type="text" disabled class="form-control" value="{{ Auth::user()->profile->fullname() }}">
                    <input type="hidden" class="form-control" name="id_instructor" value="{{  Auth::user()->id }}">
                @endif
            </div>
        </div>

        <div class="form-group" ng-if="autocompleteSearch.length > 0">
            <div class="col-sm-4 col-sm-offset-1">
                <input id="submit" name="submit" type="submit" value="Guardar" class="btn btn-primary">
            </div>
        </div>
    </form>

    @include('ipc.common.footer')
@endsection

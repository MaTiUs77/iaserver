@extends('angular')
@section('ng','app')
@section('title','Aoicollector - Route OP')
@section('body')

    <nav class="navbar navbar-default" style="padding-bottom:5px;margin-bottom:1px;" role="navigation">
        <div class="navbar-form">

            <div class="navbar-left">
                <form actio="get" action="?">
                    <table><tbody><tr>
                            <td>
                                <h4> &nbsp;&nbsp; Configurar OP: </h4>
                            </td>
                            <td>
                                <input type="text" class="form-control" placeholder="Ingrese OP" ng-model="$parent.choosedOp" name="op" />
                            </td>
                            <td>
                                &nbsp;<button ng-show="$parent.choosedOp" type="submit" class="btn btn-success">Aceptar</button>
                            </td>
                        </tr></tbody></table>
                </form>
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
    <div class="container">
        <form class="form-horizontal" role="form" method="post" action="{{ url('aoicollector/prod/routeop') }}">
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <h3>Formulario de creacion de rutas</h3>
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
                        <input type="text" class="form-control" name="puesto" placeholder="Puesto ej: SMT" value="{{  Input::old('puesto')  }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-1">
                        <input type="text"  class="form-control"  name="op" placeholder="op" value="{{  ($op!="") ? $op : Input::old('op') }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-1">
                        <input type="text" class="form-control" name="op_puesto_anterior" placeholder="OP Puesto anterior" value="{{  Input::old('op_puesto_anterior')  }}">
                    </div>
                </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-1">
                            <select class="form-control" name="declara">
                                <option value="" selected="selected">- Declara-</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-1">
                            <input id="submit" name="submit" type="submit" value="Guardar" class="btn btn-primary">
                        </div>
                    </div>
        </form>


    </div>

    @include('aoicollector.prod.partial.footer')
@endsection

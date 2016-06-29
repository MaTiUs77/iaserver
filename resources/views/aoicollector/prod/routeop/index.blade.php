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
        @if(isset($op))
            <h3>{{ $op }}</h3>
            @if(count($routeop)>0)
                <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr style="text-align: center;">
                    <th></th>
                    <th>OP</th>
                    <th>Puesto</th>
                    <th>Declara</th>
                    <th>OP Puesto anterior</th>
                </tr>
                </thead>
                <tbody>
                @foreach($routeop as $route)
                    <tr style="text-align: center;">
                        <td>
                            {!! IABtnDelete(route('aoicollector.prod.routeop.destroy',$route->id),'btn-xs btn-danger') !!}
                        </td>
                        <td>{{ $route->op }}</td>
                        <td>{{ $route->name }}</td>
                        <td>{{ $route->declare }}</td>
                        <td>{{ $route->parent_op }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif

            <a href="{{ url('aoicollector/prod/routeop/create') }}?op={{$op}}" class="btn btn-default">Crear rutas</a>
        @endif


    </div>

@include('aoicollector.prod.partial.footer')
@endsection


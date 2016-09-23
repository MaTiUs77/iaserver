@extends('adminlte/theme')
@section('ng','app')
@section('title','Editar usuario')
@section('body')
    @include('iaserver.abm.partial.header')

    <form class="form-horizontal" role="form" method="POST" action="{{ route('abm.update',$user->id) }}">
        <input name="_method" type="hidden" value="PATCH" class="">

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <h3>Editar usuario</h3>
            </div>
        </div>

        <!-- ERROR -->
        <div class="form-group">
            @if (Session::has('errors'))
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
            @endif
        </div>
        <!-- FIN -->

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="{{ $user->profile ? $user->profile->nombre : ''}}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="text" class="form-control" name="apellido" placeholder="Apellido" value="{{ $user->profile ? $user->profile->apellido : ''}}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="text" class="form-control" name="name" placeholder="Usuario, ej: mflores" value="{{  $user->name  }}" disabled="disabled">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="password" class="form-control" name="password" placeholder="Clave" value="">
            </div>
        </div>

        <div class="form-group">

            <div class="col-sm-4 col-sm-offset-1">
                <select class="form-control" name="permiso">
                    <option value="" selected="selected">- Asignar permisos -</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}">{{ $rol->display_name }}</option>
                    @endforeach
                </select>

                <hr>
                <h4>Asignados</h4>
                @foreach($user->roles()->get() as $rol)
                    <button class="btn btn-xs btn-default">{{ $rol->display_name }}</button>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input id="submit" name="submit" type="submit" value="Actualizar" class="btn btn-primary">
            </div>
        </div>
    </form>

    @include('p2i.common.footer')
@endsection

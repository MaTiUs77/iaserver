@extends('adminlte/theme')
@section('ng','app')
@section('mini',true)
@section('title','Administracion - Crear usuario')
@section('body')
    @include('iaserver.abm.partial.header')

    <form class="form-horizontal" role="form" method="post" action="{{ route('abm.store') }}">
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <h3>Crear usuario</h3>
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
                <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="{{  Input::old('nombre')  }}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="text" class="form-control" name="apellido" placeholder="Apellido" value="{{  Input::old('apellido')  }}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="text" class="form-control" name="name"  autocomplete="off" placeholder="Usuario, ej: mflores" value="{{  Input::old('name')  }}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="password" class="form-control" name="password" placeholder="Clave"  autocomplete="off"  value="">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <h4>Asignar permisos</h4>
                <select class="s2_permiso form-control" multiple="multiple" name="permiso[]">
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}">{{ $rol->display_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input id="submit" name="submit" type="submit" value="Guardar" class="btn btn-success">
            </div>
        </div>
    </form>

    @include('iaserver.abm.partial.footer')
@endsection

@section('footer')
    <script>
        $(function(){
            $(".s2_permiso").select2({
                tags: true,
                placeholder: "Ingresar permisos"
            })
        });
    </script>
@endsection

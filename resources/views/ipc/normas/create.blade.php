@extends('angular')
@section('ng','app')
@section('title','IPC - Registrar nueva persona')
@section('body')
    @include('ipc.common.header')
    @include('ipc.common.bread',['bread'=>['Personas','Nueva personas']])

    <form class="form-horizontal" role="form" method="post" action="{{ url('ipc/personas') }}">
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <h3>Nueva persona</h3>
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
                <input type="text" class="form-control" name="legajo" placeholder="Legajo" value="{{  Input::old('legajo')  }}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <select class="form-control" name="id_norma">
                    <option value="" selected="selected">- Seleccionar sector -</option>
                    @foreach(\IAServer\Http\Controllers\Ipc\Model\Sector::all() as $value)
                        <option value="{{ $value->id_sector }}">{{ $value->sector }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <select class="form-control" name="id_norma">
                    <option value="" selected="selected">- Seleccionar categoria -</option>
                    @foreach(\IAServer\Http\Controllers\Ipc\Model\Categoria::all() as $value)
                        <option value="{{ $value->id_categoria }}">{{ $value->categoria }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input id="submit" name="submit" type="submit" value="Guardar" class="btn btn-primary">
            </div>
        </div>
    </form>

    @include('ipc.common.footer')
@endsection

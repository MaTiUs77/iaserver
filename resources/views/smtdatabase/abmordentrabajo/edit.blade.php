@extends('adminlte/theme')
@section('ng','app')
@section('title','Editar Orden de trabajo')
@section('body')
    @include('smtdatabase.partial.header')

<div class="container">
    <form class="form-horizontal" role="form" method="POST" action="{{ route('smtdatabase.abmordentrabajo.update',$orden->id) }}">
        <input name="_method" type="hidden" value="PATCH" class="">

        <h3>Editar Orden de Trabajo</h3>

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

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Op</th>
                <th>Modelo</th>
                <th>Panel</th>
                <th>Lote</th>
                <th>Qty</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input type="text" class="form-control" name="op" placeholder="OP" value="{{ $orden->op }}"></td>
                <td><input type="text" class="form-control" name="modelo" placeholder="Modelo" value="{{ $orden->modelo }}"></td>
                <td><input type="text" class="form-control" name="panel" placeholder="Panel" value="{{ $orden->panel }}"></td>
                <td><input type="text" class="form-control" name="lote" placeholder="Lote" value="{{ $orden->lote }}"></td>
                <td><input type="text" class="form-control" name="qty" placeholder="Cantidad producida" value="{{ $orden->qty }}"></td>
                <td>
                    <button id="submit" name="submit" type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-lg-3">
                <a href="{{ route('smtdatabase.abmordentrabajo.index') }}" class="btn btn-danger">
                    <i class="fa fa-close"></i> Cancelar
                </a>
            </div>
        </div>


    </form>
</div>

    @include('iaserver.common.footer')
@endsection
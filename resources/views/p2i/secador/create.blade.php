@extends('angular')
@section('ng','app')
@section('title','P2i - Nuevo Registro de Secador')
@section('body')
    @include('p2i.common.header')
    @include('p2i.common.bread',['bread'=>['Secador','Nuevo registro']])

    <form class="form-horizontal"  style="margin:20px;"  role="form" method="post" action="{{ url('p2i/secador') }}">
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <h3>Registro de Secador</h3>
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
                <select class="form-control" name="secador">
                    <option value="" selected="selected">- Seleccionar secador -</option>
                    @for ($i = 1; $i <= $cantidad_secadores; $i++)
                        <option value="{{ $i }}">Secador {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="text" class="form-control" name="conjunto_jigs" placeholder="Conjunto de Jigs" value="{{  Input::old('conjunto_jigs')  }}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <select class="form-control" name="id_modelo">
                    <option value="" selected="selected">- Seleccionar modelo -</option>
                    @foreach($modelos as $item)
                        <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @include('p2i.common.checkbox',[
            'name'      => 'jigs_cargados',
            'name_desc' => 'Jigs Cargados Correctamente'
        ])

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
    </form>

    @include('p2i.common.footer')
@endsection

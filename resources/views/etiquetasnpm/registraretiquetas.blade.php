@extends('etiquetasnpm.index')
@section('body')
    <form action="{{url('/etiquetasnpm/imprimir')}}" method="POST">
        <div class="form-group">
            <label for="SN">SERIAL FEEDER</label>
            <input class="form-control" id="SN" type="text" name="sn" autofocus autocomplete="off" required><br>
            <input class="btn btn-info" type="submit" value="Aceptar">
        </div>
    </form>
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <div class="alert alert-info">{{ \Illuminate\Support\Facades\Session::get('message') }}</div>
    @endif

@endsection
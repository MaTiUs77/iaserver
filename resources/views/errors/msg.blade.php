@extends('angular')
@section('title','Atencion')
@section('body')

    <div class="container">
        @if (Session::has('errors'))
            <div class="alert alert-warning" role="alert">
                <ul>
                    <strong>Oops! algo salio mal: </strong>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Atencion</div>
                    <div class="panel-body">
                        {{ $error }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
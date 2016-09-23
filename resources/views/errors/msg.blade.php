@extends('adminlte/theme')
@section('title','Atencion')
@section('body')


    <div class="error-page">
        @if(isset($codigo))
            <h2 class="headline text-red">{{ $codigo }}</h2>
        @endif

        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Atencion @if(isset($codigo)) / Codigo: {{ $codigo }} @endif</h3>
            <p>
                {{ $error }}
            </p>

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
    </div>
@endsection
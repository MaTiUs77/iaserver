@extends('adminlte/theme')
@section('title',"Error")
@section('body')

    <div class="error-page">
        @if(isset($codigo))
            <h2 class="headline text-red">{{ $codigo }}</h2>
        @endif

        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Opss! algo salio mal!</h3>
            <p>
               {{ $mensaje }}
            </p>
        </div>
    </div>

    @if(isset($reload))
        <script>
            setTimeout("window.location.reload();", (60 * 1000) * {{ $reload }} );
        </script>
    @endif
@endsection
@extends('adminlte/theme')
@section('title','AMR - Pedido de Materiales')
@section('mini',false)
@section('collapse',false)
@section('body')
<div class="container-fluid">
    @if ($ruta =='perfiles')
        <div class="text-center">
            <h2 style="color:red;">Los Perfiles deben realizarse una vez por semana, o en un lanzamiento</h2>
            <h4>A continuación se encuentra la ruta de la carpeta donde estan alojados los Perfiles</h4>
        </div>
    <div class="text-center">
        <h4><div class="alert-info">\\USH-NT-3\V1\Users\MANT\SOLDADORAS P3-4\PERFILES</div></h4>
    </div>
        <script type="text/javascript">

        </script>
    @else
        <div class="text-center">
            <h4>A continuación se encuentra la ruta de la carpeta donde estan alojados los Analisis Químicos de los muestreos de estaño</h4>
            <h4><div class="alert-info">\\USH-NT-3\V1\Users\MANT\SOLDADORAS P3-4\ANALISIS QUIMICO</div></h4>
        </div>

    @endif
</div>

{{--<!-- Angular Translate -->--}}
{{--{!! IAScript('assets/angular-translate/angular-translate.min.js') !!}--}}
{{--<!-- BootSwatch -->--}}
{{--{!! IAStyle('assets/bootswatch/paper/bootstrap.min.css') !!}--}}
{{-- Angular File Manager --}}
{{--{!! IAStyle('assets/angular-filemanager/dist/angular-filemanager.min.css') !!}--}}
{{--{!! IAScript('assets/angular-filemanager/dist/angular-filemanager.min.js') !!}--}}

{{--{!! IAScript('vendor/ovenlog/app.js') !!}--}}
@stop

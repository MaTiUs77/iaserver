@extends('adminlte/theme')
@section('title','AMR - Pedido de Materiales')
@section('mini',false)
@section('collapse',false)
@section('menuaside')
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ url('amr/recupero/') }}">RECUPERAR</a></li>
                <li><a href="{{ url('amr/recupero/reporte') }}">REPORTE</a></li>
            </ul>
        </section>
    </aside>

@endsection
@section('body')
    @if(hasRole('smtdatabase_operator') || isAdmin())
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}">
    @else

    @endif
<div class="container-fluid">
    <h2>Recuperación de Materiales <small>- Línea <strong>{{$linea}}</strong> -</small></h2>
    <div class="well well-md col-lg-7">
        <form method="POST" action="{{url('amr/recupero/find')}}">
            <div class="col-lg-4">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1"><span class="fa fa-recycle"></span></span>
                        <input type="text" id="buscar" name="buscar" class="form-control" placeholder="LPN de Material" aria-describedby="basic-addon1" style="text-transform: uppercase;" pattern=".{10,}" required>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">QTY</span>
                        <input type="number" id="qty" name="qty" class="form-control" max="10" min="1" aria-describedby="basic-addon1" style="text-transform: uppercase;" value="1">
                </div>
            </div>
            @if(hasRole('smtdatabase_operator') || isAdmin())
                <button type="submit" class="btn btn-primary">Consultar</button>
            @endif
        </form>
    </div>
    @if (!empty($resultado))
        @if ($resultado->pluck('existe')->first() === true)
            <div class="row col-lg-7">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        LPN: <strong>{{$resultado->pluck('itemId')->first()}}<br></strong>
                        Part Number: <strong>{{$resultado->pluck('partNumber')->first()}}<br></strong>
                        Cantidad antes de Recupero: <strong>{{$resultado->pluck('quantity')->first()}}</strong><br>
                        Cantidad Recuperada: <strong>{{$resultado->pluck('cantidadRecuperada')->first()}}</strong><br>
                        Cantidad Total: <strong>{{$resultado->pluck('quantity')->first() + $resultado->pluck('cantidadRecuperada')->first()}}</strong><br>
                    </div>
                    <div class="panel-body">
                        Contenido en: <strong>{{$resultado->pluck('containerId')->first()}}</strong><br>
                        Ubicación en Máquina: <strong>{{$resultado->pluck('locationInTool')->first()}}</strong><br>
                    </div>
                    <div class="panel-footer">
                        <form method="POST" action="{{url('amr/recupero/recuperar/')}}">
                            <input type="hidden" name="resultado" value="{{$resultado}}"/>
                            <button type="submit" class="btn btn-success btn-sm col-lg-offset-10">Recuperar</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            @if(hasRole('smtdatabase_operator') || isAdmin())
                <div class="row col-lg-12">
                    <div class="alert alert-danger">
                        <strong>Atención!</strong> Elemento <strong>{{$resultado->pluck('itemId')->first()}}</strong> no existe en <strong>Cogiscan</strong>.
                    </div>
                </div>
            @endif
        @endif
    @endif
    @if (Session::has('operacion'))
        <div class="row col-lg-12">
            <div class="alert alert-success">
                Materiales recuperados para LPN <strong>{{Session::get('operacion')}}</strong> Exitosamente.
            </div>
        </div>
    @endif
</div>
@stop

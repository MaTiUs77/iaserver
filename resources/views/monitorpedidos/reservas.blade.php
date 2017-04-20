@extends('monitorpedidos.index')
@section('body')
    {{--FORMULARIO DE BUSQUEDA--}}
    <div  class="container-fluid">

        <div class="row">
            <div class="col-lg-4" align="center">
                <form class="navbar-form navbar-left" role="search" method="GET" action="{{url ('amr/parciales/almacen')}}">

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="PART_NUMBER" name="valor" required="true">
                    </div>

                    <button type="submit" class="btn btn-info"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Buscar por linea
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="{{url('amr/parciales/almacen')}}">Todas las lineas</a></li>
                        <li><a href="{{url('amr/parciales/almacen/SMT - 2')}}">SMT - 2</a></li>
                        <li><a href="{{url('amr/parciales/almacen/SMT - 3')}}">SMT - 3</a></li>
                        <li><a href="{{url('amr/parciales/almacen/SMT - 4')}}">SMT - 4</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <label>TOTAL DE REGISTROS:@if($reserva->count() != 0)
                        {{ $reserva->count()}}</label>
                @else <label class="alert-danger">SIN RESULTADOS</label>
                @endif

            </div>
        </div>
        <div class="row">
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
        </div>
        <div class="container table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>NRO_OP</th>
                <th>LINEA</th>
                <th>MAQUINA</th>
                <th>FEEDER</th>
                <th>PARTNUMBER</th>
                <th>LPN</th>
                <th>UBICACION</th>
                <th>TIEMPO</th>
                <th>ACCIONES</th>
            </tr>
            </thead>
            <tbody>
            {{--{{dd($reserva)}}--}}
            @foreach($reserva as $r)
                <tr>
                    <td><a class="btn btn-primary">{{$r->op}}</a></td>

                    <td>{{$r->linea}}</td>

                    <td>{{$r->maquina}}</td>

                    <td>{{$r->feeder}}</td>

                    <td>{{$r->pn}}</td>

                    <td>{{$r->lpn}}</td>

                    <td>{{$r->ubicacion}}</td>

                    <td>-</td>

                    <td><form method="post" action="{{url('amr/entregar/'.$r->id)}}"><button class="btn btn-success btn-xs btn-detail" name="entregar">ENTREGAR</button></form>
                        {{--<form method="post" action="{{url('amr/cancelar/'.$r->id)}}"><button class="btn btn-danger btn-xs btn-detail" name="cancelar">CANCELAR</button></form>--}}
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
    {!! IAScript('vendor/monitorpedidos/app.js') !!}
    {!! IAScript('vendor/monitorpedidos/request.js') !!}


    <script>

    </script>
@endsection

@extends('monitorpiso.layouts.master')
@section('body')
<div class="container">
    <h2>Almacen IA</h2>
    <p>Materiales en Almacén IA: <strong>{{$total->count()}}</strong></p>
        <div class="col-lg-12">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Ordernar por <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{URL('/amr/almacenia/field1')}}">Item Id</a></li>
                    <li><a href="{{URL('/amr/almacenia/field2')}}">Part Number</a></li>
                    <li><a href="{{URL('/amr/almacenia/field3')}}">Cantidad</a></li>
                    <li><a href="{{URL('/amr/almacenia/field5')}}">Ubicación</a></li>
                    <li><a href="{{URL('/amr/almacenia/field6')}}">Fecha Creación</a></li>
                    <li><a href="{{URL('/amr/almacenia/field7')}}">Fecha Ultima Carga</a></li>
                    <li><a href="{{URL('/amr/almacenia/field9')}}">Usuario de Carga</a></li>
                </ul>
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-search"></span></span>
                        <form method="POST" action="{{url('amr/')}}">
                            <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Part Number" aria-describedby="basic-addon1" style="text-transform: uppercase;">
                            {{--<button type="submit" class="btn btn-primary">Buscar</button>--}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                        {{--<span class="input-group-btn">--}}

                        {{--</span>--}}
                    </div>
                </div>
                <div class="col-lg-2">
                    <a href="{{url('/amr/almacenia/limit')}}"><button type="button" class="btn btn-info">Paginar(100)</button></a>
                </div>
                <div class="col-lg-2">
                    <a href="{{url('/amr/almacenia/all')}}"><button type="button" class="btn btn-warning">Mostrar Todos</button></a>
                </div>
                <div class="col-lg-offset-10">
                    <a href="{{url('/amr/excel/almacen/')}}"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-save-file"></span>Exportar a Excel</button></a>
                </div>
            </div>


        </div>
    @IF ($paginar){!! $items->render() !!}
    @ENDIF <!-- Paginación -->
    <div class="">
    <table class="table table-striped sortable">
        <thead>
        <tr>
            <th data-sortable="true">Item Id</th>
            <th data-sortable="true">Part Number</th>
            <th data-sortable="true">Cantidad</th>
            <th data-sortable="true">Ubicación</th>
            <th data-sortable="true">Fecha Creación</th>
            <th data-sortable="true">Fecha Ultima Carga</th>
            <th data-sortable="true">Usuario de Carga</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{$item->field1}}</td>
            <td>{{$item->field2}}</td>
            <td>{{$item->field3}}</td>
            <td>{{$item->field5}}</td>
            <td>{{$item->field6}}</td>
            <td>{{$item->field7}}</td>
            <td>{{$item->field9}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    @IF ($paginar){!! $items->render() !!}
    @ENDIF <!-- Paginación -->
</div>
@endsection

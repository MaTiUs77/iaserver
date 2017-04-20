@extends('monitorpiso.layouts.master')
@section('body')
<div class="container">
    <h2>Almacen IA</h2>
    <p>Materiales en Almacén IA: <strong>{{$total}}</strong></p>
        <div class="col-lg-12">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Ordernar por <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{URL('/amr/almacenia/ITEM_ID')}}">Item Id</a></li>
                    <li><a href="{{URL('/amr/almacenia/PART_NUMBER')}}">Part Number</a></li>
                    <li><a href="{{URL('/amr/almacenia/QUANTITY')}}">Cantidad</a></li>
                    <li><a href="{{URL('/amr/almacenia/LOCATION_IN_CNTR')}}">Ubicación</a></li>
                    <li><a href="{{URL('/amr/almacenia/INIT_TMST')}}">Fecha Creación</a></li>
                    <li><a href="{{URL('/amr/almacenia/LAST_LOAD_TMST')}}">Fecha Ultima Carga</a></li>
                    <li><a href="{{URL('/amr/almacenia/LOAD_USER_ID')}}">Usuario de Carga</a></li>
                </ul>
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-search"></span></span>
                        <form method="POST" action="{{URL('/amr/almacenia/find')}}">
                            <input type="text" id="buscar" name="buscar" class="form-control" placeholder="Part Number" aria-describedby="basic-addon1" style="text-transform: uppercase;">
                            {{--<button type="submit" class="btn btn-primary">Buscar</button>--}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                        {{--<span class="input-group-btn">--}}

                        {{--</span>--}}
                    </div>
                </div>
                {{--<div class="col-lg-2">--}}
                    {{--<a href="{{url('/amr/almacenia/limit')}}"><button type="button" class="btn btn-info">Mostrar 200</button></a>--}}
                {{--</div>--}}
                <div class="col-lg-2">
                    <a href="{{url('/amr/almacenia/all')}}"><button type="button" class="btn btn-warning">Mostrar Todos</button></a>
                </div>
                <div class="col-lg-offset-10">
                    <a href="{{url('/amr/excel/almacen/')}}"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-save-file"></span>Exportar a Excel</button></a>
                </div>
            </div>


        </div>
    @if(($paginar) && ($items instanceof \Illuminate\Pagination\LengthAwarePaginator))
                {!! $items->links() !!}
        @endif
        {{--@IF ($paginar){!!$items->render() !!}--}}
    {{--@ENDIF <!-- Paginación -->--}}
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
            <td>{{$item->ITEM_ID}}</td>
            <td>{{$item->PART_NUMBER}}</td>
            <td>{{(int)$item->QUANTITY}}</td>
            <td>{{$item->LOCATION_IN_CNTR}}</td>
            <td>{{\Carbon\Carbon::parse($item->INIT_TMST)->toDateTimeString() }}</td>
            <td>{{\Carbon\Carbon::parse($item->LAST_LOAD_TMST)->toDateTimeString()}}</td>
            <td>{{$item->LOAD_USER_ID}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    @IF ($paginar){!! $items->render() !!}
    @ENDIF <!-- Paginación -->
</div>
@endsection

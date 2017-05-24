@extends('inventario.index')
@section('ng','app')
@section('body')
    @if(hasRole('smtdatabase_operator') || isAdmin())
    <h2>Reporte de Impresiones</h2>
    <div  ng-controller="reportController">

        <div class="col-lg-12">
            <div class="btn-group col-lg-12">
                <div class="col-lg-12">
                    <form method="get" action="{{url('inventario/excel')}}" class="navbar-form navbar-left">
                        <div class="form-group">
                        <select class="form-control" name="plantas">
                            <option value="all">Seleccionar Todo</option>
                            @foreach($pl as $planta)
                            <option value="{{$planta->id_planta}}">{{$planta->descripcion}}</option>
                            @endforeach
                        </select>
                        <input type="text" name="pizarra_fecha" value="{{ Session::get('pizarra_fecha') }}" placeholder="Seleccionar fecha" class="form-control fulldatarangepicker"/>

                                <div class="pull-right">
                                <button type="submit" class="btn btn-success"><span class="fa fa-file-excel-o"></span> Exportar a Excel</button>
                                </div>

                        </div>
                    </form>
                </div>
                @endif
                <!-- AngularJS Application Scripts -->
                {!! IAScript('vendor/iaserver/iaserver.js') !!}
                {!! IAScript('vendor/inventario/consulta/consulta.factory.js') !!}
                {!! IAScript('vendor/inventario/consulta/reportes.controller.js') !!}

            </div>
        </div>

    </div>

@endsection


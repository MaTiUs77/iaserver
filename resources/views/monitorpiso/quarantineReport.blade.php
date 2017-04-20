@extends('monitorpiso.layouts.master')
@section('body')
<div class="container">
    <h2><span class="glyphicon glyphicon-alert"></span> Cuarentena <span class="glyphicon glyphicon-alert"></span></h2>
    <p>Materiales con estado de Cuarentena <u>ACTIVO</u>: <strong>{{$active->count()}}</strong></p>
    <div class="col-lg-12">
            <div class="col-lg-2">
                <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#filtrar">Filtrar Fecha</button>
            </div>
            <div class="col-lg-2">
                <a href="{{url('/cuarentena')}}"><button type="button" class="btn btn-info">Quitar Filtro</button></a>
            </div>
            <div class="col-lg-offset-10">
                <a href="{{url('/excel/cuarentena')}}"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-save-file"></span>Exportar a Excel</button></a>
            </div>
        <div id="filtrar" class="collapse">
            <div class="form-group">
                <form method="POST" action="{{url('cuarentena/filter')}}">
                    <div class="panel col-lg-12">
                        <div class="form-group">
                            <div class="panel-body col-lg-3">
                                <label for="desde">Desde</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="desde">
                                </div>
                            </div>
                            <div class="panel-body col-lg-3">
                                <label for="desde">Hasta</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                    <input type="text" class="form-control datepicker" name="hasta">
                                </div>
                            </div>
                            <div class="panel-body col-lg-1">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                            <div class="panel-body col-lg-2">
                                <form role="form">
                                    <div class="radio">
                                        <label><input type="radio" id ="rd1" value="field6" name="radio[0]">Fecha Creación</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" id ="rd2" value="field7" name="radio[0]">Fecha Desbloqueo</label>
                                    </div>
                                </form>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{--{!! $cuarentena->render() !!} <!-- Paginación -->--}}
    <div class="">
    <table class="table table-striped sortable text-center">
        <thead>
        <tr>
            <th class="text-center">Item Id</th>
            <th class="text-center">Part Number</th>
            <th class="text-center">Motivo</th>
            <th class="text-center">Fecha Creación</th>
            <th class="text-center">Usuario de Carga</th>
            <th class="text-center">Fecha de Desbloqueo</th>
            <th class="text-center">Usuario de Desbloqueo</th>
            <th class="text-center">Bloqueado</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cuarentena as $q)
        <tr>
            <td>{{$q->field1}}</td>
            <td>{{$q->field2}}</td>
            <td>{{$q->field4}}</td>
            <td>{{$q->field6}}</td>
            <td>{{$q->field9}}</td>
            <td>{{$q->field7}}</td>
            <td>{{$q->field10}}</td>
            <td>{{$q->field8}}</td>

        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    {!! $cuarentena->render() !!} <!-- Paginación -->
</div>
@stop

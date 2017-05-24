@extends('adminlte/theme')
@section('ng','app')
@section('mini',true)
@section('nobox',true)
@section('title','Aoicollector - Crear cuarentena')
@section('body')

    <div class="container" ng-controller="createCuarentenaController">

        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

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

        <h3>Creacion de cuarentena</h3>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Por barcode</a></li>
               {{-- <li><a href="#tab_2" data-toggle="tab">Por rango horario</a></li>--}}
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <form method="POST" action="{{ route('aoicollector.cuarentena.agregar.multiple') }}">
                        <div class="row">

                            <div class="col-md-4 ">
                                <textarea style="height:100px;" class="form-control input-lg" name="motivo" placeholder="Ingresar motivo de cuarentena"></textarea>
                            </div>
                            <div class="col-md-8 ">
                                <textarea style="height:100px;" class="form-control input-lg" name="agregarmultiple" placeholder="Ingresar codigos de Stocker o Placas"></textarea>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-success">Crear cuarentena</button>
                    </form>
                </div>
                <!-- /.tab-pane -->
                {{--<div class="tab-pane" id="tab_2">
                    <form method="POST" action="{{ route('aoicollector.cuarentena.agregar.multiple') }}">
                        <div class="row">

                            <div class="col-md-4 ">
                                <textarea style="height:100px;" class="form-control input-lg" name="motivo" placeholder="Ingresar motivo de cuarentena"></textarea>
                            </div>
                            <div class="col-md-8 ">

                                <div class="row">
                                    <div>

                                    </div>

                                </div>
                                <select class="form-control" name="regex">
                                    <option value="">- Seleccionar AOI -</option>
                                    <option value="">
                                        SMD-16 LR
                                    </option>
                                    <option value="">
                                        SMD-16 RL
                                    </option>
                                </select>

                                <input type="text" name="cuarentena_date_session" value="{{ Session::get('cuarentena_date_session') }}" placeholder="Seleccionar fecha" class="form-control cuarentenapicker"/>

                            </div>

                        </div>
                        <br>
                        <button type="submit" class="btn btn-success">Crear cuarentena</button>
                    </form>
                </div>--}}
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
    </div>

    @include('iaserver.common.footer')

    <script>
        $(document).ready(function(){
            // Datapicker con rango de fechas
            $('input.cuarentenapicker').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY H:mm',
                    customRangeLabel: 'Definir rango',
                    applyLabel: 'Aceptar',
                    cancelLabel: 'Cancelar'
                },
                autoApply: true,
                timePicker: true,
                timePickerIncrement: 5
            });
        });
    </script>


    <script>
        app.controller('createCuarentenaController',function($scope,$http){

        });
    </script>
@endsection

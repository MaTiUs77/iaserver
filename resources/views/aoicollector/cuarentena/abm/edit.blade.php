@extends('adminlte/theme')
@section('ng','app')
@section('mini',true)
@section('nobox',true)
@section('title','Aoicollector - Editar cuarentena')
@section('head')
    <style>
        .datatable tbody tr td {
            text-align: center;
        }

        .datatable thead th {
            background-color: #2D6CA2;
            color: white;
            text-align: center;
        }

    </style>
@endsection
@section('body')

    <div class="container" ng-controller="editCuarentenaController">

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

        <h3>Edicion de cuarentena</h3>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_info" data-toggle="tab">Informacion</a></li>
                <li><a href="#tab_detalle" data-toggle="tab">Detalle</a></li>
                <li><a href="#tab_agregar" data-toggle="tab">Adjuntar cuarentena</a></li>
                <li><a href="#tab_liberar" data-toggle="tab">Liberacion multiple</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_info">
                    <div class="row">
                        <div class="col-sm-3">
                            <input name="_method" type="hidden" value="PATCH" class="">
                            <blockquote>
                                <small>Creador</small>
                                {{ $cuarentena->joinUser->name }}

                                <small>Fecha de cuarentena</small>
                                {{ $cuarentena->created_at->format('d/m/Y') }}

                                <small>Placas comprometidas</small>
                                {{ $cuarentena->countTotal() }}

                                <small>Placas en cuarentena</small>
                                {{ $cuarentena->countCuarentena() }}

                                <small>Placas liberadas</small>
                                {{ $cuarentena->countReleased() }}

                                <small>
                                    Motivo
                                    <a href="javascript:;" class="btn btn-xs btn-default" ng-click="editarMotivo=!editarMotivo"><i class="fa fa-edit"></i> Editar</a>
                                </small>
                                <span ng-hide="editarMotivo">{{ $cuarentena->motivo }}</span>

                                <form ng-show="editarMotivo" role="form" method="POST" action="{{ route('aoicollector.cuarentena.update',$cuarentena->id) }}">
                                    <textarea id="inputMotivo" style="height:100px;" class="form-control  input-lg" name="motivo" placeholder="Redactar motivo de la cuarentena" value="{{  Input::old('motivo')  }}">{{ $cuarentena->motivo }}</textarea>
                                    <br>
                                    <button type="submit" class="btn btn-success  pull-right">Actualizar</button>
                                </form>
                            </blockquote>
                        </div>
                        <div class="col-sm-9">
                            @foreach($opList as $op => $detail)

                                <div class="col-sm-4">
                                    <div class="box box-{{ ($detail->cuarentena==0) ? 'success' : 'danger' }}">
                                        <div class="box-body">
                                            <h3 class="profile-username text-center">{{ $op }}</h3>

                                            <p class="text-muted text-center">{{ $detail->smt->modelo }} - {{ $detail->smt->panel }} - {{ $detail->smt->lote }}</p>

                                            <ul class="list-group list-group-unbordered">
                                                <li class="list-group-item">
                                                    <b>Comprometidas</b> <a class="pull-right">{{ $detail->total }}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>En cuarentena</b> <a class="pull-right">{{ $detail->cuarentena }}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Liberadas</b> <a class="pull-right">{{ $detail->released }}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    @if($detail->cuarentena==0)
                                                        <a href="" class="btn btn-xs btn-default btn-block" disabled="disabled">Liberada!</a>
                                                    @else
                                                        <a href="" class="btn btn-xs btn-success btn-block">Liberar op</a>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_detalle">
                    @if(isset($detailExpanded) && count($detailExpanded)>0)
                        <table class="table table-bordered table-striped table-hover datatable">
                            <thead>
                            <tr style="text-align: center;">
                                <th>Codigo</th>
                                <th>OP</th>
                                <th>Stocker</th>
                                <th>Ultima ruta</th>
                                <th>Fecha de cuarentena</th>
                                <th>Fecha de alta</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($detailExpanded as $placa)
                                <tr style="text-align: center;" class="{{ (!isset($placa->cuarentena_end_at)) ? 'danger':''}}">
                                    <td>
                                        {{ $placa->barcode }}
                                    </td>
                                    <td>
                                        {{ $placa->inspected_op }}
                                    </td>
                                    <td>
                                        {{ $placa->stocker }}
                                    </td>
                                    <td>
                                        {{ $placa->ultima_ruta }}
                                    </td>
                                    <td>
                                        {{ $placa->cuarentena_ini_at }}
                                    </td>
                                    <td>
                                        {{ $placa->cuarentena_end_at }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_liberar">
                    <form method="POST" action="{{ route('aoicollector.cuarentena.liberar.multiple') }}">
                        <textarea style="height:100px;" class="form-control input-lg" name="liberarmultiple" placeholder="Ingresar codigos de Stocker o Placas a liberar" value="{{  Input::old('liberacionmultiple')  }}"></textarea>
                        <br>
                        <button type="submit" class="btn btn-success">Liberar</button>
                    </form>
                </div>

                <div class="tab-pane" id="tab_agregar">
                    <form method="POST" action="{{ route('aoicollector.cuarentena.agregar.multiple') }}">
                        <textarea style="height:100px;" class="form-control input-lg" name="agregarmultiple" placeholder="Ingresar codigos de Stocker o Placas para adjuntar a la lista" value="{{  Input::old('liberacionmultiple')  }}"></textarea>
                        <input type="text" value="{{ $cuarentena->id}}" style="display:none" name="id_cuarentena">
                        <br>
                        <button type="submit" class="btn btn-success">Adjuntar</button>
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>

        <form method="POST" action="{{ route('aoicollector.cuarentena.destroy',$cuarentena->id) }}">
            <input name="_method" type="hidden" value="DELETE">
            <button type="submit" class="btn btn-danger pull-right"><i class="fa fa-trash"></i> Eliminar cuarentena</button>
        </form>
    </div>


    @include('iaserver.common.footer')

    <script>
        app.controller('editCuarentenaController',function($scope,$http){

        });

        // Javascript to enable link to tab
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
        } //add a suffix

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            //window.location.hash = e.target.hash;

            //window.scrollTo(0, 0);
        })
    </script>
@endsection

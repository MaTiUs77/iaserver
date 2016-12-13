@extends('inventario.index')
@section('body')
<div class="container" ng-controller="LabelCtrl">
    <div class="col col-sm-12 col-sm-offset-3">
        <!--     <header3>-->
        <div class="col-sm-2">
            <input class="form-control" ng-model="buscar" ng-keypress="enterCheck($event)" type="text" placeholder="Buscar Etiqueta">
        </div>
        <button type="button" class="btn btn-info" ng-click="buscarEtiqueta()">Buscar</button>

        <!--     </header3>-->
    </div>
    <div class="col col-sm-12 col-sm-offset-3">
        <ul class="col-sm-6">
            <div class="panel panel-info" ng-hide="plantilla">
                <div style=@{{statusStyle}}><strong>@{{error}}</strong></div>
                <div class="panel-heading">Información de Etiqueta <b>@{{label.lpn}}</b></div>
                <div class="panel-body">
                    Part Number: <b>@{{label.partNumber}}</b><br>
                    Descripcion: <b>@{{label.descripcion}}</b><br>
                    Primer Conteo: <b>@{{label.cantidadContada}}</b><br>
                    Segundo Conteo: <b>@{{label.cantidadSegConteo}}</b><br>
                    Tercer Conteo: <b>@{{label.cantidadTerConteo}}</b><br>
                    <div class="divider"></div>
                    Planta: <b>@{{label.planta}}</b><br>
                    Ubicación: <b>@{{label.zona}} @{{label.descZona}}</b><br>
                    Responsable de Impresion: <b>@{{label.responsable}}</b><br>
                    <div class="divider"></div>
                    <button type="button" class="btn btn-primary" ng-click="togglee()" ng-disabled="isDisabled">Editar</button>
                </div>
            </div>
        </ul>
    </div>
    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="form-group">
        <ul class="panel">
            <div style=@{{statusStyle}}>@{{updLabelStatus}}</div>
            <div class="panel-heading"><h2>Editar Etiqueta N° <b>@{{label.id}}</b></h2></div>
            <div class="panel-body">
                <div class="well well-sm-2">
                    Primer Conteo:
                    <input class="form-control" ng-model="label.cantidadContada" type="text" name="pconteo">
                </div>
                <div class="well well-sm-2">
                    Segundo Conteo:
                    <input class="form-control" ng-model="label.cantidadSegConteo" type="text" name="sconteo">
                </div>
                <div class="well well-sm-2">
                    Tercer Conteo:
                    <input class="form-control" ng-model="label.cantidadTerConteo" type="text" name="tconteo">
                </div>
                <div class="divider"></div>
                <div class="panel-footer">

                    <input type="button" name="Reimprimir" id="" Value="Reimprimir" class="btn btn-info" ng-click="reprint()">
                    <input type="button" name="Aceptar" id="" Value="Actualizar" class="btn btn-primary" ng-click="updLabelQty()">
                </div>
            </div>
        </ul>
            </div>
</div>
        </div>
</div>
    {!! IAScript('vendor/iaserver/iaserver.js') !!}
    {!! IAScript('vendor/inventario/inventario.factory.js') !!}
    {!! IAScript('vendor/inventario/consultar.controller.js') !!}
@endsection


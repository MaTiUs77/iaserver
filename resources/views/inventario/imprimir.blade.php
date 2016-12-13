@extends('inventario.index')
@section('body')
    @if(hasRole(['inventario_operador','smtdatabase_operador']) || isAdmin())
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}">
        <div ng-controller="PrintCtrl">
            <div class="container-fluid">
                <div class="col col-lg-6">
                    <form method="POST" action="{{route('inventario.impresion.imprimir')}}">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Imprimir Etiquetas</div>
                            <div class="panel-body">
                                <div class="col col-sm-10">
                                    <div class="radio">
                                        <input type="radio" name="optradio" ng-model="checked" ng-value="1" ng-click="selectedRtb(1)">Elemento Contabilizado</input>
                                    </div>
                                    <div class="radio">
                                        <input type="radio" name="optradio" ng-model="checked" ng-value="2" ng-click="selectedRtb(2)">Elemento Hibrido
                                    </div>
                                </div>

                                <div class="col col-sm-8 col-sm-offset-2">
                                    <input id="pn" name="pn" class="form-control" ng-model="partNumber" type="text" ng-blur="focusOut($event)" ng-focus="focusIn()" placeholder="Part Number" ng-readonly="isDisabledPN" ruta="{{route('inventario.impresion.getpn')}}">
                                </div>
                                <br><br>
                                <div class="col col-sm-10">
                                    <div  class="col col-sm-12"><b>Desc:</b> @{{material.desPartNumber}}</div>
                                    <div class="col col-sm-12"><b>UDM:</b> @{{material.udm}}</div>
                                    <div class="col col-sm-offset-3" ng-show="notFound">
                                        PartNumber no encontrado.
                                        <button type="button" class="btn btn-warning btn-sm" ng-click="toggle()">Agregar</button>
                                    </div>
                                </div>
                                <br><br>
                                <div class="col col-sm-8 col-sm-offset-2">
                                    <input id="qty" name="qty"class="form-control" ng-model="qty" type="number" min="0" placeholder="Cantidad" ng-disabled="isDisabledQty" ng-change="validateQty()">
                                </div>
                            </div>
                            <div class="panel-footer">
                                {{--<button type="button" name="print" class="btn btn-info " ng-click="toPrint()" ng-disabled="printDisabled">Imprimir</button>--}}
                                <button type="submit" name="print" class="btn btn-info " ng-disabled="printDisabled">Imprimir</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="form-group">
                            <ul class="panel">
                                <div class="panel-heading"><h2>Agregar Codigo</h2></div>
                                <div class="panel-body">
                                    <form method="POST" action="{{url('/inventario/agregar_material')}}">
                                    <div class="well well-sm-2">
                                        Codigo:
                                        <input class="form-control" ng-model="partNumber" type="text" name="partnumber">
                                    </div>
                                    <div class="well well-sm-2">
                                        Descripcion:
                                        <input class="form-control" ng-model="descripcion" type="text" name="descripcion">
                                    </div>
                                    <div class="dropdown">
                                        <div class="well well-sm-2">
                                            UDM:
                                        <select type="text" class="form-control" id="udm" name="udm" placeholder="udm">
                                            <option ng-repeat="unit in udmList" value="@{{ unit.descripcion }}">@{{ unit.descripcion }}</option>
                                        </select>
                                        </div>
                                        {{--<button class="btn btn-default dropdown-toggle btn-sm" type="button" data-toggle="dropdown" ng-disabled="dropDownDisabled">Seleccione UDM--}}
                                            {{--<span class="caret"></span></button>--}}
                                        {{--<ul class="dropdown-menu">--}}
                                            {{--<li ng-repeat="unit in udmList"><a>@{{ unit.descripcion }}</a></li>--}}
                                        {{--</ul>--}}
                                    </div>
                                    <div class="divider"></div>
                                    <div class="panel-footer">
                                        <button type="submit" name="Aceptar" id="" Value="Agregar" class="btn btn-success">Agregar</button>
                                    </div>
                                    </form>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (\Illuminate\Support\Facades\Session::has('message'))
            <div class="alert alert-info"><strong>{{ \Illuminate\Support\Facades\Session::get('message') }}</strong></div>
        @endif
    @else
        <div class="container-fluid">
            <div class="callout callout-warning" style="border-radius: 0;margin:0;">
                <p><b>ATENCION!!!</b> usted debe <strong>Iniciar Sesion</strong> para efectuar cualquier operación</p>
            </div>
        </div>
    @endif
    {!! IAScript('vendor/iaserver/iaserver.js') !!}
    {!! IAScript('vendor/inventario/inventario.factory.js') !!}
    {!! IAScript('vendor/inventario/usuarios/user.factory.js') !!}
    {!! IAScript('vendor/inventario/impresion.controller.js') !!}
@endsection


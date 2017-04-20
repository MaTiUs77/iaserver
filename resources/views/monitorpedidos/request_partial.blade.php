@extends('monitorpedidos.index')
@section('ng','requestRecords')
@section('ng','trazapedido')
@section('body')
    <div  ng-controller = 'requestController' class="container-fluid">
        <div class="row">
            {{--<div class="col-lg-2" align="center">--}}
                {{--<a href="{{url('amr/pedidos/nuevos')}}" class="btn btn-info btn-xs btn-detail" role="button">NUEVOS</a>--}}
                {{--<a href="{{url('amr/pedidos/procesados')}}" class="btn btn-success btn-xs btn-detail" role="button">PROCESADOS</a>--}}
            {{--</div>--}}
            <div class="col-lg-5">
                <label>TOTAL DE REGISTROS:@if($resume->count() != 0)
                        {{ $resume->count()}}</label>
                @else <label class="alert-danger">SIN RESULTADOS</label>
                @endif
            </div>
        </div><br>

        <div class="row">
            <div class="col-lg-5" align="center">
                <form class="navbar-form navbar-left" role="search" method="GET" action="{{url ('amr/pedidos/procesados')}}">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="PART_NUMBER" name="partnumber" required="true">
                    </div>
                    <button type="submit" class="btn btn-info"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                </form>
            </div>

            <div class="col-lg-3">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Buscar por linea
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="{{url('amr/pedidos/procesados')}}">Todas las lineas</a></li>
                        <li><a href="{{url('amr/pedidos/procesados/SMT - 1')}}">SMT - 1</a></li>
                        <li><a href="{{url('amr/pedidos/procesados/SMT - 2')}}">SMT - 2</a></li>
                        <li><a href="{{url('amr/pedidos/procesados/SMT - 3')}}">SMT - 3</a></li>
                        <li><a href="{{url('amr/pedidos/procesados/SMT - 4')}}">SMT - 4</a></li>
                        <li><a href="{{url('amr/pedidos/procesados/SMT - 5')}}">SMT - 5</a></li>
                        <li><a href="{{url('amr/pedidos/procesados/SMT - 6')}}">SMT - 6</a></li>
                    </ul>
                </div>
            </div>
            {{--BOTON DE PEDIDO DE MATERIALES--}}
            @if(hasRole('smtdatabase_operator') || isAdmin())
            <div class="col-lg-4" align="center">
                <button id="btn-add" class="btn btn-danger btn-xs" ng-click="toggle('add',0)"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo PartNumber</button>
            </div>
                @endif
        </div>
    <div class="container-fluid table-responsive" ng-controller="inspectionController">

        <table class="table table-hover table-bordered table-stripped">
            <thead>
            <tr>
                <th>NRO_OP</th>
                <th>PARTNUMBER</th>
                <th>LPN</th>
                <th width="5%">CANT_PEDIDA</th>
                <th width="5%">CANT_ASIGNADA</th>
                <th>LINEA</th>
                <th>MAQUINA</th>
                <th>UBICACION</th>
                <th>STATUS</th>
                <th>FECHA</th>
                <th>TIEMPO</th>

            </tr>
            </thead>
            <tbody>


            @foreach($resume as $modelo)

                    <tr>
                    <td><button id_pedido = "{{$modelo->INSERT_ID}}" route="{{url('amr/traza_pedido/'.$modelo->INSERT_ID)}}" class="btn btn-primary" ng-click="getInspectionBlocks($event);">{{$modelo->OP_NUMBER}}</button></td>

                    <td>{{$modelo->ITEM_CODE}}</td>

                    <td>{{$modelo->LPN}}</td>

                    <td>{{$modelo->QUANTITY}}</td>

                    <td>{{$modelo->LPN_QUANTITY}}</td>

                    <td>{{$modelo->PROD_LINE}}</td>

                    <td>{{$modelo->MAQUINA}}</td>

                    <td>{{$modelo->UBICACION}}</td>

                    <td>{{$modelo->STATUS}}</td>

                    <td> {{$modelo->LAST_UPDATE_DATE}}</td>
                        @if(empty($modelo->LAST_UPDATE_DATE))
                            <td>Sin Procesar</td>
                        @else
                    <td>{{$date = \IAServer\Http\Controllers\MonitorOp\GetWipOtInfo::ultimaDeclaracion($modelo->LAST_UPDATE_DATE)}}</td>
                        @endif

                    {{--<TD>--}}

                    {{--<button class="btn btn-default btn-xs btn-detail" ng-click="toggle('edit', XXE_WMS_COGISCAN_PEDIDOS.item_code)">pedir de nuevo</button>--}}
                    {{--</TD>--}}

                </tr>
            @endforeach
            </tbody>
        </table>
        {{$resume->appends(Request::only(['op']))->render()}}

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Nuevo Pedido</h4>
                    </div>

                    <div class="modal-body">
                        <form name="frmRequest" method="post" action="{{url('amr/parciales/pedir')}}" class="form-horizontal" novalidate="">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group error">
                                <label for="inputOp" class="col-sm-3 control-label">OP</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="op_number" name="op_number" placeholder="OP" autocomplete="off"
                                           ng-model="XXE_WMS_COGISCAN_PEDIDOS.OP_NUMBER" ng-required="true" strtoupper>
                                        <span class="help-inline"
                                              ng-show="frmRequest.op_number.$invalid && frmRequest.op_number.$touched">campo obligatorio</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputItemCode" class="col-sm-3 control-label">PartNumber</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="item_code" name="item_code" placeholder="PARTNUMBER" autocomplete="off"
                                           ng-model="XXE_WMS_COGISCAN_PEDIDOS.ITEM_CODE" ng-required="true">
                                        <span class="help-inline"
                                              ng-show="frmRequest.item_code.$invalid && frmRequest.item_code.$touched">campo obligatorio</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputQuantity" class="col-sm-3 control-label">Quantity</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="quantity" name="quantity" placeholder="CANTIDAD" autocomplete="off"
                                           ng-model="XXE_WMS_COGISCAN_PEDIDOS.QUANTITY" ng-required="true">
                                    <span class="help-inline"
                                          ng-show="frmRequest.quantity.$invalid && frmRequest.quantity.$touched">campo obligatorio</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputProdline" class="col-sm-3 control-label">Linea</label>
                                <div class="col-sm-9">
                                    <select CLASS="form-control" name="prod_line">
                                        <option value="SMT - 2">SMT - 2</option>
                                        <option value="SMT - 3">SMT - 3</option>
                                        <option value="SMT - 4">SMT - 4</option>
                                        <option value="SMT - 5">SMT - 5</option>
                                        <option value="SMT - 6">SMT - 6</option>
                                    </select>
                                    {{--<input type="text" class="form-control" id="prod_line" name="prod_line" placeholder="LINEA" autocomplete="off"--}}
                                           {{--ng-Model="XXE_WMS_COGISCAN_PEDIDOS.PROD_LINE" ng-required="true">--}}
                                    <span class="help-inline"
                                          ng-show="frmRequest.prod_line.$invalid && frmRequest.prod_line.$touched">campo obligatorio</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputMaquina" class="col-sm-3 control-label">Maquina</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="maquina" name="maquina" placeholder="MAQUINA" autocomplete="off"
                                           ng-model="XXE_WMS_COGISCAN_PEDIDOS.MAQUINA" ng-required="true">
                                    <span class="help-inline"
                                          ng-show="frmRequest.maquina.$invalid && frmRequest.maquina.$touched">campo obligatorio</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputUbicacion" class="col-sm-3 control-label">Ubicacion</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="UBICACION" autocomplete="off"
                                           ng-model="XXE_WMS_COGISCAN_PEDIDOS.UBICACION" ng-required="true">
                                    <span class="help-inline"
                                          ng-show="frmRequest.ubicacion.$invalid && frmRequest.ubicacion.$touched">campo obligatorio</span>
                                </div>
                            </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" ng-disabled="frmRequest.$invalid">ENVIAR PEDIDO</button>
                    </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! IAScript('vendor/monitorpedidos/app.js') !!}
    {!! IAScript('vendor/monitorpedidos/request.js') !!}
    {!! IAScript('vendor/monitorpedidos/trazapedido.js') !!}

@endsection

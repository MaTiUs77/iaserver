@extends('monitorpedidos.index')
@section('body')
    <div class="container-fluid">
        <div class="row">
            {{--<div class="col-lg-12" align="center">--}}
                {{--<a href="{{url('amr/pedidos/nuevos')}}" class="btn btn-info btn-xs btn-detail" role="button">NUEVOS</a>--}}
                {{--<a href="{{url('amr/pedidos/procesados')}}" class="btn btn-success btn-xs btn-detail" role="button">PROCESADOS</a>--}}
            {{--</div>--}}
        </div><br><br>
        <div class="row">
            <div class="col-lg-4" align="center">
                <form class="navbar-form navbar-left" role="search" method="GET" action="{{url ('amr/parciales')}}">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="PART_NUMBER" name="partnumber" required="true">
                    </div>
                    <button type="submit" class="btn btn-info"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                </form>
            </div>
            <div class="col-lg-4" align="center">
                <label>TOTAL DE REGISTROS:@if($resume->count() != 0)
                        {{ $resume->count()}}</label>
                @else <label class="alert-danger">SIN RESULTADOS</label>
                @endif

            </div>
            {{--BOTON DE PEDIDO DE MATERIALES DESACTIVADO TEMPORALMENTE--}}
            {{--<div class="col-lg-4" align="right">--}}
                {{--<button id="btn-add" class="btn btn-danger btn-xs" ng-click="toggle('add',0)"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo PartNumber</button>--}}
            {{--</div>--}}
        </div>
        <div class="container-fluid">

            <table class="table table-hover">
                <thead>
                <tr>
                    <th>NRO_OP</th>
                    <th>PARTNUMBER</th>
                    <th width="5%">CANT_PEDIDA</th>
                    <th>CANT_ASIGNADA</th>
                    <th>LINEA</th>
                    <th>MAQUINA</th>
                    <th>UBICACION</th>
                    <th>STATUS</th>
                    <th>FECHA</th>
                    <th>ERROR</th>
                    {{--<th>ACCIONES</th>--}}

                </tr>
                </thead>
                <tbody>


                @foreach($resume as $modelo)

                    <tr>

                        <td><a class="btn btn-primary">{{$modelo->OP_NUMBER}}</a></td>

                        <td>{{$modelo->ITEM_CODE}}</td>

                        <td>{{$modelo->QUANTITY}}</td>

                        <td>{{$modelo->QUANTITY_ASSIGNED}}</td>

                        <td>{{$modelo->PROD_LINE}}</td>

                        <td>{{$modelo->MAQUINA}}</td>

                        <td>{{$modelo->UBICACION}}</td>

                        <td>{{$modelo->STATUS}}</td>

                        <td> {{$modelo->LAST_UPDATE_DATE}}
                            {{--@if(isset($modelo->LAST_UPDATE_DATE))--}}
                            {{--$newfecha = Carbon::createFromFormat('Y-m-d H:i:s.u', $modelo->LAST_UPDATE_DATE());--}}
                            {{--{{\IAServer\Http\Controllers\MonitorOp\GetWipOtInfo::ultimaDeclaracion($newfecha) }}--}}
                            {{--@else--}}
                            {{--Sin Procesar--}}
                            {{--@endif--}}
                        </td>
                        <td width="15%">{{$modelo->ERROR_MESSAGE}}</td>
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
                                               ng-model="XXE_WMS_COGISCAN_PEDIDOS.OP_NUMBER" ng-required="true">
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
                                        <input type="text" class="form-control" id="prod_line" name="prod_line" placeholder="LINEA" autocomplete="off"
                                               ng-model="XXE_WMS_COGISCAN_PEDIDOS.PROD_LINE" ng-required="true">
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


@endsection

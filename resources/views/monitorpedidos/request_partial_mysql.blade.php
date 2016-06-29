@extends('angular')
@section('ng','requestRecords')
@section('body')
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand">@yield('title','')</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="{{ url('amr/parciales') }}">EBS</a></li>
                <li><a href="{{ url('amr/parciales/almacen') }}">PISO PRODUCCION</a></li>
            </ul>
        </div>
    </nav>
    {{--FORMULARIO DE BUSQUEDA--}}
    <div  ng-controller = 'requestController' class="container-fluid">
        <div class="row">
            <div class="row">
                <div class="col-lg-4" align="center">
                    <form class="navbar-form navbar-left" role="search" method="GET" action="{{url ('amr/parciales/almacen')}}">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="PART_NUMBER" name="partnumber" required="true">
                        </div>
                        <button type="submit" class="btn btn-info">Buscar</button>
                    </form>
                </div>
                <div class="col-lg-4">
                    <label>TOTAL DE REGISTROS:@if($pedido->count() != 0)
                            {{ $pedido->count()}}</label>
                    @else <label class="alert-danger">SIN RESULTADOS</label>
                    @endif

                </div>
            </div>
        </div>
        <div class="container-fluid">

            <table class="table table-hover">
                <thead>
                <tr>
                    <th>NRO_OP</th>
                    <th>LINEA</th>
                    <th>PARTNUMBER</th>
                    <th>LOTE_PARTNUMBER</th>
                    <th>LINEADESTINO</th>
                    <th>TIEMPO</th>
                    <th>UBICACION</th>

                </tr>
                </thead>
                <tbody>


                @foreach($pedido as $modelo)

                    <tr>

                        <td><a class="btn btn-primary">{{$modelo->op}}</a></td>

                        <td>{{$modelo->linMatWip}}</td>

                        <td>{{$modelo->codMat}}</td>

                        <td>{{$modelo->rawMaterial}}</td>

                        <td>{{$modelo->linDest}}</td>

                        <td>{{$modelo->timestamp}}</td>

                        <td>{{$modelo->estadoUbicacion}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$pedido->appends(Request::only(['op']))->render()}}
            {{--FORMULARIO MODAL PARA PEDIR NUEVO PARTNUMBER--}}
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
                                              ng-show="frmRequest.op_number.$invalid && frmRequest.op_number.$touched">OP field is required</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputItemCode" class="col-sm-3 control-label">PartNumber</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="item_code" name="item_code" placeholder="partnumber" autocomplete="off"
                                               ng-model="XXE_WMS_COGISCAN_PEDIDOS.ITEM_CODE" ng-required="true">
                                        <span class="help-inline"
                                              ng-show="frmRequest.item_code.$invalid && frmRequest.item_code.$touched">Partnumber field is required</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputQuantity" class="col-sm-3 control-label">Quantity</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Cantidad" autocomplete="off"
                                               ng-model="XXE_WMS_COGISCAN_PEDIDOS.QUANTITY" ng-required="true">
                                    <span class="help-inline"
                                          ng-show="frmRequest.quantity.$invalid && frmRequest.quantity.$touched">Cantidad number field is required</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputProdline" class="col-sm-3 control-label">Linea</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="prod_line" name="prod_line" placeholder="Linea" autocomplete="off"
                                               ng-model="XXE_WMS_COGISCAN_PEDIDOS.PROD_LINE" ng-required="true">
                                    <span class="help-inline"
                                          ng-show="frmRequest.prod_line.$invalid && frmRequest.prod_line.$touched">Linea field is required</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputMaquina" class="col-sm-3 control-label">Maquina</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="maquina" name="maquina" placeholder="Maquina" autocomplete="off"
                                               ng-model="XXE_WMS_COGISCAN_PEDIDOS.MAQUINA" ng-required="true">
                                    <span class="help-inline"
                                          ng-show="frmRequest.maquina.$invalid && frmRequest.maquina.$touched">Maquina field is required</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputUbicacion" class="col-sm-3 control-label">Ubicacion</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ubicacion" autocomplete="off"
                                               ng-model="XXE_WMS_COGISCAN_PEDIDOS.UBICACION" ng-required="true">
                                    <span class="help-inline"
                                          ng-show="frmRequest.ubicacion.$invalid && frmRequest.ubicacion.$touched">Ubicacion field is required</span>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" ng-disabled="frmRequest.$invalid">Save changes</button>
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

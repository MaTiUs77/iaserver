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
    <div  ng-controller = 'requestController' class="container-fluid">
        {{--<div class="row">--}}
            {{--<div class="col-lg-12" align="center">--}}
                {{--<a href="#" class="btn btn-primary btn-small active" role="button">NUEVOS</a>--}}
                {{--<a href="#" class="btn btn-success btn-small active" role="button">PROCESADOS</a>--}}
                {{--<a href="#" class="btn btn-danger btn-small active" role="button">ERROR</a>--}}
            {{--</div>--}}
        {{--</div><br><br>--}}
        <div class="row">
            <div class="col-lg-4" align="center">
                <form class="navbar-form navbar-left" role="search" method="GET" action="{{url ('amr/parciales')}}">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="PART_NUMBER" name="partnumber" required="true">
                    </div>
                    <button type="submit" class="btn btn-info">Buscar</button>
                </form>
            </div>
            <div class="col-lg-4">
                <label>TOTAL DE REGISTROS:@if($resume->count() != 0)
                        {{ $resume->count()}}</label>
                    @else <label class="alert-danger">SIN RESULTADOS</label>
                    @endif

            </div>
            <div class="col-lg-4" align="right">
                <button id="btn-add" class="btn btn-danger btn-xs" ng-click="toggle('add',0)">Nuevo PartNumber</button>
            </div>
        </div>
    <div class="container-fluid">

        <table class="table table-hover">
            <thead>
            <tr>
                <th>NRO_OP</th>
                <th>PARTNUMBER</th>
                <th>CANTIDAD_PEDIDA</th>
                <th>CANTIDAD_ASIGNADA</th>
                <th>CANTIDAD_RESTANTE</th>
                <th>LINEA</th>
                <th>MAQUINA</th>
                <th>UBICACION</th>
                <th>STATUS</th>
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

                    <td>{{$diferencia = $modelo->QUANTITY - $modelo->QUANTITY_ASSIGNED}}</td>

                    <td>{{$modelo->PROD_LINE}}</td>

                    <td>{{$modelo->MAQUINA}}</td>

                    <td>{{$modelo->UBICACION}}</td>

                    <td>{{$modelo->STATUS}}</td>

                    <td>{{$modelo->ERROR_MESSAGE}}</td>

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

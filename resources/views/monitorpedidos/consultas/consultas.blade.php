@extends('monitorpedidos.index')
@section('body')
    <div class="container-fluid">

<nav class="navbar navbar-default">

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <form class="navbar-form navbar-left" action="{{url('amr/consultar')}}">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Buscar" name="partnumber" required>
                    <select CLASS="form-control" name="prod_line" title="Filtro">
                        <option  value="codMat">PartNumber</option>
                        <option  value="rawMaterial">Lpn</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-info"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            </form>
   </div><!-- /.navbar-collapse -->
</nav>
</div>

<div class="container-fluid">
@if(!$historial_deltamonitor->isEmpty())

        <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading" align="center">amr_deltamonitor = {{$historial_deltamonitor->count()}} registros </div>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>OP</th>
                    <th>MAQUINA</th>
                    <th>LINEA</th>
                    <th>UBICACION</th>
                    <th>PARTNUMBER</th>
                    <th>LPN</th>
                    <th>PLACAS</th>
                    <th>MINUTOS</th>
                    <th>QTY_REQUEST</th>
                    <th>FECHA</th>
                </tr>
                </thead>
                <tbody>
                @foreach($historial_deltamonitor as $history_monitor)
                    <tr>
                        <td> {{$history_monitor->batchId}} </td>
                        <td> {{$history_monitor->idMaquina}} </td>
                        <td> {{$history_monitor->laneNumber}} </td>
                        <td> {{$history_monitor->location}} </td>
                        <td> {{$history_monitor->partNumber}} </td>
                        <td> {{$history_monitor->rawMaterialId}} </td>
                        <td> {{$history_monitor->remainingBoards}} </td>
                        <td> {{$history_monitor->minutos}} </td>
                        <td> {{$history_monitor->valueqtyPerASSYFinal}} </td>
                        <td> {{$history_monitor->timeStampRegistro  }} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>
</div>

@if(!$historial_cgs->isEmpty())

<div class="container-fluid">
        <div class="panel panel-primary">
        <div class="panel-heading" align="center">cgs_materialrequest</div>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>OP</th>
            <th>LPN</th>
            <th>PARTNUMBER</th>
            <th>QTY_REQUEST</th>
            <th>ORIGEN</th>
            <th>FECHA</th>
            <th>LINEA</th>
            <th>MAQUINA</th>
            <th>UBICACION</th>
        </tr>
        </thead>
        <tbody>
        @foreach($historial_cgs as $history_cgs)
        <tr>
                <td> {{$history_cgs->id}} </td>
                <td> {{$history_cgs->op}} </td>
                <td> {{$history_cgs->rawMaterial}} </td>
                <td> {{$history_cgs->codMat}} </td>
                <td> {{$history_cgs->cantASolic}} </td>
                <td> {{$history_cgs->estadoUbicacion}} </td>
                <td> {{$history_cgs->timestamp}} </td>
                <td> {{$history_cgs->PROD_LINE}} </td>
                <td> {{$history_cgs->MAQUINA}} </td>
                <td> {{$history_cgs->UBICACION}} </td>
        </tr>
        @endforeach
        </tbody>
    </table>
            @endif
        </div>

</div>

    @if(!$historial_interfaz->isEmpty())
<div class="container-fluid">
            <div class="panel panel-success">
                <div class="panel-heading" align="center">XXE_WMS_COGISCAN_PEDIDOS</div>
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>OP</th>
                        <th>PARTNUMBER</th>
                        <th>LPN</th>
                        <th>QTY_REQUEST</th>
                        <th>QTY_ASSIGNED</th>
                        <th>QTY_LPN</th>
                        <th>PROD_LINE</th>
                        <th>MAQUINA</th>
                        <th>UBICACION</th>
                        <th>STATUS</th>
                        <th>INSERT_ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($historial_interfaz as $history_interfaz)
                        <tr>
                            <td> {{$history_interfaz->OP_NUMBER}} </td>
                            <td> {{$history_interfaz->ITEM_CODE}} </td>
                            <td> {{$history_interfaz->LPN}} </td>
                            <td> {{$history_interfaz->QUANTITY}} </td>
                            <td> {{$history_interfaz->QUANTITY_ASSIGNED}} </td>
                            <td> {{$history_interfaz->LPN_QUANTITY}} </td>
                            <td> {{$history_interfaz->PROD_LINE}} </td>
                            <td> {{$history_interfaz->MAQUINA}} </td>
                            <td> {{$history_interfaz->UBICACION}} </td>
                            <td> {{$history_interfaz->STATUS  }} </td>
                            <td> {{$history_interfaz->INSERT_ID }} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
</div>

            @if(!$historial_reservas->isEmpty())
<div class="container-fluid">
            <div class="panel panel-primary">
                <div class="panel-heading" align="center">reservas</div>
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>OP</th>
                        <th>LINEA</th>
                        <th>MAQUINA</th>
                        <th>FEEDER</th>
                        <th>PARTNUMBER</th>
                        <th>LPN</th>
                        <th>UBICACION</th>
                        <th>ID PEDIDO</th>
                        <th>FECHA</th>
                        <th>STATUS</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($historial_reservas as $history_reservas)
                        <tr>
                            <td> {{$history_reservas->op}} </td>
                            <td> {{$history_reservas->linea}} </td>
                            <td> {{$history_reservas->maquina}} </td>
                            <td> {{$history_reservas->feeder}} </td>
                            <td> {{$history_reservas->pn}} </td>
                            <td> {{$history_reservas->lpn}} </td>
                            <td> {{$history_reservas->ubicacion}} </td>
                            <td> {{$history_reservas->id_pedido}} </td>
                            <td> {{$history_reservas->tiempopedido}} </td>
                            <td> {{$history_reservas->id_instruction  }} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
</div>
                    @if(!$historial_interfaz_error->isEmpty())
<div class="container-fluid">
                            <div class="panel panel-danger">
                                <div class="panel-heading" align="center">XXE_WMS_COGISCAN_PEDIDOS</div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>OP</th>
                                        <th>PARTNUMBER</th>
                                        <th>QTY_REQUEST</th>
                                        <th>QTY_ASSIGNED</th>
                                        <th>QTY_LPN</th>
                                        <th>PROD_LINE</th>
                                        <th>MAQUINA</th>
                                        <th>UBICACION</th>
                                        <th>STATUS</th>
                                        <th>INSERT_ID</th>
                                        <th>ERROR</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($historial_interfaz_error as $history_interfaz_error)
                                        <tr>
                                            <td> {{$history_interfaz_error->OP_NUMBER}} </td>
                                            <td> {{$history_interfaz_error->ITEM_CODE}} </td>
                                            <td> {{$history_interfaz_error->QUANTITY}} </td>
                                            <td> {{$history_interfaz_error->QUANTITY_ASSIGNED}} </td>
                                            <td> {{$history_interfaz_error->LPN_QUANTITY}} </td>
                                            <td> {{$history_interfaz_error->PROD_LINE}} </td>
                                            <td> {{$history_interfaz_error->MAQUINA}} </td>
                                            <td> {{$history_interfaz_error->UBICACION}} </td>
                                            <td> {{$history_interfaz_error->STATUS  }} </td>
                                            <td> {{$history_interfaz_error->INSERT_ID }} </td>
                                            <td> {{$history_interfaz_error->ERROR_MESSAGE  }} </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </div>
</div>
{{--                        {{dd($historial_pedidoXlpn->first()->STATUS)}}--}}
                        @if(!$historial_pedidoXlpn->isEmpty())
<div class="container-fluid">
                                @if($historial_pedidoXlpn->first()->STATUS == "ERROR")
                                    <?php $panel = "panel-danger"; ?>
                                @else
                                    <?php $panel = "panel-success"; ?>
                                @endif
                                {{--{{dd($panel)}}--}}
                                <div class="panel <?php echo $panel; ?>">
                                    <div class="panel-heading" align="center">XXE_WMS_COGISCAN_PEDIDOS</div>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>OP</th>
                                            <th>PARTNUMBER</th>
                                            <th>LPN</th>
                                            <th>QTY_REQUEST</th>
                                            <th>QTY_ASSIGNED</th>
                                            <th>QTY_LPN</th>
                                            <th>PROD_LINE</th>
                                            <th>MAQUINA</th>
                                            <th>UBICACION</th>
                                            <th>STATUS</th>
                                            <th>INSERT_ID</th>
                                            <th>MSG</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($historial_pedidoXlpn as $history_lpn)
                                            <tr>
                                                <td> {{$history_lpn->OP_NUMBER}} </td>
                                                <td> {{$history_lpn->ITEM_CODE}} </td>
                                                <td> {{$history_lpn->LPN}} </td>
                                                <td> {{$history_lpn->QUANTITY}} </td>
                                                <td> {{$history_lpn->QUANTITY_ASSIGNED}} </td>
                                                <td> {{$history_lpn->LPN_QUANTITY}} </td>
                                                <td> {{$history_lpn->PROD_LINE}} </td>
                                                <td> {{$history_lpn->MAQUINA}} </td>
                                                <td> {{$history_lpn->UBICACION}} </td>
                                                <td> {{$history_lpn->STATUS  }} </td>
                                                <td> {{$history_lpn->INSERT_ID }} </td>
                                                <td> {{$history_lpn->ERROR_MESSAGE  }} </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
</div>

                            @if(!$historial_reservaXid->isEmpty())
<div class="container">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading" align="center">reservas</div>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>OP</th>
                                                <th>LINEA</th>
                                                <th>MAQUINA</th>
                                                <th>FEEDER</th>
                                                <th>PARTNUMBER</th>
                                                <th>LPN</th>
                                                <th>UBICACION</th>
                                                <th>ID PEDIDO</th>
                                                <th>FECHA</th>
                                                <th>STATUS</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($historial_reservaXid as $history_reservasId)
                                                <tr>
                                                    <td> {{$history_reservasId->op}} </td>
                                                    <td> {{$history_reservasId->linea}} </td>
                                                    <td> {{$history_reservasId->maquina}} </td>
                                                    <td> {{$history_reservasId->feeder}} </td>
                                                    <td> {{$history_reservasId->pn}} </td>
                                                    <td> {{$history_reservasId->lpn}} </td>
                                                    <td> {{$history_reservasId->ubicacion}} </td>
                                                    <td> {{$history_reservasId->id_pedido}} </td>
                                                    <td> {{$history_reservasId->tiempopedido}} </td>
                                                    <td> {{$history_reservasId->id_instruction  }} </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                </div>
@endsection
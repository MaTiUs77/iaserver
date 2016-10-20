@if(!$trazaPartNumber->isEmpty())
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
                    <th>TIEMPO</th>
                    <th>LINEA</th>
                    <th>MAQUINA</th>
                    <th>UBICACION</th>
                </tr>
                </thead>
                <tbody>
                @foreach($trazaPartNumber as $history_traza)
                    <tr>
                        <td> <button id_pedido = "{{$history_traza->id}}" route = {{url('amr/trazabilidad/'.$history_traza->id,$history_traza->codMat)}}  class="btn-success">{{$history_traza->id}}</button></td>
                        <td> {{$history_traza->op}} </td>
                        <td> {{$history_traza->rawMaterial}} </td>
                        <td> {{$history_traza->codMat}} </td>
                        <td> {{$history_traza->cantASolic}} </td>
                        <td> {{$history_traza->timestamp}} </td>
                        <td> {{$history_traza->PROD_LINE}} </td>
                        <td> {{$history_traza->MAQUINA}} </td>
                        <td> {{$history_traza->UBICACION}} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>


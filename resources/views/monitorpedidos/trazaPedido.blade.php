
<style>
    .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #8C8C8C; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }.datagrid table td, .datagrid table th { padding: 3px 10px; }.datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1F7178), color-stop(1, #000000) );background:-moz-linear-gradient( center top, #1F7178 5%, #000000 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1F7178', endColorstr='#000000');background-color:#1F7178; color:#FFFFFF; font-size: 10px; font-weight: bold; border-left: 0px solid #050303; } .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #6B6B6B; border-left: 1px solid #DBDBDB;font-size: 12px;font-weight: normal; }.datagrid table tbody .alt td { background: #EBEBEB; color: #7D7D7D; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }.datagrid table tfoot td div { border-top: 1px solid #8C8C8C;background: #EBEBEB;} .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }.datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }.datagrid table tfoot  li { display: inline; }.datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #8C8C8C;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) );background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D');background-color:#8C8C8C; }.datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #7D7D7D; color: #F7EFED; background: none; background-color:#8C8C8C;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; }
</style>
@if(!$traza->isEmpty())
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
                    @foreach($traza as $history_traza)
                        <tr>
                            <td> {{$history_traza->id}} </td>
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
        @if(!$traza_complete->isEmpty())
            <div class="container-fluid">
                <div class="panel panel-primary">
                    <div class="panel-heading" align="center">EBS-INTERFAZ</div>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>QTY_PANEL</th>
                            <th>QTY_DISPONIBLE</th>
                            <th>QTY_USADA</th>
                            <th>TOTAL_PLACAS</th>
                            <th>TOTAL_COMPLETADAS</th>
                            <th>SEMIELABORADO</th>
                            <th>DESCRIPCION</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($traza_complete as $history_traza_complete)
                            <tr>
                                <td> {{$history_traza_complete->INSERT_ID}}</td>
                                <td> {{$history_traza_complete->QUANTITY_PER_ASSEMBLY}} </td>
                                <td> {{$history_traza_complete->REQUIRED_QUANTITY}} </td>
                                <td> {{$history_traza_complete->QUANTITY_ISSUED}} </td>
                                <td> {{$history_traza_complete->START_QUANTITY}} </td>
                                <td> {{$history_traza_complete->QUANTITY_COMPLETED}} </td>
                                <td> {{$history_traza_complete->SEGMENT1}} </td>
                                <td> {{$history_traza_complete->DESCRIPTION}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        {!! IAScript('vendor/monitorpedidos/trazapedido.js') !!}
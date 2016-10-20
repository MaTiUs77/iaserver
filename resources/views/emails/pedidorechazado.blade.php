<style>
    .datagrid table { border-collapse: collapse; text-align: left; width: 100%; }
    .datagrid {font: normal 12px/150% Geneva, Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; }
    .datagrid table td, .datagrid table th { padding: 5px 9px; }
    .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 2px solid #0070A8; }
    .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #000000; border-left: 1px solid #E1EEF4;font-size: 17px;font-weight: normal; }
    .datagrid table tbody .alt td { background: #E1EEf4; color: #000000; }.datagrid table tbody td:first-child { border-left: none; }
    .datagrid table tbody tr:last-child td { border-bottom: none; }.datagrid table tfoot td div { border-top: 1px solid #006699;background: #E1EEf4;}
    .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }.datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }
    .datagrid table tfoot  li { display: inline; }.datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; }
    .datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #00557F; color: #FFFFFF; background: none; background-color:#006699;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; }
</style>
<div class="datagrid">
    <h1>Pedido Rechazado</h1>
    <table style="width: 1px;" border="1" cellpadding="1">
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
        </tr>
        </thead>
        <tbody>
            <tr>

                <td>{{$data->OP_NUMBER}}</td>

                <td>{{$data->ITEM_CODE}}</td>

                <td>{{$data->QUANTITY}}</td>
                @if($data->QUANTITY_ASSIGNED == null)
                    <td>{{$data->QUANTITY_ASSIGNED = 0}}</td>
                @else
                <td>{{$data->QUANTITY_ASSIGNED}}</td>
                @endif
                <td>{{$data->PROD_LINE}}</td>

                <td>{{$data->MAQUINA}}</td>

                <td>{{$data->UBICACION}}</td>

                <td>{{$data->STATUS}}</td>

                <td> {{$data->LAST_UPDATE_DATE}}</td>
                @if($data->ERROR_MESSAGE == null)
                    <td width="15%">{{$data->ERROR_MESSAGE = "-"}}</td>
                @else
                <td width="15%">{{$data->ERROR_MESSAGE}}</td>
                @endif
            </tr>

        </tbody>
    </table>
</div>
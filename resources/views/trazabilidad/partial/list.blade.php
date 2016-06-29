<style>
   /* .table-striped tbody tr:nth-child(2n+1) > td {
        background-color: #F7F7F7;
    }

    .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
        background-color: #FFFDD1;
    }*/

    .table tbody tr td {
        text-align: center;

    }

    thead.panel th {
        background-color: #2D6CA2;
        color: white;
        text-align: center;
    }

   .finalizado {
       background-color: #b2dba1;
   }
   .excedido {
       background-color: #da4f49;
   }

   .warning {
       background-color: #FCFB98;
   }

</style>

<table class="table table-bordered table-striped">
    <thead class="panel">
    <tr>
        <th></th>
        <th style="min-width: 100px;">OP</th>
        <th>Modelo</th>
        <th>Lote</th>
        <th>Panel</th>
        <th>Cant. Lote</th>
        <th>Declarado</th>
        <th>Restante</th>
        <th>Pendiente</th>
        <th>Solicitudes</th>
        <th>Solicitudes con error</th>
        <th>Semielaborado</th>
        <th>Descripcion</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lista as $ot)
        <?php
            $fila_css = '';
            if($ot->declarado == $ot->start_quantity) {
                $fila_css = 'finalizado';
            }
            if($ot->declarado > $ot->start_quantity) {
                $fila_css = 'excedido';
            }
            if($ot->solicitudes > $ot->start_quantity) {
                $fila_css = 'warning';
            }
        ?>
        <tr class="{{ $fila_css  }}">
            <td style="min-width: 100px;">
                <a class="btn btn-sm btn-success" tooltip="Declarar OPs" ng-click="openModal('{{ route('trazabilidad.form.declarar',$ot->nro_op) }}','Declarar OP','success')"><span class="glyphicon glyphicon-plus"></span></a>
            </td>
            <td>{{ $ot->nro_op }}</td>
            <td>{{ $ot->smt!=null ? $ot->smt->modelo : '' }}</td>
            <td>{{ $ot->smt!=null ? $ot->smt->lote : '' }}</td>
            <td>{{ $ot->smt!=null ? $ot->smt->panel : '' }}</td>
            <td>{{ $ot->start_quantity }}</td>
            <td>{{ $ot->declarado }}</td>
            <td>{{ $ot->restante }}</td>
            <td>
                @if($ot->pendiente > 0)
                    <a class="btn btn-sm btn-warning" tooltip="Ver detalle" ng-click="openModal('{{ route('trazabilidad.form.trans_pendiente',$ot->nro_op) }}','PENDIENTES','warning')">{{ $ot->pendiente }}</a
                @else
                    {{ $ot->pendiente }}
                @endif
            </td>
            <td>{{ $ot->solicitudes }}</td>
            <td>
                    @if($ot->errores > 0)
                        <a class="btn btn-sm btn-danger" tooltip="Ver detalle" ng-click="openModal('{{ route('trazabilidad.form.trans_error',$ot->nro_op) }}','TRANS CON ERROR','danger')">{{ $ot->errores }}</a
                    @else
                        {{ $ot->errores }}
                    @endif
            </td>
            <td>{{ $ot->codigo_producto }}</td>
            <td><small>{{ $ot->description }}</small></td>
        </tr>
    @endforeach
    </tbody>
</table>

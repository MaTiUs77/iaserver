@if($wipInfo!=null && $wipInfo->active)

    <h3>{{ $wipInfo->wip_ot->nro_op }} <small>{{ $wipInfo->wip_ot->codigo_producto }}</small></h3>

    <form method="POST" action="{{ route('trazabilidad.form.declarar.send',$wipInfo->wip_ot->nro_op) }}">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Lote</th>
                <th>Declarado</th>
                <th>Restante</th>
                <th>Pendiente</th>
                <th>Cantidad a declarar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $wipInfo->wip_ot->start_quantity }}</td>
                <td>{{ $wipInfo->wip_ot->quantity_completed }}</td>
                <td>{{ $wipInfo->wip_ot->restante }}</td>
                <td>{{ $wipInfo->wip_ot->start_quantity }}</td>
                <td>
                    <input type="number" name="cantidad" class="form-control focus" placeholder="Ingresar cantidad"/>
                </td>
            </tr>
        </tbody>
    </table>

        <input type="hidden" name="op" class="form-control" value="{{ $wipInfo->wip_ot->nro_op }}"/>
        <input type="hidden" name="codigo_producto" class="form-control" value="{{ $wipInfo->wip_ot->codigo_producto }}"/>
        <button type="submit" class="btn btn-success btn-block">Declarar</button>
    </form>
@else
    <div class="alert alert-danger">
        <span class="glyphicon glyphicon-exclamation-sign"></span>
        <span class="sr-only">Error:</span>
        La OP solicitada fue cerrada!
    </div>
@endif
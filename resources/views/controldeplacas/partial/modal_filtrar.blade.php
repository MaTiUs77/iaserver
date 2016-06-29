<form method="post" action="{{ route('controldeplacas.filtrar.submit') }}" role="form" class="form-horizontal"  >
    <input class="form-control" type="text" name="op" placeholder="OP"/>
    <input class="form-control" type="text" name="modelo" placeholder="Modelo"/>
    <input class="form-control" type="text" name="lote" placeholder="Lote"/>
    <input   type="submit" value="Aplicar filtro" class="btn btn-info btn-block"/>
</form>

<div class="well" style="height: 70px;">
    <!-- BUSQUEDA -->
    <form method="POST" action="{{ route('trazabilidad.find.op') }}" class="pull-right">
        <div class="input-group"  style="width: 300px;">
            <input type="text" name="op" class="form-control" placeholder="Ingresar op" value="{{ Input::get('op')  }}"/>
            <span class="input-group-btn">
                <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
            </span>
        </div>
    </form>
    <!-- END BUSQUEDA -->
</div>
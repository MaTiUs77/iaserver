<!-- HEADER -->
<div class="well" style="height: 70px;">
    <div class="row">
        <!-- BUSQUEDA -->
        <div class="col-sm-4">
            <form method="POST" action="{{ route('trazabilidad.find.op') }}" >
                <div class="input-group" >
                    <input type="text" name="op" class="form-control" placeholder="Ingresar op" value="{{ Input::get('op')  }}"/>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Informacion de OP</button>
                    </span>
                </div>
            </form>
        </div>
        <!-- END BUSQUEDA -->

        @if(isAdmin())
        <div class="col-sm-4">
            <form method="POST" action="{{ route('aoicollector.stocker.trazabilidad.rastrearop.view') }}">
                <div class="input-group" >
                    <input type="text" name="rastrearop" class="form-control" placeholder="OP de stockers" value="{{ Input::get('rastrearop')  }}"/>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Rastrear</button>
                    </span>
                </div>
            </form>
        </div>
        @endif

        <div class="col-sm-4">
            <form method="POST" action="{{ route('aoicollector.stocker.trazabilidad.view') }}">
                <div class="input-group" >
                    <input type="text" name="element" class="form-control" placeholder="Stocker o Placa" value="{{ Input::get('element')  }}"/>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                    </span>
                </div>
            </form>
        </div>

    </div>
</div>
<!-- END HEADER -->

<div class="well" style="height: 70px;">

    <div class="row">

        <!-- BUSQUEDA -->
        <div class="col-lg-3">
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
            <div class="col-lg-3">
                <form method="POST" action="{{ route('trazabilidad.stocker.find') }}">
                    <div class="input-group" >
                        <input type="text" name="element" class="form-control" placeholder="Stocker o Placa" value="{{ Input::get('element')  }}"/>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                        </span>
                    </div>
                </form>
            </div>
        @endif


    </div>




</div>
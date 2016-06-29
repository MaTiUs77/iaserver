<div class="well">

    <div class="pull-right">
        @include('iaserver.common.datepicker',['date_session'=>Session::get('date_session'),'route'=>route('aoicollector.inspection.show',$maquina->id)])
    </div>

    <!-- BUSQUEDA -->
    <form method="POST" action="{{ route('aoicollector.inspection.search') }}">
        <div class="input-group pull-right"  style="width: 300px;margin-right: 5px;">
            <input type="text" name="barcode" class="form-control" placeholder="Ingresar barcode a buscar" ng-required="true"/>
            <span class="input-group-btn">
                <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Buscar</button>
            </span>
        </div>
    </form>
    <!-- END BUSQUEDA -->

    <a href="{{ route('aoicollector.stat.index') }}" class="btn btn-info">Ir a Estadisticas</a>
</div>
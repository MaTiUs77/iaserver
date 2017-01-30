<div class="well">
    <div class="pull-right">
        <form method="GET" action="{{ route('aoicollector.inspection.show',$maquina->id) }}" class="navbar-form navbar-left" style="margin: 0;">
            <div class="form-group">
                <input type="text" name="date_session" value="{{ Session::get('date_session') }}" placeholder="Seleccionar fecha" class="form-control"/>
            </div>

            <select name="listMode" class="form-control">
                <option value="MAX" {{ (Input::get('listMode')=='MAX') ? 'selected=selected' : '' }}>Ultimo estado de inspeccion</option>
                <option value="MIN" {{ (Input::get('listMode')=='MIN') ? 'selected=selected' : '' }}>Primer estado de inspeccion</option>
                <option value="MINA"  {{ (Input::get('listMode')=='MINA') ? 'selected=selected' : '' }}>Primer aparicion de placa</option>
            </select>

            <select name="filterPeriod" class="form-control">
                <option value="" {{ (Input::get('filterPeriod')=='') ? 'selected=selected' : '' }}>Todo el dia</option>
                @for($i = 0; $i < 23; $i++) {
                    <?php $iZeroLeft = str_pad($i, 2, 0, STR_PAD_LEFT);?>
                    <option value="{{ $iZeroLeft }}:00:00" {{ (Input::get('filterPeriod')=="$iZeroLeft:00:00") ? 'selected=selected' : '' }}>{{ $iZeroLeft }}:00</option>
                @endfor
            </select>

            <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
        </form>
    </div>

    <script type="text/javascript">
        $(function() {
            $('input[name="date_session"]').daterangepicker({
                //timePicker: true,
                //timePicker24Hour: true,
                //timePickerIncrement: 10,
                locale: {
                    //format: 'DD/MM/YYYY H:mm',
                    format: 'DD/MM/YYYY',
                    customRangeLabel: 'Definir rango'
                },
                ranges: {
                    //'Hoy': [moment().set({hour:0,minute:0,second:0,millisecond:0}), moment().set({hour:23,minute:59,second:0,millisecond:0})],
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()]
                },
                autoApply: true
            });
        });
    </script>

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
    <a href="{{ route('aoicollector.inspection.defectos.periodo') }}" class="btn btn-info">Defectos por periodo</a>


</div>
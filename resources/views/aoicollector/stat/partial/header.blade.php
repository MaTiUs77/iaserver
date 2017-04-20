<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Ver menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ route('aoicollector.inspection.index') }}">Ver Inspecciones</a>
                </li>
                <li>
                    <form  class="navbar-form">
                        <div class="btn-group btn-group-sm pull-right" style="padding: 2px 5px 0px 0px;">
                            <a href="{{ route('aoicollector.stat.show',[$maquina->id,'M',$fecha]) }}" class="btn btn-default {{ $turno == 'M' ? 'active' : '' }}">Mañana</a>
                            <a href="{{ route('aoicollector.stat.show',[$maquina->id,'T',$fecha]) }}" class="btn btn-default {{ $turno == 'T' ? 'active' : '' }}">Tarde</a>
                        </div>
                    </form>
                </li>
                <li>
                    <form method="GET" action="{{ route('aoicollector.stat.show',[$maquina->id, $turno , 0]) }}" class="navbar-form">
                        <div class="form-group">
                            <input type="text" name="inspection_date_session" value="{{ Session::get('inspection_date_session') }}" placeholder="Seleccionar fecha" class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
                    </form>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


<!-- Include Date Range Picker -->
{!! IAScript('assets/moment.min.js') !!}
{!! IAScript('assets/moment.locale.es.js') !!}
{!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
{!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}
<script type="text/javascript">
    moment.locale("es");

    $(function() {
        $('input[name="inspection_date_session"]').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                customRangeLabel: 'Definir rango'
            },
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()]
            },
            autoApply: true,
            singleDatePicker: true
        });
    });
</script>
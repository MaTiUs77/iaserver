<div class="well">

    <div class="pull-right">
        @include('iaserver.common.datepicker',[
            'date_session' => Session::get('date_session'),
            'route' => route('aoicollector.stat.show',[$maquina->id, $turno , 0])
        ])
    </div>

    <div class="btn-group btn-group-sm pull-right" style="padding: 2px 5px 0px 0px;">
        <a href="{{ route('aoicollector.stat.show',[$maquina->id,'M',$fecha]) }}" class="btn btn-default {{ $turno == 'M' ? 'active' : '' }}">Mañana</a>
        <a href="{{ route('aoicollector.stat.show',[$maquina->id,'T',$fecha]) }}" class="btn btn-default {{ $turno == 'T' ? 'active' : '' }}">Tarde</a>
    </div>

    <a href="{{ route('aoicollector.inspection.index') }}" class="btn btn-info">Ir a Inspecciones</a>
    @if( Request::segment(2) == 'stat' )
        <a href="{{ route('aoicollector.stat.resume') }}" class="btn btn-info">Ver resumen</a>
    @endif
</div>
<div class="list-group ">
    @foreach( \IAServer\Http\Controllers\Aoicollector\Model\Maquina::groupBy('linea')->orderBy('linea')->get() as $mac)
        <?php
            // Permite definir un estilo, dependiendo el estado de la maquina
            $offline_style = '';
            $icon = 'glyphicon-chevron-right';
            if( $mac->active == 0 ) {
                $offline_style = 'color: #ffffff;background-color: #d2322d;border-color: #ac2925;' ;
                $icon = 'glyphicon-ban-circle';
            }
            // Fin
        ?>
        <a href="{{ route('aoicollector.stat.show',[$mac->id, $turno, $fecha]) }}" class="list-group-item {{ $mac->linea == $maquina->linea  ? 'active' : '' }}" style="{{ $offline_style }}">
            <span class="glyphicon {{ $icon }}"></span>
            <span class="badge" style="width: 30px;">{{ $mac->tipo }}</span>
            SMD-{{ $mac->linea }}
        </a>
        @endforeach
</div>

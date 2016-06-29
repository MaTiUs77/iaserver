<div class="list-group">
    @foreach( $maquinas as $mac)
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
        <a href="{{ route('aoicollector.inspection.show',$mac->id) }}" class="list-group-item {{ $mac->id == $maquina->id && !isset($insp->error) ? 'active' : '' }}" style="{{ $offline_style }}" tooltip-placement="left" tooltip="{{ $mac->maquina }}">
            <span class="glyphicon {{ $icon }}"></span>
            <span class="badge" style="width: 30px;">{{ $mac->tipo }}</span>
            SMD-{{ $mac->linea }}
        </a>
        @endforeach
</div>

<div class="list-group">
    @foreach(\IAServer\Http\Controllers\Aoicollector\Model\Produccion::vista()->groupBy('linea')->orderBy('numero_linea')->get()  as $prod)
        <?php
            // Permite definir un estilo, dependiendo el estado de la maquina
            $offline_style = '';
            $icon = 'glyphicon-chevron-right';
            if( $prod->op == null ) {
                $offline_style = 'color: #aaaaaa;background-color: #efefef;' ;
                //$offline_style = 'color: #ffffff;background-color: #d2322d;border-color: #ac2925;' ;
                $icon = 'glyphicon-time';
            }
            // Fin
        ?>
        @if(isset($prod->id_maquina) )
            <a href="{{ isset($url_before) ? $url_before.'/' : '' }}{{ is_numeric($prod->numero_linea) ? $prod->numero_linea : $prod->barcode }}{{ isset($url_after) ? $url_after  : ''}}" class="list-group-item {{ (isset($current) && $current == $prod->linea) ? 'active' : ''  }}" style="{{ $offline_style }}">
                <span class="glyphicon {{ $icon }}"></span>
                <span class="badge" style="width: 30px;">{{ $prod->tipo }}</span>
                {{ $prod->linea }}
            </a>
        @endif
        @endforeach
</div>

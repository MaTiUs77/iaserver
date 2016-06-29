    <h3>Lista de ingenieria</h3>

    @if(!is_object($ingenieria))
        <span class="label label-danger" style="font-size: 14px;">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            {{ $ingenieria  }}
        </span>
    @else
        <small>Material</small>
        {{ (isset($ingenieria->componente)) ? $ingenieria->componente : 'Imposible extraer datos' }}

        <small>Firmware</small>
        {{ (isset($ingenieria->firmware)) ? $ingenieria->firmware : 'Imposible extraer datos' }}
    @endif

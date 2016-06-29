<!-- PROGRESSBAR -->
@if(isset($leyend))
    <div>
        {{  $now }} / {{  $max }}
        <div class="pull-right">
            @if(($now - $max)>0)
                +
            @endif

            @if(($now - $max)!=0)
                {{ ($now - $max) }}
            @endif

        </div>
    </div>
@endif

<?php
    if($max <= 0) { $max = 1; }
    $porcentaje =  number_format((($now / $max) * 100), 1, '.', '');
?>

@if(isset($stacked))
    <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar" style="width:{{ $porcentaje }}%">
            {{ $porcentaje }}%
        </div>
        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:10%">
            10
        </div>
    </div>
@else
<div class="progress" style="margin-bottom: 5px; {{ isset($height) ? 'height:'.$height.'px;' : '' }}">
    <div class="progress-bar progress-bar-{{ $type }} progress-bar-striped {{ (!isset($active)) ? 'active' : '' }}" role="progressbar" aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="{{ $max }}" style="width: {{ $porcentaje .'%' }}; min-width: 4em;">
        @if(isset($percent) && $percent)
            {{ $porcentaje }}%
        @else
            {{ $now }} / {{  $max }}
        @endif
    </div>
</div>
@endif
<!-- END PROGRESSBAR -->

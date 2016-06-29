<div style="font-size: 18px;width:200px;">
    <div style="text-align:center;">
        <label class="label label-info" style="font-size: 16px;"><b>{{ $leyend }}</b></label>

        <canvas width="200" height="90" id="id_gauge{{ $id }}" ng-init="makeGauge('id_gauge{{ $id }}',{{ $fpy }})"></canvas>

        <label class="label label-{{ $level }}" style="font-size: 20px;">FPY: <b>{{ $fpy }}%</b></label>

        <div style="margin:2px;padding:2px;"></div>

        <label class="label label-danger" tooltip-placement="top" tooltip="Bloques con errores detectados"><b>NG:</b> {{ $ng}}</label>
        &nbsp;
        <label class="label label-success" tooltip-placement="top" tooltip="Bloques sin defectos"><b>OK:</b> {{ $ok }} </label>
    </div>
</div>
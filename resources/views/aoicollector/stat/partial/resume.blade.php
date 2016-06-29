@if( isset($resume) )
    <div style="margin-top:2px;clear: both; ">

        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-6">
                <table class="table">
                <thead>
                <tr>
                    <th colspan="4">
                        <span style="font-weight: normal;">
                            El siguiente resumen esta basado en la
                            <b>{{  $resume_type=='first' ? 'primer' : 'ultima' }}</b>
                            inspeccion detectada de cada panel
                        </span>

                        <h1>
                            {{ $resume->programa }}
                            <label class="label label-default f15" tooltip-placement="top" tooltip="({{ $resume->bloques }}) Bloques por panel">x{{ $resume->bloques }}</label>
                        </h1>
                    </th>
                </tr>
                <tr>
                    <th>Paneles</th>
                    <th>Bloques</th>
                    <th>Ultima inspeccion</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><label class="label label-default center-block f15" ><b>{{ $resume->total_paneles }}</b> </label></td>
                    <td><label class="label label-default center-block f15" ><b>{{ $resume->total_bloques }}</b> </label></td>
                    <td><label class="label label-default center-block f15" ><b>{{ $resume->ultima_inspeccion }}</b> </label></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="label label-{{ $resume->level_falso }} center-block f15"  tooltip-placement="top" tooltip="Falsos: {{ $resume->total_falso }} / Bloques: {{ $resume->total_bloques }}">Promedio falsos errores: {{ $resume->promedio_falso_error }}</label>
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
            <div class="clearfix visible-xs-block"></div>
            <div class="col-xs-5 col-sm-4 col-md-3">
                <!-- MEDIDOR DE AOI -->
                @include('aoicollector.stat.partial.gauge',[
                    'id'=>1,
                    'leyend'=>'AOI',
                    'fpy'=>$resume->fpy_aoi,
                    'level'=>$resume->level_aoi,
                    'ng'=>$resume->ng_aoi,
                    'ok'=>$resume->ok_aoi
                ])
                        <!-- FIN -->
            </div>
            <div class="col-xs-5 col-sm-4 col-md-3">
                <!-- MEDIDOR DE INSPECTOR -->
                @include('aoicollector.stat.partial.gauge',[
                    'id'=>2,
                    'leyend'=>'INSPECTOR',
                    'fpy'=>$resume->fpy_insp,
                    'level'=>$resume->level_insp,
                    'ng'=>$resume->ng_ins,
                    'ok'=>$resume->ok_ins
                ])
                        <!-- FIN -->
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Ultima aparicion</th>
                <th><input type="text" ng-model="filter_referencia" ng-init="filter_referencia=''" class="form-control" placeholder="Filtrar referencia"/></th>
                <th>Total</th>
                <th>Falsos</th>
                <th>Reales</th>
            </tr>
            </thead>
            <tbody>
                @foreach($reference as $r)
                    <tr ng-hide="('{{ strtolower($r->referencia) }}').indexOf(filter_referencia.toLowerCase()) == -1" class="{{ ($r->total_real > 0) ? 'danger' : '' }}">
                        <td>
                            <?php
                            $ultima_aparicion = $fecha_eng. " ".$r->ultima_aparicion;
                            $ultima_inspeccion  = $fecha_eng. " ". $resume->ultima_inspeccion;

                            $diferencia = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $ultima_aparicion)->diff(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $ultima_inspeccion));

                            ?>
                            <span tooltip="Hace {{ $diferencia->h.'h, '. $diferencia->i.'m '}}">
                                {{ $r->ultima_aparicion }}
                            </span>
                        </td>
                        <td>{{ $r->referencia }}</td>
                        <td>{{ $r->total }}</td>
                        <td>{{ $r->total_falso }}</td>
                        <td>
                            @if($r->total_real > 0 )
                                <a href="{{  route('aoicollector.inspection.search.reference',[$r->referencia, $maquina->id, $turno, $fecha_eng, $resume->programa] ) }}" target="_blank">{{ $r->total_real  }}</a>
                            @else
                                0
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

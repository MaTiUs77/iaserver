@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector - Resumen de Estadisticas')
@section('body')
        @include('aoicollector.inspection.partial.stat.header')

        <table class="table table-striped">
            <thead>
                <th>Linea</th>
                <th>Programa</th>
                <th>OP</th>
                <th>Modelo</th>
                <th>Lote</th>
                <th>Panel</th>
                <th>FPY Aoi</th>
                <th>FPY Inspector</th>
                <th>Promedio falso error</th>
            </thead>
            <tbody>
        @foreach($resume as $linea)
            @foreach($linea as $item)
                <tr style="{{ ($item->op == 'SIN OP') ? 'background-color:#efefef;' : '' }}">
                    <td>
                        @if($item->total_bloques)
                            <a href="{{ route('aoicollector.stat.show',[$item->id_maquina, $item->turno, \IAServer\Http\Controllers\IAServer\Util::dateToEs($item->fecha), 'first', $item->programa, $item->op ] ) }}" target="_blank" class="btn btn-xs btn-primary btn-block">{{ $item->linea }}</a>
                        @else
                            <a href="javascript:;" class="btn btn-xs btn-default btn-block">{{ $item->linea }}</a>
                        @endif
                    </td>
                    <td>{{ $item->programa }}</td>
                    <td>{{ $item->op }}</td>
                    <td>{{ $item->modelo }}</td>
                    <td>{{ $item->lote }}</td>
                    <td>{{ $item->panel }}</td>
                @if($item->total_bloques)
                    <?php
                            $fpy_aoi = round((($item->total_bloques - $item->ng_aoi) / $item->total_bloques  ) * 100, 2);
                            $fpy_insp = round((($item->total_bloques - $item->ng_insp) / $item->total_bloques  ) * 100, 2);

                            $lvl = \IAServer\Http\Controllers\Aoicollector\Stat\StatController::dangerLevels($item->promedio_falso_error,$fpy_aoi,$fpy_insp);
                    ?>
                    <td>
                        <a href="javascript:;" type="button" class="btn  btn-xs btn-{{ $lvl->aoi }} tip" data-toggle="tooltip" data-placement="top" data-original-title="FPY segun Aoi" style="width:80px;">{{ $fpy_aoi }}%</a>
                    </td>
                    <td>
                        <a href="javascript:;" type="button" class="btn  btn-xs btn-{{ $lvl->insp }} tip" data-toggle="tooltip" data-placement="top" data-original-title="FPY segun Inspector" style="width:80px;">{{ $fpy_insp }}%</a>
                    </td>
                    <td>
                        <a href="javascript:;" type="button" class="btn  btn-xs btn-{{ $lvl->falso }} tip" data-toggle="tooltip" data-placement="top" data-original-title="Promedio" style="width:80px;">{{ $item->promedio_falso_error}}%</a>
                    </td>
                    @else
                        <td colspan="3"></td>
                    @endif
            @endforeach
        @endforeach
            </tbody>
        </table>
    @include('iaserver.common.footer')
@endsection
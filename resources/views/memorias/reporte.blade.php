@extends('angular')
@section('ng','app')
@section('title','Memorias - Reporte de produccion')
@section('body')


    <div class="well" style="height: 70px;">
            @include('iaserver.common.datepicker',[
                'from_session'=>Session::get('from_session'),
                'to_session'=>Session::get('to_session'),
                'route'=>route('memorias.reporte')])
    </div>

    <div class="container">
        <h2>Produccion por Operador</h2>
            Desde {{ Session::get('from_session') }} hasta {{ Session::get('to_session') }} / Memorias grabadas: <b>{{  $produccion->sum('cantidad')  }}</b>
        <hr>
        <div class="row">
            @if(count($produccion)>0)
                @foreach($produccion->groupBy('id_usuario')->keys()->all() as $id_user)
                    <?php
                        $user = $produccion->where('id_usuario',$id_user)->first();
                        $grabaciones = $produccion->where('id_usuario',$id_user);
                        $total = $produccion->sum('cantidad');
                    ?>
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <blockquote>
                            <h3>
                                {{ ucwords(strtolower($user->operador->profile->fullname()))  }}
                            </h3>
                            <small>Grabaciones</small>
                            {{ $grabaciones->sum('cantidad') }}

                            <small>Porcentaje</small>

                            @if($total>0)
                            {{  number_format((($grabaciones->sum('cantidad') / $total) * 100), 2, '.', '') }}%
                            @endif
                            <a ng-click="showDetail_{{ $user->id_usuario }}=!showDetail_{{ $user->id_usuario }}" class="btn btn-xs btn-block btn-default">Ver detalles</a>

                        </blockquote>

                        <ul class="list-group" ng-model="showDetail_{{ $user->id_usuario }}" ng-show="showDetail_{{ $user->id_usuario }}">
                            @foreach($grabaciones as $item)
                                <li class="list-group-item">
                                    <span class="badge">{{ $item->cantidad  }}</span>
                                    <a href="{{ route('trazabilidad.find.op',$item->op) }}" target="_blank" class="btn btn-xs btn-default">{{ $item->op }}</a>

                                    <small style="display: block;color: #939393;;">{{ $item->fecha }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @else
                <h4>No hay resultados</h4>
            @endif
        </div>

            <?php
                $prodchart = 'prodchart_'.rand(0,99999);
            ?>
            <div id="{{ $prodchart }}container" style="width: 95%;height:500px;"></div>
            <script>
                $(function () {
                    var {{ $prodchart }} = null;

                    var {{ $prodchart }}options = {
                        chart: {
                            renderTo: '{{ $prodchart }}container',
                            type: 'column'
                        },

                        rangeSelector: {
                            enabled: false
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: 'Detalle del periodo'
                        },
                        xAxis: {
                            type: 'datetime',
                            tickInterval: 24 * 3600 * 1000,
                            title: {
                                text: 'Fecha'
                            },
                            range: 24 * 3600 * 1000 // six months
                        },
                        yAxis: {
                            title: {
                                text: 'Total'
                            },
                            min: 0
                        },
                        legend: {
                            enabled: true

                        },
                        tooltip: {
                            useHTML: true,
                            backgroundColor: null,
                            borderWidth: 0,
                            shadow: false,
                            formatter: function () {
                                var s = '<b>' + Highcharts.dateFormat('%b %e', this.x) + '</b> ';
                                var hora = parseInt(Highcharts.dateFormat('%H', this.x));

                                $.each(this.points, function () {
                                    s += '<br/><span style="color:'+this.series.color+'" class="glyphicon glyphicon glyphicon-record"></span> '+this.series.name+': <b>' + this.y + '</b>';
                                });

                                var div = '<div style="background-color:#fffef2; padding: 5px; border-radius: 5px; box-shadow: 2px 2px 2px;" > ' + s + '</div>';
                                return div;
                            }
                        },
                        series: [
                            {
                            name: 'Grabaciones',
                            dataLabels: {
                                enabled: true,
                                borderRadius: 2,
                                backgroundColor: 'rgba(252, 255, 197, 0.7)',
                                borderWidth: 1,
                                borderColor: '#AAA',
                                y: -6
                            },
                            data: [
                            @foreach($periodoDiario as $periodo => $value)
                                    [moment("{{ $periodo }} -0000", "YYYY-MM-DD Z").valueOf(),{{ $value->sum('total') }}],
                            @endforeach
                            ],
                            }
                        ]
                    };

                    {{ $prodchart }} = new Highcharts.StockChart({{ $prodchart }}options);
                });
            </script>
    </div>

    @include('iaserver.common.footer')
    {!! IAScript('assets/highstock/js/highstock.js') !!}
    {!! IAScript('assets/moment.min.js') !!}

    <script>
        setTimeout('window.location.reload();', (60 * 1000) * 2);
    </script>
@endsection


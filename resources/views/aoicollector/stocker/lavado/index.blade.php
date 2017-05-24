@extends('adminlte/theme')
@section('ng','app')
@section('mini',true)
@section('nobox',true)
@section('title','Lavado de Stockers - Jornada')
@section('body')
<div class="container">
    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Lavados de stockers</h3>
            </div>

            <div class="box-body chart-responsive">
                <!-- BUSQUEDA -->
                <div class="row">
                    <div class="col-sm-4">
                        <form method="POST" action="{{ route('aoicollector.stocker.lavado.search') }}" >
                            <div class="input-group" >
                                <input type="text" name="stkbarcode" class="form-control" autocomplete="off" placeholder="Ingresar codigo de stocker" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary"> Ver historial</button>
                        </span>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-4 pull-right">
                        <form method="POST" action="{{ route('aoicollector.stocker.lavado.etiquetar') }}" >
                            <div class="input-group" >
                                <input type="text" name="stk" class="form-control" autocomplete="off" placeholder="Ingresar codigo de stocker" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success"> Iniciar lavado</button>
                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END BUSQUEDA -->
                <br>

                @if(isset($stockers) && count($stockers)>0)
                    <h4>Lavados de la jornada</h4>
                    <table class="table table-bordered table-striped datatable">
                    <thead>
                        <tr>
                            <th>Stocker</th>
                            <th>Ruta</th>
                            <th>Lavados historicos</th>
                            <th>Operador</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockers as $item)
                            <tr>
                                <td>{{ $item->barcode }}</td>
                                <td>
                                    @if($item->name==null)
                                        <div class="label label-danger">Sin ruta</div>
                                    @else
                                        <div class="label label-{{ $item->id_stocker_route == 2 ? 'primary' : 'success' }}">{{ $item->name }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->lavados()->count() }}</td>
                                <td>
                                    <?php
                                        $inspector = $item->inspector();
                                    ?>
                                    @if(isset($inspector))
                                        {{ $inspector->fullname  }}
                                    @else
                                        Desconocido
                                    @endif
                                </td>
                                <td>{{ $item->created_at ? $item->created_at  : 'Desconocido' }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No hay lavados en la jornada</p>
                @endif

            </div>
        </div>

    <!-- AREA CHART -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Lavados por dia</h3>
        </div>
        <div class="box-body chart-responsive">

            <div id="prodchart_26974container" style="width: 95%;height:500px;"></div>

            <script>

                $(function () {
                    var prodchart_26974 = null;
                    var prodchart_26974options = {
                        chart: {
                            renderTo: 'prodchart_26974container',
                            type: 'column'
                        },

                        rangeSelector: {
                            enabled: false
                        },
                        credits: {
                            enabled: false
                        },
                        navigator: {
                            enabled: true
                        },
                        title: {
                            text: ''
                        },
                        xAxis: {
                            title: {
                                text: 'Fecha'
                            },
                            type: 'datetime',
                            tickInterval: moment.duration(1, 'day').asMilliseconds(),
                            range:  moment.duration(1, 'week').asMilliseconds(),
                        },
                        yAxis: {
                            title: {
                                text: 'Total'
                            },
                            min: 0
                        },
                        legend: {
                            enabled: false
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
                                name: 'Lavados',
                                dataLabels: {
                                    enabled: true,
                                    borderRadius: 2,
                                    backgroundColor: 'rgba(252, 255, 197, 0.7)',
                                    borderWidth: 1,
                                    borderColor: '#AAA',
                                    y: -6
                                },
                                data: [
                                    @foreach($lavados as $item)
                                     [moment("{{ $item->fecha }} 00:00 -0000", "YYYY-MM-DD").valueOf(),{{ $item->lavados }}],
                                    @endforeach
                                ]}
                        ]
                    };

                    prodchart_26974 = new Highcharts.Chart(prodchart_26974options);
                });
            </script>


            {!! IAScript('assets/highstock/js/highstock-all.js') !!}
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</div>

    @include('iaserver.common.footer')
@endsection
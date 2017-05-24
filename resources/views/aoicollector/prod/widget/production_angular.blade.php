<div class="row" ng-show="aoiService.produccion.op">

    <!-- Stocker en Produccion-->
    <div class="col-sm-4 col-md-3 col-lg-3">
        @include('aoicollector.prod.widget.stocker_angular')
    </div>

    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 style="margin:0;">
                    @{{ aoiService.produccion.op }}
                    <small>
                        @{{ aoiService.produccion.smt.modelo}} -
                        @{{ aoiService.produccion.smt.panel}} -
                        @{{ aoiService.produccion.smt.lote}}
                    </small>
                </h2>

                <div class="box-tools pull-right" style="margin-top:10px;">
                    <span style="font-size:20px;padding: 5px;font-weight: normal;" class="label" ng-class="aoiService.produccion.wip.active ? 'label-success' : 'label-danger'">
                        <span ng-show="aoiService.produccion.wip.active">ACTIVA</span>
                        <span ng-hide="aoiService.produccion.wip.active">CERRADA</span>
                    </span>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <!-- MODO -->
                Modo:
                <span ng-if="aoiService.produccion.manual_mode==0">
                    <span style="padding: 5px;" class="label label-success">AOI</span>
                </span>
                <span ng-if="aoiService.produccion.manual_mode==1">
                    <span style="padding: 5px;" class="label label-warning">MANUAL</span>
                </span>
                <span ng-if="aoiService.produccion.manual_mode==2">
                    <span style="padding: 5px;" class="label label-info">MONTAJE</span>
                </span>
                 <span ng-if="aoiService.produccion.manual_mode==3">
                    <span style="padding: 5px;background-color: #ff8e09 !important;" class="label label-danger">COGISCAN->TH</span>
                </span>
                <!-- FIN MODO -->

               {{-- <!-- SFCS -->
                Puesto SFCS
                <span style="padding: 5px;" class="label label-primary">@{{ aoiService.produccion.sfcs.puesto }}</span>
                <span ng-if="aoiService.produccion.sfcs.declara==1">
                    <span style="padding: 5px;" class="label label-info">DECLARA</span>
                </span>
                <span ng-if="aoiService.produccion.sfcs.declara==0">
                    <span style="padding: 5px;" class="label label-danger">NO DECLARA</span>
                </span>
                <!-- FIN SFCS -->--}}

                <!-- IAROUTE -->
                Puesto
                <span ng-show="aoiService.produccion.route">
                    <span style="padding: 5px;" class="label label-primary">@{{ aoiService.produccion.route.name }}</span>
                    <span ng-if="aoiService.produccion.route.declare==1">
                        <span style="padding: 5px;" class="label label-info">DECLARA</span>
                    </span>
                    <span ng-if="aoiService.produccion.route.declare==0">
                        <span style="padding: 5px;" class="label label-danger">NO DECLARA</span>
                    </span>
                    <span class="pull-right">
                        <b>@{{ aoiService.produccion.route.qty_etiquetas }}</b> Etiquetas /
                        <b>@{{ aoiService.produccion.route.qty_bloques }}</b> Placas
                    </span>
                </span>
                <span ng-hide="aoiService.produccion.route">
                    <span style="padding: 5px;" class="label label-danger">SIN DEFINIR</span>
                </span>
                <!-- FIN IAROUTE -->

            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="box box-primary box-solid ">
            <div class="box-header with-border">
                <h1 class="box-title">IAServer <small style="color: #ffffff;">Produccion</small></h1>

                <div class="box-tools pull-right" style="margin-top:5px;">
                    <span style="font-size:15px;padding: 5px;font-weight: normal;" class="label label-info">
                       Producir:  @{{ aoiService.produccion.wip.wip_ot.start_quantity }}
                    </span>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">

                <h4 ng-if="aoiService.produccion.smt.prod_aoi == aoiService.produccion.smt.qty" style="margin: 0;">
                    Produccion completa!
                </h4>

                <div class="progress" style="margin-bottom: 5px; ">
                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="@{{ aoiService.produccion.smt.porcentaje }}" aria-valuemin="0" aria-valuemax="@{{ aoiService.produccion.smt.qty }}" style="width: @{{ aoiService.produccion.smt.porcentaje }}%; min-width: 4em;">
                        @{{ aoiService.produccion.smt.porcentaje }}%
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 border-left">
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{ aoiService.produccion.smt.prod_aoi }}
                            </h5>
                            <span class="description-text">PRODUCCION</span>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6" >
                        <div class="description-block">
                            <h5 class="description-header">
                                <div ng-if="aoiService.produccion.smt.prod_aoi > aoiService.produccion.smt.qty">
                                    +@{{ aoiService.produccion.smt.prod_aoi - aoiService.produccion.smt.qty }}
                                </div>

                                <div ng-if="aoiService.produccion.smt.prod_aoi < aoiService.produccion.smt.qty">
                                    @{{ aoiService.produccion.smt.qty - aoiService.produccion.smt.prod_aoi  }}
                                </div>
                            </h5>

                            <span ng-if="aoiService.produccion.smt.prod_aoi < aoiService.produccion.smt.qty" class="description-text">RESTANTES</span>
                            <span ng-if="aoiService.produccion.smt.prod_aoi > aoiService.produccion.smt.qty" class="description-text">EXCEDENTE</span>

                            <span ng-if="aoiService.produccion.smt.prod_aoi == aoiService.produccion.smt.qty">
                                    <i class="fa fa-thumbs-o-up fa-3x text-success"></i>
                            </span>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">

                    <div class="col-xs-6 col-sm-6 col-md-4">
                        <div class="description-block">
                            <h5 class="description-header" ng-hide="aoiService.produccion.controldeplacas.error">
                                @{{ aoiService.produccion.controldeplacas }}
                                <small style="color: #ff0000;">-@{{ aoiService.produccion.smt.qty - aoiService.produccion.controldeplacas }}</small>
                            </h5>

                            <h5 class="description-header" ng-show="aoiService.produccion.controldeplacas.error">
                                @{{ aoiService.produccion.controldeplacas.error }}
                            </h5>

                            <span class="description-text">CONTROL</span>
                        </div>
                        <!-- /.description-block -->
                    </div>


                   {{-- <div class="col-xs-6 col-sm-6 col-md-4 border-left">
                        <div class="description-block">
                            <h5 class="description-header">0</h5>
                            <span class="description-text">REPARACION</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 border-left">
                        <div class="description-block">
                            <h5 class="description-header">0</h5>
                            <span class="description-text">SCRAP</span>
                        </div>
                        <!-- /.description-block -->
                    </div>--}}


                </div>
                <!-- /.row -->
            </div>
        </div>
    </div>

    <div class="col-sm-4" ng-hide="aoiService.produccion.route.declare==0">
        <div class="box box-solid" ng-class="aoiService.produccion.wip.active ? 'box-primary' : 'box-danger'">
            <div class="box-header with-border">
                <h3 class="box-title">EBS <small style="color: #ffffff;">Declaraciones</small></h3>
            </div>
            <div class="box-body" ng-show="aoiService.produccion.wip.active" >
                <div class="progress" style="margin-bottom: 5px; ">
                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="@{{ aoiService.produccion.wip.wip_ot.porcentaje }}" aria-valuemin="0" aria-valuemax="@{{ aoiService.produccion.wip.wip_ot.quantity_completed }}" style="width: @{{ aoiService.produccion.wip.wip_ot.porcentaje }}%; min-width: 4em;">
                        @{{ aoiService.produccion.wip.wip_ot.porcentaje }}%
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 border-left">
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{ aoiService.produccion.wip.wip_ot.quantity_completed }}
                            </h5>
                            <span class="description-text">DECLARADO</span>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6" >
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{  aoiService.produccion.wip.wip_ot.restante }}
                            </h5>
                            <span class="description-text">RESTANTES</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-body" ng-hide="aoiService.produccion.wip.active" >
                <div class="row">
                    <div class="col-xs-12">
                        <div class="description-block">
                            <h5 class="description-header">
                                <span style="padding: 5px;font-weight: normal;font-size: 20px;" class="label label-danger">
                                    <span>OP CERRADA</span>
                                </span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{ aoiService.produccion.wip.transactions.pendientes }}
                            </h5>
                            <span class="description-text">PENDIENTES</span>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 border-left">
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{ aoiService.produccion.wip.transactions.errores }}
                            </h5>
                            <span class="description-text">ERRORES</span>
                        </div>
                    </div>
                   {{-- <div class="col-xs-12 col-sm-12 col-md-4 border-left">
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{ aoiService.produccion.wip.transactions.solicitudes }}
                            </h5>
                            <span class="description-text">PEDIDOS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>--}}
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
    </div>

    <!-- Detalle de transacciones WIP -->
    <table class="table table-bordered table-striped table-hover" ng-if="aoiService.produccion.route.declare==1">
        <thead>
        <tr style="text-align: center;">
            <th>DB</th>
            <th>Solicitudes</th>
            <th>Codigo</th>
            <th>Detalle de codigo</th>
            <th>Ebs Trans Error</th>
        </tr>
        </thead>
        <tbody>
        <tr style="text-align: center;" ng-repeat="n in aoiService.produccion.wip.transactions.detail.wip_serie" >
            <td>WIP_SERIE</td>
            <td>@{{ n.total }}</td>
            <td>
                <span class="label label-success" ng-if="n.trans_ok == 1">@{{ n.trans_ok }}</span>
                <span class="label label-danger" ng-if="n.trans_ok > 1">@{{ n.trans_ok }}</span>
                <span class="label label-warning" ng-if="n.trans_ok == 0">@{{ n.trans_ok }}</span>
            </td>
            <td>@{{ n.description }}</td>
            <td>@{{ n.ebs_error_trans }}</td>
        </tr>
        <tr style="text-align: center;" ng-repeat="n in aoiService.produccion.wip.transactions.detail.wip_history" >
            <td>WIP_SERIE_HISTORY</td>
            <td>@{{ n.total }}</td>
            <td>
				<span class="label label-success" ng-if="n.trans_ok == 1">@{{ n.trans_ok }}</span>
                <span class="label label-danger" ng-if="n.trans_ok > 1">@{{ n.trans_ok }}</span>
                <span class="label label-warning" ng-if="n.trans_ok == 0">@{{ n.trans_ok }}</span>
            </td>
            <td>@{{ n.description }}</td>
            <td>@{{ n.ebs_error_trans }}</td>
        </tr>
        </tbody>
    </table>

    <!-- Grafico de Produccion -->
    <div ng-controller="prodChartController" ng-show="aoiService.produccion.period" id="container" style="width: 90%;height:300px;"></div>

    <!-- Todos los stockers asignados a la OP -->
    <div class="row" ng-if="aoiService.produccion.allstocker">
        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-2" ng-repeat="stocker in aoiService.produccion.allstocker | orderBy: 'id_stocker_route'">
            <div class="panel panel-default">
                <div class="panel-body">
                    <span class="label pull-right" ng-class="stocker.id_stocker_route == 1 ? 'label-success' : 'label-primary'" style="padding:5px;">@{{stocker.unidades}}</span>
                    @{{ stocker.barcode }}

                    <div style="padding-top: 5px;border-top:1px solid #e2e2e2;">
                        <div class="label" ng-class="stocker.id_stocker_route == 1 ? 'label-success' : 'label-primary'">@{{ stocker.name }}</div>

                    </div>
                </div>
                <div style="color: #727272;font-size: 10px;text-align: center;background-color: #e3e3e3;">
                    @{{ stocker.created_at  }}
                </div>
            </div>
        </div>
    </div>
</div>

{!! IAScript('assets/highchart/js/highcharts.js') !!}

<script>
    var prodchart = null;
    var prodchartoptions = {
        chart: {
            renderTo: 'container',
            type: 'column',
            zoomType : 'x'
        },
        title: {
            text: 'Inspecciones unicas por hora'
        },
        xAxis: {
            type: 'datetime',
            tickInterval: 3600 * 1000,
            title: {
                text: 'Fecha'
            }
        },
        yAxis: {
            title: {
                text: 'Total'
            },
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e. %b}: {point.y}'
        },

        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    borderRadius: 5,
                    backgroundColor: 'rgba(252, 255, 197, 0.7)',
                    borderWidth: 1,
                    borderColor: '#AAA',
                    y: -6
                }
            }
        },
        series: []
    };
    $(function () {
        prodchart = new Highcharts.Chart(prodchartoptions);
    });

    function prodchartReset() {
        prodchart.destroy();
        prodchart = new Highcharts.Chart(prodchartoptions);
    }

</script>






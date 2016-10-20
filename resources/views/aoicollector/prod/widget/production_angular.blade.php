<div class="row" ng-show="aoiService.produccion.op">

    <!-- Stocker en Produccion-->
    <div class="col-sm-4 col-md-3 col-lg-3">
        @include('aoicollector.prod.widget.stocker_angular')
    </div>

    <div class="col-sm-8 col-md-9 col-lg-9">
        <div class="info-box" style="margin-bottom: 5px;">

            <span class="info-box-icon" ng-class="aoiService.produccion.wip.active ? 'bg-green' : 'bg-red'" style="width:150px;font-size: 28px;padding:0px 5px 0px 5px;margin-right:5px;">
                <text ng-show="aoiService.produccion.wip.active">
                    ACTIVA
                </text>
                 <text ng-hide="aoiService.produccion.wip.active">
                     CERRADA
                 </text>
            </span>

            <div class="info-box-content">

                <h1 style="margin: 0px;">
                    @{{ aoiService.produccion.op }}
                    <small>@{{ aoiService.produccion.wip.wip_ot.codigo_producto}}</small>
                </h1>
                <h4>
                @{{ aoiService.produccion.smt.modelo}} -
                @{{ aoiService.produccion.smt.panel}} -
                @{{ aoiService.produccion.smt.lote}}
                </h4>
            </div>
            <!-- /.info-box-content -->
        </div>

        <div style="border-bottom: 1px solid #efefef;margin-bottom: 5px;padding-bottom:5px;">
            <small>Modo:</small>
            <text ng-if="aoiService.produccion.manual_mode==0">
                <span style="padding: 5px;" class="label label-success">AOI</span>
            </text>
            <text ng-if="aoiService.produccion.manual_mode==1">
                <span style="padding: 5px;" class="label label-warning">MANUAL</span>
            </text>
            <text ng-if="aoiService.produccion.manual_mode==2">
                <span style="padding: 5px;" class="label label-info">MONTAJE</span>
            </text>

            <small>Puesto SFCS:</small>
            <span style="padding: 5px;" class="label label-primary">@{{ aoiService.produccion.sfcs.puesto }}</span>
            <span ng-if="aoiService.produccion.sfcs.declara==1">
                <span style="padding: 5px;" class="label label-info">DECLARA</span>
            </span>
            <span ng-if="aoiService.produccion.sfcs.declara==0">
                <span style="padding: 5px;" class="label label-danger">NO DECLARA</span>
            </span>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="box box-primary box-solid ">
            <div class="box-header with-border">
                <h3 class="box-title">IAServer <small style="color: #ffffff;">Inspecciones</small></h3>
            </div><!-- /.box-header -->
            <div class="box-body">

                <div class="row">
                    <div class="col-md-3" ng-show="aoiService.produccion.smt.prod_aoi != aoiService.produccion.smt.qty">
                         <span style="font-size:25px;height: 56px;line-height: 56px;" class="info-box-icon" ng-class="aoiService.produccion.smt.prod_aoi > aoiService.produccion.smt.qty ? 'bg-green' : 'bg-red'">
                            <span ng-if="aoiService.produccion.smt.prod_aoi > aoiService.produccion.smt.qty">
                                +@{{ aoiService.produccion.smt.prod_aoi - aoiService.produccion.smt.qty }}
                            </span>

                            <span ng-if="aoiService.produccion.smt.prod_aoi < aoiService.produccion.smt.qty">
                                @{{ aoiService.produccion.smt.qty - aoiService.produccion.smt.prod_aoi  }}
                            </span>
                         </span>
                    </div>
                    <div class="col-md-3" ng-show="aoiService.produccion.smt.prod_aoi == aoiService.produccion.smt.qty">
                         <span style="font-size:25px;height: 56px;line-height: 56px;" class="info-box-icon bg-green">
                             FIN
                         </span>
                    </div>
                    <div class="col-md-9">
                        <h3 style="margin-top: 0px;">@{{ aoiService.produccion.smt.prod_aoi }} <small>de</small>
                            @{{ aoiService.produccion.smt.qty }}
                        </h3>

                        <div class="progress" style="margin-bottom: 5px; ">
                            <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar" aria-valuenow="@{{ aoiService.produccion.smt.porcentaje }}" aria-valuemin="0" aria-valuemax="4740" style="width: @{{ aoiService.produccion.smt.porcentaje }}%; min-width: 4em;">
                                @{{ aoiService.produccion.smt.porcentaje }}%
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-4">
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{ aoiService.produccion.controldeplacas }}
                                <small style="color: #ff0000;">-@{{ aoiService.produccion.smt.qty - aoiService.produccion.controldeplacas }}</small>
                            </h5>
                            <span class="description-text">CONTROL</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-4 border-left">
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
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
    </div>

    <div class="col-sm-4" ng-hide="aoiService.produccion.sfcs.declara==0">
        <div class="box box-solid" ng-class="aoiService.produccion.wip.active ? 'box-primary' : 'box-danger'">
            <div class="box-header with-border">
                <h3 class="box-title">EBS <small style="color: #ffffff;">Declaraciones</small></h3>
            </div><!-- /.box-header -->
            <div class="box-body" ng-show="aoiService.produccion.wip.active" >
                <div class="row">
                    <div class="col-md-3">
                         <span style="font-size:25px;height: 56px;line-height: 56px;" class="info-box-icon" ng-class="aoiService.produccion.wip.wip_ot.restante > aoiService.produccion.wip.wip_ot.start_quantity ? 'bg-green' : 'bg-red'">
                            @{{  aoiService.produccion.wip.wip_ot.restante }}
                         </span>
                    </div>
                    <div class="col-md-9">
                        <h3 style="margin-top: 0px;">@{{ aoiService.produccion.wip.wip_ot.quantity_completed }} <small>de</small>
                            @{{ aoiService.produccion.wip.wip_ot.start_quantity }}
                        </h3>

                        <div class="progress" style="margin-bottom: 5px; ">
                            <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar" aria-valuenow="@{{ aoiService.produccion.wip.wip_ot.porcentaje }}" aria-valuemin="0" aria-valuemax="4740" style="width: @{{ aoiService.produccion.wip.wip_ot.porcentaje }}%; min-width: 4em;">
                                @{{ aoiService.produccion.wip.wip_ot.porcentaje }}%
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PROGRESSBAR -->
            </div><!-- /.box-body -->

            <div class="box-footer">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-4">
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{ aoiService.produccion.wip.transactions.pendientes }}
                            </h5>
                            <span class="description-text">PENDIENTES</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-4 border-left">
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{ aoiService.produccion.wip.transactions.errores }}
                            </h5>
                            <span class="description-text">ERRORES</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 border-left">
                        <div class="description-block">
                            <h5 class="description-header">
                                @{{ aoiService.produccion.wip.transactions.declaradas }}
                            </h5>
                            <span class="description-text">SOLICITUDES</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div><!-- /.box -->
    </div>

    <!-- Detalle de transacciones WIP -->
    <table class="table table-bordered table-striped table-hover" ng-if="aoiService.produccion.sfcs.declara==1">
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






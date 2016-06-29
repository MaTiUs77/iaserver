<div class="row" ng-show="aoiService.produccion.op">
    <!-- Stocker en Produccion-->
    <div class="col-sm-4 col-md-3 col-lg-3">
        @include('aoicollector.prod.widget.stocker_angular')
    </div>

    <!-- OP en Produccion -->
    <div class="col-sm-8 col-md-3 col-lg-3">
        <blockquote>
                <h2><b>@{{ aoiService.produccion.op }}</b>    </h2>
                <small>Modelo:</small>
                <b>@{{ aoiService.produccion.smt.modelo}}</b> - <b>@{{ aoiService.produccion.smt.panel}}</b> - <b>@{{ aoiService.produccion.smt.lote}}</b>

                <div ng-show="aoiService.produccion.wip.active">
                    <small>Estado:</small> <span style="padding: 5px;" class="label label-success">ACTIVA</span>
                </div>
                <div ng-hide="aoiService.produccion.wip.active">
                    <small>Estado:</small> <span style="padding: 5px;" class="label label-danger">CERRADA</span>
                </div>

                <div>
                    <small>Modo:</small>
                    <div ng-if="aoiService.produccion.manual_mode==0">
                        <span style="padding: 5px;" class="label label-success">AOI</span>
                    </div>
                    <div ng-if="aoiService.produccion.manual_mode==1">
                        <span style="padding: 5px;" class="label label-warning">MANUAL</span>
                    </div>
                    <div ng-if="aoiService.produccion.manual_mode==2">
                        <span style="padding: 5px;" class="label label-info">MONTAJE</span>
                    </div>
                </div>

                <small>Puesto SFCS:</small>
                <span style="padding: 5px;" class="label label-primary">@{{ aoiService.produccion.sfcs.puesto }}</span>
                <span ng-if="aoiService.produccion.sfcs.declara==1">
                    <span style="padding: 5px;" class="label label-info">DECLARA</span>
                </span>
                <span ng-if="aoiService.produccion.sfcs.declara==0">
                    <span style="padding: 5px;" class="label label-danger">NO DECLARA</span>
                </span>

                <div style="display: none">
                <small>Puesto AOI:</small>
                <span style="padding: 5px;" class="label label-primary">@{{ aoiService.produccion.puesto }}</span>
                <span ng-if="aoiService.produccion.declara==1">
                    <span style="padding: 5px;" class="label label-info">DECLARA</span>
                </span>
                <span ng-if="aoiService.produccion.declara==0">
                    <span style="padding: 5px;" class="label label-danger">NO DECLARA</span>
                </span>
                </div>

                <small>Semielaborado:</small> @{{ aoiService.produccion.wip.wip_ot.codigo_producto}}

                <div ng-show="aoiService.produccion.wip.active">
                    <small>Descripcion:</small> <div style="font-size: 12px;"> @{{ aoiService.produccion.wip.wip_ot.description }} </div>
                    <small>Cantidad de lote: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de lote segun WIP_OT"></span></small> @{{ aoiService.produccion.wip.wip_ot.start_quantity }}

                    <div ng-if="aoiService.produccion.sfcs.declara==1">
                        <small>Cantidad declarada: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Declaraciones segun WIP_OT"></span></small> @{{ aoiService.produccion.wip.wip_ot.quantity_completed }}
                        <small>Restante:</small> @{{ aoiService.produccion.wip.wip_ot.restante }}
                    </div>
                </div>

                <div ng-hide="aoiService.produccion.wip.active">
                    <small>Cantidad de lote: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de lote segun SMTDatabase"></span></small> @{{ aoiService.produccion.smt.qty }}
                    <small>Cantidad declarada: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Declaraciones segun Transacciones en WIP_SERIE"></span></small> @{{ aoiService.produccion.wip.transactions.declaradas  }}
                </div>


            </blockquote>
    </div>
    <div class="clearfix visible-sm-block"></div>

    <!-- Resumen de declaraciones WIP -->
    <div class="col-sm-6 col-md-6 col-lg-3" ng-if="aoiService.produccion.sfcs.declara==1">
        <blockquote>
        <h3>
            Resumen de transacciones
        </h3>
        <small>Solicitudes:</small> @{{ aoiService.produccion.wip.transactions.solicitudes }}
        <small>Declaradas:</small>  @{{ aoiService.produccion.wip.transactions.declaradas }}
        <small>Pendientes:</small>  @{{ aoiService.produccion.wip.transactions.pendientes }}
        <small>Errores:</small>  @{{ aoiService.produccion.wip.transactions.errores }}
        </blockquote>
    </div>

    <!-- Resumen de inspecciones IAServer -->
    <div class="col-sm-6 col-md-6 col-lg-3">
        <blockquote>
            <h3>
                Resumen de IAServer
            </h3>
            <small>Cantidad de lote: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de lote en SMTDatabase"></span></small>
            @{{ aoiService.produccion.smt.qty }}

            <small>Paneles en Aoi: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de paneles con @{{ aoiService.produccion.op }}, si se cambio manualmente la OP por otra, la diferencia se veria reflejada con el contador incremental"></span></small>
            @{{ aoiService.produccion.smt.registros }}

            <small>Contador incremental: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cada vez que se inspecciona un bloque, este contador se incrementa"></span></small>
            @{{ aoiService.produccion.smt.prod_aoi }}

            <small>Control de placas: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="En control de placas, el modelo,lote y panel, debe coincidir para tener el dato correcto de salida"></span></small>
            @{{ aoiService.produccion.controldeplacas.salidas }}
        </blockquote>
    </div>

    <!-- Detalle de transacciones WIP -->
    <table class="table table-bordered table-striped table-hover" ng-if="aoiService.produccion.sfcs.declara==1">
        <thead>
        <tr style="text-align: center;">
            <th>DB</th>
            <th>Solicitudes</th>
            <th>Trans_Ok</th>
            <th>Detalle</th>
            <th>Ebs Trans Error</th>
        </tr>
        </thead>
        <tbody>
        <tr style="text-align: center;" ng-repeat="n in aoiService.produccion.wip.transactions.detail.wip_serie" >
            <td>WIP_SERIE</td>
            <td>@{{ n.total }}</td>
            <td>
                @{{ n.trans_ok }}
            </td>
            <td>@{{ n.description }}</td>
            <td>@{{ n.ebs_error_trans }}</td>
        </tr>
        <tr style="text-align: center;" ng-repeat="n in aoiService.produccion.wip.transactions.detail.wip_history" >
            <td>WIP_SERIE_HISTORY</td>
            <td>@{{ n.total }}</td>
            <td>
                @{{ n.trans_ok }}
            </td>
            <td>@{{ n.description }}</td>
            <td>@{{ n.ebs_error_trans }}</td>
        </tr>
        </tbody>
    </table>

    <!-- Grafico de Produccion -->
    <div ng-show="aoiService.produccion.period" id="container" style="width: 90%;height:300px;"></div>

    <!-- Todos los stockers asignados a la OP -->
    <div class="row" ng-if="aoiService.produccion.allstocker">
        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-2" ng-repeat="stocker in aoiService.produccion.allstocker | orderBy: 'despachado'">
            <div class="panel panel-default">
                <div class="panel-body">
                    <span class="label label-success pull-right" style="padding:5px;">@{{stocker.unidades}}</span>
                    @{{ stocker.barcode }}

                    <div style="padding-top: 5px;border-top:1px solid #e2e2e2;">
                        <div class="label label-success" ng-if="stocker.despachado==0">Piso de planta</div>
                        <div class="label label-primary"ng-if="stocker.despachado==1">Control de placas </div>
                    </div>
                </div>
                <div style="color: #727272;font-size: 10px;text-align: center;background-color: #e3e3e3;">
                    @{{ stocker.updated_at  }}
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

    function prodchartReset()
    {
        prodchart.destroy();
        prodchart = new Highcharts.Chart(prodchartoptions);
    }
</script>






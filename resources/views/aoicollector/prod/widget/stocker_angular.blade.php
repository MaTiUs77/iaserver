<style>
    @-webkit-keyframes yellow-fade {
        0% {background: yellow; color:#000000;}
        100% {background: none;color: #ffffff;}
    }
    @keyframes yellow-fade {
        0% {background: yellow; color:#000000;}
        100% {background: none;color: #ffffff;}
    }

    #stocker_box a.panel_highlight {
        -webkit-animation: yellow-fade 1s ease-in 1;
        animation: yellow-fade 1s ease-in 1;
    }

    #stocker_box a.header {
        font-size:16px;
        background-color:#000000;
        color:#FFFFFF;
    }

    #stocker_box a.total {
        font-size:16px;
        background-color:#727272;
        color:#FFFFFF;
    }

    #stocker_box div.panel_trace {
        height:200px;
        overflow:auto;
    }

    #stocker_box div.panel_trace a.panel_item {
        font-size: 12px;
    }

    #stocker_box div.panel_trace a.panel_declarado {
        background-color:#7ed67e;
        color:#FFFFFF;
    }

    #stocker_box div.panel_trace a.panel_empty {
        background-color: #f8f8f8;
        color:#000000;
    }
    /*

    #stocker_box div.panel_trace a.panel_pendiente {
        background-color: #7cb3d6;
        color:#FFFFFF;
    }

    #stocker_box div.panel_trace a.panel_empty {
        background-color:#FFFFFF;
        color:#000000;
    }

    #stocker_box div.panel_trace a.panel_item {
        font-size:12px;
        padding:2px;
        margin:0px;
    }

    #stocker_box div.panel_trace a.panel_item:hover {
        background-color: #000000;
        color: #FFFFFF;
    }*/
</style>

<div ng-hide="stockerService.barcode" >
    <div class="panel panel-danger" ng-show="aoiService.produccion.op && !stockerService.barcode">
        <div class="panel-heading">ATENCION</div>
        <div class="panel-body">
            Debe asignar un stocker!, puede usar la opcion
            <b>"Asignar stocker"</b><br>
            o bien realizando un Scan directamente al codigo del stocker.
        </div>
    </div>
</div>

<div id="stocker_box" class="list-group" ng-show="stockerService.barcode">
    <!-- STOCKER HEADER -->
    <a class="list-group-item header">
        <div class="text-center">Codigo: @{{ stockerService.barcode }}</div>

        <div ng-show="inspectorService.id">
            <div class="text-center" ng-hide="stockerConfigMode">
                <small>Stocker x@{{stockerService.limite}} - Panel x@{{stockerService.bloques}} </small>
                <button type="button" class="btn btn-xs btn-default btn-block" ng-click="stockerConfigMode=!stockerConfigMode" ><span class="glyphicon glyphicon-wrench"></span> Cambiar configuracion</button>
            </div>

            <!-- CONFIGURADOR DE STOCKER -->
            <div ng-show="stockerConfigMode">
                <div class="row">
                    <div class="col-xs-6">
                        <input type="number" class="form-control" ng-model="stockerConfigModeNewlimite" placeholder="Filas de stocker"/>
                    </div>
                   <div class="col-xs-6">
                       <input type="number" class="form-control" ng-model="stockerConfigModeNewbloques" placeholder="Placas en panel"/>
                    </div>
                </div>
                <button type="button" class="btn btn-xs btn-success btn-block" ng-click="StockerConfigSave(stockerConfigModeNewlimite,stockerConfigModeNewbloques);" >Guardar</button>
                <button type="button" class="btn btn-xs btn-default btn-block" ng-click="stockerConfigMode=!stockerConfigMode" >Cancelar</button>
            </div>
            <!-- FIN CONFIGURADOR DE STOCKER -->
        </div>

    </a>
    <!-- FIN STOCKER HEADER -->

    <a href="javascript:;" class="list-group-item total">
        <div>Total: <span id="stocker_count_unity">@{{stockerService.unidades}}</span></div>
    </a>

    <div class="panel_trace">
        <a ng-attr-id="panel_@{{n}}" ng-repeat="n in [] | range:stockerService.limite" class="list-group-item panel_item" ng-class="n <= stockerService.paneles ? 'panel_declarado' : 'panel_empty'">
           Panel: @{{ n }}
        </a>
    </div>

</div>


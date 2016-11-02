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
    #stocker_box div.panel_trace a.panel_saved {
        background-color:#7ed67e;
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
    }
</style>

<div ng-hide="stockerService.stocker.barcode || stockerService.loading" >
    <div class="panel panel-danger" ng-show="aoiService.produccion.op && !stockerService.stocker.barcode">
        <div class="panel-heading">ATENCION</div>
        <div class="panel-body">
            Debe asignar un stocker!, puede usar la opcion
            <b>"Asignar stocker"</b><br>
            o bien realizando un Scan directamente al codigo del stocker.
        </div>
    </div>
</div>

{{-- SI EXISTE CONFIGURACION DE STOCKER, MUESTRO ESTADO DEL MISMO --}}
<div id="stocker_box" class="list-group" ng-show="stockerService.stocker.barcode">
    <!-- STOCKER HEADER -->
    <a class="list-group-item header">
        <div class="text-center">Codigo: @{{ stockerService.stocker.barcode }}</div>

        <div class="text-center" ng-hide="stockerConfigMode">
            <small>Stocker x@{{stockerService.stocker.limite}} - Panel x@{{stockerService.stocker.bloques}} </small>
            <button type="button" class="btn btn-xs btn-default btn-block" ng-click="stockerConfigMode=!stockerConfigMode" ><span class="glyphicon glyphicon-wrench"></span> Cambiar configuracion</button>
        </div>

        <!-- CONFIGURADOR DE STOCKER -->
        <div ng-show="stockerConfigMode">
                    <input type="number" class="form-control" ng-model="stockerConfigModeNewlimite" placeholder="Filas de stocker"/>
                    <input type="number" class="form-control" ng-model="stockerConfigModeNewbloques" placeholder="Bloques de panel"/>
            <button type="button" class="btn btn-xs btn-success btn-block" ng-click="stockerConfig('save');" >Guardar</button>
            <button type="button" class="btn btn-xs btn-default btn-block" ng-click="stockerConfigMode=!stockerConfigMode" >Cancelar</button>
        </div>
        <!-- FIN CONFIGURADOR DE STOCKER -->
    </a>
    <!-- FIN STOCKER HEADER -->

    <a href="javascript:;" class="list-group-item total">
        <div>Total: <span id="stocker_count_unity">@{{stockerService.stocker.unidades}}</span></div>
    </a>

    <!-- PANELES EN STOCKER -->
    <div class="panel_trace">
        <a id="panel_@{{n}}" ng-repeat="n in [] | range:(stockerService.stocker.limite)" class="list-group-item panel_item" ng-class="stockerService.contenido.paneles[n].declaracion.declarado ? 'panel_saved panel_highlight' : 'panel_empty'">
            #@{{ n }} @{{ stockerService.contenido.paneles[n].panel.panel_barcode }}
        </a>
    </div>
    <!-- FIN -->
</div>


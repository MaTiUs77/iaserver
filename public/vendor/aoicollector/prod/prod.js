app.factory("Aoi",["$q","IaCore",function(e,o){var r,t,n=10,c=!1,i={};return i.info=function(i,a){return r=e.defer(),c||(c=!0,t=o.http({url:"prod/info/"+i+"?filter=1&allstocker=1",method:"GET",timeout:n}),t.result.promise.then(function(e){c=!1,r.resolve(e)},function(e){c=!1,r.reject(e)})),r.promise},i.rerun=function(){c=!1},i.cancel=function(){t.cancel()},i}]),app.factory("Inspector",["$q","$rootScope","IaCore","toasty","Panel",function(e,o,r,t,n){var c,i=function(e,o){return 0===e.indexOf(o)},a={};return a.nodeInit=function(e){c=e,c.on("inspector:login:response",function(e,r){t.clear(r),e&&(e.error?(t.error({title:"Inspector",msg:e.error,timeout:2e3}),console.error("inspector:auth:response",e)):t.success({title:"Inspector",msg:"Bienvenido "+e.fullname,timeout:2e3})),console.log("inspector:login:response",e),o.inspectorService=e,o.$digest()}),c.on("inspector:logout:response",function(e,r){t.clear(r),e&&(e.error?(t.error({title:"Inspector",msg:e.error,timeout:2e3}),console.error("inspector:logout:response",e)):t.success({title:"Inspector",msg:"Sesion finalizada",timeout:2e3})),o.inspectorService={},o.$digest()})},a.auth=function(e){if((i(e,"LOGIN")||i(e,"DLOGIN"))&&e.length>5&&o.aoiService.produccion.barcode){var r=e.match(/\d+/);r&&(r=r[0]);var n=e.replace("DLOGIN","").replace("LOGIN",""),a=n.replace(r,""),s={name:a,userid:r,aoibarcode:o.aoiService.produccion.barcode};o.inspectorService&&o.inspectorService.id?t.wait({title:"Inspector",msg:"Finalizando sesion",timeout:!1,onAdd:function(){c.emit("inspector:logout",s,this.id)}}):t.wait({title:"Inspector",msg:"Buscando datos de inspector",timeout:!1,onAdd:function(){c.emit("inspector:login",s,this.id)}})}},a}]),app.factory("Panel",function(){var e=10,o=19,r={};return r.valid=function(r){return!!r&&!(!$.isNumeric(r)||r.length!=e&&r.length!=o)},r}),app.controller("prodController",["$scope","$rootScope","$http","$timeout","$interval","IaCore","Aoi","Stocker","Panel","Inspector","toasty","cfpLoadingBar",function(e,o,r,t,n,c,i,a,s,l,p,d){var u,m;o.lastScannerCommand={cmd:"",toastId:0},o.configprod={aoibarcode:c.storage({name:"aoibarcode"})},o.aoiService={},o.stockerService={},o.inspectorService={},o.socketserver="localhost:3333",u=ws(o.socketserver,{}),m=u.channel("inspectordash"),m.connect(function(r,t){return r?void console.log(r):(console.log("InspectorDash Connected"),p.wait({title:"Produccion",msg:"Descargando informacion",timeout:!1,onAdd:function(){m.emit("start",o.configprod.aoibarcode,this.id)}}),void e.$apply())}),m.on("start:response",function(r,t){console.log("start:response",r),r.trycatch?(p.clear(t),p.error({title:"Produccion",msg:r.trycatch,timeout:3e3})):(p.clear(t),r.error?p.error({title:"Produccion",msg:r.error,timeout:2e3}):p.success({title:"Produccion",msg:"Descarga completa",timeout:2e3}),o.aoiService=r,o.stockerService=r.produccion.stocker,o.inspectorService=r.produccion.inspector,a.autoscroll(o.stockerService.paneles)),setInterval(function(){m.emit("prod:info",o.configprod.aoibarcode)},1e3),e.$apply()}),m.on("stocker:channel:response",function(r){r=JSON.parse(r),console.log("stocker:channel:response",r),o.stockerService=r,a.autoscroll(o.stockerService.paneles),e.$apply()}),m.on("prodinfo:channel:response",function(r){r=JSON.parse(r),console.log("prodinfo:channel:response",r),o.aoiService=r,o.stockerService=r.produccion.stocker,o.inspectorService=r.produccion.inspector,a.autoscroll(o.stockerService.paneles),e.$apply()}),m.on("prod:info:response",function(r){console.log("prod:info:response",r),r.trycatch?p.error({title:"Produccion",msg:r.trycatch,timeout:3e3}):(r.error&&p.error({title:"Produccion",msg:r.error,timeout:2e3}),o.aoiService=r,o.stockerService=r.produccion.stocker,o.inspectorService=r.produccion.inspector,a.autoscroll(o.stockerService.paneles)),e.$apply()}),m.on("disconnect",function(){console.log("InspectorDash Disconnected"),p.warning({title:"Produccion",msg:"Desconectado del servidor"}),e.$apply()}),m.on("connect_error",function(){p.error({title:"Produccion",msg:"Error de conexion, servidor caido",timeout:5e3}),e.$apply()}),a.nodeInit(m),l.nodeInit(m),o.printError=function(o,r,t){if(void 0!=r.error&&(r=r.error),r)switch(t){case"modal":c.modalError(e,r);break;default:p.error({title:o,msg:r,timeout:5e3})}},o.restartAoiData=function(e){p.wait({title:"Configuracion",msg:"Aplicando cambios...",timeout:!1,onAdd:function(){}}),void 0!=e&&(c.storage({name:"aoibarcode",value:e}),o.configprod.aoibarcode=e),o.aoiService={},m.emit("start",o.configprod.aoibarcode)};o.$on("scannerEvent:enter",function(e,r){switch(o.lastScannerCommand.cmd){case"CMDSTKREM":a.remove(r.value),o.lastScannerCommand.cmd="",p.clear(o.lastScannerCommand.toastId);break;case"CMDPANREM":a.panelRemove(r.value),o.lastScannerCommand.cmd="",p.clear(o.lastScannerCommand.toastId);break;default:""==o.lastScannerCommand.cmd&&0===r.value.toUpperCase().indexOf("CMD")&&p.wait({title:"Comando de etiqueta",msg:"Esperando escaneo del elemento",timeout:!1,onAdd:function(){o.lastScannerCommand={cmd:r.value.toUpperCase(),toastId:this.id}}}),l.auth(r.value),a.add(r.value),a.panelAdd(r.value)}})}]),app.controller("prodChartController",["$rootScope",function(e){e.renderPeriodChart=function(){if(void 0!=e.aoiService.produccion.period){var o=e.aoiService.produccion.period.map(function(e){return e.op}),r=o.filter(function(e,r){return o.indexOf(e)==r});$.each(r,function(o,r){var t=e.aoiService.produccion.period.filter(function(e,o){if(e.op==r)return e}),n=[];$.each(t,function(e,o){var r=new Date;n.push([Date.UTC(r.getUTCFullYear(),r.getUTCMonth(),r.getUTCDate(),o.periodo.split(":")[0]),o.total])});var c=prodchart.series.map(function(e){return e.name}),i=c.indexOf(r);i<0?prodchart.addSeries({name:r,data:n}):prodchart.series[i].setData(n)})}}}]),app.controller("prodHeaderController",["$scope","$rootScope","IaCore",function(e,o,r){e.promptAoiSet=function(o){r.modal({scope:e,route:o,title:"AOI en produccion",type:"primary",controller:"promptAoiSetController"})},e.btnFormSelectOp=function(t){o.btnFormSelectOpProccessing||(o.btnFormSelectOpProccessing=!0,r.modal({scope:e,route:t,title:"Informacion de OP",type:"primary",controller:"btnFormSelectOpController"}))},e.promptStockerSet=function(o){r.modal({scope:e,route:o,title:"Nuevo stocker",type:"info",controller:"promptStockerSetController"})},e.promptStockerAddPanel=function(o){r.modal({scope:e,route:o,title:"Asignar panel a stocker",type:"warning",controller:"promptStockerAddPanelController"})},e.promptStockerReset=function(o){r.modal({scope:e,route:o,title:"Liberar stocker",type:"success",controller:"promptStockerRemoveController"})},e.promptStockerRemovePanel=function(o){r.modal({scope:e,route:o,title:"Remover panel de stocker",type:"danger",controller:"promptStockerRemovePanelController"})}}]),app.controller("promptStockerSetController",["$scope","$rootScope","$timeout","Stocker",function(e,o,r,t){var n=o.$on("modal:hide",function(e,t){r(function(){o.scannerListener=!0}),n()}),c=o.$on("modal:show",function(e,t){r(function(){o.scannerListener=!1}),c()});e.$on("prompt:enter",function(e,o){t.add(o.prompt_value),o.dialog.close()})}]),app.controller("promptStockerRemoveController",["$scope","$rootScope","$timeout","Stocker",function(e,o,r,t){var n=o.$on("modal:hide",function(e,t){r(function(){o.scannerListener=!0}),n()}),c=o.$on("modal:show",function(e,t){r(function(){o.scannerListener=!1}),c()});e.$on("prompt:enter",function(e,o){t.remove(o.prompt_value),o.dialog.close()})}]),app.controller("promptStockerAddPanelController",["$scope","$rootScope","$timeout","Stocker",function(e,o,r,t){var n=o.$on("modal:hide",function(e,t){r(function(){o.scannerListener=!0}),n()}),c=o.$on("modal:show",function(e,t){r(function(){o.scannerListener=!1}),c()});e.$on("prompt:enter",function(e,o){t.panelAdd(o.prompt_value),o.dialog.close()})}]),app.controller("promptStockerRemovePanelController",["$scope","$rootScope","$timeout","Stocker",function(e,o,r,t){var n=o.$on("modal:hide",function(e,t){r(function(){o.scannerListener=!0}),n()}),c=o.$on("modal:show",function(e,t){r(function(){o.scannerListener=!1}),c()});e.$on("prompt:enter",function(e,o){t.panelRemove(o.prompt_value),o.dialog.close()})}]),app.controller("promptAoiSetController",["$scope","$rootScope","$timeout","IaCore","Aoi",function(e,o,r,t,n){var c=o.$on("modal:hide",function(e,t){r(function(){o.scannerListener=!0}),c()}),i=o.$on("modal:show",function(e,t){r(function(){o.scannerListener=!1}),i()});e.$on("prompt:enter",function(e,r){o.restartAoiData(r.prompt_value),r.dialog.close()})}]),app.controller("btnFormSelectOpController",["$scope","$rootScope","$timeout","IaCore","Aoi",function(e,o,r,t,n){var c=o.$on("modal:hide",function(e,t){r(function(){o.scannerListener=!0,o.btnFormSelectOpProccessing=!1}),c()}),i=o.$on("modal:show",function(e,t){r(function(){o.scannerListener=!1,o.btnFormSelectOpProccessing=!1}),i()})}]),app.factory("Stocker",["$q","$rootScope","IaCore","toasty","Panel",function(e,o,r,t,n){var c,i=8,a=function(e,o){return 0===e.indexOf(o)},s={};return s.autoscroll=function(e){var o=$("#stocker_box div.panel_trace"),r=$("#panel_"+e);r.offset()&&o.animate({scrollTop:r.offset().top-o.offset().top+o.scrollTop()})},s.nodeInit=function(e){c=e,c.on("stocker:info:response",function(e,r){t.clear(r),e&&(e.error?console.log("Error",e):(o.stockerService=e,s.autoscroll(o.stockerService.stocker.paneles))),o.$digest()}),c.on("stocker:add:response",function(e,r){t.clear(r),e&&(e.error?t.error({title:"Stocker",msg:e.error,timeout:5e3}):(t.success({title:"Stocker",msg:"Agregado correctamente",timeout:2e3}),o.stockerService=e)),console.log("stocker:add:response",e),o.$digest()}),c.on("stocker:remove:response",function(e,r){t.clear(r),e&&(e.error?t.error({title:"Stocker",msg:e.error,timeout:5e3}):(t.success({title:"Stocker",msg:"Libreado correctamente",timeout:2e3}),o.stockerService={})),console.log("stocker:remove:response",e),o.$digest()}),c.on("panel:add:response",function(e,r){t.clear(r),e&&(e.error?t.error({title:"Stocker",msg:e.error,timeout:5e3}):(t.success({title:"Panel",msg:"Agregado correctamente",timeout:2e3}),o.stockerService=e,s.autoscroll(e.paneles))),console.log("panel:add:response",e),o.$digest()}),c.on("panel:remove:response",function(e,r){t.clear(r),e&&(e.error?t.error({title:"Stocker",msg:e.error,timeout:5e3}):t.success({title:"Panel",msg:"Removido correctamente",timeout:2e3})),console.log("panel:remove:response",e),o.$digest()})},s.valid=function(e){return!!e&&!(!a(e.toUpperCase(),"STK")||e.length!=i)},s.add=function(e){e=e.toUpperCase(),s.valid(e)&&t.wait({title:"Stocker",msg:"Agregando stocker a produccion",timeout:!1,onAdd:function(){c.emit("stocker:add",e,this.id)}})},s.remove=function(e){e=e.toUpperCase(),s.valid(e)&&t.wait({title:"Stocker",msg:"Liberando de produccion",timeout:!1,onAdd:function(){c.emit("stocker:remove",e,this.id)}})},s.panelAdd=function(e){n.valid(e)&&t.wait({title:"Panel",msg:"Agregando panel al stocker",timeout:!1,onAdd:function(){c.emit("panel:add",e,this.id)}})},s.panelRemove=function(e){n.valid(e)&&t.wait({title:"Panel",msg:"Removiendo panel de stocker",timeout:!1,onAdd:function(){c.emit("panel:remove",e,this.id)}})},s}]);
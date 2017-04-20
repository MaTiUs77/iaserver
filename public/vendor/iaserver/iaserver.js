var app=angular.module("app",["ngRoute","chieffancypants.loadingBar","ngAnimate","ui.bootstrap","angular-toasty","cfp.loadingBar"]);app.config(["cfpLoadingBarProvider",function(e){e.includeSpinner=!1}]),app.config(["toastyConfigProvider",function(e){e.setConfig({sound:!0,shake:!1,showClose:!1,clickToClose:!0,theme:"bootstrap"})}]),app.directive("dynamicbar",function(){return{scope:{data:"="},link:function(e,t){t.sparkline(e.data,{type:"line",height:"30",width:"120",barWidth:8,barSpacing:3,barColor:"#65edae",negBarColor:"#ff5656"})}}}),app.directive("dynamicknob",function(){return{link:function(e,t){t.knob()}}}),app.directive("hourAgo",function(){return function(e,t,o){var n="HH:mm:ss",r=moment(o.hourAgo,n),a=o.refresh;void 0==a&&(a=60),t.html("Calculando...");var i=function(){var e=(new Date).toTimeString(),o=moment(e,n),a=moment.duration(o-r);t.html(a.hours()+"h, "+a.minutes()+"m")};i(),setInterval(i,1e3*a)}}),app.directive("modalBtn",function(e,t){return{restrict:"E",replace:!0,transclude:!0,template:"<button ng-transclude></button>",link:function(e,t,o){var n="",r=BootstrapDialog.TYPE_PRIMARY;n=o.header?o.header:o.tooltip?o.tooltip:t.text(),t.bind("click",function(){e.openModal(o.load,n,r,o.controller)})}}}),app.directive("ngAdmin",function(){return function(e,t,o){e.$watch(o.ngAdmin,function(e){e?t.show():t.hide()},!0)}}),app.directive("ngEnter",function(){return function(e,t,o){t.bind("keydown keypress",function(t){13===t.which&&(e.$apply(function(){e.$eval(o.ngEnter)}),t.preventDefault())})}}),app.directive("ngShortcut",function(){return function(e,t,o){var n=o.ngShortcut,r=n.split(",");r.length>0&&angular.forEach(r,function(t){var o=t.split("=>"),n=o[0],r=o[1];shortcut.add(n,function(){e.$eval(r)})})}}),app.controller("datapickerController",["$scope",function(e){e.open=function(t){t.preventDefault(),t.stopPropagation(),e.datepickerOpened=!0},e.dateOptions={formatYear:"yy",startingDay:1}}]),app.controller("promptController",["$scope","$http","$rootScope",function(e,t,o){var n,r=o.$on("modal:show",function(e,t){n=t,r()}),a=o.$on("modal:hide",function(e,t){a()});e.promptEnter=function(t){t&&(n.prompt_value=t,e.$emit("prompt:enter",n))}}]),app.controller("scannerController",["$scope","$rootScope",function(e,t){console.log("ScannerController Loaded"),t.scannerListener=!0,e.scannerValue="",e.scannerReset=!1,e.scannerEvent=function(o){if(t.scannerListener){var n=o.keyCode?o.keyCode:o.which;switch(n){case 13:e.$emit("scannerEvent:enter",{value:e.scannerValue}),e.scannerReset&&(e.scannerValue="");break;default:if(16!=n){var r=String.fromCharCode(n);e.scannerValue+=r}}e.scannerReset=!0}}}]),app.filter("porcentaje",["$window",function(e){return function(t,o,n){return o=angular.isNumber(o)?o:3,""!=n&&(n=n||"%"),e.isNaN(t)?"":Math.round(t*Math.pow(10,o+2))/Math.pow(10,o)+n}}]),app.filter("range",function(){return function(e,t){t=parseInt(t);for(var o=1;o<=t;o++)e.push(o);return e}}),app.factory("IaCore",["$http","$rootScope","$q","cfpLoadingBar","Modal",function(e,t,o,n,r){var a={};return a.http=function(t){var r,a,i=!1,c={};return c.canceled=!1,c.result=o.defer(),c.timeout=o.defer(),c.promise=null,c.cancel=function(){i=!0,c.timeout.resolve(),c.result.reject(),c.canceled=!0,n.complete()},t.timeout||(t.timeout=30),setTimeout(function(){i=!0,c.timeout.resolve()},1e3*t.timeout),a={method:t.method,url:t.url,cache:!1,timeout:c.timeout.promise},t.data&&(a.data=t.data),r=e(a),r.success(function(e,t,o,n){c.result.resolve(e)}),r.error(function(e,o,n,r){i?c.canceled||c.result.reject({error:"HTTP Timeout ("+t.timeout+" seg)"}):0===o?c.result.reject({error:"No se detecto conexion de red"}):c.result.reject({error:"ERROR: "+o})}),c},a.modal=function(e){var o=BootstrapDialog.TYPE_PRIMARY;switch(e.type){case"danger":o=BootstrapDialog.TYPE_DANGER;break;case"default":o=BootstrapDialog.TYPE_DEFAULT;break;case"success":o=BootstrapDialog.TYPE_SUCCESS;break;case"warning":o=BootstrapDialog.TYPE_WARNING;break;case"info":o=BootstrapDialog.TYPE_INFO;break;case"primary":o=BootstrapDialog.TYPE_PRIMARY}t.modalOpened||r.create(e.scope,e.title,e.route,o,e.controller,e.ignoreloadingbar)},a.modalError=function(e,o){t.modalOpened||r.error(e,o)},a.storage=function(e){if(!e.value){var t=window.localStorage.getItem(e.name);return e.json?JSON.parse(t):t}var o=e.value;e.json&&(o=JSON.stringify(e.value)),window.localStorage.setItem(e.name,o)},a}]),app.factory("Modal",["$http","$compile","$rootScope","$timeout",function(e,t,o,n){var r,a,i={};return i.build=function(e,t,n){void 0==n&&(n=BootstrapDialog.TYPE_PRIMARY),BootstrapDialog.show({type:n,title:e,message:t,onshown:function(e){o.modalOpened=!0,$("input.focus").focus(),r.$emit("modal:show",{modalscope:r,parentscope:a,dialog:e})},onhide:function(e){o.modalOpened=!1,r.$emit("modal:hide",{modalscope:r,parentscope:a,dialog:e}),r.$destroy()}})},i.create=function(o,n,c,u,l,s){return void 0==s&&(s=!0),a=o,r=o.$new(),e.get(c,{ignoreLoadingBar:s}).then(function(e){var o=e.data;return l&&(o='<div ng-controller="'+l+'">'+e.data+"</div>"),i.build(n,t(o)(r),u),r},function(e){var t="Ocurrio un error ("+e.status+") durante la operacion, intente nuevamente en unos minutos, si el problema persiste consulte con el supervisor de programacion de automatica";l&&(t='<div ng-controller="'+l+'">'+t+"</div>"),i.error(o,t)})},i.error=function(e,o){a=e,r=e.$new();var n='<div class="alert alert-danger">'+o+"<div>";return i.build("ERROR",t(n)(r),BootstrapDialog.TYPE_DANGER),r},i}]);
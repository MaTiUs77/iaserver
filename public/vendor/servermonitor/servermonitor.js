app.directive("dynchart",function(){return{scope:{data:"="},link:function(o,n){n.sparkline(o.data,{type:"line",width:"100%",height:"50",lineWidth:2,maxSpotColor:"#ff0000",spotRadius:3,normalRangeMin:0,normalRangeMax:80,normalRangeColor:"#e5e5e5"})}}}),app.controller("servidorMonitorController",["$scope","$http","$interval","toasty",function(o,n,e,r){o.serverList=[];var t=io.connect("http://ARUSHAP34:8081");t.on("connect_error",function(o){console.log("Error de conexion, servidor caido",o)}),t.on("redisError",function(o){console.log(o)}),t.on("disconnect",function(){console.log("Conexion finalizada")}),t.on("connect",function(){console.log("Conectado"),t.emit("subscribe","servermonitor")}),t.on("subscribeResponse",function(o){console.log(o)}),t.on("message",function(n){for(var e=JSON.parse(n),r=!1,t=0;t<o.serverList.length;t++)if(o.serverList[t].nombre==e.nombre){o.serverList[t]=e,r=!0;break}r||o.serverList.push(e),o.$apply()})}]);
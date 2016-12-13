app.factory('InventarioFactory',function($http,$location){
    var absurl = $location.absUrl();
    return{
        getLabelInfo:function(label){
            var url1 = '../inventario/consultar/etiqueta/info/'+label;
            return $http.get(url1);
        },
        getUdmFromDB:function(){
            return $http.get('../inventario/unidad_medida');
        },
        rePrint:function(lpn){
            return $http.get('../inventario/reimprimir/'+lpn.lpn+'/'+lpn.partNumber+'/'+lpn.cantidadContada+'/'+lpn.cantidadSegConteo+'/'+lpn.cantidadTerConteo);
        },
        getPartNumberInfo:function(ruta){
            return $http.get(ruta);
        },
        updLabel:function(label){
            console.log(label);
            return $http.get('../inventario/consultar/etiqueta/info/update/'+label.id_etiqueta+'/'+label.cantidadContada+'/'+label.cantidadSegConteo+'/'+label.cantidadTerConteo);
        }
       /* getUserInfo:function(user){
            return $http.get('../controlador/queryUserInfo.php',{params:{USER:user}}).success(function(response){
                var userInfo=[];
                userInfo.user= response.user;
                userInfo.ip = response.ip;
                userInfo.id_sector = response.id_sector;
                userInfo.id_planta = response.id_planta;
            });
        },*/
        /*insertPrintedLabel:function(pn,qty,zona,usuario,planta){
            return $http.get('../controlador/insertPrintedPN.php',{
                params:{
                    PN:pn,
                    QTY:qty,
                    Zona:zona,
                    User:usuario,
                    Planta:planta
                }
            });
        },*/
        /*print:function(pn,qty,selrb,desc,id_etiqueta,umedida,planta,zona,ip,printertype){
            return $http.get('../controlador/print.php',{
                params:{
                    PN:pn,
                    QTY:qty,
                    RB:selrb,
                    DESC:desc,
                    id_etiqueta:id_etiqueta,
                    uMedida:umedida,
                    Planta:planta,
                    Zona:zona,
                    IP:ip,
                    PrinterType:printertype
                }})
        },*/

        /*updUserInfo:function(user,planta,zona,ip){
            return $http.get('../controlador/updUserInfo.php',{params:{user:user,planta:planta,zona:zona,ip:ip}});
        },
        getSession:function(){
            return $http.get('../controlador/getSession.php');
            //return CSRF_TOKEN;
        },
        getPlant:function(){
            return $http.get('../controlador/getPlant.php');
        },
        getZones:function(planta){
            return $http.get('../controlador/getZone.php',{params:{planta:planta}});
        },*/

        /*getPrinterType:function(ip){
            return $http.get('../controlador/getPrinterType.php',{params:{ip:ip}});
        },
        getLastId:function(){
            return $http.get('../controlador/getLastId.php');
        },
        logOut:function(){
            return $http.get('../controlador/logout.php');
        }*/
    }
});

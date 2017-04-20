app.factory('Inspector',[
    "$q", "$rootScope", "IaCore", "toasty", "Panel",
    function($q, $rootScope, IaCore, toasty, Panel) {
    var socket;

    var strStartsWith = function (str, prefix) {
        return str.indexOf(prefix) === 0;
    };

    var interfaz = {};

    interfaz.nodeInit = function(_socket)
    {
        socket = _socket;

        socket.on('inspector:login:response', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    toasty.error({
                        title: "Inspector",
                        msg: result.error,
                        timeout: 2000
                    });
                    console.error('inspector:auth:response',result);
                } else {
                    toasty.success({
                        title: "Inspector",
                        msg: "Bienvenido " + result.fullname,
                        timeout: 2000
                    });
                }
            }

            console.log('inspector:login:response',result);
            $rootScope.inspectorService = result;
            $rootScope.$digest();
        });

        socket.on('inspector:logout:response', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    toasty.error({
                        title: "Inspector",
                        msg: result.error,
                        timeout: 2000
                    });
                    console.error('inspector:logout:response',result);
                } else {
                    toasty.success({
                        title: "Inspector",
                        msg: "Sesion finalizada",
                        timeout: 2000
                    });
                }
            }

            $rootScope.inspectorService = {};
            $rootScope.$digest();
        });
    };

    interfaz.auth = function(scannedValue) {
        if(
            (
                strStartsWith(scannedValue,"LOGIN")||
                strStartsWith(scannedValue,"DLOGIN")
            ) &&
            scannedValue.length > 5 &&
            $rootScope.aoiService.produccion.barcode
        ) {
            var userId = scannedValue.match( /\d+/ );
            if(userId)  { userId = userId[0]; }

            var userBarcode = scannedValue.replace("DLOGIN", "").replace("LOGIN", "");
            var userName = userBarcode.replace(userId, "");

            var credentials = {
                name : userName,
                userid : userId,
                aoibarcode: $rootScope.aoiService.produccion.barcode
            };

            if($rootScope.inspectorService && $rootScope.inspectorService.id)
            {
                toasty.wait({
                    title: "Inspector",
                    msg: "Finalizando sesion",
                    timeout: false,
                    onAdd: function(){
                        socket.emit('inspector:logout',credentials,this.id);
                    }
                });
            } else
            {
                toasty.wait({
                    title: "Inspector",
                    msg: "Buscando datos de inspector",
                    timeout: false,
                    onAdd: function(){
                        socket.emit('inspector:login',credentials,this.id);
                    }
                });
            }

            //$http({method:'POST',url:'prod/user/login',params:credentials})
        };
    };

    return interfaz;
}]);

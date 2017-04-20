app.factory('Stocker',[
    "$q", "$rootScope", "IaCore", "toasty", "Panel",
    function($q, $rootScope, IaCore, toasty, Panel) {
    var socket;

    var barcode_length = 8;

    var strStartsWith = function (str, prefix) {
        return str.indexOf(prefix) === 0;
    };

    var interfaz = {};

    interfaz.autoscroll = function(pos) {
        var container = $('#stocker_box div.panel_trace');
        var scrollTo = $('#panel_'+pos);
        if(scrollTo.offset()) {
            container.animate({
                scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()
            });
        }
    };

    interfaz.nodeInit = function(_socket)
    {
        socket = _socket;

        socket.on('stocker:info:response', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    console.log('Error',result);
                } else {
                    $rootScope.stockerService = result;
                    interfaz.autoscroll($rootScope.stockerService.stocker.paneles);
                }
            }
            $rootScope.$digest();
        });

        socket.on('stocker:add:response', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    toasty.error({
                        title: "Stocker",
                        msg: result.error,
                        timeout: 5000
                    });
                } else {
                    toasty.success({
                        title: "Stocker",
                        msg: "Agregado correctamente",
                        timeout: 2000
                    });

                    $rootScope.stockerService = result;
                }
            }


            console.log('stocker:add:response',result);

            $rootScope.$digest();
        });

        socket.on('stocker:remove:response', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    toasty.error({
                        title: "Stocker",
                        msg: result.error,
                        timeout: 5000
                    });
                } else {
                    toasty.success({
                        title: "Stocker",
                        msg: "Libreado correctamente",
                        timeout: 2000
                    });

                    $rootScope.stockerService = {};
                }
            }

            console.log('stocker:remove:response',result);

            $rootScope.$digest();
        });

        socket.on('panel:add:response', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    toasty.error({
                        title: "Stocker",
                        msg: result.error,
                        timeout: 5000
                    });
                } else {
                    toasty.success({
                        title: "Panel",
                        msg: "Agregado correctamente",
                        timeout: 2000
                    });

                    $rootScope.stockerService = result;
                    interfaz.autoscroll(result.paneles);
                }
            }

            console.log('panel:add:response',result);

            $rootScope.$digest();
        });

        socket.on('panel:remove:response', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    toasty.error({
                        title: "Stocker",
                        msg: result.error,
                        timeout: 5000
                    });
                } else {
                    toasty.success({
                        title: "Panel",
                        msg: "Removido correctamente",
                        timeout: 2000
                    });
                    //interfaz.autoscroll(result.paneles);
                    //$rootScope.stockerService = result;
                }
            }

            console.log('panel:remove:response',result);

            $rootScope.$digest();
        });
    };

    interfaz.valid = function(stkbarcode) {
        if(stkbarcode) {
            if(
                strStartsWith(stkbarcode.toUpperCase(),'STK') &&
                stkbarcode.length==barcode_length
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    };

    interfaz.add = function(stkbarcode) {
        stkbarcode = stkbarcode.toUpperCase();
        if (interfaz.valid(stkbarcode)) {
            toasty.wait({
                title: "Stocker",
                msg: "Agregando stocker a produccion",
                timeout: false,
                onAdd: function(){
                    socket.emit('stocker:add',stkbarcode,this.id);
                }
            });
        }
    };

    interfaz.remove = function(stkbarcode) {
        stkbarcode = stkbarcode.toUpperCase();
        if (interfaz.valid(stkbarcode)) {
            toasty.wait({
                title: "Stocker",
                msg: "Liberando de produccion",
                timeout: false,
                onAdd: function(){
                    socket.emit('stocker:remove',stkbarcode,this.id);
                }
            });
        }
    };

    interfaz.panelAdd = function(panelbarcode) {
        if(Panel.valid(panelbarcode)) {
            toasty.wait({
                title: "Panel",
                msg: "Agregando panel al stocker",
                timeout: false,
                onAdd: function(){
                    socket.emit('panel:add',panelbarcode,this.id);
                }
            });
        }
    };

    interfaz.panelRemove = function(panelbarcode) {
        if(Panel.valid(panelbarcode)) {
            toasty.wait({
                title: "Panel",
                msg: "Removiendo panel de stocker",
                timeout: false,
                onAdd: function(){
                    socket.emit('panel:remove',panelbarcode,this.id);
                }
            });
        }
    };

    return interfaz;
}]);

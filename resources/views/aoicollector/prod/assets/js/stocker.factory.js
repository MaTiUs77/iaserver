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

        socket.on('stockerDeclaredResponse', function (result,toastId) {
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

        socket.on('stockerInfoResponse', function (result,toastId) {
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

        socket.on('stockerAddResponse', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    $rootScope.printError('Stocker',result,'modal');
                } else {
                    toasty.success({
                        title: "Stocker",
                        msg: "Agregado correctamente",
                        timeout: 2000
                    });

                    $rootScope.stockerService = result;
                }
            }
            $rootScope.$digest();
        });

        socket.on('stockerRemoveResponse', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    $rootScope.printError('Stocker',result,'modal');
                } else {
                    toasty.success({
                        title: "Stocker",
                        msg: "Libreado correctamente",
                        timeout: 2000
                    });

                    $rootScope.stockerService = {};
                }
            }
            $rootScope.$digest();
        });

        socket.on('panelAddResponse', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    $rootScope.printError('Panel',result,'modal');
                } else {
                    toasty.success({
                        title: "Panel",
                        msg: "Agregado correctamente",
                        timeout: 2000
                    });

                    $rootScope.stockerService.stocker = result;
                }
            }
            $rootScope.$digest();
        });

        socket.on('panelRemoveResponse', function (result,toastId) {
            toasty.clear(toastId);

            if(result) {
                if(result.error) {
                    $rootScope.printError('Panel',result,'modal');
                } else {
                    toasty.success({
                        title: "Panel",
                        msg: "Removido correctamente",
                        timeout: 2000
                    });

                    $rootScope.stockerService.stocker = result;
                }
            }
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
                    socket.emit('stockerAdd',stkbarcode,this.id);
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
                    socket.emit('stockerRemove',stkbarcode,this.id);
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
                    socket.emit('panelAdd',panelbarcode,this.id);
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
                    socket.emit('panelRemove',panelbarcode,this.id);
                }
            });
        }
    };

    return interfaz;
}]);

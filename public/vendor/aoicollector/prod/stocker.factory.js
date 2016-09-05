app.factory('Stocker',function($q, IaCore, toasty) {
    var timeout = 10;
    var runinng;
    var result;
    var httpget;

    var barcode_length = 8;
    var panel_barcode_length = 10;
    var panelsony_barcode_length = 19;

    var strStartsWith = function (str, prefix) {
        return str.indexOf(prefix) === 0;
    }

    var interfaz = {};

    interfaz.handlehttp = function(url) {
        result = $q.defer();
        if(!runinng)
        {
            runinng = true;
            httpget = IaCore.http({
                url: url.join('/'),
                method: 'GET',
                timeout: timeout
            });

            httpget.result.promise.then(function(data) {
                runinng = false;
                result.resolve(data);
            },function(error) {
                runinng = false;
                result.reject(error);
            });
        }
        return result.promise;
    };
    interfaz.autoscroll = function(pos) {
        var container = $('#stocker_box div.panel_trace');
        var scrollTo = $('#panel_'+pos);
        if(scrollTo.offset()) {
            container.animate({
                scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()
            });
        }
    };
    interfaz.valid = function(stocker_barcode) {
        if(stocker_barcode) {
            if(
                strStartsWith(stocker_barcode.toUpperCase(),'STK') &&
                stocker_barcode.length==barcode_length
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    };
    interfaz.set = function(stocker_barcode,aoi_barcode) {
        new_stocker_barcode = stocker_barcode.toUpperCase();

        toasty.info({
            title: "Stocker",
            msg: "Agregando "+stocker_barcode,
            timeout: 5000
        });

        var url = [
            'stocker',
            'prod',
            'set',
            new_stocker_barcode,
            aoi_barcode
        ];

        return interfaz.handlehttp(url);
    };
    interfaz.remove = function(stocker_barcode) {
        stocker_barcode = stocker_barcode.toUpperCase();

        toasty.info({
            title: "Stocker",
            msg: "Liberando "+stocker_barcode,
            timeout: 5000
        });
        var url = [
            'stocker',
            'prod',
            'remove',
            stocker_barcode
        ];

        return interfaz.handlehttp(url);
    };

    /*
    interfaz.info = function(stocker_barcode) {
        var url = [
            'service',
            'aoicollector',
            'stocker',
            stocker_barcode
        ];
        return $http.get(Framework.url(url),{
            ignoreLoadingBar: true
        }).then(function(response) {
            if (typeof response.data === 'object') {
                return response.data.service;
            }
        });
    };
    interfaz.infoAll = function(op,aoi_barcode) {
        var url = [
            'service',
            'aoicollector',
            'stocker',
            'all',
            aoi_barcode,
            op
        ];
        return $http.get(Framework.url(url),{
            ignoreLoadingBar: true
        }).then(function(response) {
            if (typeof response.data === 'object') {
                return response.data.service;
            }
        });
    };
    interfaz.config = function(modelo_id, stocker_barcode,xstocker, xpanel) {
        var url = [
            'service',
            'aoicollector',
            'stocker',
            stocker_barcode
        ];
        var params = {
            "modelo_id":modelo_id,
            "stocker_por":xstocker,
            "declara_por":xpanel
        };
        return $http.put(Framework.url(url), JSON.stringify(params),{
            ignoreLoadingBar: true
        }).then(function(response) {
            if (typeof response.data === 'object') {
                return response.data.service;
            }
        });
    };


    interfaz.panelValid = function(panel_barcode) {
        if(panel_barcode) {
            if(
                $.isNumeric( panel_barcode )
                &&
                (
                panel_barcode.length==panel_barcode_length ||
                panel_barcode.length==panelsony_barcode_length
                )
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    };
    interfaz.panelRemove = function(panel_barcode) {
        var url = [
            'service',
            'aoicollector',
            'stocker',
            'panel',
            'remove',
            panel_barcode
        ];
        return $http.get(Framework.url(url),{
            ignoreLoadingBar: true
        }).then(function(response) {
            if (typeof response.data === 'object') {
                return response.data;
            }
        });
    };
    interfaz.panelAdd = function(panel_barcode,aoi_barcode,manual) {
        var mode = 'add';
        switch (manual) {
            case 1:
                mode = 'addmanualaoi';
                break;
            case 2:
                mode = 'addmanual';
                break;
        }
        var url = [
            'service',
            'aoicollector',
            'stocker',
            'panel',
            mode,
            panel_barcode,
            aoi_barcode
        ];
        return $http.get(Framework.url(url),{
            ignoreLoadingBar: true
        }).then(function(response) {
            if (typeof response.data === 'object') {
                return response.data;
            }
        });
    };
*/
    return interfaz;
});

app.factory('Panel',function($q, IaCore, toasty) {
    var barcode_length = 8;
    var panel_barcode_length = 10;
    var panelsony_barcode_length = 19;

    var timeout = 10,
        runinng,
        result,
        httpget;

    var strStartsWith = function (str, prefix) {
        return str.indexOf(prefix) === 0;
    };

    var interfaz = {};

    interfaz.handlehttp = function(url) {
        result = $q.defer();
        if(!runinng)
        {
            runinng = true;
            httpget = IaCore.http({
                url: url.join('/'),
                method: 'GET',
                timeout: timeout
            });

            httpget.result.promise.then(function(data) {
                runinng = false;
                result.resolve(data);
            },function(error) {
                runinng = false;
                result.reject(error);
            });
        }
        return result.promise;
    };
    interfaz.valid = function(panel_barcode) {
        if (panel_barcode) {
            if (
                $.isNumeric(panel_barcode)
                &&
                (
                    panel_barcode.length == panel_barcode_length ||
                    panel_barcode.length == panelsony_barcode_length
                )
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    };
    interfaz.add = function(panel_barcode,aoi_barcode) {
        toasty.info({
            title: "Panel",
            msg: "Agregando "+panel_barcode,
            timeout: 5000
        });

        var url = [
            'stocker',
            'panel',
            'add',
            panel_barcode,
            aoi_barcode
        ];

        return interfaz.handlehttp(url);
    };
    interfaz.remove = function(panel_barcode) {
        toasty.info({
            title: "Panel",
            msg: "Removiendo "+panel_barcode,
            timeout: 5000
        });

        var url = [
            'stocker',
            'panel',
            'remove',
            panel_barcode
        ];

        return interfaz.handlehttp(url);
    };

    return interfaz;
});

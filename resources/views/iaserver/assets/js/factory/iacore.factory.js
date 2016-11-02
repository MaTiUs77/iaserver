app.factory('IaCore',[
    "$http","$rootScope","$q","cfpLoadingBar", "Modal",
    function($http,$rootScope,$q,cfpLoadingBar, Modal) {
    var interfaz = {};

    interfaz.http = function (options) {
            var isTimeout = false,
                httpRequest,
                httpRequestOptions;

            var rt = {};
            rt.canceled = false;
            rt.result = $q.defer();
            rt.timeout = $q.defer();
            rt.promise = null;

            rt.cancel = function()
            {
                isTimeout = true;
                rt.timeout.resolve();
                rt.result.reject();
                rt.canceled = true;
                cfpLoadingBar.complete();
            };

            if(!options.timeout) {
                options.timeout = 30;
            };

            setTimeout(function () {
                isTimeout = true;
                rt.timeout.resolve();
            }, (1000 * options.timeout));

            httpRequestOptions = {
//            headers:  { 'X-Requested-With': 'XMLHttpRequest' },
                method: options.method,
                url: options.url,
                cache: false,
                timeout: rt.timeout.promise,
            };

            if(options.data) {
                httpRequestOptions.data = options.data
            };

            httpRequest = $http(httpRequestOptions);

            httpRequest.success(function(data, status, headers, config) {
                rt.result.resolve(data);
            });

            httpRequest.error(function(data, status, headers, config) {
                if (isTimeout) {
                    if(rt.canceled)
                    {
/*                        rt.result.reject({
                            message: 'Canceled: ' + options.url
                        });*/
                    } else {
                        rt.result.reject({
                            error: 'HTTP Timeout (' + options.timeout + ' seg)'
                        });
                    }
                } else {
                    if(status===0) {
                        rt.result.reject({
                            error: "No se detecto conexion de red"
                        });
                    } else {
                        rt.result.reject({
                            error: "ERROR: "+status
                        });
                    }
                }
            });

            return rt;
        };
    interfaz.modal =  function(options) {
        var btype = BootstrapDialog.TYPE_PRIMARY;
        switch (options.type)
        {
            case 'danger':  btype = BootstrapDialog.TYPE_DANGER;  break;
            case 'default': btype = BootstrapDialog.TYPE_DEFAULT; break;
            case 'success': btype = BootstrapDialog.TYPE_SUCCESS; break;
            case 'warning': btype = BootstrapDialog.TYPE_WARNING; break;
            case 'info':    btype = BootstrapDialog.TYPE_INFO;    break;
            case 'primary': btype = BootstrapDialog.TYPE_PRIMARY; break;
        }

        if(!$rootScope.modalOpened) {
            Modal.create(options.scope, options.title, options.route, btype, options.controller,options.ignoreloadingbar);
        }
    };
    interfaz.modalError =  function(scope,err_msg) {
        if(!$rootScope.modalOpened) {
            Modal.error(scope,err_msg);
        };
    };
    interfaz.storage = function(options){
            if(options.value)
            {
                var save_value = options.value;
                if (options.json) {
                    save_value = JSON.stringify(options.value);
                }
                window.localStorage.setItem(options.name, save_value);
            } else
            {
                var get_value = window.localStorage.getItem(options.name);
                if(options.json) {
                    return JSON.parse(get_value);
                } else {
                    return get_value;
                }
            }
        };

    return interfaz;
}]);

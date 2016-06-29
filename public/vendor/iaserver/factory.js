/*
=========================================================
                        FACTORY
---------------------------------------------------------
*/
app.factory('Modal',function($http,$compile,$rootScope, $timeout) {
    var modalScope;
    var parentScope;
    var interfaz = {};

    interfaz.build = function(title, template, type) {
            if(type == undefined) {
                type = BootstrapDialog.TYPE_PRIMARY;
            }
            BootstrapDialog.show({
                type:type,
                title: title,
                message: template,
                onshown: function(dialogItself)
                {
                    $rootScope.modalOpened = true;

                    $('input.focus').focus();
                    modalScope.$emit("modal:show",{
                        modalscope: modalScope,
                        parentscope: parentScope,
                        dialog: dialogItself
                    });
                },
                onhide: function(dialogItself)
                {
                    $rootScope.modalOpened = false;

                    modalScope.$emit("modal:hide",{
                        modalscope: modalScope,
                        parentscope: parentScope,
                        dialog: dialogItself
                    });
                    modalScope.$destroy();
                }
            });
        };
    interfaz.create = function(scope,title,uri,type,controller,ignoreloadingbar) {
            if(ignoreloadingbar==undefined)
            {
                ignoreloadingbar = true;
            }
            parentScope = scope;
            modalScope = scope.$new();

            return $http.get(uri,{
                ignoreLoadingBar: ignoreloadingbar
            }).then(function(response) {
                var toCompile = response.data;
                if(controller) {
                    toCompile = '<div ng-controller="'+controller+'">'+response.data+'</div>';
                }
                interfaz.build(
                    title,
                    $compile(toCompile)(modalScope),
                    type
                );
                return modalScope;
            }, function errorCallback(response) {
//                var toCompile = '<div class="alert alert-danger">Ocurrio un error ('+response.status+') durante la operacion, intente nuevamente en unos minutos, si el problema persiste consulte con el supervisor de programacion de automatica<div>';
                var toCompile = 'Ocurrio un error ('+response.status+') durante la operacion, intente nuevamente en unos minutos, si el problema persiste consulte con el supervisor de programacion de automatica';
                if(controller) {
                    toCompile = '<div ng-controller="'+controller+'">'+toCompile+'</div>';
                }
                interfaz.error(scope,toCompile);
/*                interfaz.build(
                    title,
                    $compile(toCompile)(modalScope),
                    type
                );*/
            });
        };
    interfaz.error = function(scope,err_msg) {
            parentScope = scope;
            modalScope = scope.$new();

            var toCompile = '<div class="alert alert-danger">'+err_msg+'<div>';

            interfaz.build(
                'ERROR',
                $compile(toCompile)(modalScope),
                BootstrapDialog.TYPE_DANGER
            );
            return modalScope;
        };

    return interfaz;
});

app.factory('IaCore',function($http,$rootScope,$q,cfpLoadingBar, Modal) {
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
});

/*
app.factory('User',function($http,$rootScope, Framework){
    var serviceDone = null;
    var service = null;
    var interfaz = {
        service : function() { return service},
        isLogged : function() { return serviceDone;},
        isAdmin: function(app) {
            if(serviceDone) {
                service.isAdmin = false;
                if(service.permiso[app] == "ADMINISTRADOR") {
                    service.isAdmin = true;
                } else {
                    service.isAdmin = false;
                }
                return service.isAdmin;
            }
        },
        isOperator: function() {
            if(serviceDone) {
                service.isOperator = false;
                if(service.permiso["MEM"] == "OPERADOR") {
                    service.isOperator = true;
                } else {
                    if(interfaz.isAdmin()) {
                        service.isOperator = true;
                    } else {
                        service.isOperator = false;
                    }
                }
                return service.isOperator;
            }
        },
        info: function()
        {
            var url = [
                'service',
                'user',
                'info'
            ];
            return $http.get(Framework.url(url), {
                ignoreLoadingBar: true
            }).then(function(result) {
                data = result.data.service;
                service = data.result;
                if(data.login) {
                    serviceDone = true;
                    $rootScope.$broadcast('user:login',true);
                } else {
                    serviceDone = false;
                    $rootScope.$broadcast('user:login',false);
                }
            });
        }
    }
    return interfaz;
});

*/
/*
 =========================================================
  FILTER
 =========================================================
*//*


app.filter('range', function() {
    return function(input, total) {
        total = parseInt(total);
        for (var i=1; i<=total; i++)
            input.push(i);
        return input;
    };
});

app.filter('porcentaje', ['$window', function ($window) {
    return function (input, decimals, suffix) {
        decimals = angular.isNumber(decimals)? decimals :  3;
        if(suffix!='') {
            suffix = suffix || '%';
        }
        if ($window.isNaN(input)) {
            return '';
        }
        return Math.round(input * Math.pow(10, decimals + 2))/Math.pow(10, decimals) + suffix
    };
}]);*/

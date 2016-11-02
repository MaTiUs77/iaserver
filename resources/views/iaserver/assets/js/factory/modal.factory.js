app.factory('Modal',[
    "$http","$compile","$rootScope","$timeout",
    function($http,$compile,$rootScope, $timeout) {
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
}]);

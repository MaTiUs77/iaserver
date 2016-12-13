app.factory('UserFactory',["$http",function($http){
    return{
        getUsers:function(){
            return $http.get('/iaserver/public/inventario/configurar/usuarios/getUsers');
        },
        getSectors:function(){
            return $http.get('/iaserver/public/inventario/configurar/getSectors');
        },
        getPlants:function(){
            return $http.get('/iaserver/public/inventario/configurar/getPlants');
        },
        getPrinters:function(){
            return $http.get('/iaserver/public/inventario/impresoras');
        },
        getRoles:function(id){
            return $http.get('/iaserver/public/inventario/configurar/usuarios/getRoles/'+id);
        },
        getProfileData:function(id){
            return $http.get('/iaserver/public/inventario/configurar/usuarios/getProfile/'+id);
        },
        getSessionData:function(){
            return $http.get('/iaserver/public/inventario/configurar/perfil/get');
        }

    }
}]);

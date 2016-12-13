app.factory('reportFactory',["$http",function($http){
    return{
        getPrints:function(){
            return $http.get('/iaserver/public/inventario/consultar/reporte/get');
        }
    }
}]);
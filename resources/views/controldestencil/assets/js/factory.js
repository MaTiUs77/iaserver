app.factory("Factory",["$http",
    function($http){
        return{
            insRecord:function(linea,codigo){
                return $http.get('save/'+linea+'/'+codigo);
            },
            getHistory:function(){
                return $http.get('placas/get/all');
            },
            filtrar:function(fecha,linea){
                return $http.get('placas/get/all?'+fecha+'&linea='+linea);
            }
        }
    }
]);
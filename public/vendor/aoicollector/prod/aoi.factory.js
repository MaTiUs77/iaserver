app.factory('Aoi', function($q, IaCore){
    var timeout = 10;
    var runinng = false;
    var result;
    var httpget;

    var interfaz = {}
    interfaz.info = function (aoibarcode,scope) {
        result = $q.defer();
        if(!runinng)
        {
            runinng = true;
            httpget = IaCore.http({
                url: 'prod/info/'+aoibarcode+'?filter=1&allstocker=1',
//                url : 'http://localhost:8000/produccion',
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

    interfaz.rerun = function()
    {
        runinng = false;
    };

    interfaz.cancel = function() {
        httpget.cancel();
    };

    return interfaz;
});
var http = require('http');

var host = 'arushde04';
var port = 80;

function init(socket) {
	var produccionInterval;

	socket.on('disconnect', function () {
		console.log('Un cliente salio de produccion');
		clearInterval(produccionInterval);
	});

	socket.on('produccion', function (aoibarcode) {
		clearInterval(produccionInterval);

		if(aoibarcode!=undefined)
		{
			produccionInterval = setInterval(function() {
				getProduccion(aoibarcode,socket);
			}, 1000 * 5);
		}
	});
}

function getProduccion(aoibarcode,socket) {
	console.log('Method','GetProduccion()',aoibarcode);

	socket.emit('getProduccion', aoibarcode);

	var uripath = '/iaserver/public/aoicollector/prod/info/'+aoibarcode+'?filter=1&allstocker=1&json';

	console.log({ host: host, port: port, path: uripath });

	http.get({ host: host, port: port, path: uripath }, function(response) {
        var data = "";
        response.on('data', function(chunk) {
            data += chunk;
        });
        response.on('end', function() {			

			var output;
			try
			{
				output = JSON.parse(data);
				console.log('Response','OK');
			} catch(e)
			{
				output = { error: e.message };
				console.log('Response',output);
			}

			socket.emit('getProduccionResponse', output);
        });
    }).on("error", function (e){
		console.log('ERROR',e.message);
		socket.emit('getProduccionResponse', { error: e.message });
	});
};

exports.host = host;
exports.port = port;
exports.init = init;
exports.getProduccion = getProduccion;
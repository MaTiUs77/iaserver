var app = require('express')();
var http = require('http');
var server = http.Server(app);
var io = require('socket.io')(server);

/*
app.get('/', function (req, res) {
	res.sendFile(__dirname + '/index.html');
});
*/

server.listen(8080, function(){
	console.log('listening on *:8080');	
});

io.on('connection', function (socket) 
{	
	var produccion;

	console.log('Cliente conectado');

	socket.join('aoiMachine');

	socket.on('disconnect', function () {
        console.log('Disconnected');
		clearInterval(produccion);
    });
	
	socket.on('produccion', function (aoibarcode) {
		clearInterval(produccion);

		if(aoibarcode!=undefined)
		{
			produccion = setInterval(function() { 
				GetProduccion(aoibarcode,socket);
			}, 1000 * 5); 
		}
	});
});		

function GetProduccion(aoibarcode,socket) {	
	socket.emit('getProduccion', aoibarcode);	
	console.log('Method','GetProduccion()',aoibarcode);
	
	var uripath = '/iaserver/public/aoicollector/prod/info/'+aoibarcode+'?filter=1&allstocker=1&json';
    http.get({ host: 'arushde04', port: 80, path: uripath }, function(response) {
        var data = "";
        response.on('data', function(chunk) {
            data += chunk;
        });
        response.on('end', function() {			
			console.log('Response: OK');
			socket.emit('getProduccionResponse', JSON.parse(data));
        });
    });
}
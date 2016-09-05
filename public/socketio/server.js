var http = require('http')
var app = require('express')();
var server = http.Server(app);
var io = require('socket.io')(server);
var produccion = require('./controllers/produccion');

server.listen(8080, function(){
	console.log('listening on *:8080');
});

io.on('connection', function (socket)
{
	console.log('Cliente conectado');
	socket.join('aoiMachine');

	produccion.init(socket);
});
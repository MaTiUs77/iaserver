var http = require('http');
var app = require('express')();
var server = http.Server(app);
var io = require('socket.io')(server);

var log = require('./controllers/logcolor');

var produccion = require('./controllers/produccion');

server.listen(8080, function(){
	log.info('Servidor de produccion iniciado en *:8080');
});

//produccion.init(app);

io.on('connection', function (client) {
	produccion.clientConnected(client);
});

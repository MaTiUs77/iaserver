var http = require('http');
var app = require('express')();
var server = http.Server(app);
var io = require('socket.io')(server);
var log = require('./controllers/logcolor');

//var produccionWatcher = require('./controllers/produccionWatcher');
var produccion = require('./controllers/produccion');

server.listen(8080, function(){
	log.info('Servidor iniciado en *:8080');
});

//produccionWatcher.init(app);
//produccionWatcher.startWatcher('reproceso');

produccion.init(app);

io.on('connection', function (socket) {
	log.info('Socket connected');
	produccion.socketConnected(socket);
});


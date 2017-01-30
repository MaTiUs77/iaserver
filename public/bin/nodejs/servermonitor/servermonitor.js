var http = require('http');
var app = require('express')();
var server = http.Server(app);
var io = require('socket.io')(server);
var log = require('./controllers/logcolor');

var redis = require("redis");
var redisHost = 'ARUSHAP34';
var socketPort = 8081;

server.listen(socketPort, function(){
	log.info('Canal de comunicacion iniciado en *:'+socketPort);
});

io.on('connection', function (client) {
	redisConnect(client);
	clientConnected(client);
});

// Socket
function clientConnected(client) {

	log.notify(['Cliente conectado']);

	client.on("error", function (err) {
		log.error(err);
	});

	client.on('disconnect', function () {
		log.notify(['Cliente desconectado']);
	});

	client.on('subscribe', function (channel) {
		log.notify(['Subscribiendo a canal',channel]);
		subscribe(client,channel);
	});
}

// Redis
function redisConnect(client) {
	client.sub = redis.createClient({host: redisHost});
	handleChannel(client);
}

function subscribe(client,channel) {
	client.sub.subscribe(channel);

	client.emit('subscribeResponse', 'Subscripto a '+channel);
}

function handleChannel(client) {
	client.sub.on("error", function (err) {
		log.error(["Redis",err]);
		client.emit('redisError', err);
	});

	client.sub.on("subscribe", function(channel, count) {
		log.info("Subscripto a " + channel + ". Total:" + count + " canal(es).");
	});

	client.sub.on('message', function (channel,message) {
		console.log("Mensaje en canal" + channel + ": " + message);
		io.emit('message',message);
	});
}

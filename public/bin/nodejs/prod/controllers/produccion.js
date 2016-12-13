var http = require('http');
var _ = require('underscore');
var moment = require('moment');
var redis = require("redis");

var blu = require('./blu');
var stocker = require('./stocker');
var log = require('./logcolor');

var redisHost = 'localhost';
var iaserverApi = {
	host: 'localhost',
	port: 80,
	timeout: 10000
};

function createRedisClient(client)
{
//	client.pub = redis.createClient({host: redisHost});
	client.sub = redis.createClient({host: redisHost});
//	client.store = redis.createClient({host: redisHost});


	client.sub.on("error", function (err) {
		log.error(err);
		client.emit('getProduccionResponseError', 'REDIS '+err.code);
	});
}

function destroyRedisClient(client)
{
//	client.pub.quit();
	client.sub.quit();
//	client.store.quit();
}

function clientConnected(client) {
	var produccionInterval;

	log.notify(['Cliente conectado',client.id]);

	//createRedisClient(client);

	// Crea las rutas socket para stockers
	stocker.init(client);

	client.on("error", function (err) {
		log.error(err);
		client.emit('getProduccionResponseError', "SOCKET "+err.code);
	});

	client.on('disconnect', function () {
		log.notify(['Cliente desconectado',client.id,client.aoibarcode]);
		clearInterval(produccionInterval);
	});

	client.on('produccion', function (_aoibarcode) {
		clearInterval(produccionInterval);

		if(_aoibarcode!=undefined)
		{
			if(client.aoibarcode!=undefined && client.aoibarcode != _aoibarcode.toUpperCase())
			{
				log.info('Cambiando de maquina..');
				//destroyRedisClient(client);
				//createRedisClient(client);
			}

			client.aoibarcode =  _aoibarcode.toUpperCase();
			client.join(client.aoibarcode);
			client.infoRunning = false;

			//subscribeRedisProd(client);

			// Ejecutar por primera vez
			info(client);

			// Iniciar intervalo
			produccionInterval = setInterval(function() {
				info(client);
			}, 3000);
		}
	});
}

/*
function getRedisProd(client)
{
	var redisProdChannel = "aoicollector.prod."+client.aoibarcode;
	client.store.get(redisProdChannel,function(err,data){
		client.emit('getProduccionResponse', JSON.parse(data));
	});
}*/

function subscribeRedisProd(client)
{
	var redisProdChannel = "aoicollector.prod."+client.aoibarcode;
	client.sub.subscribe(redisProdChannel);

	client.sub.on("subscribe", function (channel) {
		log.verbose('REDIS Subscripto al canal '+channel);
	});

	client.sub.on("message", function(channel,message) {
		log.verbose(['REDIS Message',channel]);
		client.emit('getProduccionResponse', JSON.parse(message));
	});

	client.on('disconnect', function () {
		log.verbose(['REDIS Removiendo listener',client.aoibarcode]);

		destroyRedisClient(client);
	});
}

function info(client) {
	client.emit('waitForGetProduction',true);

	var uripath = '/iaserver/public/api/aoicollector/prodinfo/'+client.aoibarcode+'?json';

    if(client.infoRunning) {
        log.warning(['Esperando informacion de produccion, tiempo de respuesta lento...',client.aoibarcode]);
    } else {

		client.infoRunning = true;

        blu.webPromise(iaserverApi.host, iaserverApi.port, uripath, iaserverApi.timeout).then(function (response) {
            log.debug([client.aoibarcode,'API Info',client.aoibarcode, 'complete']);
			client.emit('getProduccionResponse', response);

			client.infoRunning = false;
        }).error(function (err) {

            log.error([client.aoibarcode,'API Info','error', err]);

			client.emit('getProduccionResponseError', "API "+ err.code);
			client.infoRunning = false;
        }).catch(function (err) {
            log.error([client.aoibarcode,'API Info','catch', err]);

			client.emit('getProduccionResponseError', "API "+ err.code);
			client.infoRunning = false;
        });
    }
}

var produccion = {
	clientConnected : clientConnected
};

module.exports = produccion;

var http = require('http');
var _ = require('underscore');
var moment = require('moment');

var blu = require('./blu');
var stocker = require('./stocker');
var log = require('./logcolor');

var config = require('./config');
config.local();

var app;
var host = config.default.host;
var port = config.default.port;
var timeout = config.default.timeout;
var interval = 4000;

var infoRunning = false;
var prodcache = [];

function socketConnected(socket) {
	var produccionInterval;
	
	// Crea las rutas socket para stockers
	stocker.init(socket);

	socket.on('disconnect', function () {
		log.notify(['Socket disconnected',aoibarcode]);
		clearInterval(produccionInterval);
	});

	socket.on('produccion', function (_aoibarcode) {
		clearInterval(produccionInterval);

		if(_aoibarcode!=undefined)
		{
			aoibarcode = _aoibarcode.toUpperCase();
			socket.aoibarcode = aoibarcode;
			subscribe(socket);

			// Ejecutar por primera vez
			info(socket);

			// Iniciar intervalo
			produccionInterval = setInterval(function() {
				info(socket);
			}, interval);
		}
	});
}

function info(socket) {
	socket.emit('waitForGetProduction',true);

	var uripath = config.default.rootPath +
		'public/aoicollector/prod/info/'+socket.aoibarcode+'?filter=1&allstocker=0&json';

    if(infoRunning) {
        log.warning('Esperando informacion de produccion');
    } else {

        infoRunning = true;

        log.debug(['RUN!', host, port, uripath]);

        blu.webPromise(host, port, uripath, timeout).then(function (response) {
            log.debug([socket.aoibarcode, 'complete']);

            socket.emit('getProduccionResponse', response);

            setMachineCache(socket.aoibarcode, response);

            infoRunning = false;
        }).error(function (e) {

            log.error(['error', e]);

            socket.emit('getProduccionResponseError', e);

            infoRunning = false;
        }).catch(function (e) {
            log.error(['error', e]);

            socket.emit('getProduccionResponseError', e);
            infoRunning = false;
        });
    }
}

function subscribe(socket) {
	log.info('Subscribe '+ socket.aoibarcode);
	socket.join(socket.aoibarcode);
	routeMachineCache(socket.aoibarcode);
}

function setMachineCache(aoibarcode,response) {
	var finded = _.findWhere(prodcache, {aoibarcode: aoibarcode});
	if (finded == undefined) {
		prodcache.push({
			updated: {
				date: moment().format('DD-MM-YYYY'),
				time: moment().format('HH:mm:ss')
			},
			aoibarcode: aoibarcode,
			output: response
		});
	} else {
		finded.updated = {
			date: moment().format('DD-MM-YYYY'),
			time: moment().format('HH:mm:ss')
		};
		finded.output = response;
	}
}

// ROUTES
function routeProduccionCache() {
	app.get('/', function(req, res){
		res.send(prodcache);
	});
}

function routeMachineCache(aoibarcode) {
	app.get('/aoibarcode/' + aoibarcode, function (req, res) {
		var finded = _.findWhere(prodcache, {aoibarcode: aoibarcode});
		res.send(finded);
	});
}

function init(_app) {
	app = _app;
	routeProduccionCache();
}

var produccion = {
	init : init,
	socketConnected : socketConnected
};

module.exports = produccion;

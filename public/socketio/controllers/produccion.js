var http = require('http');
var _ = require('underscore');
var moment = require('moment');

var util = require('./util');
var stocker = require('./stocker');

var app;
var host = 'arushde04';
var port = 80;
var timeout = 5000;
var interval = 5000;

var prodcache = [];

function socketConnected(socket) {
	var produccionInterval;

	// Crea las rutas socket para stockers
	stocker.init(socket);

	socket.on('disconnect', function () {
		console.log('Salio de produccion',aoibarcode);
		clearInterval(produccionInterval);
	});

	socket.on('produccion', function (_aoibarcode) {
		clearInterval(produccionInterval);

		aoibarcode = _aoibarcode.toUpperCase();
		if(aoibarcode!=undefined)
		{
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
	var uripath = '/iaserver/public/aoicollector/prod/info/'+socket.aoibarcode+'?json'; //?filter=1&stocker=0&json';

	util.webPromise(host,port,uripath,timeout).then(function (response) {
		console.log("Info",socket.aoibarcode,'complete');
		socket.emit('getProduccionResponse', response);
		setMachineCache(socket.aoibarcode,response);
	}).error(function (e) {
		console.log('error',e);
		socket.emit('getProduccionResponseError', e );
	}).catch(function (e) {
		console.log('catch',e);
		socket.emit('getProduccionResponseError', e );
	});
}

function subscribe(socket) {
	console.log('Subscribe',socket.aoibarcode);
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
	app.get('/produccion/cache', function(req, res){
		res.send(prodcache);
	});
}

function routeMachineCache(aoibarcode) {
	app.get('/' + aoibarcode, function (req, res) {
		var finded = _.findWhere(prodcache, {aoibarcode: 'IMAIRE'});
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

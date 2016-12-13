var async = require('async');
/*var _ = require('underscore');
var moment = require('moment');*/
var events = require('events');
var http = require('http');
var app = require('express')();
var server = http.Server(app);
var io = require('socket.io')(server);
var log = require('./../lib/logcolor');
var blu = require('./../lib/blu');

var host = 'localhost',
	port = 80,
	timeout = 50000,
	rootPath = '/iaserver/';

/*var prodcache = [];*/
const evento = new events();

server.listen(8081, function(){
	log.info('Servidor iniciado en *:8081');
});

/*routeProduccionCache();*/
getProductionList();

// Tareas que se van a ejecutar cada vez que se detecte un objeto en el array
var q = async.queue(function(machine, callback) {
	if(machine.op != null)
	{
		getOpStatus(machine);
	} else
	{
		log.warning([machine.barcode,'Sin produccion']);
		putDelayed(machine,5000);
	}
	callback();
}, 2);

evento.on('getProductionListResponse', function(machineList) {
	var index = 0;
	async.each(machineList,
		function(machine, callback){
			/*routeMachineCache(machine.barcode);*/

			putDelayed(machine,5000 * index);

			index++;
		},
		function(err){
			log.debug('EACH machine complete');
		}
	);
});

evento.on('productionUpdated', function(machine,response) {
	log.info('Actualizacion completa '+machine.barcode+ ' Refresco en 5seg');

	/*setMachineCache(machine,response);*/
	putDelayed(machine,5000);
});

evento.on('productionError', function(machine,response) {
	// Volver a ejecutar
	log.error(['ERROR', machine.barcode, e]);
	putDelayed(machine,5000);
});

function putDelayed(machine,delay) {
	log.debug(machine.barcode+' ejecutar en '+delay+' ms');

	setTimeout(function() {
		q.push(machine);
	},delay);
}

function getOpStatus(machine) {
	log.debug(['GET STATUS', machine.barcode]);

//	var uripath = '/iaserver/public/aoicollector/prod/info/'+machine.barcode+'?filter=1&period=0&stocker=0';
	var uripath = '/iaserver/public/api/aoicollector/prodinfo/'+machine.barcode+'?json';

	blu.webPromise('ARUSHAP34', 80, uripath, timeout).then(function (response) {
		evento.emit('productionUpdated',machine,response);
	}).error(function (e) {
		evento.emit('productionError',machine);
	}).catch(function (e) {
		evento.emit('productionError',machine);
	});
}

function getProductionList() {
	var uripath = rootPath +
		'public/api/prodlist?json';

	log.debug([host, port, uripath]);

	blu.webPromise(host, port, uripath, timeout).then(function (response) {
		log.debug('Lista actualizada');

		evento.emit('getProductionListResponse',response);
	}).error(function (e) {

		log.error(['error', e]);
	}).catch(function (e) {
		log.error(['error', e]);
	});
}
/*
// ROUTES
function routeProduccionCache() {
	app.get('/', function(req, res){
		res.send(prodcache);
	});
}*/
/*
function routeMachineCache(aoibarcode) {
	app.get('/aoibarcode/' + aoibarcode, function (req, res) {
		var finded = _.findWhere(prodcache, {aoibarcode: aoibarcode});
		if(finded != undefined)
		{
			res.send(finded.output);
		} else
		{
			res.send({wait:true});
		}
	});
}*/

/*
function setMachineCache(machine,response) {
	var finded = _.findWhere(prodcache, {aoibarcode: machine.barcode});
	if (finded == undefined) {
		var toCache = {
			updated: {
				date: moment().format('DD-MM-YYYY'),
				time: moment().format('HH:mm:ss')
			},
			aoibarcode: machine.barcode,
			output: response
		};

		prodcache.push(toCache);
	} else {
		finded.updated = {
			date: moment().format('DD-MM-YYYY'),
			time: moment().format('HH:mm:ss')
		};
		finded.output = response;
	}
}
*/


var http = require('http');
var _ = require('underscore');
var moment = require('moment');
var events = require('events');

var blu = require('./blu');
var stocker = require('./stocker');
var config = require('./config');
var log = require('./logcolor');

var app;
var host = config.default.host;
var port = config.default.port;
var timeout = config.default.timeout;
var interval = 4000;

var prodcache = [];

var eventEmitter = new events.EventEmitter();

function startWatcher(_aoibarcode) {
	if(_aoibarcode!=undefined)
	{
		aoibarcode = _aoibarcode.toUpperCase();
		
		var machineCode = aoibarcode;
		
		routeMachineCache(aoibarcode);
		
		routePromise(machineCode);

		// Iniciar intervalo
		produccionInterval = setInterval(function() {
			routePromise(machineCode);
		}, interval);
	}

}

function routePromise(aoibarcode) {
	var uripath = config.default.rootPath +
		'public/aoicollector/prod/info/'+aoibarcode+'?json'; //?filter=1&stocker=0&allstocker=0&json';
	
	log.verbose(['REQUEST', host,port,uripath, aoibarcode]);

	blu.webPromise(host,port,uripath,timeout).then(function (response) {
		log.debug([uripath,' UPDATED ']);
		updateMachineCache(aoibarcode,response);

		eventEmitter.emit('routePromise',aoibarcode);
	}).error(function (e) {
		log.error([' ERROR ',e]);

	}).catch(function (e) {
		log.error([' ERROR ',e]);
	});
}

function updateMachineCache(aoibarcode,response) {
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
	log.notify('New cache route: /produccion/cache');
	app.get('/produccion/cache', function(req, res){
		res.send(prodcache);
	});
}

function routeMachineCache(aoibarcode) {
	log.notify('New cache route: /produccion/cache/' + aoibarcode);
	app.get('/produccion/cache/' + aoibarcode, function (req, res) {
		res.send(getProdCache(aoibarcode));
	});
}

function getProdCache(barcode)
{
//	log.verbose('Finding' + barcode + ' in cache');
	var finded =  _.findWhere(prodcache, {aoibarcode: barcode});
	return finded;
}

function init(_app) {
	app = _app;
	routeProduccionCache();
}

var produccionWatcher = {
	init : init,
	eventEmitter : eventEmitter,
	getProdCache : getProdCache,
	startWatcher : startWatcher
};

module.exports = produccionWatcher;

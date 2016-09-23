var http = require('http');
var blu = require('./blu');
var config = require('./config');
var log = require('./logcolor');

var host = config.default.host;
var port = config.default.port;
var timeout = config.default.timeout;

function init(socket) {
	log.debug("Rutas de stocker inicializadas");

	socket.on('stockerAdd', function (stkbarcode,toastId) {
		add(socket,stkbarcode,toastId);
	});

	socket.on('stockerRemove', function (stkbarcode,toastId) {
		remove(socket,stkbarcode,toastId);
	});

	socket.on('stockerInfo', function (stkbarcode) {
		info(socket,stkbarcode);
	});

	socket.on('panelAdd', function (panelbarcode) {
		panelAdd(socket,panelbarcode);
	});

	socket.on('panelRemove', function (panelbarcode) {
		panelRemove(socket,panelbarcode);
	});
}

function add(socket,stkbarcode,toastId) {
	var uripath = config.default.rootPath +
		'public/aoicollector/stocker/prod/set/'+stkbarcode+'/'+socket.aoibarcode+'?json=1';
	blu.webPromise(host,port,uripath,timeout).then(function (response) {
		log.verbose("STK ADD Complete");

		socket.emit('stockerAddResponse', response, toastId);
	})
	.error(function (e) {
		log.error(['STK ADD Error',e]);
		socket.emit('stockerAddResponseError', e);
	})
	.catch(function (e) {
		log.error(['STK ADD Catch',e]);
		socket.emit('stockerAddResponseError', e);
	});
}

function remove(socket,stkbarcode,toastId) {
	var uripath = config.default.rootPath +
		'public/aoicollector/stocker/prod/remove/'+stkbarcode+'?json=1';
	blu.webPromise(host,port,uripath,timeout).then(function (response) {
		log.verbose("STK REMOVE Complete");

		socket.emit('stockerRemoveResponse', response, toastId);
	})
	.error(function (e) {
		log.error(['STK REMOVE Error',e]);

		socket.emit('stockerRemoveResponseError', e);
	})
	.catch(function (e) {
		log.error(['STK REMOVE Catch',e]);

		socket.emit('stockerRemoveResponseError', e);
	});
}

function info(stkbarcode) {
	var uripath = config.default.rootPath +
		'public/aoicollector/stocker/info/'+stkbarcode;

	blu.webPromise(host,port,uripath,timeout).then(function (response) {
		log.verbose("StockerInfo complete");

		socket.emit('stockerInfoResponse', response);
	}).error(function (e) {
		log.error(['StockerInfo error',e]);

		socket.emit('stockerInfoResponseError', e);
	}).catch(function (e) {
		log.error(['StockerInfo catch',e]);

		socket.emit('stockerInfoResponseError', e);

	});
}

function panelAdd(socket,panelbarcode) {
	var uripath = config.default.rootPath +
		'public/aoicollector/stocker/panel/add/'+panelbarcode+'/'+socket.aoibarcode+'?json=1';
	blu.webPromise(host,port,uripath,timeout).then(function (response) {
			log.verbose("Panel ADD Complete");

			socket.emit('panelAddResponse', response);
		})
		.error(function (e) {
			log.error(['Panel ADD Error',e]);
			socket.emit('panelAddResponseError', e);
		})
		.catch(function (e) {
			log.error(['Panel ADD Catch',e]);
			socket.emit('panelAddResponseError', e);
		});
}

function panelRemove(socket,panelbarcode) {
	var uripath = config.default.rootPath +
		'public/aoicollector/stocker/panel/remove/'+panelbarcode+'?json=1';
	blu.webPromise(host,port,uripath,timeout).then(function (response) {
			log.verbose("Panel REMOVE Complete");

			socket.emit('panelRemoveResponse', response);
		})
		.error(function (e) {
			log.error(['Panel REMOVE Error',e]);

			socket.emit('panelRemoveResponseError', e);
		})
		.catch(function (e) {
			log.error(['Panel REMOVE Catch',e]);

			socket.emit('panelRemoveResponseError', e);
		});
}

var stocker = {
	init : init
};

module.exports = stocker;

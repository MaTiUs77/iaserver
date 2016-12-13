var http = require('http');
var blu = require('./blu');
var log = require('./logcolor');

var redis = require("redis");

var redisHost = 'localhost';
var iaserverApi = {
	host: 'localhost',
	port: 80,
	timeout: 10000
};

var declaredRunning = false;

function init(client) {
	log.debug("Rutas ABM de stocker inicializadas");

	client.on('stockerAdd', function (stkbarcode,toastId) {
		add(client,stkbarcode,toastId);
	});

	client.on('stockerRemove', function (stkbarcode,toastId) {
		remove(client,stkbarcode,toastId);
	});

	client.on('stockerInfo', function (stkbarcode) {
		info(client,stkbarcode);
	});

	client.on('stockerDeclared', function (stkbarcode) {
		declared(client,stkbarcode);
	});

	client.on('panelAdd', function (panelbarcode) {
		panelAdd(client,panelbarcode);
	});

	client.on('panelRemove', function (panelbarcode) {
		panelRemove(client,panelbarcode);
	});
}

function add(client,stkbarcode,toastId) {
	var uripath = '/iaserver/public/aoicollector/stocker/prod/set/'+stkbarcode+'/'+client.aoibarcode+'?json=1';

	blu.webPromise(iaserverApi.host,iaserverApi.port,uripath,iaserverApi.timeout).then(function (response) {
		log.verbose("STK ADD Complete");
		client.emit('stockerAddResponse', response, toastId);
	})
	.error(function (err) {
		log.error(['STK ADD Error',err]);
		client.emit('stockerAddResponseError', err.code);
	})
	.catch(function (err) {
		log.error(['STK ADD Catch',err]);
		client.emit('stockerAddResponseError', err.code);
	});
}

function remove(client,stkbarcode,toastId) {
	var uripath = '/iaserver/public/aoicollector/stocker/prod/remove/'+stkbarcode+'?json=1';

	blu.webPromise(iaserverApi.host,iaserverApi.port,uripath,iaserverApi.timeout).then(function (response) {
		log.verbose("STK REMOVE Complete");

		client.emit('stockerRemoveResponse', response, toastId);
	})
	.error(function (err) {
		log.error(['STK REMOVE Error',err]);

		client.emit('stockerRemoveResponseError', err.code);
	})
	.catch(function (err) {
		log.error(['STK REMOVE Catch',err]);

		client.emit('stockerRemoveResponseError', err.code);
	});
}

function info(client,stkbarcode) {
	var uripath =  '/iaserver/public/aoicollector/stocker/info/'+stkbarcode;

	blu.webPromise(iaserverApi.host,iaserverApi.port,uripath,iaserverApi.timeout).then(function (response) {
		log.verbose("StockerInfo complete");

		client.emit('stockerInfoResponse', response);
	}).error(function (err) {
		log.error(['StockerInfo error',err]);

		client.emit('stockerInfoResponseError', err.code);
	}).catch(function (err) {
		log.error(['StockerInfo catch',err]);

		client.emit('stockerInfoResponseError', err.cde);
	});
}

function declared(client,stkbarcode) {
	var uripath = '/iaserver/public/aoicollector/stocker/declared/'+stkbarcode;

	if(declaredRunning) {
		log.warning(['Esperando informacion de stocker declarado',stkbarcode]);
	} else {

		declaredRunning = true;

		log.verbose(["Verificando estado de stocker",stkbarcode]);

		blu.webPromise(iaserverApi.host,iaserverApi.port,uripath,iaserverApi.timeout).then(function (response) {
			log.verbose("StockerDeclared complete");

			client.emit('stockerDeclaredResponse', response);

			declaredRunning = false;
		}).error(function (err) {
			log.error(['StockerDeclared error', err]);

			client.emit('stockerDeclaredResponseError', err.code);
			declaredRunning = false;
		}).catch(function (err) {
			log.error(['StockerDeclared catch', err]);

			client.emit('stockerDeclaredResponseError', err.code);
			declaredRunning = false;
		});
	}
}

function panelAdd(client,panelbarcode) {
	var uripath = '/iaserver/public/aoicollector/stocker/panel/add/'+panelbarcode+'/'+client.aoibarcode+'?json=1';

	blu.webPromise(iaserverApi.host,iaserverApi.port,uripath,iaserverApi.timeout).then(function (response) {
			log.verbose("Panel ADD Complete");

			client.emit('panelAddResponse', response);
		})
		.error(function (err) {
			log.error(['Panel ADD Error',err]);
			client.emit('panelAddResponseError', err.code);
		})
		.catch(function (err) {
			log.error(['Panel ADD Catch',err]);
			client.emit('panelAddResponseError', err.code);
		});
}

function panelRemove(client,panelbarcode) {
	var uripath =  '/iaserver/public/aoicollector/stocker/panel/remove/'+panelbarcode+'?json=1';

	blu.webPromise(iaserverApi.host,iaserverApi.port,uripath,iaserverApi.timeout).then(function (response) {
			log.verbose("Panel REMOVE Complete");

			client.emit('panelRemoveResponse', response);
		})
		.error(function (err) {
			log.error(['Panel REMOVE Error',err]);

			client.emit('panelRemoveResponseError', err.code);
		})
		.catch(function (err) {
			log.error(['Panel REMOVE Catch',err]);

			client.emit('panelRemoveResponseError', err.code);
		});
}

var stocker = {
	init : init
};

module.exports = stocker;

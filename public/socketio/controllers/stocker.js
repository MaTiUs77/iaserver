var http = require('http');
var util = require('./util');
var config = require('./config');

var host = config.default.host;
var port = config.default.port;
var timeout = config.default.timeout;

function init(socket) {
	console.log("Stocker","Init");

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
	util.webPromise(host,port,uripath,timeout).then(function (response) {
		console.log("STK ADD Complete");
		socket.emit('stockerAddResponse', response, toastId);
	})
	.error(function (e) {
		console.log('STK ADD Error',e);
		socket.emit('stockerAddResponseError', e);
	})
	.catch(function (e) {
		console.log('STK ADD Catch',e);
		socket.emit('stockerAddResponseError', e);
	});
}

function remove(socket,stkbarcode,toastId) {
	var uripath = config.default.rootPath +
		'public/aoicollector/stocker/prod/remove/'+stkbarcode+'?json=1';
	util.webPromise(host,port,uripath,timeout).then(function (response) {
		console.log("STK REMOVE Complete");
		socket.emit('stockerRemoveResponse', response, toastId);
	})
	.error(function (e) {
		console.log('STK REMOVE Error',e);
		socket.emit('stockerRemoveResponseError', e);
	})
	.catch(function (e) {
		console.log('STK REMOVE Catch',e);
		socket.emit('stockerRemoveResponseError', e);
	});
}

function info(stkbarcode) {
	var uripath = config.default.rootPath +
		'public/aoicollector/stocker/info/'+stkbarcode;

	util.webPromise(host,port,uripath,timeout).then(function (response) {
		console.log("StockerInfo complete");
		socket.emit('stockerInfoResponse', response);
	}).error(function (e) {
		console.log('StockerInfo error',e);
		socket.emit('stockerInfoResponseError', e);
	}).catch(function (e) {
		console.log('StockerInfo catch',e);
		socket.emit('stockerInfoResponseError', e);

	});
}

function panelAdd(socket,panelbarcode) {
	var uripath = config.default.rootPath +
		'public/aoicollector/stocker/panel/add/'+panelbarcode+'/'+socket.aoibarcode+'?json=1';
	util.webPromise(host,port,uripath,timeout).then(function (response) {
			console.log("Panel ADD Complete");
			socket.emit('panelAddResponse', response);
		})
		.error(function (e) {
			console.log('Panel ADD Error',e);
			socket.emit('panelAddResponseError', e);
		})
		.catch(function (e) {
			console.log('Panel ADD Catch',e);
			socket.emit('panelAddResponseError', e);
		});
}

function panelRemove(socket,panelbarcode) {
	var uripath = config.default.rootPath +
		'public/aoicollector/stocker/panel/remove/'+panelbarcode+'?json=1';
	util.webPromise(host,port,uripath,timeout).then(function (response) {
			console.log("Panel REMOVE Complete");
			socket.emit('panelRemoveResponse', response);
		})
		.error(function (e) {
			console.log('Panel REMOVE Error',e);
			socket.emit('panelRemoveResponseError', e);
		})
		.catch(function (e) {
			console.log('Panel REMOVE Catch',e);
			socket.emit('panelRemoveResponseError', e);
		});
}

var stocker = {
	init : init
};

module.exports = stocker;

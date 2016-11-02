var http = require('http');
var app = require('express')();
var server = http.Server(app);
var cors = require('cors');
var net = require('net');
var dns = require('dns');
var _ = require('underscore');

var moment = require('moment');
var isReachable = require('is-reachable');
var blu = require('./controllers/blu');
var cmd = require('child_process').exec;

//var iapath = 'iaserver_laravel_svn';
var iapath = 'iaserver';

var host = 'localhost';
var port = 80;
var timeout = 5000;
var listenPort = 8081;
var statusInterval = 5000;

app.use(cors());
 
var serverStatus = [];

downloadServerList();

server.listen(listenPort, function(){
	console.log('Servidor iniciado en *:'+listenPort);
	isAlive();
});

app.get('/status', function (req, res) {	
	res.json(serverStatus);
});

setInterval(function() {
	isAlive();
}, statusInterval);
	
function downloadServerList() {
	console.log("Descargando lista de servidores");
	var uripath = '/'+iapath+'/public/servermonitor/lista';

	blu.webPromise(host,port,uripath,timeout).then(function (response) {
		serverStatus = response;
		console.log("Lista descargada correctamente");
	}).error(function (e) {
		console.log(e);
	}).catch(function (e) {
		console.log(e);
	});
}

function isAlive()
{
	console.log("-----------------------");
	console.log("Iniciando verificacion");
	console.log("-----------------------");
	if(serverStatus.length>0)
	{
		serverStatus.forEach(function(srv)
		{
			verify(srv);
		});
	} else
	{
		console.log('No se descarglo la lista de servidores');
	}
}

// Por defecto hace un ping al puerto 80
function verify(srv)
{
	if(srv.ping==undefined)
	{
		srv.ping = {
			start: null,
			end: null,
			max: 0,
			diffs: []
		}
	}

	consolePing(srv);
	/*
	srv.ping.start = {
		moment: moment(),
		fecha: moment().format('DD-MM-YYYY'),
		hora:  moment().format('HH:mm:ss')
	};

	isReachable(srv.host, function(err, reachable)
	{
		if(reachable) {
			srv.alive = reachable;

			srv.ping.end = {
				moment: moment(),
				fecha: moment().format('DD-MM-YYYY'),
				hora:  moment().format('HH:mm:ss')
			};

			var secdiff = srv.ping.end.moment.diff(srv.ping.start.moment,'seconds');
			srv.ping.diffs.push(secdiff);
		} else {
			consolePing(srv);
		}
	});*/
}

function consolePing(srv)
{
	srv.ping.start = {
		moment: moment(),
		fecha: moment().format('DD-MM-YYYY'),
		hora:  moment().format('HH:mm:ss')
	};

	cmd('ping '+srv.host, function(error, stdout, stderr) {
		if(stdout.indexOf("time") > -1) {
			var engMode = true;
		}

		if(stdout.indexOf("tiempo") > -1 || stdout.indexOf("time") > -1) {
			srv.alive = true;
		} else
		{
			srv.alive = false;
			console.log(srv.nombre, srv.host,'---- OFFLINE ----');
		}

		srv.stdout = stdout;

		srv.ping.end = {
			moment: moment(),
			fecha: moment().format('DD-MM-YYYY'),
			hora:  moment().format('HH:mm:ss')
		};

		var secdiff = srv.ping.end.moment.diff(srv.ping.start.moment,'seconds');

		var esRegex = /(.*)ximo = (.*)ms, Media/g;
		var enRegex = /(.*)ximum = (.*)ms, Average/g;

		var esMatch = esRegex.exec(stdout);
		var enMatch = enRegex.exec(stdout);
		var delay;

		if(esMatch) {
			delay = esMatch[2];
		} else if (enMatch) {
			delay = enMatch[2];
		}

		if(delay)
		{
			console.log('Delay',delay,srv.host);

			if(_.size(srv.ping.diffs)>=10)
			{
				srv.ping.diffs = _.rest(srv.ping.diffs);
			}
			srv.ping.diffs.push(delay);
			srv.ping.max = _.max(srv.ping.diffs);
		}
	});
}


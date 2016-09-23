var http = require('http');
var Promise = require('bluebird');

function webPromise(host,port,uripath,timeout) {
	return new Promise(function(resolve, reject) {
		var request = http.get({host: host, port: port, path: uripath}, function (response) {
			var data = "";

			response.on('data', function (chunk) {
				data += chunk;
			});
			response.on('end', function () {

				var output;
				try {
					output = JSON.parse(data);
				} catch (e) {
					output = {error: e.message};
				}

				resolve(output);
			});
		}).on("error", function (err) {
			reject(err);
		});

		setTimeout(function () {
			request.abort();
			reject('Timeout');
		}, timeout);
	});
}

var blu = {
	webPromise: webPromise
}
module.exports = blu;

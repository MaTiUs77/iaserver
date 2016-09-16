var cfg = {};

var localhost = {
	host : 'localhost',
	port : 80,
	timeout : 30000,
	rootPath : '/iaserver_laravel_svn/'
};

var produccion = {
	host : 'arushde04',
	port : 80,
	timeout : 10000,
	rootPath : '/iaserver/'
};

function local() { cfg.default = localhost; };
function prod() { cfg.default = produccion; };

cfg.default = produccion;

module.exports = cfg;


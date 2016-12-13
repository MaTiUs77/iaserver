var cfg = {};

var localhost = {
	host : 'localhost',
	port : 80,
	timeout : 50000,
	rootPath : '/iaserver/'
};

var produccion = {
	host : 'ARUSHAP34',
	port : 80,
	timeout : 10000,
	rootPath : '/iaserver/'
};

function local() { cfg.default = localhost; };
function prod() { cfg.default = produccion; };

cfg.default = local;
cfg.local = local;
cfg.prod = prod;

module.exports = cfg;


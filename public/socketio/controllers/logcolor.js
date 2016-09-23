var colors = require('colors/safe');
var moment = require('moment');
var util = require('util');

colors.setTheme({
	warning: 'yellow',
	error: 'red',
	debug: 'grey',
	verbose: 'green',
	info: 'cyan',
	notify: 'magenta'
});

function parseTime()
{
	var datetime = moment().format('DD-MM-YY HH:mm:ss');
	return util.format('%s #', datetime);
}

function warning(object) {
	var output = util.format('%s %s', parseTime(), object);
	console.log(colors.warning(object));
}
function error(object) {
	var output = util.format('%s %s', parseTime(), object);
	console.log(colors.error(output));
}
function debug(object) {
	var output = util.format('%s %s', parseTime(), object);
	console.log(colors.debug(output));
}
function verbose(object) {
	var output = util.format('%s %s', parseTime(), object);
	console.log(colors.verbose(output));
}
function info(object) {
	var output = util.format('%s %s', parseTime(), object);
	console.log(colors.info(output));
}
function notify(object) {
	var output = util.format('%s %s', parseTime(), object);
	console.log(colors.notify(output));
}

var cfg = {};
cfg.warning = warning;
cfg.error = error;
cfg.debug = debug;
cfg.verbose = verbose;
cfg.info = info;
cfg.notify = notify;

module.exports = cfg;


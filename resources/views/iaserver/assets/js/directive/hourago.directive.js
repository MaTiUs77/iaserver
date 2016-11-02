/**
 * Calcula el tiempo transcurrido entre un horario y el horario actual
 */
app.directive('hourAgo', function () {
    return function (scope, element, attrs)
    {
        var format = 'HH:mm:ss';
        var inicio  = moment(attrs.hourAgo,format);

        var seconds = attrs.refresh;
        if(seconds==undefined) { seconds = 60;}

        element.html('Calculando...');

        var update = function() {
            var time = new Date().toTimeString();
            var fin = moment(time,format);
            var ago = moment.duration(fin - inicio);
            var output;

            element.html(ago.hours()+'h, '+ago.minutes()+'m');
        }

        update();

        setInterval(update, seconds * 1000);
    };
});

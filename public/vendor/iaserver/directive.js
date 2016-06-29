/*
 <modal-btn load="archivo/contenido.html" header="Declarar OP" type="primary" class="btn-sm">Declarar</modal-btn>
 */
app.directive('modalBtn', function($rootScope, Modal) {
    return {
        restrict: 'E',
        replace: true,
        transclude: true,
        template: '<button ng-transclude></button>',
        link: function($scope, element, attrs)
        {
            var title = '';
            var type = BootstrapDialog.TYPE_PRIMARY;

            if(attrs.header)
            {
                title = attrs.header;
            } else
            {
                if(attrs.tooltip)
                {
                    title = attrs.tooltip;
                } else
                {
                    title = element.text();
                }
            }

            element.bind('click',function()
            {
                $scope.openModal(attrs.load,title,type,attrs.controller);
            });
        }
    }
});

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

app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
});

app.directive('ngAdmin', function () {
    return function (scope, element, attrs) {
        scope.$watch(attrs.ngAdmin, function(value) {
            if(value) {
                element.show();
            } else {
                element.hide();
            }
        }, true);
    };
});

app.directive('ngShortcut', function () {
    return function (scope, element, attrs) {
        var att = attrs.ngShortcut;
        var totalshort = att.split(',');
        if(totalshort.length > 0) {
            angular.forEach(totalshort, function(value) {
                var sp = value.split('=>');
                var shortkey = sp[0];
                var shortfunc = sp[1];

                shortcut.add(shortkey,function() {
                    scope.$eval(shortfunc)
                });
            });
        }
    };
});
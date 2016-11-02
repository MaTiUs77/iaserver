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
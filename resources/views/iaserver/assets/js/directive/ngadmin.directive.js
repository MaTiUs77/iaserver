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

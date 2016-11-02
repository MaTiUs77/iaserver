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

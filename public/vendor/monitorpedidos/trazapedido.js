app.controller("inspectionController",function ($scope,$http, $compile)
{

    $scope.currentPage = 1;

    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };

    $scope.pageChanged = function(uri) {
        window.location.href = uri + '/' + $scope.currentPage;
    };


    $scope.getInspectionBlocks = function(e)
    {
        var id_pedido = e.target.attributes.id_pedido.value;
        var route = e.target.attributes.route.value;

        var el = $scope.showRow(e, id_pedido);
        if(el)
        {
            $http.get(route).then(function(response) {
                var render = $compile(response.data)($scope);
                el.find('td').html(render);
            });
        }
    }

    $scope.getInspectionDetail = function(e)
    {
        var id_bloque = e.target.attributes.id_bloque.value;
        var route = e.target.attributes.route.value;
        var el = $scope.showRow(e, id_bloque);
        if(el)
        {
            $http.get(route).then(function(response) {
                var render = $compile(response.data)($scope);
                el.find('td').html(render);
            });
        }
    }

    $scope.showRow = function(e,rid)
    {
        var row_id = 'row_'+rid;
        var tr = $(e.target).parent().parent();
        var columnas = tr[0].childElementCount;

        var row = angular.element.find('#'+row_id);
        if(row.length>0) {
            angular.element(row).parent().remove();
            return false;
        } else {
            var content = angular.element('<tr><td colspan="'+columnas+'" id="'+row_id+'">' +
                '<div class="loader_mini">' +
                '<div class="rect1"></div> ' +
                '<div class="rect2"></div> ' +
                '<div class="rect3"></div> ' +
                '</div>' +
                '</td></tr>');
            var el = content.insertAfter(tr);
            return el;
        }
    }
});
@extends('inventario.index')
@extends('ng','myApp')
@section('body')

{{dd($label)}}

@endsection

<script>
    var app = angular.module('myApp', []);
    app.controller('customersCtrl', function($scope, $http) {
        $http.get("{{url('inventario/actualizar')}}")
                .then(function (response) {$scope.names = response.data.records;});
    });
</script>
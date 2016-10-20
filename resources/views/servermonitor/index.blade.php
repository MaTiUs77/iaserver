@extends('adminlte/theme')
@section('ng','app')
@section('title','Monitor de servidores')
@section('body')

    <div class="container" ng-controller="servidorMonitorController">
        <!-- will be used to show any messages -->
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <h3>Monitor de servidores</h3>
        <hr>

        <div class="callout callout-danger" ng-show="nodejserror">
            <h4>NodeJS OFFLINE</h4>

            <p>No fue posible obtener datos del servidor NodeJs</p>
        </div>

        <input dynamicknob type="text" class="knob" value="10" data-skin="tron" data-thickness="0.2" data-width="90" data-height="90" data-fgColor="#3c8dbc" data-readonly="true">

        <div dynamicbar data="cpumonitor">
            Cargando...
        </div>

        <div dynamicbar data="memoriamonitor">
            Cargando...
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12" ng-repeat="item in serverList">
                <div class="info-box" ng-class="item.alive ? 'bg-green' : 'bg-red'">
                    <span class="info-box-icon">
                        <span ng-show="item.alive">
                            @{{ item.ping.max  }}<small>ms</small>
                        </span>

                        <span ng-hide="item.alive">
                            <i class="fa fa-thumbs-o-down"></i>
                        </span>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">@{{ item.nombre }}</span>
                        <span class="info-box-number">@{{ item.host | uppercase }}</span>

                        <div dynamicbar data="item.ping.diffs">
                            Cargando...
                        </div>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </div>
    </div>

    @include('iaserver.common.footer')
    {!! IAScript('adminlte/plugins/sparkline/jquery.sparkline.min.js') !!}
    {!! IAScript('adminlte/plugins/knob/jquery.knob.js') !!}

    <script>
        app.controller("servidorMonitorController",function ($scope,$http, $interval)
        {
            $("input[dynamicknob]").knob();


            $scope.serverList = [];
            $scope.osmonitor = [];
            $scope.memoriamonitor = [];

            $scope.nodejserror = false;

            $scope.getMonitorStatus = function()
            {
                $http.get("http://arushde04:8081/status").then(function(res){
                    $scope.serverList = res.data;
                    $scope.nodejserror = false;

                }, function errorCallback(response) {
                    $scope.serverList = [];
                    $scope.nodejserror = true;
                });

                $http.get("http://localhost:8888/status").then(function(res){
                    $scope.osmonitor.push(res.data.cpu[0]);
                    $scope.memoriamonitor.push(res.data.memoria.porcent);

                    $("input[dynamicknob]").val(res.data.cpu[0]);

                    $("[data='cpumonitor']").sparkline($scope.osmonitor, {
                        type: 'line',
                        height: '30',
                        width: '120',
                        barWidth: 8,
                        barSpacing: 3,
                        barColor: '#65edae',
                        negBarColor: '#ff5656'
                    });

                    $("[data='memoriamonitor']").sparkline($scope.memoriamonitor, {
                        type: 'line',
                        height: '30',
                        width: '120',
                        barWidth: 8,
                        barSpacing: 3,
                        barColor: '#65edae',
                        negBarColor: '#ff5656'
                    });

                }, function errorCallback(response) {
                });
            };

            $interval(function(){
                $scope.getMonitorStatus();
            }, 5000);

            $scope.getMonitorStatus();
        });

        app.directive('dynamicbar', function() {
            return {
                scope: {
                    data: '='
                },
                link: function(scope, element) {
                    element.sparkline(scope.data, {
                        type: 'line',
                        height: '30',
                        width: '120',
                        barWidth: 8,
                        barSpacing: 3,
                        barColor: '#65edae',
                        negBarColor: '#ff5656'
                    });
                }
            }
        })
    </script>
@endsection
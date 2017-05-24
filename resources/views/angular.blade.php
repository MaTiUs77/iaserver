<!DOCTYPE html>
@if ($__env->yieldContent('ng'))
    <html lang="en" ng-app="@yield('ng')">
@else
    <html lang="en" ng-app="">
@endif
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IAServer - @yield('title')</title>

    <!-- jQuery 2.2.3 -->
    {!! IAScript('adminlte/plugins/jQuery/jquery-2.2.3.min.js') !!}
            <!-- Bootstrap 3.3.6 -->
    {!! IAStyle('adminlte/bootstrap/css/bootstrap.min.css') !!}
    {!! IAScript('adminlte/bootstrap/js/bootstrap.min.js') !!}
            <!-- Bootstrap Dialog-->
    {!! IAStyle('assets/dialog-master/css/bootstrap-dialog.min.css') !!}
    {!! IAScript('assets/dialog-master/js/bootstrap-dialog.min.js') !!}
            <!-- Font Awesome -->
    {!! IAStyle('assets/font-awesome/css/font-awesome.min.css') !!}
            <!-- Shortcut -->
    {!! IAScript('assets/jquery/shortcut.js') !!}
            <!-- Cookies -->
    {!! IAScript('assets/jquery/cookies/cookies.js') !!}
            <!-- AngularJS-->
    {!! IAStyle('assets/angularjs/loading-bar.css') !!}
    {!! IAScript('assets/angularjs/angular.min.js') !!}
    {!! IAScript('assets/angularjs/angular-route.min.js') !!}
    {!! IAScript('assets/angularjs/angular-animate.min.js') !!}
    {!! IAScript('assets/angularjs/loading-bar.js') !!}
            <!-- Angular Bootstrap -->
    {!! IAScript('assets/angularjs/ui-bootstrap-tpls-0.12.1.min.js') !!}
            <!-- Angular Toasty-->
    {!! IAStyle('assets/angularjs/toasty/angular-toasty.min.css') !!}
    {!! IAScript('assets/angularjs/toasty/angular-toasty.min.js') !!}
            <!-- Other styles -->
    {!! IAStyle('assets/loader_mini.css') !!}
            <!-- AdminLTE App -->
    {!! IAStyle('adminlte/dist/css/AdminLTE.css') !!}
    {!! IAStyle('adminlte/dist/css/skins/skin-blue.min.css') !!}
    {!! IAScript('adminlte/dist/js/app.min.js') !!}
            <!-- DataTables -->
    {!! IAStyle('adminlte/plugins/datatables/dataTables.bootstrap.css') !!}
    {!! IAScript('adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
    {!! IAScript('adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}
            <!-- Select2 -->
    {!! IAStyle('adminlte/plugins/select2/select2.min.css') !!}
            <!-- Moments en español -->
    {!! IAScript('assets/moment.min.js') !!}
    {!! IAScript('assets/moment.locale.es.js') !!}
            <!-- DataRangePicker -->
    {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
    {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}
            <!-- Angular DataTables -->
    {!! IAScript('assets/angular-datatables/angular-datatables.min.js') !!}

    @yield('head')
</head>
<body @if($__env->yieldContent('ng')) ng-cloak @endif @yield('bodytag')>

    @if(app()->environment()=='desarrollo')
        <div class="alert alert-warning" role="alert">
            <p><b>ATENCION</b> Usted se encuentra en el servidor de DESARROLLO, por favor, dirijase al servidor de PRODUCCION haciendo <a href="http://ARUSHAP34" target="_top">CLICK ACA</a></p>
        </div>
    @endif

    @yield('body')

    <toasty></toasty>
</body>
</html>
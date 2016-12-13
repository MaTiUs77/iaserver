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

    <!-- JQuery -->
    {!! IAScript('assets/jquery/js/jquery-1.10.2.min.js') !!}
    {!! IAStyle('assets/jquery/css/jquery-ui-gris.css') !!}

    <!-- Shortcut -->
    {!! IAScript('assets/jquery/shortcut.js') !!}

    <!-- Cookies -->
    {!! IAScript('assets/jquery/cookies/cookies.js') !!}

    <!-- Bootstrap -->
    {!! IAStyle('assets/bootstrap/css/bootstrap.css') !!}
    {!! IAStyle('assets/bootstrap/css/bootstrap-theme.min.css') !!}
    {!! IAStyle('assets/dialog-master/css/bootstrap-dialog.min.css') !!}
    {!! IAScript('assets/bootstrap/js/bootstrap.min.js') !!}
    {!! IAScript('assets/dialog-master/js/bootstrap-dialog.min.js') !!}

    <!-- BootSwatch 
    {!! IAStyle('assets/bootswatch/paper/bootstrap.min.css') !!}
	-->

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

    <!-- BootSwatch Angular File Manager
    {!! IAStyle('assets/angular-filemanager/dist/angular-filemanager.min.css') !!}
    {!! IAScript('assets/angular-filemanager/dist/angular-filemanager.min.js') !!}
    -->

    <!-- Angular Translate
    {!! IAScript('assets/angular-translate/angular-translate.min.js') !!}
    -->

    <!-- Other styles -->
    {!! IAStyle('assets/loader_mini.css') !!}

    <!-- Font Awesome -->
    {!! IAStyle('assets/font-awesome/css/font-awesome.min.css') !!}

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
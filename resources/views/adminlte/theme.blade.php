<!DOCTYPE html>
@if ($__env->yieldContent('ng'))
  <html lang="en" ng-app="@yield('ng')">
@else
  <html lang="en" ng-app="">
@endif
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IAServer 4.0 - @yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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

</head>
<body class="hold-transition skin-blue @hasSection('menumini') sidebar-mini @endif sidebar-collapse" @hasSection('ng') ng-cloak @endif @yield('bodytag')>

<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IA</b>4</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>IAServer</b>4.0</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Menu de navegacion</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            @include('adminlte/partial/navlogin')
          <!-- Control Sidebar Toggle Button
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
           -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  @include('adminlte/partial/menu')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-body">
              @yield('body')
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <!-- End Control Sidebar -->
</div>
<!-- ./wrapper -->

<toasty></toasty>
</body>
</html>

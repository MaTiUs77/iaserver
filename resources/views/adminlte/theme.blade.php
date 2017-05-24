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
    <!-- Select2 -->
    {!! IAStyle('adminlte/plugins/select2/select2.min.css') !!}
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
    <!-- Moments en español -->
    {!! IAScript('assets/moment.min.js') !!}
    {!! IAScript('assets/moment.locale.es.js') !!}
    <!-- DataRangePicker -->
    {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
    {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}
    <!-- Angular DataTables -->
    {!! IAScript('assets/angular-datatables/angular-datatables.min.js') !!}
    <script>
      function remoteLink(uri) {
        document.getElementById("ltebody").innerHTML='<object type="text/html" data="'+uri+'"  width="100%" height="2000"></object>';
      }
    </script>
  <style>
    html, body {
      height: 100%;
    }
  </style>
  @yield('head')
</head>
<body class="hold-transition skin-blue @yield('mini','sidebar-mini') @yield('collapse','sidebar-collapse')" @hasSection('ng') ng-cloak @endif @yield('bodytag')>

@if(app()->environment()=='desarrollo')
  <div class="callout callout-warning" style="border-radius: 0;margin:0;">
    <p><b>ATENCION</b> Usted se encuentra en el servidor de DESARROLLO, por favor, dirijase al servidor de PRODUCCION haciendo <a href="http://ARUSHAP34">CLICK ACA</a></p>
  </div>
@endif

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

      <!-- Navbar Login Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            @include('adminlte/partial/navlogin')
        </ul>
      </div>

      @hasSection('menutop')
        @yield('menutop')
      @endif

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  @hasSection('menuaside')
      @yield('menuaside')
  @else
      @include('adminlte/partial/menu')
  @endif

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
      @hasSection('nobox')
        @yield('body')
      @else
        <div class="box">
          <div class="box-body" id="ltebody">
            @yield('body')
          </div>
        </div>
      @endif
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <!-- End Control Sidebar -->
</div>
<!-- ./wrapper -->

<toasty></toasty>

<!-- iCheck para checkboxes -->
{!! IAStyle('adminlte/plugins/iCheck/all.css') !!}
{!! IAScript('adminlte/plugins/iCheck/icheck.min.js') !!}

<!-- Select2 -->
{!! IAScript('adminlte/plugins/select2/select2.full.min.js') !!}

<script type="text/javascript">
  // Moment en español
  moment.locale("es");

  // Funcion para levantar el datatable
  $(function() {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Initialize datatable Elements
    $(".datatable").DataTable( {
      "language": {
        "scrollX": true,
        "search": "Buscar",
        "lengthMenu":     "Ver _MENU_ resultados",
        "info": "Ver _START_ a _END_ de _TOTAL_ resultados",
        "zeroRecords": "No hay resultados",
        "paginate": {
          "first":      "Primero",
          "last":       "Ultimo",
          "next":       "Siguiente",
          "previous":   "Anterior"
        }
      }
    });

    // Datapicker sin rango de fechas
    $('input.defaultdatapicker').daterangepicker({
      locale: {
        format: 'DD/MM/YYYY'
      },
      autoApply: true,
      singleDatePicker: true
    });

    // Datapicker con rango de fechas
    $('input.defaultdatarangepicker').daterangepicker({
      locale: {
        format: 'DD/MM/YYYY',
        customRangeLabel: 'Definir rango'
      },
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()]
      },
      autoApply: true
    });

    // Datapicker con rango de fechas full
    $('input.fulldatarangepicker').daterangepicker({
      locale: {
        format: 'DD/MM/YYYY',
        customRangeLabel: 'Definir rango'
      },
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()],
        'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
        'Este Mes': [moment().startOf('month'), moment().endOf('month')],
        'Ultimo Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      autoApply: true
    });
  });
</script>


@yield('footer')

</body>
</html>

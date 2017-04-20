@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Aoicollector - Inspecciones')
@section('body')
    <div class="row" ng-controller="inspectionController">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-10">
            @include('aoicollector.inspection.partial.header')

            <div class="dropdown pull-right" style="margin:10px;">

                <button ng-show="statExporting" class="btn btn-sm btn-warning" type="button">
                    Procesando datos, espere...
                </button>

                <button ng-hide="statExporting" class="btn btn-sm btn-danger dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Exportar datos desde historial
                    <span class="caret"></span>
                </button>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenu" ng-hide="statExporting">
                    <li><a href="{{ route('aoicollector.inspection.export',[$maquina->id,str_replace('/','-',Session::get('inspection_date_session')),'MIN']) }}" ng-click="statExporting = true">Primer estado de cada placa en maquina</a></li>
                    <li><a href="{{ route('aoicollector.inspection.export',[$maquina->id,str_replace('/','-',Session::get('inspection_date_session')),'MAX']) }}" ng-click="statExporting = true">Ultimo estado de cada placa  en maquina</a></li>
                    <li><a href="{{ route('aoicollector.inspection.export',[$maquina->id,str_replace('/','-',Session::get('inspection_date_session')),'MINA']) }}" ng-click="statExporting = true">Primer estado de cada placa</a></li>
                </ul>
            </div>

            <div class="dropdown pull-right" style="margin:10px;">
                <button class="btn btn-sm btn-primary" ng-click="detalledeop=!detalledeop">
                    Ver detalle de OP
                </button>
            </div>

            <h3>Inspecciones en <b>SMD-{{ $maquina->linea }}</b> - Paneles en Total: <b>{{ $inspectionList->filas  }}</b></h3>
            <hr>

            @if(isAdmin())
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                <li class="dropdown user user-menu" >
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><i class="glyphicon glyphicon-add"></i> Alta de panel</span>
                    </a>

                    <ul class="dropdown-menu" style="width:300px;height:200px;">
                        <!-- The user image in the menu -->
                        <form method="POST" action="{{ route('aoicollector.inspection.admin.createinspection') }}">
                            <div class="box box-primary box-solid" style="width:100%;height:100%">
                                <div class="box-body">
                                    <textarea style="height:100px;" name="barcodes" class="form-control" placeholder="Ingresar barcodes de panel" ng-required="true"/></textarea>
                                    <input type="text" name="op" placeholder="Asignar OP" class="form-control">
                                </div>
                                <div class="box-footer">
                                    <button type="submit" style="float:left;" class="btn btn-info">Crear inspeccion</button>
                                </div>
                            </div>
                        </form>
                    </ul>
                </li></ul></div>
            @endif


        @foreach( $inspectionList->programas as $p )
                <?php
                $smt = \IAServer\Http\Controllers\SMTDatabase\SMTDatabase::findOp($p->inspected_op);
                ?>
                    <div class="col-xs-6 col-sm-4" ng-show="detalledeop">
                    <blockquote style="font-size: 16px;">
                        <div class="dropdown" style="padding-right:5px;">
                            <button class="btn btn-sm dropdown-toggle {{ isset($resume) && ($p->programa == $resume->programa)  && ($p->inspected_op == $resume->inspected_op)  ? 'btn-default active' : 'btn-default' }}" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span style="font-size: 16px;">{{ $p->inspected_op }}</span>
                            </button>
                        </div>

                        <small>Modelo</small>
                        {{ $smt->modelo  }} - {{ $smt->lote  }} - {{ $smt->panel  }}
                        <small>Programa</small>
                        {{ $p->programa  }}
                    </blockquote>

                </div>

                <a style="display: none;" href="#{{ route('aoicollector.inspection.showop',[$p->inspected_op ] ) }}" class="btn btn-sm btn-default" type="button" >
                    <span class="label label-default active" style="font-size: 12px;text-shadow: none;">{{ $p->inspected_op }}</span>
                    {{ $p->programa  }}
                </a>
            @endforeach

            <!-- OP INSPECCIONADAS EN LA JORNADA -->
            <div style="padding: 5px;">
                @if( isset($op_list) )
                    @foreach( $op_list as $op )
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default">{{ $op->op  }}</button>
                            <button type="button" class="btn btn-success">{{ $op->total  }}</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <!-- END -->

            @include('aoicollector.inspection.partial.list')
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2">
            @include('aoicollector.inspection.partial.sidebar')
        </div>
    </div>

    @include('iaserver.common.footer')
    {!! IAScript('vendor/aoicollector/inspection/inspection.js') !!}

    <!-- Include Date Range Picker -->
    {!! IAScript('assets/moment.min.js') !!}
    {!! IAScript('assets/moment.locale.es.js') !!}
    {!! IAScript('assets/jquery/daterangepicker/daterangepicker.js') !!}
    {!! IAStyle('assets/jquery/daterangepicker/daterangepicker.css') !!}
    <script type="text/javascript">
        moment.locale("es");

        $(function() {
            $('input[name="inspection_date_session"]').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    customRangeLabel: 'Definir rango'
                },
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()]
                },
                autoApply: true,
                singleDatePicker: true
            });
        });
    </script>
@endsection
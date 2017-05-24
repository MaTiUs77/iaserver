@extends('adminlte/theme')
@section('ng','app')
@section('mini',false)
@section('title','Cogiscan - Grafico de carga')
@section('body')

    <div ng-controller="inspectionController">

        <div class="well">
            <div class="pull-right">
                <form method="GET" action="{{ route('cogiscan.graficos.carga') }}" class="navbar-form navbar-left" style="margin: 0;">
                    <div class="form-group">
                        <input type="text" name="date_session" value="{{ Session::get('date_session') }}" placeholder="Seleccionar fecha" class="form-control defaultdatarangepicker"/>
                    </div>

                    <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
                </form>
            </div>

            <a href="{{ route('aoicollector.inspection.index') }}" class="btn btn-info">WDSL Service</a>
            <a href="{{ route('aoicollector.stat.index') }}" class="btn btn-info">DB2 Service</a>
        </div>

        @foreach($byUser as $user => $info)
            <div class="col-sm-6" >
                @include('cogiscan.chart.drilldown',[
                    'user' => $user,
                    'info' => $info
                ])
            </div>
        @endforeach
    </div>

    @include('iaserver.common.footer')
    {!! IAScript('vendor/aoicollector/inspection/inspection.js') !!}

    {!! IAScript('assets/highchart/js/highcharts.js') !!}
    {!! IAScript('assets/highchart/js/modules/drilldown.js') !!}
@endsection
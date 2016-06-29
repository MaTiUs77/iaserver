@extends('angular')
@section('ng','app')
@section('title','P2i - Lista de registros de carga')
@section('body')
    @include('p2i.common.header')
    @include('p2i.common.bread',['bread'=>['Carga','Ver registros']])

    <div style="float: right;">
        @include('iaserver.common.datepicker',['date_session'=>Session::get('date_session'),'route'=> url('p2i/carga')])
    </div>

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    @if(count($proceso)>0)
        <h3>En proceso</h3>
        @include('p2i.carga.partial.table',['carga'=>$proceso])
    @endif

    @if(count($finalizado)>0)
        <h3>Finalizado</h3>
        @include('p2i.carga.partial.table',['carga'=>$finalizado])
    @endif

    @if(count($proceso)==0 && count($finalizado)==0)
        <h3>No hay registros cargados en el dia {{ Session::get('date_session') }}</h3>
    @endif

    @include('p2i.common.footer')
@endsection

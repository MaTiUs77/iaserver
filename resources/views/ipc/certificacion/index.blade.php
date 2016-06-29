@extends('angular')
@section('ng','app')
@section('title','IPC - Certificacion')
@section('body')
    @include('ipc.common.header')
    @include('ipc.common.bread',['bread'=>['Certificacion','Ver registros']])

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    @include('ipc.certificacion.partial.table',$certificacion)
    @include('ipc.common.footer')
@endsection

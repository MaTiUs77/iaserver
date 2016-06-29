@extends('angular')
@section('ng','app')
@section('title','IPC - Personas')
@section('body')
    @include('ipc.common.header')
    @include('ipc.common.bread',['bread'=>['Personas','Ver registros']])

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    @include('ipc.personas.partial.table',$personas)
    @include('ipc.common.footer')
@endsection

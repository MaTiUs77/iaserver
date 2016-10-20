@extends('adminlte/theme')
@section('title', 'Bienvenido')
@section('head')
    {!! IAStyle('assets/home/iaserver.css') !!}
    {!! IAScript('assets/home/iaserver.js') !!}
@endsection

@section('collapse',false)
@section('mini',false)
@section('body')
    <object type="text/html" data="{{ route('iaserver.logo') }}"  width="100%" height="650"></object>
@endsection

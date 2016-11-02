@extends('adminlte/theme')
@section('title', 'Bienvenido')
@section('collapse',false)
@section('mini',false)
@section('body')
    <object type="text/html" data="{{ route('iaserver.logo') }}"  width="100%" height="650"></object>
@endsection

@extends('angular')
@section('title', 'Logo')
@section('head')
    {!! IAStyle('assets/animate.min.css') !!}
    {!! IAStyle('assets/home/logo.css') !!}
    {!! IAScript('assets/home/logo.js') !!}
@endsection
@section('body')
    <div style="overflow: hidden;">
        <span id="animationLogo" style="display: block;"><h1 class="site__title mega">IAServer</h1></span>
        <span id="animationText" style="display: block;"><h2>Servidor de aplicaciones</h2></span>
    </div>

    <small style="color:#c4c4c4;">Environment: {{ app()->environment() }}</small>
@endsection

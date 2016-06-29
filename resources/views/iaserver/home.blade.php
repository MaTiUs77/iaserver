@extends('angular')
@section('title', 'Bienvenido')
@section('head')
    {!! IAStyle('assets/home/iaserver.css') !!}
    {!! IAScript('assets/home/iaserver.js') !!}
@endsection
@section('body')
    <div ng-click="sysMenu=!sysMenu" class="sysMenuBtn btn btn-info">@{{ !sysMenu && 'Ocultar' || 'Mostrar' }} Menu de aplicaciones</div>

    <table class="tabla" align="center" style="width:100%;height:100%;" cellpadding=0 cellspacing=0>
        <tr>
            <td colspan="2" id="header">
                <div>IAServer</div>
            </td>
        </tr>
        <tr>
            <td class="sysMenu" ng-hide="sysMenu">
                <div id="sysUser">
                    @include('iaserver.user')
                </div>

                <div id="sysMenu" style="height:90%; overflow: auto;">
                    @include('iaserver.menu')
                </div>
            </td>
            <td class="sysMain">
                <div id="sysMain">
                    <iframe frameborder="0" class="iframe" src="{{ route('iaserver.logo') }}"></iframe>
                </div>
            </td>
        </tr>
    </table>
@endsection
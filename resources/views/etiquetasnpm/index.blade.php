@extends('adminlte/theme')
@section('title','Etiquetas NPM')
@section('mini',false)
@section('collapse',false)
@section('menuaside')
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ url('/etiquetasnpm/registrar') }}">IMPRIMIR ETIQUETAS</a></li>
            </ul>
        </section>
    </aside>
@endsection
@section('body')

    @endsection

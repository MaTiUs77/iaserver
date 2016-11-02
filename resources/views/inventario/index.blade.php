
@extends('adminlte/theme')
@section('title','Inventario 2016')
@section('mini',false)
@section('collapse',false)
@section('menuaside')
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">

                <!-- TREEMENU -->
                <li class="treeview">
                    <a href="#"><i class="glyphicon glyphicon-qrcode"></i>
                        <span>EBS</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="{{ url('amr/parciales') }}">PEDIDOS PARCIALES/ERROR</a></li>--}}
                        {{--<li><a href="{{ url('amr/pedidos/nuevos') }}">PEDIDOS NUEVOS</a></li>--}}
                        {{--<li><a href="{{ url('amr/pedidos/procesados') }}">PEDIDOS PROCESADOS</a></li>--}}
                    {{--</ul>--}}
                </li>
                <!-- END TREEMENU -->
                <li><a href="{{ url('inventario/actualizar') }}">Editar Etiquetas</a></li>
            </ul>
        </section>
    </aside>

@endsection
@section('body')

@endsection

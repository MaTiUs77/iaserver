@extends('adminlte/theme')
@section('title','Inventario 2016')
@section('ng','app')
@section('mini',false)
@section('collapse',false)
@section('menuaside')
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">

                <!-- MENU -->
                <li><a href="{{ route('inventario.imprimir') }}">Imprimir Etiquetas</a></li>
                <li><a href="{{ url('inventario/consultar') }}">Consultar Etiquetas</a></li>
                <!-- TREEMENU -->
                <li class="treeview">
                    @if(isAdmin()|| isInventoryOper())
                    <a href="#"><i class="glyphicon glyphicon-wrench"></i>
                        <span>Configuración</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('inventario.configurar.usuarios.profile') }}">Perfil de Usuario</a></li>
                        @if (isAdmin())
                        <li><a href="{{ route('inventario.configurar.impresoras') }}">ABM Impresoras</a></li>

                        <li><a href="{{ route('inventario.configurar.usuarios') }}">Usuarios</a></li>
                            @endif
                        @endif
                    </ul>
                </li>
                <!-- END TREEMENU -->
                <!-- END MENU -->
            </ul>
        </section>
    </aside>

@endsection

@section('body')

@endsection

<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">

            <!-- TREEMENU -->
            <li class="treeview">
                <a href="#"><i class="glyphicon glyphicon-qrcode"></i>
                    <span>AOI</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('aoicollector.prod.index') }}">Panel de inspector</a></li>
                    <li><a href="{{ route('aoicollector.stat.index') }}">Estadisticas</a></li>
                    <li><a href="{{ route('aoicollector.inspection.index') }}">Inspecciones</a></li>
                </ul>
            </li>
            <!-- END TREEMENU -->

            <li><a href="{{ route('aoicollector.stocker.lavado.index') }}"><i class="fa fa-link"></i> <span>Lavado de stocker</span></a></li>
            <li><a href="{{ route('smtdatabase.index') }}"><i class="fa fa-link"></i> <span>SMTDatabase</span></a></li>
            <li><a href="{{ route('trazabilidad.index') }}"><i class="fa fa-link"></i> <span>Trazabilidad</span></a></li>

            <li><a href="#"><i class="glyphicon glyphicon-sd-video"></i> <span>Control de Stencil</span></a></li>
        </ul>
    </section>
</aside>

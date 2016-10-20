@if(count($item->submenu)>0)
    <!-- SUBMENU -->
    <li class="treeview">
        <a href="javascript:;">
            <i class="{{ $item->icono  }}"></i>
            <span>{{ $item->titulo }}</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            @foreach($item->submenu as $sub)
                @include('adminlte.partial.submenu',['item'=>$sub])
            @endforeach
        </ul>
    </li>
    <!-- END SUBMENU -->
@else
    <li>
        <a href="{{ ($item->type=='route') ? route($item->enlace): "javascript:remoteLink('".$item->link."')" }}">
            <i class="{{ $item->icono }}"></i>
            <span>{{ $item->titulo }}</span>
        </a>
    </li>
@endif

<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">

            @foreach(\IAServer\Http\Controllers\IAServer\IAServerController::IAServerMenu() as $menu)
                @if(count($menu->submenu)>0 && $menu->root == 0)
                    @include('adminlte.partial.submenu',['item'=>$menu])
                @else
                    @if($menu->root == 0)
                        <li>
                            <a href="{{ ($menu->type=='route') ? route($menu->enlace) : "javascript:remoteLink('".$menu->link."')" }}">
                                <i class="{{ $menu->icono ? $menu->icono : 'fa fa-circle-o' }}"></i>
                                <span>{{ $menu->titulo }}</span>
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </section>
</aside>

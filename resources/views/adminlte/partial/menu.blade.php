<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            @foreach(\IAServer\Http\Controllers\IAServer\IAServerController::IAServerMenu() as $menu)
                @if(count($menu->submenu)>0 && $menu->root == 0)
                    @include('adminlte.partial.submenu',['item'=>$menu])
                @else
                    @if($menu->root == 0)
                        <li>
                            <?php
                                switch($menu->type){
                                    case 'route':
                                        $enlace = route($menu->enlace);
                                    break;
                                    case 'self':
                                        $enlace = $menu->enlace;
                                    break;
                                    default:
                                        $enlace = "javascript:remoteLink('$menu->link')";
                                    break;
                                }
                            ?>
                            <a href="{{ $enlace }}">
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

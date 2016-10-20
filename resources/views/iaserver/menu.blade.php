
<nav>
    <ul>
    @foreach($root as $m)
        @if($m->root == 0)
            <li>
                @if(count($m->submenu)>0)
                    <a href="javascript:;" rel="{{ $m->titulo }}"> <span class="{{ $m->icono }}" style="color:#147ace;"></span>  <span class="glyphicon glyphicon-qrcode" style="font-size:20px;"></span> {{ $m->titulo }}</a>
                    <ul>
                    @foreach($m->submenu as $s)
                        @if(!empty($s->enlace))
                            <li>
                                <a href="javascript:IniciarModulo('{{ $s->type == 'route' ? route($s->enlace) : $s->enlace }}');" >{{ $s->titulo }}</a>
                            </li>
                        @endif
                    @endforeach
                    </ul>
                @else
                    @if(!empty($m->enlace))
                        <a href="javascript:IniciarModulo('{{ $m->type == 'route' ? route($m->enlace) : $m->enlace }}');">
                            <span class="{{ $m->icono }}" style="font-size:20px;"></span> {{ $m->titulo }}
                        </a>
                    @endif

                @endif
            </li>
        @endif
    @endforeach
    </ul>
</nav>
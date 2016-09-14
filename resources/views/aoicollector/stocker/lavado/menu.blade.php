<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('aoicollector.stocker.lavado.index') }}">Lavado de stocker</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @if(!Auth::user())
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="glyphicon glyphicon-user"></i> Ingresar
                    </a>
                    <div style="padding: 5px;" class="dropdown-menu form-login stop-propagation" role="menu">
                        <form method="POST" action="{{ route('aoicollector.stocker.lavado.index') }}">
                        <div class="form-group">
                            <label for="name">Usuario</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Usuario" />
                        </div>
                        <div class="form-group">
                            <label for="password">Clave</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Clave" />
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Ingresar</button>
                        </form>
                    </div>
                </li>
            </ul>
            @else
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="glyphicon glyphicon-user"></i>

                            @if (Auth::user()->hasProfile())
                                {{ Auth::user()->profile->fullname() }}
                            @else
                                {{ Auth::user()->name }}
                            @endif
                        </a>
                        <div style="padding: 5px;" class="dropdown-menu form-login stop-propagation" role="menu">
                            <a href="{{ route('aoicollector.stocker.lavado.index','logout=1') }}" class="btn btn-default btn-block">Salir</a>
                        </div>
                    </li>
                </ul>
            @endif
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif



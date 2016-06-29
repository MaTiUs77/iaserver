@if (Auth::guest())
    <button class="btn btn-block btn-default" ng-click="showLoginBox=1" ng-hide="showLoginBox">Iniciar sesion</button>

    <div ng-show="showLoginBox" class="panel panel-default">
        <div class="panel-heading">Iniciar sesion</div>
        <div class="panel-body">
            <form action="auth/login" method="post" class="form">
                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" name="name" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" />
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-sm btn-success">Ingresar</button>
                    <button class="btn btn-sm btn-default" ng-click="showLoginBox=0">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

@else
    <div style="padding:5px;">Bienvenido:
        <b>
            @if (Auth::user()->hasProfile())
                {{ Auth::user()->profile->fullname() }}
            @else
                {{ Auth::user()->name }}
            @endif
        </b></div>
    <a href="{{ url('auth/logout') }}" class="btn btn-sm btn-default btn-block">Cerrar session</a>
@endif

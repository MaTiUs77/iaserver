  <li class="dropdown user user-menu">

    <!-- Menu Toggle Button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <!-- The user image in the navbar-->
      <img src="{{ asset('adminlte/dist/img/user.png') }}" class="user-image">
      <!-- hidden-xs hides the username on small devices so only the image appears. -->
       <span class="hidden-xs">
            @if(Auth::user())
                @if (Auth::user()->hasProfile())
                   {{ Auth::user()->profile->fullname() }}
                @else
                   {{ Auth::user()->name }}
                @endif
            @else
                Ingresar
           @endif
      </span>
    </a>

    <ul class="dropdown-menu">
      <!-- The user image in the menu -->
            @if(Auth::user())
            <li class="user-header">
                <img src="{{ asset('adminlte/dist/img/user.png') }}" class="img-circle">
                  <p>
                    @if (Auth::user()->hasProfile())
                        {{ Auth::user()->profile->fullname() }}
                    @else
                        {{ Auth::user()->name }}
                    @endif
                  </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
                <div class="pull-right">
                    <a href="{{ route('iaserver.logout') }}" class="btn btn-default btn-flat">Finalizar</a>
                </div>
            </li>
          @else
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ingresar credenciales</h3>
                    </div>
                    <form method="POST" class="form-horizontal" action="{{ route('iaserver.atlogin') }}">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputUser" class="col-sm-3 control-label">Usuario</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" class="form-control" id="inputUser" placeholder="Usuario">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword" class="col-sm-3 control-label">Clave</label>

                                <div class="col-sm-8">
                                    <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Clave">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-info pull-right">Ingresar</button>
                        </div>
                    </form>
                </div>
            </form>
        @endif

    </ul>
  </li>

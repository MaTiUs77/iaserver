<nav class="navbar navbar-default" style="padding-bottom:5px;margin-bottom:1px;" role="navigation">
    <div class="navbar-form">
        <a href="{{ route('abm.index') }}" class="btn btn-info {{ Request::segment(count(Request::segments())) == 'abm' ? 'active' : '' }}" ><span class="fa fa-users fa-lg"></span> Lista de usuarios</a>
        <a href="{{ route('abm.create') }}" class="btn btn-info {{ Request::segment(count(Request::segments())) == 'create' ? 'active' : '' }}" ><span class="fa fa-user-plus fa-lg"></span> Agregar usuario</a>
    </div>
</nav>


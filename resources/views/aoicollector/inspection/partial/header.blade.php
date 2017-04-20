<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Ver menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ route('aoicollector.stat.index') }}">Ver Estadisticas</a>
                </li>
                <li>
                    <a href="{{ route('aoicollector.inspection.defectos.periodo') }}">Defectos por periodo</a>
                </li>
                <li>
                    <a href="#search"><i class="glyphicon glyphicon-search"></i> Buscar placa</a>
                </li>

                <li class="dropdown user user-menu" >
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><i class="glyphicon glyphicon-search"></i> Busqueda multiple</span>
                    </a>

                    <ul class="dropdown-menu" style="width:300px;height:200px;">
                        <!-- The user image in the menu -->
                        <form method="POST" action="{{ route('aoicollector.inspection.multiplesearch') }}">
                            <div class="box box-primary box-solid" style="width:100%;height:100%">
                            <div class="box-body">
                                <textarea style="height:100px;" name="barcodes" class="form-control" placeholder="Ingresar multiples barcode" ng-required="true"/></textarea>
                            </div>
                            <div class="box-footer">
                                <button type="submit" style="float:left;" name="mode" value="first" class="btn btn-info">Primer resultado</button>
                                <button type="submit" style="float:left;margin-left:5px;"  name="mode" value="last" class="btn btn-info">Ultimo resultado</button>
                            </div>
                        </div>
                        </form>
                    </ul>
                </li>
                <li>
                    <form method="GET" action="{{ route('aoicollector.inspection.show',$maquina->id) }}" class="navbar-form">
                        <div class="form-group">
                            <input type="text" name="inspection_date_session" value="{{ Session::get('inspection_date_session') }}" placeholder="Seleccionar fecha" class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
                    </form>
                </li>

                <li class="dropdown user user-menu" style="display:none;">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><i class="glyphicon glyphicon-search"></i> Filtrar</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                            <div class="box box-info" style="width:300px;">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Filtro avanzado</h3>
                                </div>
                                <form method="POST" class="form-horizontal" action="{{ route('aoicollector.inspection.show',$maquina->id) }}">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="inputUser" class="col-sm-3 control-label">Inspeccion</label>
                                            <div class="col-sm-8">
                                                <select name="listMode" class="form-control">
                                                    <option value="MAX" {{ (Input::get('listMode')=='MAX') ? 'selected=selected' : '' }}>Ultimo estado</option>
                                                    <option value="MIN" {{ (Input::get('listMode')=='MIN') ? 'selected=selected' : '' }}>Primer estado</option>
                                                    <option value="MINA"  {{ (Input::get('listMode')=='MINA') ? 'selected=selected' : '' }}>Primer aparicion</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword" class="col-sm-3 control-label">Hora</label>

                                            <div class="col-sm-8">
                                                <select name="filterPeriod" class="form-control">
                                                    <option value="" {{ (Input::get('filterPeriod')=='') ? 'selected=selected' : '' }}>Todo el dia</option>
                                                    @for($i = 0; $i < 23; $i++) {
                                                    <?php $iZeroLeft = str_pad($i, 2, 0, STR_PAD_LEFT);?>
                                                    <option value="{{ $iZeroLeft }}:00:00" {{ (Input::get('filterPeriod')=="$iZeroLeft:00:00") ? 'selected=selected' : '' }}>{{ $iZeroLeft }}:00</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-info btn-block"><i class="glyphicon glyphicon-calendar"></i> Aplicar</button>
                                    </div>
                                </form>
                            </div>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div id="search">
    <button type="button" class="close">×</button>
    <!-- BUSQUEDA DE PLACA -->
    <form method="POST" action="{{ route('aoicollector.inspection.search') }}" class="navbar-form">
        <input type="search" name="barcode" placeholder="Ingresar barcode a buscar" ng-required="true" autocomplete="off"/>
        <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Buscar</button>
    </form>
    <!-- END BUSQUEDA DE PLACA -->
</div>

<style>
    #search {
        position: fixed;
        z-index: 99999;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);

        -webkit-transition: all 0.5s ease-in-out;
        -moz-transition: all 0.5s ease-in-out;
        -o-transition: all 0.5s ease-in-out;
        -ms-transition: all 0.5s ease-in-out;
        transition: all 0.5s ease-in-out;

        -webkit-transform: translate(0px, -100%) scale(0, 0);
        -moz-transform: translate(0px, -100%) scale(0, 0);
        -o-transform: translate(0px, -100%) scale(0, 0);
        -ms-transform: translate(0px, -100%) scale(0, 0);
        transform: translate(0px, -100%) scale(0, 0);

        opacity: 0;
    }

    #search.open {
        -webkit-transform: translate(0px, 0px) scale(1, 1);
        -moz-transform: translate(0px, 0px) scale(1, 1);
        -o-transform: translate(0px, 0px) scale(1, 1);
        -ms-transform: translate(0px, 0px) scale(1, 1);
        transform: translate(0px, 0px) scale(1, 1);
        opacity: 1;
    }

    #search input[type="search"] {
        position: absolute;
        top: 50%;
        width: 100%;
        color: rgb(255, 255, 255);
        background: rgba(0, 0, 0, 0);
        font-size: 60px;
        font-weight: 300;
        text-align: center;
        border: 0px;
        margin: 0px auto;
        margin-top: -51px;
        padding-left: 30px;
        padding-right: 30px;
        outline: none;
    }
    #search .btn {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: 61px;
        margin-left: -45px;
    }
    #search .close {
        position: fixed;
        top: 15px;
        right: 15px;
        color: #fff;
        background-color: #428bca;
        border-color: #357ebd;
        opacity: 1;
        padding: 10px 17px;
        font-size: 27px;
    }
</style>

<script>
    $(function () {
        $('a[href="#search"]').on('click', function(event) {
            event.preventDefault();
            $('#search').addClass('open');
            $('#search > form > input[type="search"]').focus();
        });

        $('#search, #search button.close').on('click keyup', function(event) {
            if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
                $(this).removeClass('open');
            }
        });
    });
</script>
@if($wip->active)
    @if(isset($btnDeclarar) && $btnDeclarar)
        <?php
            $lastColor = \IAServer\Http\Controllers\Memorias\Model\Grabacion::where('op',$wip->wip_ot->nro_op)->orderBy('fecha','desc')->first();
        ?>
        <div class="well" style="margin: 5px 0px 5px 0px;" ng-init="memoryColor = ['000000','0000FF','7755aa','00FF00','00BB00','00FFFF','FF0000','FF00FF','FFFF00','FFFFFF','FF9900','964B00','666600','BBBBBB'];memoryDefaultColor = '{{ (isset($lastColor->color)) ? $lastColor->color : 'FF0000' }}';memorySelectedColor = ''; ">

        <form method="post" action="{{ route('memorias.form.declarar.submit',[$wip->wip_ot->nro_op,'trazabilidad.find.op']) }}">
            <div class="row">
                <div class="col-sm-2 col-md-2 col-lg-2">
                    <h4>Declarar:</h4>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3">
                    <input type="text" class="form-control" name="cantidad" placeholder="Cantidad">
                    <input type="hidden" class="form-control" name="op" value="{{ $wip->wip_ot->nro_op}}">
                    <input type="text" class="form-control" name="color" ng-model="memorySelectedColor" style="display:none;">
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4">
                    <select class="form-control" name="id_programador">
                        @foreach(\IAServer\Http\Controllers\Memorias\Model\Programador::all() as $programador)
                            <option value="{{ $programador->id }}">{{ $programador->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3">
                    <button type="submit" class="btn btn-block btn-success" ng-click="grabandoMemoria = true" ng-hide="grabandoMemoria">Declarar</button>
                    <button type="button" class="btn btn-block btn-default" ng-show="grabandoMemoria">Espere...</button>
                </div>
            </div>

            <p>
            <div class="row">
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                    <div style="width:25px; height:25px; border: 1px solid #000; background-color: #@{{memorySelectedColor ? memorySelectedColor : memoryDefaultColor }};"></div>
                </div>
                <div class="col-xs-8 col-sm-10 col-md-10 col-lg-10 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                    <input type="button" ng-repeat="color in memoryColor" ng-click="$parent.memorySelectedColor = color"  class="col-xs-6 col-sm-8 col-md-8 col-lg-8 " style="border: 1px solid #000; width:20px; height:20px; background-color: #@{{color}};"></input>
                </div>
            </div>
            </p>

        </form>

        </div>

    @endif
@endif

{{ \IAServer\Http\Controllers\Memorias\Memorias::updateTransPendientes() }}

<table class="table table-bordered table-striped table-hover">
    <thead>
    <tr>
        @if(isset($btnPrint) && $btnPrint)
            <th></th>
        @endif
        <th>Fecha</th>
        <th>Color</th>
        <th style="width: 70px">Cantidad</th>
        <th>Maquina</th>
        <th>Usuarios</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
        @foreach(\IAServer\Http\Controllers\Memorias\Model\Grabacion::where('op',$wip->wip_ot->nro_op)->get() as $grabacion)
        <tr>
            @if(isset($btnPrint) && $btnPrint)
                <td>
                    {{--@if($grabacion->traza_code==0)--}}
                        <form method="post" target="_blank" action="{{ route('memorias.zebra.print',[$grabacion->op,$grabacion->cantidad,$grabacion->id]) }}">
                            <button type="submit" class="btn btn-xs btn-primary" tooltip-placement="right" tooltip="Imprimir etiqueta"><span class="fa fa-print"></span></button>
                        </form>
                    {{--@endif--}}
                </td>
            @endif
            <td>
                <small>{{ $grabacion->fecha }}</small>
            </td>
            <td>
                <span class="badge" style="background-color: #{{ $grabacion->color }};box-shadow:0px 0px 1px #DDDDDD,0px 1px 1px #DDDDDD, -1px 0px 1px #DDDDDD, 0px -1px 1px #DDDDDD">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </td>
            <td>
                {{ $grabacion->cantidad }}
            </td>
            <td>
                {{ \IAServer\Http\Controllers\Memorias\Model\Programador::where('id',$grabacion->id_programador)->first()->descripcion }}
            </td>
            <td>
                <b>{{ $grabacion->operador->name }}</b>
            </td>
            <td>
                @if($grabacion->traza_code==0)
                    <button class="btn btn-xs btn-default" tooltip-placement="left" tooltip="En proceso..."><span class="glyphicon glyphicon-hand-right"></span></button>
                @endif
                @if($grabacion->traza_code==1)
                    <button class="btn btn-xs btn-success" tooltip-placement="left" tooltip="{{ $grabacion->traza_det }}"><span class="glyphicon glyphicon-thumbs-up"></span></button>
                @endif
                @if($grabacion->traza_code>1)
                    <button class="btn btn-xs btn-danger" tooltip-placement="left" tooltip="{{ $grabacion->traza_det }}"><span class="glyphicon glyphicon-thumbs-down"></span></button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

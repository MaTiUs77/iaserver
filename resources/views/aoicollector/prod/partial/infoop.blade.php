@if(empty($op))
    <h3>No especifico una OP</h3>
@else
    @if($wip->wip_ot)
        <form class="form-horizontal" role="form" method="post" action="{{ route('aoicollector.prod.infoop.submit') }}">
            <input type="hidden"  name="op" value="{{ $op }}">
            <input type="hidden" name="aoibarcode" value="{{ $aoibarcode }}">

            <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6">
                <blockquote>
                    <h3>
                        {{ $op }}
                    </h3>
                    <small>Estado:</small> <span style="padding: 5px;" class="label label-{{ $wip->active ? 'success' : 'danger' }}">{{ $wip->active ? 'ACTIVA' : 'CERRADA' }}</span>

                    <small>Modelo:</small>
                    @if(isset($smt))
                        {{ $smt->modelo }} - {{ $smt->panel }} - {{ $smt->lote }}
                    @else
                        <span class="label label-danger">No hay datos en SMTDatabase</span>
                    @endif

                    <small>Semielaborado:</small> {{ $wip->wip_ot->codigo_producto }}
                    @if($wip->active )
                        <small>Cantidad de lote: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Cantidad de lote segun WIP_OT"></span></small> {{ $wip->wip_ot->start_quantity }}
                        <small>Cantidad declarada: <span class="glyphicon glyphicon-info-sign" tooltip-placement="right" tooltip="Declaraciones segun WIP_OT"></span></small> {{ $wip->wip_ot->quantity_completed }}
                        <small>Restante:</small> {{ $wip->wip_ot->restante }}
                    @endif

                </blockquote>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6">
{{--

                <blockquote>
                    <h4>Seleccionar ruta</h4>
                    <div class="form-group">
                        <input type="hidden" name="modelo_id" value="{{ $sfcs['modelo_id'] }}">
                        <input type="hidden" name="line_id" value="{{ $sfcs['line_id'] }}">
                        <select class="form-control" name="puesto_id" ng-model="puesto_id">
                            <option value="">- Seleccionar puesto -</option>
                            @foreach($sfcs['list'] as $route)
                                <option value="{{ $route['puesto_id'] }}">
                                    {{ $route['puesto'] }} -
                                    @if($route['declara'])
                                        Declara
                                    @else
                                        No declara
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </blockquote>
--}}

                <blockquote>
                    <h4>Seleccionar puesto</h4>
                    @if(isset($routeop) && count($routeop)>0)
                        <div class="form-group">
                            <select class="form-control" name="id_route_op" ng-model="id_route_op" style="font-size:18px;">
                                <option value="">- Elegir una opcion -</option>
                                @foreach($routeop as $route)
                                    <option value="{{ $route->id }}">
                                        {{ $route->name }} -
                                        @if($route->declare)
                                            Declara
                                            @else
                                            No declara
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="callout callout-danger">
                            <p>No existen rutas</p>
                            <small style="color:#FFF;">Solicitar a un programador que genere las rutas de produccion</small>
                        </div>
                        @if(isAdmin())
                            <a href="{{ url("aoicollector/prod/routeop/?op=$op") }}" target="_blank" class="btn btn-xs btn-block btn-default">Crear ruta para {{ $op }}</a>
                        @endif
                    @endif
                </blockquote>

                <blockquote>
                    <h4>Seleccionar modo</h4>
                        <div class="form-group" >
                            <select class="form-control" name="id_modo" ng-model="id_modo" style="font-size:18px;">
                                <option value="0">AOI</option>
                                <option value="1">MANUAL</option>
                                <option value="3">COGISCAN->TH</option>
                            </select>
                        </div>
                </blockquote>
            </div>
        </div>
            <input ng-if="id_route_op && id_modo" class="form-control btn btn-block btn-info"  type="submit" value="Usar esta configuracion de OP">
        </form>
    @else
        <div class="callout callout-danger">
            <h4>{{ $op }}</h4>
            <p>No fue localizada en la interfaz, esta seguro que la OP es la correcta?</p>
        </div>
    @endif

@endif

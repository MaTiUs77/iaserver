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

                @if(isset($routeop) && count($routeop)>0)
                <blockquote>
                    <h4>IAServer route</h4>
                    <div class="form-group">
                        <select class="form-control" name="id_puesto">
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
                </blockquote>
                @endif
            </div>
        </div>
            <input ng-if="puesto_id" class="form-control btn btn-block btn-info"  type="submit" value="Usar esta configuracion de OP">
        </form>
    @else
        <h3>{{ $op }}</h3>
        No fue localizada en Wip_ot
    @endif

@endif

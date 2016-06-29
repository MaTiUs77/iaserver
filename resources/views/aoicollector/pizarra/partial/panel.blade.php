<div class="panel">
    <div class="panel-body">
        <div class="row">
            @if($resume->produccion->aoi->M>0 || $resume->produccion->cone->M>0)
            <div class="col-sm-12 col-md-8 col-lg-6">
                    @include('aoicollector.pizarra.partial.detalle_declaracion_por_hora',[
                        'height' => 400,
                        'title' => 'Inspecciones Turno Ma\u00f1ana',
                        'turno'=>'M'
                    ])
                    <hr>

                    @foreach($resume->byOp as $op => $item)
                        @if(count($item->periodo['M']))
                            <div class="col-lg-6">
                                <blockquote>
                                {{ $op }}
                                <small>Modelo</small>
                                {{ $item->smt->modelo }} -
                                {{ $item->smt->panel }}
                                <small>Lote</small>
                                {{ $item->smt->lote }}
                                <small>Producido</small>
                                {{$item->produccionM }}
                                </blockquote>
                            </div>
                        @endif
                    @endforeach
            </div>
            @endif

            @if($resume->produccion->aoi->T>0 || $resume->produccion->cone->T>0)
                <div class="col-sm-12 col-md-8 col-lg-6">
                    @include('aoicollector.pizarra.partial.detalle_declaracion_por_hora',[
                        'height' => 400,
                        'title' => 'Inspecciones Turno Tarde',
                        'turno'=>'T'
                    ])
                    <hr>

                    @foreach($resume->byOp as $op => $item)
                        @if(count($item->periodo['T']))
                            <div class="col-lg-6">
                                <blockquote>
                            {{ $op }}
                            <small>Modelo</small>
                            {{ $item->smt->modelo }} -
                            {{ $item->smt->panel }}
                            <small>Lote</small>
                            {{ $item->smt->lote }}
                            <small>Producido</small>
                                {{$item->produccionT }}
                        </blockquote>
                            </div>
                        @endif
                    @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

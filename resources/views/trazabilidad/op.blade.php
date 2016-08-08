@if(empty($op))
    <h3>Por favor ingrese una OP</h3>
@else
    @if($wip->wip_ot)
    <?php
        $is_memory = false;
        if(isset($smt) && starts_with($smt->panel,'MEM-'))  { $is_memory = true; }
     ?>
    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-4">
            <blockquote>
                @include('trazabilidad.widget.wipinfo',[
                'wip'=>$wip,
                'smt'=>$smt
                ])
            </blockquote>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-4">
            <blockquote>
                @include('trazabilidad.widget.resumen_transacciones',['wip'=>$wip])
            </blockquote>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-4">
            @if(isset($smt) && !$is_memory)
                <blockquote>
                    @include('trazabilidad.widget.resumen_controldeplacas',[$smt,$controldeplacas])
                </blockquote>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-{{ ($is_memory) ? '6': '12'  }}">
            <h3>Transacciones wip</h3>
            @include('trazabilidad.widget.detalle_transacciones',[
                'nro_op' => $wip->wip_ot->nro_op,
                'wip_serie' => $wip->transactions->detail->wip_serie,
                'wip_history' => $wip->transactions->detail->wip_history,
            ])
        </div>
        @if($is_memory)
            <div class="col-lg-6">
                <h3>Transacciones en memorias</h3>
                @include('memorias.widget.declare',[
                    'wip'=>$wip
               ])
            </div>
        @endif
    </div>

    @if(!$is_memory)
        @if(count($manualWipSerie)>0 || count($manualWipHistory)>0)
            <div class="row">
                <div class="col-lg-12">
                    <h3>Transacciones manuales en wip</h3>
                    @include('trazabilidad.widget.detalle_transacciones',[
                        'nro_op' => $wip->wip_ot->nro_op,
                        'manual' => true,
                        'wip_serie' => $manualWipSerie,
                        'wip_history' => $manualWipHistory,
                    ])
                </div>
            </div>
        @endif
    @endif

    @if(collect($wip->transactions->detail->wip_serie)->count()>0 || collect($wip->transactions->detail->wip_history)->count()>0)
        @include('trazabilidad.widget.detalle_declaracion_por_hora',[
            'wip_op' => $wip->wip_ot->nro_op,
            'title' => 'Declaraciones por hora',
            'period' => $wip->period,
            'navigator' => true
        ])
    @endif

    @else
    <h3>No hay registros de declaraciones para la op solicitada</h3>
@endif
    

<script>
    setTimeout('window.location.reload();', (60 * 1000) * 2);
</script>
@endif

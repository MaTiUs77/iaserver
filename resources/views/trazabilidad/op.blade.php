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

                    <small>Stockers</small>
                    <a class="btn btn-sm btn-success" ng-click="openModal('{{ route('trazabilidad.form.allprodstocker',$wip->wip_ot->nro_op) }}','Stockers en produccion','success')">Ver stockers en produccion</a>
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

    @if(isAdmin())
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Inconsistencias en declaraciones</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <?php
            $iaPluck = collect($enIa)->pluck('barcode');
            $wipPluck = collect($enWip)->pluck('barcode');
            ?>

            <div class="row">
                <div class="col-sm-6">
                    <strong><i class="fa fa-file-text-o margin-r-5"></i>No existen en Wip</strong>
                    @foreach($iaPluck as $barcode)
                        @if(!empty($barcode))
                            <ul style="list-style: none;">
                                @if(!$wipPluck->contains($barcode))
                                    <li>{{ $barcode  }}
                                        <ul style="list-style: none;">
                                            <li><small>Sin declarar</small></li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    @endforeach
                </div>
                <div class="col-sm-6">
                    <strong><i class="fa fa-file-text-o margin-r-5"></i>Declarados pero no existen en IAServer</strong>
                    @foreach($wipPluck as $barcode)
                        <ul style="list-style: none;">
                            @if(!$iaPluck->contains($barcode))
                                <li>{{ $barcode  }}
                                    <ul style="list-style: none;">
                                        <li>no hay datos de la placa</li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    @endforeach
                </div>
            </div>

        </div>
        <!-- /.box-body -->
    </div>
    @endif

    @else
    <h3>No hay registros de declaraciones para la op solicitada</h3>
@endif
    
<script>
    setTimeout('window.location.reload();', (60 * 1000) * 2);
</script>
@endif

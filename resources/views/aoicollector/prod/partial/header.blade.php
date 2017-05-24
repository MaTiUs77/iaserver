<nav class="navbar navbar-default" style="padding-bottom:5px;margin-bottom:1px;" role="navigation" ng-controller="prodHeaderController">
    <div class="navbar-form">
        <div class="navbar-left">

            <div class="btn-group">
                <button type="button" class="btn btn-primary" ng-click="promptAoiSet('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de aoi']) }}')">
                    <span ng-show="aoiService.produccion.linea">Linea <b>@{{ aoiService.produccion.linea  }}</b></span>
                    <span ng-hide="aoiService.produccion.linea">Seleccionar linea</span>
                </button>
                @if(isAdmin())
                    <button type="button" class="btn btn-primary active dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" style="height: 350px;width:300px; overflow: auto;">
                        @foreach(\IAServer\Http\Controllers\Aoicollector\Model\Produccion::allBarcode() as $produccion)
                            <li>
                                <a href="javascript:;" ng-click="restartAoiData('{{ $produccion->barcode }}')">{{ $produccion->linea }}
                                    <span class="pull-right badge bg-blue">{{ $produccion->barcode }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <a href="#" class="btn bg-purple" ng-show="inspectorService.id">
               Inspector:  <b>@{{ inspectorService.fullname }}</b>
            </a>

            <a href="#" class="btn bg-navy" ng-show="inspectorService.id" data-toggle="modal" data-target="#modalInspeccionVisual" data-op="@{{ aoiService.produccion.barcode }}">
                Inspeccion visual
            </a>

            <div class="btn-group">
                <div class="btn-group" ng-show="aoiService.produccion.op && inspectorService.id" >
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        Stocker <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="javascript:;" ng-click="promptStockerSet('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de stocker']) }}')"><span class="glyphicon glyphicon-th-large"></span> Asignar <small style="color:#6b6b6b;">(F3)</small></a>
                        </li>
                        <li>
                            <a href="javascript:;" ng-click="promptStockerReset('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de stocker']) }}')"><span class="glyphicon glyphicon-refresh"></span> Liberar</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:;" ng-click="promptStockerAddPanel('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de panel']) }}')"><span class="glyphicon glyphicon-download-alt"></span> Relacionar panel <small style="color:#6b6b6b;">(F4)</small></a>
                        </li>
                        <li>
                            <a href="javascript:;" ng-click="promptStockerRemovePanel('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de panel']) }}')"><span class="glyphicon glyphicon-remove"></span> Remover panel <small style="color:#6b6b6b;">(F9)</small></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="navbar-left" ng-show="inspectorService.id">
            <table><tbody><tr>
                    <td>
                        <h4> &nbsp;&nbsp; Declaracion: </h4>
                    </td>
                    <td>
                        <input type="text" class="form-control" placeholder="Ingrese OP" ng-model="$parent.choosedOp" ng-enter="btnFormSelectOp('{{ url('aoicollector/prod/infoop') }}/'+$parent.choosedOp+'/'+aoiService.produccion.barcode)" />
                    </td>
                    <td style="padding-left:10px;">
                        <div ng-hide="btnFormSelectOpProccessing">
                            <button ng-show="$parent.choosedOp" ng-click="btnFormSelectOp('{{ url('aoicollector/prod/infoop') }}/'+$parent.choosedOp+'/'+aoiService.produccion.barcode)" type="button" class="btn btn-success animate-show">
                                Informacion de OP
                            </button>
                        </div>
                        <div ng-show="btnFormSelectOpProccessing">
                            <button ng-show="$parent.choosedOp"  type="button" class="btn btn-default animate-show">
                                @include('aoicollector.inspection.partial.loader_mini',[
                                    'style'=>'height:20px'
                                ])
                            </button>
                        </div>
                    </td>
                </tr></tbody></table>
        </div>

        <div class="navbar-right">
            <a ng-show="aoiService.produccion.op && inspectorService.id" ng-href="{{ url('aoicollector/prod/removeop') }}/@{{ aoiService.produccion.barcode }}" class="btn btn-danger">Remover op</a>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="modalInspeccionVisual" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Agregar inspeccion visual</h4>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-6">
                        <input class="form-control" name="panelbarcode" placeholder="Codigo de placa" ng-model="visualPanelBarcode"/>
                    </div>

                    <div class="col-xs-6">
                        <select class="form-control select2" style="width: 100%;" ng-show="visualPanelBarcode">
                            <option>- Seleccionar posicion -</option>
                            <?php
                                $posiciones =  \IAServer\Http\Controllers\SMTDatabase\Model\Lotes::select('posicion')
                                        ->where('id_ingenieria',1661390)
                                        ->where('logop','MAI')
                                        ->get();
                            ?>
                            @foreach($posiciones as $item)
                                <option value="{{ $item->posicion }}">{{ $item->posicion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Agregar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#modalInspeccionVisual').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
//        var recipient = button.data('op'); // Extract info from data-* attributes
        var modal = $(this);
//        modal.find('.modal-body input').val(recipient);

    })
</script>

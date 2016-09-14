<nav class="navbar navbar-default" style="padding-bottom:5px;margin-bottom:1px;" role="navigation" ng-controller="prodHeaderController">
    <div class="navbar-form">
        <div class="navbar-left">

            <a class="btn btn-primary" ng-click="promptAoiSet('{{ route('iaserver.forms.prompt',['holder'=>'Ingresar codigo de aoi']) }}')">
                <span ng-show="aoiService.produccion.linea">Linea <b>@{{ aoiService.produccion.linea  }}</b></span>
                <span ng-hide="aoiService.produccion.linea">Seleccionar linea</span>
            </a>

            <!--
            <a href="" class="btn btn-default" ng-show="userService.id">
               Inspector:  <b>@{{ userService.fullname }}</b>
            </a>
            !-->

            <div class="btn-group">
                <div class="btn-group" ng-show="aoiService.produccion.op" >
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

        <div class="navbar-left">
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
            <a ng-show="aoiService.produccion.op" ng-href="{{ url('aoicollector/prod/removeop') }}/@{{ aoiService.produccion.barcode }}" class="btn btn-danger">Remover op</a>
        </div>
    </div>
</nav>

{!! IAScript('assets/moment.min.js') !!}

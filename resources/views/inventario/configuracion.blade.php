@extends('inventario.index')
@section('ng','printerRecords')
@section('body')
    @if(isAdmin())
    <h2>Impresoras disponibles</h2>
    <div  ng-controller="printerController">

        <!-- Table-to-load-the-data Part -->
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Ip Impresora</th>
                <th>Tipo impresora</th>
                <th>Tono de impresion</th>
                <th>Velocidad de impresion</th>
                <th><button id="btn-add" class="btn btn-primary btn-large" ng-click="toggle('add', 0)">Agregar Nueva Impresora</button></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat = "printer in printers">
                <td>@{{  printer.id_printer_config }}</td>
                <td>@{{ printer.printer_address }}</td>
                <td>@{{ printer.id_printer_type }}</td>
                <td>@{{ printer.setdarkness }}</td>
                <td>@{{ printer.velocidad_impresion }}</td>
                <td>

                    <button class="btn btn-default btn-large btn-detail" ng-click="toggle('edit', printer.id_printer_config)">Editar</button>
                    <button class="btn btn-danger btn-large btn-delete" ng-click="confirmDelete(printer.id_printer_config)">Borrar</button>

                </td>
            </tr>
            </tbody>
        </table>
        <!-- End of Table-to-load-the-data Part -->
        <!-- Modal (Pop up when detail button clicked) -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">@{{form_title}}</h4>
                    </div>
                    <div class="modal-body">
                        <form name="frmPrinter" class="form-horizontal" novalidate="">

                            <div class="form-group error">
                                <label for="inputEmail3" class="col-sm-3 control-label">Ip Impresora</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="printer_address" name="printer_address" placeholder="ip de la impresora" value="@{{printer_address}}"
                                           ng-model="printer.printer_address" ng-required="true">
                                        <span class="help-inline"
                                              ng-show="frmPrinter.printer_address.$invalid && frmPrinter.printer_address.$touched">La ip es requerida</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Tipo de Impresora</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="id_printer_type" name="id_printer_type" placeholder="Tipo de impresora"
                                           ng-model="printer.id_printer_type" ng-required="true">
                                        <option ng-repeat = "tipo in ['200dpi','300dpi','600dpi','zm400-200','300dpi-com','zm400-600','203dpi']" value="@{{ tipo }}">@{{ tipo }}</option>
                                        </select>
                                        <span class="help-inline"
                                              ng-show="frmPrinter.id_printer_type.$invalid && frmPrinter.id_printer_type.$touched">El tipo de impresora es requerido</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Tono de Impresion</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="setdarkness" name="setdarkness" placeholder="Tono de impresion"
                                           ng-model="printer.setdarkness" ng-required="true" min="1" max="30" >

                                    <span class="help-inline"
                                          ng-show="frmPrinter.setdarkness.$invalid && frmPrinter.setdarkness.$touched">El tono es min. 1 y max. 30</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Velocidad de Impresion</label>
                                <div class="col-sm-9">
                                    <select type="text" class="form-control" id="velocidad_impresion" name="velocidad_impresion" placeholder="velocidad_impresion" value="@{{velocidad_impresion}}"
                                           ng-model="printer.velocidad_impresion" ng-required="true">
                                        <option ng-repeat = "v in ['2,2','3,3','4,4','5,5']">@{{ v }}</option>
                                        </select>
                                    <span class="help-inline"
                                          ng-show="frmPrinter.velocidad_impresion.$invalid && frmPrinter.velocidad_impresion.$touched">La velocidad de impresion es requerida</span>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, id)" ng-disabled="frmPrinter.$invalid">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="container-fluid">
            <div class="callout callout-warning" style="border-radius: 0;margin:0;">
                <p><b>ATENCION!!!</b> usted debe <strong>Iniciar Sesion</strong></p>
            </div>
        </div>
        @endif
    <!-- AngularJS Application Scripts -->
    {!! IAScript('vendor/printerconfig/app.js') !!}
    {!! IAScript('vendor/printerconfig/printers.js') !!}

@endsection


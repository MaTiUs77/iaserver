@extends('inventario.index')
@section('ng','app')
@section('body')
    <h2>Usuarios</h2>
    <div  ng-controller="usersController">

        <!-- Table-to-load-the-data Part -->
        <table class="table">
            <thead>
            <tr >
                <th class="text-center">ID</th>
                <th class="text-center">Usuario</th>
                <th class="text-center">Descripcion</th>
                <th class="text-center">Sector</th>
                <th class="text-center">Planta</th>
                <th class="text-center">Impresora</th>
                <th class="text-center">ROL</th>
                <th class="text-center"><button id="btn-add" class="btn btn-primary btn-large" ng-click="toggle('add', 0)"><span class="fa  fa-user-plus"></span> Agregar Usuario</button></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat = "user in users">
                <td class="text-center">@{{  user.perfil.user_id }}</td>
                <td class="text-center">@{{  user.perfil.username }}</td>
                <td class="text-center">@{{  user.perfil.nombre }} @{{user.perfil.apellido}}</td>
                <td class="text-center">@{{  user.config_user.sector }}</td>
                <td class="text-center">@{{  user.config_user.planta }}</td>
                <td class="text-center">@{{  user.config_user.impresora.printer_address }}</td>
                <td class="text-center">
                    <div ng-repeat="rol in user.perfil.rol" class="btn-group">
                        <label type="button" class="btn btn-@{{rol.class}} btn-xs">@{{ rol.name }}</label>
                    </div>
                </td>
                <td class="text-center">

                    <button class="btn btn-default btn-xs btn-detail" ng-click="toggle('edit', user.perfil.user_id)"><span class="fa fa-edit"></span> Editar</button>
                    {{--<button class="btn btn-danger btn-xs btn-delete" ng-click="confirmDelete(user.perfil.user_id)">Borrar</button>--}}

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
                        <form name="frmUser" class="form-horizontal" novalidate="">

                            <div class="form-group" ng-show="newUser">
                                <label for="inputEmail3" class="col-sm-3 control-label">Usuarios IAServer</label>
                                <div class="col-sm-9">
                                    <select type="text" class="form-control has-error" id="user_name" name="user_name" placeholder="Usuarios"
                                            ng-model="editUser.perfil.user_id" ng-change="getProfileData()">
                                        <option ng-repeat = "user in usersFromIAServer" value="@{{user.id}}" >@{{ user.name }}</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group" ng-hide="newUser">
                                <label for="inputEmail3" class="col-sm-3 control-label">Usuario</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="user_name" name="user_name" placeholder="Nombre de Usuario" value="@{{editUser.perfil.username}}"
                                           ng-model="editUser.perfil.username" ng-required="@{{isRequired}}">
                                    <input type="hidden" name="id"  value="@{{editUser.perfil.user_id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Nombre</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="user_nombre" name="user_nombre" placeholder="Nombre" value="@{{  editUser.perfil.nombre }}"
                                           ng-model="editUser.perfil.nombre" ng-required="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Apellido</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control has-error" id="user_apellido" name="user_apellido" placeholder="Apellido" value="@{{  editUser.perfil.apellido }}"
                                           ng-model="editUser.perfil.apellido" ng-required="true">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Planta</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="planta" name="planta" placeholder="Planta"
                                           ng-model="editUser.config_user.id_planta" ng-required="true">
                                        <option ng-repeat = "plant in plants" ng-selected="plant.id_planta == editUser.config_user.id_planta" value="@{{ plant.id_planta }}">@{{ plant.id_planta }} - @{{plant.descripcion}}</option>
                                        </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Sector</label>
                                <div class="col-sm-9">
                                    <select type="text" class="form-control" id="sector" name="sector" placeholder="Sector"
                                           ng-model="editUser.config_user.id_sector" ng-required="true">
                                        <option ng-repeat = "sector in sectors" ng-selected="sector.id_sector == editUser.config_user.id_sector" value="@{{sector.id_sector}}">@{{ sector.id_sector }} - @{{ sector.descripcion }}</option>
                                        </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Impresora</label>
                                <div class="col-sm-9">
                                    <select type="text" class="form-control" id="impresora" name="impresora" placeholder="Impresora"
                                            ng-model="editUser.config_user.impresora.id_impresora" ng-required="true">
                                        <option ng-repeat = "printer in printers" ng-selected="printer.id_printer_config == editUser.config_user.impresora.id_impresora" value="@{{printer.id_printer_config}}">@{{ printer.id_printer_config }} - @{{ printer.printer_address }}</option>
                                    </select>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, id)" ng-disabled="frmUser.$invalid">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AngularJS Application Scripts -->
    {!! IAScript('vendor/iaserver/iaserver.js') !!}
    {!! IAScript('vendor/inventario/usuarios/user.factory.js') !!}
    {!! IAScript('vendor/inventario/usuarios/usuarios.controller.js') !!}

@endsection


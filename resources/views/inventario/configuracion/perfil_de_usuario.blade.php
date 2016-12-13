@extends('inventario.index')
@section('ng')
@section('body')
    <h2>Perfil de Usuario <small>(Inventario)</small></h2>
    <div  ng-controller="profileController">

        <div class="container">
            <div class="pull-left">
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

                    <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, id)" ng-disabled="frmUser.$invalid">Guardar</button>
            </div>
        </div>
        </div>

    </div>

    <!-- AngularJS Application Scripts -->
    {!! IAScript('vendor/iaserver/iaserver.js') !!}
    {!! IAScript('vendor/inventario/usuarios/user.factory.js') !!}
    {!! IAScript('vendor/inventario/usuarios/profile.controller.js') !!}

@endsection


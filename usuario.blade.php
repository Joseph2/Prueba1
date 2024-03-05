@extends('main')

@section('title')
    Usuarios
@endsection

@section('bodycontroller')
    id='usuarioController' ng-controller='usuarioController'
@endsection

@section('content')

    <div class="row" ng-show="estado_registro == 0">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title"><i class="fa fa-align-justify"></i> Lista de Usuarios</div>
                        <div class="card-tools">
                            <a class="btn btn-info btn-border btn-round btn-sm mr-2" href="javascript:void(0)" ng-click="nuevoRegistro()">
                             <span class="btn-label">
                                <i class="fa fa-plus"></i>
                             </span>
                                Agregar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-0">
                </div>
                <div class="card-footer pb-0"  no-padding>
                    <div class="table-responsive" style="padding-top: 10px !important;">
                        <table datatable="ng" dt-options="elementos.dtOptions" dt-instance="dtInstance" class="table table-hover table-color-red table-bordered">
                            <thead>
                            <th>N°</th>
                            <th>DNI</th>
                            <th>NOMBRES</th>
                            <th>NIVEL</th>
                            <th>PROFESION</th>
                            <th>USUARIO</th>
                            <th>ACCESO</th>
                            <th>ACCIONES</th>
                            </thead>
                            <tbody>
                            <tr ng-repeat="item in lista">
                                <td>@{{ ($index +1)  }}</td>
                                <td>@{{ item.nro_doc }}</td>
                                <td>@{{ item.nombres }} @{{ item.paterno }} @{{ item.materno }}</td>
                                <td>@{{ item.nom_nivel }}</td>
                                <td>@{{ item.nom_profesion }}</td>
                                <td>@{{ item.nick }}</td>
                                <td>
                                    <center>
                                        <label ng-class="{'label label-success': item.acceso == 1, 'label label-danger': item.acceso!=1}">@{{ (item.acceso==1?'SI':'NO') }}</label>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info tip" title="Click para Editar" ng-click="prepararEditar(item)" ><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-warning tip" title="Click Quitar y Agregar Acceso" data-toggle="modal" data-target="#cambiarAccesoModal"  ng-click="prepararQuitarAccesso(item)" ><i class="fas fa-low-vision"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger tip" title="Click para Eliminar" ng-click="prepararEliminar(item)" ><i class="fas fa-times"></i></button>
                                        </div>
                                    </center>
                                </td>
                                <script>$('.tip').tooltip();</script>
                            </tr>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row" ng-show="estado_registro == 1">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title"><i class="fa fa-users"></i> Datos del Usuario</div>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-3">
                            <div c  lass="form-group">
                                <form name="Myform">
                                 <label>Tipo Documento: <span class="text-danger">(*)</span></label>
                                  <select class="form-control" style="width: 100% !important;" id="cmbTipoDocumento" ng-model="registro.id_tipo_documento" name="id_tipo_documento" required >
                                    <option value="">---</option>
                                    <option value="1">DNI</option>
                                    <option value="2">CE</option>
                                    <option value="3">PASS</option>
                                    <option value="4">DIE</option>
                                  </select>
                                  <div class="col-lg-12">
                                      <span ng-show="Myform.id_tipo_documento.$untouched && Myform.id_tipo_documento.$invalid" class="text-danger" >Este campo es obligatorio</span>
                                    </div>
                                </form> 
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                              <form name="Myform">
                                <label for="" >Numero de Documento: <span class="text-danger" >(*)</span></label>
                                <div class="input-group ">
                                    <input type="text" class="form-control" id="nro_doc_txt" ng-model="registro.nro_doc" name="nro_doc" required>
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-sm btn-primary" ng-click="buscarDocumento()" ng-disabled="estado_busqueda == 1"><i ng-class="{'fas fa-search': estado_busqueda == 0, 'spinner-border spinner-border-sm mr-2': estado_busqueda == 1}"></i></button>
                                     </div>
                                    <div class="col-lg-12">
                                      <span ng-show="Myform.nro_doc.$touched && Myform.nro_doc.$invalid" class="text-danger" >Este campo es obligatorio</span>
                                    </div>
                                </div>
                              </form>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Apellido Paterno:</label>
                                <input type="text" class="form-control" ng-model="registro.paterno">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Apellido Materno:</label>
                                <input type="text" class="form-control" ng-model="registro.materno">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Nombres:</label>
                                <input type="text" class="form-control" ng-model="registro.nombres">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Tipo Profesión: <span class="text-danger">(*)</span></label>
                                <select class="form-control" style="width: 100% !important;" id="cmbTipoProfesion" ng-model="registro.id_profesion" name="id_profesion" required >
                                    <option value="">---</option>
                                    <option ng-repeat="item in lista_profesiones" value="@{{ item.id_profesion }}">@{{ item.descripcion }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="">Telefono:</label>
                                <input type="text" class="form-control" ng-model="registro.telefono">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Correo:</label>
                                <input type="text" class="form-control" ng-model="registro.email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-check">
                                <label>Acceso</label>
                                <br>
                                <label class="form-radio-label">
                                    <input class="form-radio-input" type="radio" value="1" name="accesoRadios" ng-model="registro.acceso" ng-change="nuevoAcceso()">
                                    <span class="form-radio-sign">SI</span>
                                </label>
                                <label class="form-radio-label ml-3">
                                    <input class="form-radio-input" type="radio" value="0" name="accesoRadios"  ng-model="registro.acceso" ng-change="nuevoAcceso()">
                                    <span class="form-radio-sign">NO</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-3" ng-show="registro.acceso == 1">
                            <div class="form-group">
                                <label>Usuario: <span class="text-danger" >(*)</span></label>
                                <input type="text" class="form-control form-control-sm" ng-model="registro.nick" id="User" name="nick_usuario" ng-required="registro.acceso == 1" />
                            </div>
                        </div>
                        <div class="col-lg-3" ng-show="registro.acceso == 1">
                            <div class="form-group">
                                <label>Contraseña: <span class="text-danger">(*)</span></label>
                                <input type="password" class="form-control form-control-sm" ng-model="registro.password" id= "Password" name="password_usuario" ng-required="registro.acceso == 1" />
                            </div>
                        </div>
                        <div class="col-lg-3" ng-show="registro.acceso == 1">
                            <div class="form-group">
                                <label>Nivel: <span class="text-danger">(*)</span></label>
                                <select class="form-control" style="width: 100% !important;" id="cmbNivel_usuario" ng-model="registro.idnivel" name="idnivel_usuario" ng-required="registro.acceso == 1" ng-change="cambiarNivel()">
                                    <option value="" >---</option>
                                    <option value="1">ADMINISTRADOR</option>
                                    <option value="2">DIRESA</option>
                                    <option value="3">RED</option>
                                    <option value="4">CONSULTOR</option>
                                    <option value="5">INDICADOR Y REPORTES</option>
                                    <option value="6">MUNICIPIO</option>
                                    <option value="7">INVITADO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4" ng-show="registro.acceso == 1 && (registro.idnivel == 3 || registro.idnivel == 4 || registro.idnivel == 5 || registro.idnivel == 6)">
                            <div class="form-group">
                                <label>Red: <span class="text-danger">(*)</span></label>
                                <select class="form-control form-control-sm" id="cmbRed_usuario" style="width: 100% !important;" name="red_usuario" ng-change="listarMicroRedByRed()" ng-model="registro.codigo_red" ng-required="registro.idnivel == 3" >
                                    <option value="" >---</option>
                                    <option value="AGUAYTIA">AGUAYTIA</option>
                                    <option value="ATALAYA">ATALAYA</option>
                                    <option value="CORONEL  PORTILLO">CORONEL  PORTILLO</option>
                                    <option value="FEDERICO BASADRE - YARINACOCHA">FEDERICO BASADRE - YARINACOCHA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4" ng-show="registro.acceso == 1 && (registro.idnivel == 4 || registro.idnivel == 5 || registro.idnivel == 6)">
                            <div class="form-group">
                                <label>Micro Red: <span class="text-danger">(*)</span></label>
                                <select class="form-control form-control-sm" id="cmbMicroRed_usuario" name="microred_usuario" style="width: 100% !important;" ng-change="listarEstablecimientosByNivel()" ng-model="registro.codigo_microred" ng-required="registro.idnivel == 4">
                                    <option value="" >---</option>
                                    <option ng-repeat="item in lista_microredes" value="@{{ item.codigo_microred }}">@{{ item.nom_microred }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4" ng-show="registro.acceso == 1 && (registro.idnivel == 5 || registro.idnivel == 6 || registro.idnivel == 7)" >
                            <div class="form-group">
                                <label>Establecimiento: <span class="text-danger">(*)</span></label>
                                <select class="form-control form-control-sm" id="   " name="cod_2000_usuario" style="width: 100% !important;" ng-model="registro.cod_2000" ng-required="registro.idnivel == 5 || registro.idnivel == 6 || registro.idnivel == 7">
                                    <option value="">---</option>
                                    <option ng-repeat="item in lista_eess" value="@{{ item.codigo_unico }}">@{{ item.codigo_unico }} - @{{ item.nombre_establecimiento }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-success " ng-click="guardar()" ng-disabled="estado_procesar == 1"><i ng-class="{'fas fa-save': estado_procesar == 0, 'spinner-border spinner-border-sm mr-2': estado_procesar == 1}"></i> @{{ estado_procesar!=1?'Guardar':'Guardando, Espere...' }}</button>
                    <button type="button" class="btn btn-danger " ng-click="salir()"><i class="fas fa-times"></i> Salir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cambiarAccesoModal"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header no-bd">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold"> Agregar y/o Quitar </span>
                        <span class="fw-light"> Acceso</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-check">
                                    <label>Acceso</label>
                                    <br>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" value="1" name="acceso1Radios" ng-model="registro.acceso" >
                                        <span class="form-radio-sign">SI</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" value="0" name="acceso1Radios"  ng-model="registro.acceso" >
                                        <span class="form-radio-sign">NO</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer no-bd">
                    <button type="button" ng-click="guardarAcceso()" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Salir</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascripts')
    @parent
    <script src="js/angular/controller/configuracion/usuarioController.js"></script>
    <script>
        $(function () {
            $('select').select2();


        });
    </script>
@endsection

/**
 * Created by carlo on 1/09/2018.
 */

app.controller('usuarioController', function ($scope,$timeout,DTOptionsBuilder, accesorioService, personaService){
    $scope.dtInstance = {};
    $scope.elementos = {lista:[]};

    $scope.anios = [];
    $scope.meses = [];

    $scope.estado_registro = 0;
    $scope.estado_editar   = 0;
    $scope.estado_busqueda = 0;
    $scope.estado_procesar = 0;
    $scope.lista_profesiones = [];
    $scope.registro = {};

    $scope.nuevoRegistro = function () {
        $scope.registro = {};
        $scope.registro.acceso = 0;
        $timeout(function () {
            $("#cmbTipoDocumento").val(1).change();
            $("#cmbTipoProfesion").val('').change();
            $("#cmbNivel_usuario").val('').change();
        })
        $scope.estado_registro = 1;
        $scope.estado_editar    =0;
    }


    $scope.salir = function () {
        $timeout(function () {
            $scope.estado_registro = 0;
            $scope.registro = {};
        }, 0);
    }


    accesorioService.getListarProfesion({}).success(function (data) {
        $scope.lista_profesiones = data;
    });

    $scope.buscarDocumento = function () {
        if ($("#cmbTipoDocumento").val() != ""){
            if ($("#nro_doc_txt").val() != ""){
                $scope.estado_busqueda = 1;
                personaService.buscarPersonaUsuarioDocumento({nro_doc: $("#nro_doc_txt").val(), id_tipo_documento: $("#cmbTipoDocumento").val()}).success(function (data) {
                    $timeout(function () {
                        $scope.estado_busqueda = 0;
                    }, 0);
                    if (data.data[0] != undefined){
                        $scope.registro = data.data[0];
                        $timeout(function () {
                            $("#cmbNivel_usuario").val($scope.registro.idnivel!=null?$scope.registro.idnivel:'').change();
                            $("#cmbTipoProfesion").val($scope.registro.id_profesion!=null?$scope.registro.id_profesion:'').change();
                            $("#cmbTipoDocumento").val($scope.registro.idtipo_documento!=null?$scope.registro.idtipo_documento:'').change();
                        });
                        $scope.registro.password = $scope.registro.refer;
                    }else {
                        $timeout(function () {
                            $("#cmbNivel_usuario").val('').change();
                            $("#cmbTipoProfesion").val('').change();
                        }, 0);
                        $scope.registro.paterno=null;
                        $scope.registro.materno=null;
                        $scope.registro.nombres=null;
                        $scope.registro.telefono=null;
                        $scope.registro.email=null;
                        $scope.registro.acceso=0;
                        $scope.registro.nick=null;
                        $scope.registro.password=null;
                    }
                })
            }
        }

    }

    $scope.listarMicroRedByRed = function(){
        console.log("obtener microredes");
        if ($("#cmbRed_usuario").val() !== "") {
            accesorioService.getMicroRedByRed({codigo_disa:"34", nom_red: $("#cmbRed_usuario").val()}).success(function(data){
                $scope.lista_microredes=data;
            })
        }else{ 
            $scope.lista_microredes=[];
            $scope.lista_eess=[];
        }
    }
    $scope.listarEstablecimientosByNivel = function(){
        if ($("#cmbMicroRed_usuario").val() !== "" && $("#cmbRed_usuario").val() !== "") {
            accesorioService.getEstablecimientoByMicrored({codigo_disa:"34", nom_red: $("#cmbRed_usuario").val(), codigo_microred: $("#cmbMicroRed_usuario").val()}).success(function(data){
                $scope.lista_eess=data;
            })
        }else{ 
            $scope.lista_eess=[];
        }
    }
    $scope.generarAnio = function () {
        var fecha = new Date();
        var anio = fecha.getFullYear();
        for(var i=anio; i>=2019; i--){
            $scope.anios.push({anio:i});
        }
    }


    $scope.generarMeses = function () {
        $scope.meses.push({mes:1, nom_mes: 'ENERO'});
        $scope.meses.push({mes:2, nom_mes: 'FEBRERO'});
        $scope.meses.push({mes:3, nom_mes: 'MARZO'});
        $scope.meses.push({mes:4, nom_mes: 'ABRIL'});
        $scope.meses.push({mes:5, nom_mes: 'MAYO'});
        $scope.meses.push({mes:6, nom_mes: 'JUNIO'});
        $scope.meses.push({mes:7, nom_mes: 'JULIO'});
        $scope.meses.push({mes:8, nom_mes: 'AGOSTO'});
        $scope.meses.push({mes:9, nom_mes: 'SETIEMBRE'});
        $scope.meses.push({mes:10, nom_mes: 'OCTUBRE'});
        $scope.meses.push({mes:11, nom_mes: 'NOVIEMBRE'});
        $scope.meses.push({mes:12, nom_mes: 'DICIEMBRE'});
    }

    $scope.guardar = function (item) {
          personaService.guardarUsuario($scope.registro).success(function (data) {

            if ($("#nro_doc_txt").val()!= ""&& $("#Password").val()!= "" && $("#User").val()!= "" && $("#cmbTipoDocumento").val()!= "" && $("#cmbTipoProfesion").val()!= "" && $("#cmbNivel_usuario").val()!= "" && $("#cmbRed_usuario").val()!= "" && $("#cmbMicroRed_usuario").val()!= "" && $("#cmbEstablecimiento_usuario").val()!= "" && data.confirm == true  ){
                swal("Exito!", "Se completo correctamente la operación!", { icon : "success", buttons: {  confirm: {  className : 'btn btn-success' } }, });
                $scope.salir();
                $scope.listar();
            }else {

                swal("Error!", "No se completo con el registro!", { icon : "warning",  buttons: {  confirm: { className : 'btn btn-danger' }  }, }) ;
            }
        })
    }

    $scope.listar = function () {
        console.log();
        personaService.listarUsuarios({}).success(function (data) {
            $scope.lista = data;
        })
    }

    $scope.prepararEliminar = function (item) {
        swal({
            title: 'Desea Eliminar?',
            text: "Se eliminará el usuario " + item.nombres,
            icon : "warning",
            type: 'warning',
            buttons:{
                confirm: {
                    text : 'Eliminar',
                    className : 'btn btn-warning'
                },cancel: {
                    visible: true,
                    text : 'Cancelar',
                    className: 'btn btn-danger'
                }
            }
        }).then(function(willDelete) {
            if (willDelete) {
                //registramos los datos
                personaService.eliminarUsuario({id: item.id}).success(function (data) {
                    if (data.confirm == true){
                        $scope.listar();
                        swal("Exito!", "" + data.message, {
                            icon : "success",
                            buttons: {
                                confirm: {
                                    className : 'btn btn-success'
                                }
                            },
                        });
                    }
                });
            } else {
                //
            }
        });
    }

    $scope.prepararQuitarAccesso = function (item) {
        $scope.registro = {};
        $scope.registro = item;
    }

    $scope.guardarAcceso = function () {
        personaService.accesoUsuario($scope.registro).success(function (data) {
            if (data.confirm == true){
                $scope.listar();
                swal("Exito!", "Se completo con acción correctamente!", {
                    icon : "success",
                    buttons: {
                        confirm: {
                            className : 'btn btn-success'
                        }
                    },
                });
                $("#cambiarAccesoModal").modal('hide');
            }else {
                swal("Error!", "No se pudo completar la acción!", { icon : "warning",  buttons: {  confirm: { className : 'btn btn-danger' }  }, });
            }
        })
    }

    $scope.prepararEditar = function (item) {
        console.log(item);
        //let codigo_microred = item.codigo_microred;
        $scope.estado_editar =1;
        $scope.registro = item;
        //console.log("microred ", codigo_microred);
        $scope.registro.password = $scope.registro.refer;
        $timeout(function () {
            
            $("#cmbNivel_usuario").val(item.idnivel).change();
            $("#cmbTipoProfesion").val(item.id_profesion).change();
            $("#cmbTipoDocumento").val(item.id_tipo_documento?item.id_tipo_documento:"").change();
            
        }, 100);
            
        $timeout(function(){
            $("#cmbRed_usuario").val(item.codigo_red?item.codigo_red:"").change();

             $timeout(function(){
                $("#cmbMicroRed_usuario").val(item.codigo_microred?item.codigo_microred:"").change();
                    $timeout(function(){
                    $scope.estado_editar =0;
                    },1000);
                $("#cmbEstablecimiento_usuario").val(item.cod_2000?item.cod_2000:"").change();
                 $scope.estado_registro = 1;
                 },100)
                
        }, 100);
        
    }

    $scope.cambiarNivel = function(){
        if($scope.estado_editar  !== 1 ){
            $timeout(function(){
                $("#cmbRed_usuario").val("").change();
                $("#cmbMicroRed_usuario").val("").change();
                $("#cmbEstablecimiento_usuario").val("").change();
            },0 ); 
        }
        
    }

    $scope.elementos.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withLanguage({
        "sEmptyTable": "No hay Datos Disponibles",
        "sInfo": "Mostrando _START_ hasta _END_ de _TOTAL_ Filas",
        "sInfoEmpty": "Viendo 0 hasta 0 de 0 filas",
        "sInfoFiltered": "(filtrado de _MAX_ Filas)",
        "sInfoPostFix": "",
        "sInfoThousands": ",",
        "sLengthMenu": "Ver _MENU_ Filas",
        "sLoadingRecords": "Cargando...",
        "sProcessing": "Procesando...",
        "sSearch": "Buscar:",
        "sZeroRecords": "No se encontraron registros",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Ultimo",
            "sNext": ">>",
            "sPrevious": "<<"
        },
        "oAria": {
            "sSortAscending": ": activado para ordenar columna ascendente",
            "sSortDescending": ": activado para ordenar columna descendente"
        }
    }).withOption('order', [0, 'asc'])
        .withOption('lengthMenu',[[15,25,50],[15,25,50]])
        .withOption('processing', true);

    $scope.redrawDT = function(){
        $scope.$emit('event:dataTableLoaded');
    }

    $scope.$on("event:dataTableLoaded", function(event, loadedDT) {
        $scope.dtInstance.DataTable.draw();
    });

    $scope.generarAnio();
    $scope.generarMeses();
    $scope.listar();
});

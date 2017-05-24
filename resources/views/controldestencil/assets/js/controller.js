app.controller("lavadoController",["$scope","$http","Factory","toasty","DTOptionsBuilder",
    function($scope,$http,Factory,toasty,DTOptionsBuilder){
        $scope.codDisabled = false;
        $scope._lineaHistory = null;

        // DataTables configurable options


        $scope.dtOptions = DTOptionsBuilder.newOptions()
            .withOption('order', [2,'desc'])
            .withLanguage({
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "No se ha encontrado ningún dato",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                    "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
            });


        $(document).ready(function(){
            $('#selectLinea').select2().select2("val","NULL");
            $('#selectLineaHistory').select2().select2("val","NULL");
            $scope.getHistory();
            $('input').each(function(){
                var self = $(this),
                    label = self.next(),
                    label_text = label.text();
                label.remove();
                self.iCheck({
                    checkboxClass: 'icheckbox_line-blue',
                    radioClass: 'iradio_line',
                    insert: '<div class="icheck_line-icon"></div>' + label_text
                });
            });
            $('input').on('ifUnchecked',function(event){
                $("#_codigo").attr('disabled',true);
            });
            $('input').on('ifChecked',function(event){
                $("#_codigo").attr('disabled',false);
                $scope._barcode = "";
            });



        });

         $scope.getHistory = function(){
            Factory.getHistory().then(function(response){
                $scope.history = response.data;
            });
        };

        $scope.registrar = function(){
            if($scope._linea != 'NULL') {
                if($("#_codigo").attr('disabled') == 'disabled'){
                    $scope._barcode = "SIN_CODIGO";
                    Factory.insRecord($scope._linea,$scope._barcode).then(function(response){
                        toasty.success({
                            title: 'Registro de Lavado Exitoso!'
                        });
                        $scope._codigo ="";
                        $scope.getHistory();
                    });
                }
                else{
                    if(angular.isDefined($scope._codigo)){
                        $scope._barcode = $scope._codigo;
                        if($scope._barcode.match('^[0-9]{10}$')){
                            Factory.insRecord($scope._linea,$scope._barcode).then(function(response){
                                toasty.success({
                                    title: 'Registro de Lavado Exitoso!'
                                });
                                $scope._codigo ="";
                                $scope.getHistory();
                            });
                        }
                        else{
                            toasty.error({
                                title: 'Error!!',
                                msg: 'El Código de Panel no cumple con los requisitos de la expresión regular (10 Digitos)'
                            });
                        }

                    }
                    else{
                        console.log("Debe Ingresar un código de referencia para el Panel");
                        toasty.error({
                            title: 'Error!',
                            msg: 'Debe Ingresar un código de referencia para el Panel'
                        });
                    }
                }
            }
            else{
                toasty.error({
                    title: 'Error!',
                    msg: 'Debe Seleccionar una Línea'
                });
            }

        };

        $scope.filtrar= function(e){
            var fecha = $(e.target).serialize();
            Factory.filtrar(fecha,$scope._lineaHistory).then(function(response){
                $scope.history ="";
                $scope.history = response.data;
            });
        };

        $scope.validar = function(e){
            if (e.which === 13){
                $scope.registrar();
            }
        };
    }
]);
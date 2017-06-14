app.controller('mainController', function ($scope, $route, $timeout, loginService) {
	$scope.$route = $route;

	jQuery(function($) {

    // cerrar sesion
    $scope.salir = function() {
        loginService.salir();
    } 
    // fin
        


	// funcion total proformas
    function proformas() {
        $.ajax({
            type: "POST",
            url: "data/inicio/app.php",
            data: {cargar_proformas:'cargar_proformas'},
            dataType: 'json',
            async: false,
            success: function(data) {
            	if (data.total_proforma == null) {
            		$scope.proformas = '0.00';
            	} else {
            		$scope.proformas = parseFloat(data.total_proforma).toFixed(2);	
            	}   
            }
        });
    }
    // fin



    // funcion informacion
    function informacion() {
        $.ajax({
            type: "POST",
            url: "data/inicio/app.php",
            data: {cargar_informacion:'cargar_informacion'},
            dataType: 'json',
            async: false,
            success: function(data) {
                $scope.usuario = data.usuario;
                $scope.conexion = data.fecha_creacion;               
            }
        });
    }
    // fin

    // incio funciones
    proformas();
    informacion();
    // fin

	});	
});
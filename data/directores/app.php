<?php 
	if(!isset($_SESSION)) {
        session_start();        
    }
    
    include_once('../../admin/datos_sri.php');
	include_once('../../admin/datos_cedula.php');
	include_once('../../admin/class.php');
	$class = new constante();
	$fecha = $class->fecha_hora();

	if (isset($_POST['btn_guardar']) == "btn_guardar") {
		// contador directores
		$id_director = 0;
		$resultado = $class->consulta("SELECT max(id) FROM directores");
		while ($row = $class->fetch_array($resultado)) {
			$id_director = $row[0];
		}
		$id_director++;
		// fin

		$acumulable = "NO";
		if(isset($_POST["acumulable"]))
			$acumulable = "SI";

		$resp = $class->consulta("INSERT INTO directores VALUES (	'$id_director',
																	'$_POST[select_documento]',
																	'$_POST[identificacion]',
																	'$_POST[nombres_completos]',
																	'$_POST[telefono1]',
																	'$_POST[telefono2]',
																	'$_POST[ciudad]',
																	'$_POST[direccion]',
																	'$_POST[correo]',
																	'".md5($_POST['identificacion'])."',
																	'$acumulable',
																	'$_POST[porcentaje]',
																	'$_POST[observaciones]',
																	'1', 
																	'$fecha');");	
		

		$data = 1;
		echo $data;
	}

	if (isset($_POST['btn_modificar']) == "btn_modificar") {
		$acumulable = "NO";
		if(isset($_POST["acumulable"]))
			$acumulable = "SI";

		$resp = $class->consulta("UPDATE directores SET	tipo_documento = '$_POST[select_documento]',
														identificacion = '$_POST[identificacion]',
														nombres_completos = '$_POST[nombres_completos]',
														telefono1 = '$_POST[telefono1]',
														telefono2 = '$_POST[telefono2]',
														ciudad = '$_POST[ciudad]',
														direccion = '$_POST[direccion]',
														correo = '$_POST[correo]',
														acumulado = '$acumulable',
														porcentaje = '$_POST[porcentaje]',
														observaciones = '$_POST[observaciones]',
														fecha_creacion = '$fecha' WHERE id = '$_POST[id_director]'");	

		$data = 2;
		echo $data;
	}

	//comprarar identificaciones directores
	if (isset($_POST['comparar_identificacion'])) {
		$cont = 0;

		$resultado = $class->consulta("SELECT * FROM directores C WHERE C.tipo_documento = '$_POST[tipo_documento]' AND C.identificacion = '$_POST[identificacion]' AND estado = '1'");
		while ($row = $class->fetch_array($resultado)) {
			$cont++;
		}

		if ($cont == 0) {
		    $data = 0;
		} else {
		    $data = 1;
		}
		echo $data;
	}
	// fin

	// consultar ruc
	if (isset($_POST['consulta_ruc'])) {
		$ruc = $_POST['txt_ruc'];
		$servicio = new ServicioSRI();///creamos nuevo objeto de servicios SRI
		$datosEmpresa = $servicio->consultar_ruc($ruc); ////accedemos a la funcion datosSRI
		$establecimientos = $servicio->establecimientoSRI($ruc);

		print_r(json_encode(['datosEmpresa'=>$datosEmpresa,'establecimientos'=>$establecimientos]));		
	}
	// fin

	// consultar cedula
	if (isset($_POST['consulta_cedula'])) {
		$ruc = $_POST['txt_ruc'];
		$servicio = new DatosCedula();///creamos nuevo objeto de antecedentes
		$datosCedula = $servicio->consultar_cedula($ruc); ////accedemos a la funcion datosSRI

		print_r(json_encode(['datosPersona'=>$datosCedula]));		
	}
	// fin
?>
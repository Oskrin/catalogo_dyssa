<?php 
	if(!isset($_SESSION)){
        session_start();        
    }
	include_once('../../admin/class.php');
	$class = new constante();
	error_reporting(0);
	$fecha = $class->fecha_hora();

	// contador clientes
	$id_dias = 0;
	$resultado = $class->consulta("SELECT max(id) FROM dias");
	while ($row = $class->fetch_array($resultado)) {
		$id_dias = $row[0];
	}
	$id_dias++;
	// fin

	// cargar datos 
	$lunes = "NO";
	if(isset($_POST["lunes"]))
		$lunes = "SI";

	$martes = "NO";
	if(isset($_POST["martes"]))
		$martes = "SI";

	$miercoles = "NO";
	if(isset($_POST["miercoles"]))
		$miercoles = "SI";

	$jueves = "NO";
	if(isset($_POST["jueves"]))
		$jueves = "SI";

	$viernes = "NO";
	if(isset($_POST["viernes"]))
		$viernes = "SI";

	$sabado = "NO";
	if(isset($_POST["sabado"]))
		$sabado = "SI";

	$domingo = "NO";
	if(isset($_POST["domingo"]))
		$domingo = "SI";
	// fin

	if ($_POST['oper'] == "add") {
			$resp = $class->consulta("INSERT INTO dias VALUES ('$id_dias','$lunes','$martes','$miercoles','$jueves','$viernes','$sabado','$domingo','1','$fecha');");
			$data = "1";
	} else {
	    if ($_POST['oper'] == "edit") {
			$resp = $class->consulta("UPDATE dias SET lunes = '$lunes', martes = '$martes', miercoles = '$miercoles', jueves = '$jueves', viernes = '$viernes', sabado = '$sabado', domingo = '$domingo', fecha_creacion = '$fecha' WHERE id = '$_POST[id]'");
	    	$data = "2";
	    } else {
	    	if ($_POST['oper'] == "del") {
	    		$resp = $class->consulta("UPDATE dias SET estado = '2' WHERE id = '$_POST[id]'");
	    		$data = "4";	
	    	}
	    }
	}    
	echo $data;
?>
<?php
	include_once('../admin/class.php');
	$class = new constante();
	session_start();
	$fecha = $class->fecha_hora();

	// modificar ultima conexion
	$resultado = $class->consulta("UPDATE directores SET fecha_creacion = '$fecha' WHERE id = '".$_SESSION['user']['id']."'");
	// fin

	// destruir sesion
	session_destroy();
	// fin
?>

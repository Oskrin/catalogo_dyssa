<?php 
	include_once('../../../admin/class.php');
	$class = new constante();
	session_start(); 
	error_reporting(0);

	$fecha = $class->fecha_hora();
	$fecha_corta = $class->fecha();

	// cargar_productos_vendidos
	if (isset($_POST['cargar_productos_vendidos'])) {
		$resultado = $class->consulta("SELECT D.id_producto, P.descripcion, SUM(CAST(D.cantidad AS INT)) total
										FROM detalle_factura_venta D, productos P WHERE D.id_producto = P.id
										GROUP BY D.id_producto, P.descripcion 
										ORDER BY total DESC
										LIMIT 10");
		while ($row = $class->fetch_array($resultado)) {
			$data[] = array('name' => $row[1], 'y' => intval($row[2]));
		}
		echo $data = json_encode($data);
	}
	// fin

	// proformas diaria
	if (isset($_POST['cargar_proformas'])) {
		$resultado = $class->consulta("SELECT SUM(CAST(total_proforma as float)) total_proforma FROM proforma WHERE fecha_actual = '$fecha_corta' AND id_director = '".$_SESSION['user']['id']."' ORDER BY total_proforma DESC");
		while ($row = $class->fetch_array($resultado)) {
			$data = array('total_proforma' => $row[0]);
		}
		echo $data = json_encode($data);
	}
	// fin

	// informacion ingresos usuarios
	if (isset($_POST['cargar_informacion'])) {
		$resultado = $class->consulta("SELECT identificacion, fecha_creacion FROM directores WHERE id = '".$_SESSION['user']['id']."'");
		while ($row = $class->fetch_array($resultado)) {
			$data = array('usuario' => $row[0], 'fecha_creacion' => substr($row[1], 0, -6));
		}
		echo $data = json_encode($data);
	}
	// fin

?>
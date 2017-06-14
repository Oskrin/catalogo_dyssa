<?php 
	if(!isset($_SESSION)){
        session_start();        
    }
	include_once('../../admin/class.php');
	include_once('../../admin/funciones_generales.php');
	$class = new constante();
	// error_reporting(0);
	
	// guardar productos de excel
	$fecha = $class->fecha_hora();
	if (isset($_POST['btn_guardar']) == "btn_guardar") {
		// $id_productos = $class->idz();

		// $resp = $class->consulta("INSERT INTO productos VALUES (	'$id_productos',
		// 															'$_POST[var]',
		// 															'$_POST[var1]',
		// 															'$_POST[var2]',
		// 															'".number_format($_POST['var3'], 3, '.', '')."',
		// 															'0.000',
		// 															'0.000',
		// 															'".number_format($_POST['var4'], 3, '.', '')."',
		// 															'".number_format($_POST['var5'], 3, '.', '')."',
		// 															'201605121659255734fcbd130da',
		// 															'',
		// 															'',
		// 															'',
		// 															'201605111430385733885e2e043',
		// 															'201605121600305734eeee5f3e6',
		// 															'$_POST[var8]',
		// 															'$_POST[var6]',
		// 															'0',
		// 															'0',
		// 															'0',
		// 															'NO',
		// 															'$_POST[var9]',
		// 															'',
		// 															'',
		// 															'NO',
		// 															'articulo.jpg',
		// 															'',
		// 															'1', 
		// 															'$fecha');");


		// $resp = $class->consulta("INSERT INTO directores VALUES (	'$_POST[var]',
		// 															'CEDULA',
		// 															'$_POST[var1]',
		// 															'$_POST[var2]',
		// 															'$_POST[var4]',
		// 															'$_POST[var5]',
		// 															'$_POST[var6]',
		// 															'$_POST[var3]',
		// 															'$_POST[var7]',
		// 															'$_POST[var9]',
		// 															'SI',
		// 															'0',
		// 															'',
		// 															'$_POST[var8]')");



		// $resp = $class->consulta("INSERT INTO clientes VALUES (		'$_POST[var]',
		// 															'$_POST[var11]',
		// 															'$_POST[var1]',
		// 															'$_POST[var2]',
		// 															'$_POST[var3]',
		// 															'$_POST[var5]',
		// 															'$_POST[var6]',
		// 															'$_POST[var7]',
		// 															'$_POST[var4]',
		// 															'$_POST[var8]',
		// 															'$_POST[var9]',
		// 															'',
		// 															'$_POST[var10]')");

		$resp = $class->consulta("INSERT INTO productos VALUES (	'$_POST[var]',
																	'',
																	'$_POST[var1]',
																	'$_POST[var2]',
																	'".number_format($_POST['var5'], 2, '.', '')."',
																	'$_POST[var6]',
																	'$_POST[var7]',
																	'".number_format($_POST['var8'], 2, '.', '')."',
																	'".number_format($_POST['var9'], 2, '.', '')."',
																	'',
																	'',
																	'',
																	'',
																	'1',
																	'2',
																	'$_POST[var3]',
																	'$_POST[var10]',
																	'$_POST[var11]',
																	'$_POST[var12]',
																	'$_POST[var13]',
																	'NO',
																	'$_POST[var15]',
																	'',
																	'',
																	'$_POST[var4]',
																	'articulo.jpg',
																	'',
																	'$_POST[var14]')");


		// $resp = $class->consulta("INSERT INTO proforma VALUES  (	'$_POST[var]',
		// 															'".$_SESSION['empresa']['id']."',
		// 															'$_POST[var1]',
		// 															'$_POST[var2]',
		// 															'$_POST[var4]',
		// 															'$_POST[var5]',
		// 															'$_POST[var6]',
		// 															'',
		// 															'$_POST[var7]',
		// 															'".floatval($_POST[var8]/1000)."',
		// 															'".floatval($_POST[var9]/1000)."',
		// 															'$_POST[var10]',
		// 															'$_POST[var11]',
		// 															'$_POST[var12]',
		// 															'$_POST[var13]')");

		
		// $resp = $class->consulta("INSERT INTO nota_venta VALUES (	'$_POST[var]',
		// 															'".$_SESSION['empresa']['id']."',
		// 															'$_POST[var1]',
		// 															'$_POST[var2]',
		// 															'$_POST[var]',
		// 															'$_POST[var3]',
		// 															'$_POST[var4]',
		// 															'2',
		// 															'$_POST[var5]',
		// 															'$_POST[var6]',
		// 															'',
		// 															'$_POST[var7]',
		// 															'$_POST[var8]',
		// 															'$_POST[var9]',
		// 															'$_POST[var10]',
		// 															'$_POST[var11]',
		// 															'',
		// 															'$_POST[var12]')");

		// guardar factura
		$resp = $class->consulta("INSERT INTO factura_venta VALUES  (	'$_POST[var]',
																		'".$_SESSION['empresa']['id']."',
																		'$_POST[var2]',
																		'$_POST[var3]',
																		'$id_factura',
																		'$_POST[var5]',
																		'$_POST[var6]',
																		'$_POST[var7]',
																		'$_POST[var8]',
																		'1',
																		'$_POST[var9]',
																		'$_POST[var10]',
																		'$_POST[var11]',
																		'$_POST[var12]',
																		'$_POST[var13]',
																		'',
																		'$_POST[var14]',
																		'$_POST[var15]',
																		'$_POST[var16]',
																		'$_POST[var17]',
																		'$_POST[var18]',
																		'$_POST[var21]',
																		'$_POST[var22]',
																		'$_POST[var20]',
																		'',
																		'$_POST[var19]')");
		// fin	

		$data = 1;
		echo $data;
	}
	// fin
?>
<?php
	include_once('../../admin/datos_sri.php');
	include_once('../../admin/datos_cedula.php');        
	include_once('../../admin/class.php');
	$class = new constante();
	session_start(); 
	error_reporting(0);

	// guardar devolucion 
	if (isset($_POST['btn_guardar']) == "btn_guardar") {
		$fecha = $class->fecha_hora();
		$fecha_corta = $class->fecha();

		// contador devolucion
		$id_devolucion = 0;
		$resultado = $class->consulta("SELECT max(id) FROM devolucion_venta");
		while ($row = $class->fetch_array($resultado)) {
			$id_devolucion = $row[0];
		}
		$id_devolucion++;
		// fin

		// guardar devolucion
		$resp = $class->consulta("INSERT INTO devolucion_venta VALUES  (	'$id_devolucion',
																			'".$_SESSION['empresa']['id']."',
																			'$_POST[id_cliente]',
																			'".$_SESSION['user']['id']."',
																			'$_POST[id_factura]',
																			'$_POST[fecha_actual]',
																			'$_POST[hora_actual]',
																			'$_POST[subtotal]',
																			'$_POST[tarifa_0]',
																			'$_POST[tarifa]',
																			'$_POST[iva]',
																			'$_POST[otros]',
																			'$_POST[total_pagar]',
																			'',
																			'1')");
		// fin	

		// datos detalle devolucion
		$campo1 = $_POST['campo1'];
	    $campo2 = $_POST['campo2'];
	    $campo3 = $_POST['campo3'];
	    $campo4 = $_POST['campo4'];
	    $campo5 = $_POST['campo5'];
	    // Fin

	    // descomponer detalle devolucion
		$arreglo1 = explode('|', $campo1);
	    $arreglo2 = explode('|', $campo2);
	    $arreglo3 = explode('|', $campo3);
	    $arreglo4 = explode('|', $campo4);
	    $arreglo5 = explode('|', $campo5);
	    $nelem = count($arreglo1);
	    // fin

	    for ($i = 1; $i < $nelem; $i++) {
	    	// contador detalle devolucion
			$id_detalle_devolucion = 0;
			$resultado = $class->consulta("SELECT max(id) FROM detalle_devolucion_venta");
			while ($row = $class->fetch_array($resultado)) {
				$id_detalle_devolucion = $row[0];
			}
			$id_detalle_devolucion++;
			// fin

			$resp = $class->consulta("INSERT INTO detalle_devolucion_venta VALUES (	'$id_detalle_devolucion',
																					'$id_devolucion',
																					'".$arreglo1[$i]."',
																					'".$arreglo2[$i]."',
																					'".$arreglo3[$i]."',
																					'".$arreglo4[$i]."',
																					'".$arreglo5[$i]."',
																					'1')");

			// modificar productos
           	$consulta = $class->consulta("SELECT * FROM productos WHERE id = '".$arreglo1[$i]."'");
           	while ($row = $class->fetch_array($consulta)) {
                $stock = $row[16];
            }

            $cal = $stock + $arreglo2[$i];
            $class->consulta("UPDATE productos SET stock = '$cal' WHERE id = '".$arreglo1[$i]."'");
            // fin

            // // consultar movimientos
           	// $consulta2 = $class->consulta("SELECT * FROM movimientos WHERE id_producto = '".$arreglo1[$i]."'");
           	// while ($row = $class->fetch_array($consulta2)) {
            //     $salida = $row[5];
            // }

            // $cal2 = $salida + $arreglo2[$i]; 
            // $class->consulta("UPDATE movimientos SET salidas = '$cal2', saldo = '$cal' WHERE id_producto = '".$arreglo1[$i]."'");
            // // fin

            // contador kardex
			$id_kardex = 0;
			$resultado = $class->consulta("SELECT max(id) FROM kardex");
			while ($row = $class->fetch_array($resultado)) {
				$id_kardex = $row[0];
			}
			$id_kardex++;
			// fin

			// guardar kardex
			$resp = $class->consulta("INSERT INTO kardex VALUES (	'$id_kardex',
																	'".$arreglo1[$i]."',
																	'$fecha_corta',
																	'".'D.V:'.$_POST[serie]."',
																	'".$arreglo2[$i]."',
																	'".$arreglo3[$i]."',
																	'".$arreglo5[$i]."',
																	'$cal',
																	'',
																	'',
																	'8', 
																	'$fecha')");
			// fin
	    }
	   
		echo $id_devolucion;
	}
	// fin

	// buscar series
	if (isset($_POST['buscador_series'])) {
		$resultado = $class->consulta("SELECT id, serie FROM factura_venta WHERE estado = '1'");
		while ($row = $class->fetch_array($resultado)) {
			$data[] = array(
	            'id' => $row[0],
	            'value' => $row[1]
	        );			

		}
		echo $data = json_encode($data);	
	}
	// fin

	// buscar clientes
	if (isset($_POST['buscador_clientes'])) {
		$resultado = $class->consulta("SELECT C.id, C.identificacion, C.nombres_completos, C.telefono2, C.direccion, C.correo, D.nombres_completos, D.acumulado, D.porcentaje FROM clientes C, directores D WHERE C.id_director = D.id AND C.estado = '1'");
		while ($row = $class->fetch_array($resultado)) {
			if($_POST['tipo_busqueda'] == 'ruc') {
				$data[] = array(
		            'id' => $row[0],
		            'value' => $row[1],
		            'cliente' => $row[2],
		            'telefono' => $row[3],
		            'direccion' => $row[4],
		            'correo' => $row[5],
		            'director' => $row[6],
		            'acumulado' => $row[7],
		            'porcentaje' => $row[8] 
		        );			
			} else {
				if($_POST['tipo_busqueda'] == 'cliente') {
					$data[] = array(
			            'id' => $row[0],
			            'value' => $row[2],
			            'ruc' => $row[1],
			            'telefono' => $row[3],
			            'direccion' => $row[4],
			            'correo' => $row[5],
			            'director' => $row[6],
			            'acumulado' => $row[7],
			            'porcentaje' => $row[8] 
			        );	
				}
			}
		}
		echo $data = json_encode($data);	
	}
	// fin

	// buscar productos
	if (isset($_POST['buscador_productos'])) {
		$resultado = $class->consulta("SELECT * FROM productos  P, porcentaje_iva V WHERE P.id_porcentaje = V.id AND P.estado = '1'");
		while ($row = $class->fetch_array($resultado)) {
			if($_POST['tipo_busqueda'] == 'codigo') {
				if ($_POST['tipo_precio'] == "MINORISTA") {
			        $data[] = array(
			        	'id' => $row[0],
			            'value' => $row[2],
			            'codigo_barras' => $row[1],
			            'producto' => $row[3],
			            'precio_costo' => $row[4],
			            'precio_venta' => $row[7],
			            'descuento' => $row[19],
			            'stock' => $row[16],
			            'iva_producto' => $row[31],
			            'incluye' => $row[15]
			        );
			    } else {
			        if ($_POST['tipo_precio'] == "MAYORISTA") {
			            $data[] = array(
			            	'id' => $row[0],
			                'value' => $row[2],
			                'codigo_barras' => $row[1],
			                'producto' => $row[3],
			                'precio_costo' => $row[4],
			                'precio_venta' => $row[8],
			                'descuento' => $row[19],
			                'stock' => $row[16],
			                'iva_producto' => $row[31],
			                'incluye' => $row[15]
			            );
			        }
		    	}	
			} else {
				if($_POST['tipo_busqueda'] == 'producto') {
					if ($_POST['tipo_precio'] == "MINORISTA") {
				        $data[] = array(
				        	'id' => $row[0],
				            'value' => $row[3],
				            'codigo_barras' => $row[1],
				            'codigo' => $row[2],
				            'precio_costo' => $row[4],
				            'precio_venta' => $row[7],
				            'descuento' => $row[19],
				            'stock' => $row[16],
				            'iva_producto' => $row[31],
				            'incluye' => $row[15]
				        );
				    } else {
				        if ($_POST['tipo_precio'] == "MAYORISTA") {
				            $data[] = array(
				            	'id' => $row[0],
				                'value' => $row[3],
				                'codigo_barras' => $row[1],
				                'codigo' => $row[2],
				                'precio_costo' => $row[4],
				                'precio_venta' => $row[8],
				                'descuento' => $row[19],
				                'stock' => $row[16],
				                'iva_producto' => $row[31],
				                'incluye' => $row[15]
				            );
				        }
				    }
				}
			}
		}
		echo $data = json_encode($data);	
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
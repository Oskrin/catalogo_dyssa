<?php
	include_once('../../admin/datos_sri.php');
	include_once('../../admin/datos_cedula.php');        
	include_once('../../admin/class.php');
	$class = new constante();
	session_start(); 
	error_reporting(0);

	if ($_POST["select_tipo_comprobante"] == 1) {
		// guardar facturas 
		if (isset($_POST['btn_guardar']) == "btn_guardar") {
			$fecha = $class->fecha_hora();
			$fecha_corta = $class->fecha();

			// modificar proformas
	        if ($_POST['id_proforma'] != "") {
	        	$class->consulta("UPDATE proforma SET estado = '0' WHERE id = '".$_POST['id_proforma']."'");
	        }
	        // fin

			// contador factura
			$id_factura = 0;
			$resultado = $class->consulta("SELECT max(id) FROM factura_venta");
			while ($row = $class->fetch_array($resultado)) {
				$id_factura = $row[0];
			}
			$id_factura++;
			// fin

			// guardar factura
			$resp = $class->consulta("INSERT INTO factura_venta VALUES  (	'$id_factura',
																			'".$_SESSION['empresa']['id']."',
																			'$_POST[id_cliente]',
																			'".$_SESSION['user']['id']."',
																			'$id_factura',
																			'$_POST[serie]',
																			'$_POST[fecha_actual]',
																			'$_POST[hora_actual]',
																			'',
																			'$_POST[select_tipo_comprobante]',
																			'$_POST[select_tipo_precio]',
																			'$_POST[select_forma_pago]',
																			'',
																			'',
																			'',
																			'$_POST[subtotal]',
																			'$_POST[tarifa_0]',
																			'$_POST[tarifa]',
																			'$_POST[iva]',
																			'$_POST[otros]',
																			'$_POST[total_pagar]',
																			'$_POST[porcentaje]',
																			'$_POST[porcentaje_director]',
																			'',
																			'',
																			'1', 
																			'$fecha')");
			// fin	

			// datos detalle factura
			$campo1 = $_POST['campo1'];
		    $campo2 = $_POST['campo2'];
		    $campo3 = $_POST['campo3'];
		    $campo4 = $_POST['campo4'];
		    $campo5 = $_POST['campo5'];
		    $campo6 = $_POST['campo6'];
		    // Fin

		    // descomponer detalle factura
			$arreglo1 = explode('|', $campo1);
		    $arreglo2 = explode('|', $campo2);
		    $arreglo3 = explode('|', $campo3);
		    $arreglo4 = explode('|', $campo4);
		    $arreglo5 = explode('|', $campo5);
		    $arreglo6 = explode('|', $campo6);
		    $nelem = count($arreglo1);
		    // fin

		    for ($i = 1; $i < $nelem; $i++) {
		    	// contador detalle factura
				$id_detalle_factura = 0;
				$resultado = $class->consulta("SELECT max(id) FROM detalle_factura_venta");
				while ($row = $class->fetch_array($resultado)) {
					$id_detalle_factura = $row[0];
				}
				$id_detalle_factura++;
				// fin

				$resp = $class->consulta("INSERT INTO detalle_factura_venta VALUES (	'$id_detalle_factura',
																						'$id_factura',
																						'".$arreglo1[$i]."',
																						'".$arreglo2[$i]."',
																						'".$arreglo3[$i]."',
																						'".$arreglo4[$i]."',
																						'".$arreglo5[$i]."',
																						'".$arreglo6[$i]."',
																						'1', 
																						'$fecha')");

				// modificar productos
	           	$consulta = $class->consulta("SELECT * FROM productos WHERE id = '".$arreglo1[$i]."'");
	           	while ($row = $class->fetch_array($consulta)) {
	                $stock = $row[16];
	            }

	            $cal = $stock - $arreglo2[$i];
	            $class->consulta("UPDATE productos SET stock = '$cal' WHERE id = '".$arreglo1[$i]."'");
	            // fin

	            // consultar movimientos
	           	$consulta2 = $class->consulta("SELECT * FROM movimientos WHERE id_producto = '".$arreglo1[$i]."'");
	           	while ($row = $class->fetch_array($consulta2)) {
	                $salida = $row[5];
	            }

	            $cal2 = $salida + $arreglo2[$i]; 
	            $class->consulta("UPDATE movimientos SET salidas = '$cal2', saldo = '$cal' WHERE id_producto = '".$arreglo1[$i]."'");
	            // fin

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
																		'".'F.V:'.$_POST[serie]."',
																		'".$arreglo2[$i]."',
																		'".$arreglo3[$i]."',
																		'".$arreglo5[$i]."',
																		'$cal',
																		'',
																		'',
																		'2', 
																		'$fecha')");
				// fin
		    }

		    $campo7 = $_POST['id_notas'];

		    // descomponer detalle factura
			$arreglo7 = explode(',', $campo7);
		    $nelem2 = count($arreglo7);
		    // fin

		    for ($i = 1; $i < $nelem2; $i++) {
		    	$class->consulta("UPDATE nota_venta SET estado = '0', referencia = '$_POST[serie]' WHERE id = '".$arreglo7[$i]."'");
		    }
		   
			echo $id_factura;
		}
		// fin

		// anular facturas
		if (isset($_POST['btn_anular']) == "btn_anular") {
			$class->consulta("UPDATE factura_venta SET fecha_anulacion = '$_POST[fecha_actual]', estado = '2'  WHERE id = '$_POST[id_factura]'");

			// datos detalle factura
			$campo1 = $_POST['campo1'];
		    $campo2 = $_POST['campo2'];
		    // Fin

		    // descomponer detalle factura
			$arreglo1 = explode('|', $campo1);
		    $arreglo2 = explode('|', $campo2);
		    $nelem = count($arreglo1);
		    // fin

		    for ($i = 1; $i < $nelem; $i++) {
		    	// modificar productos
	           	$consulta = $class->consulta("SELECT * FROM productos WHERE id = '".$arreglo1[$i]."'");
	           	while ($row = $class->fetch_array($consulta)) {
	                $stock = $row[16];
	            }

	            $cal = $stock + $arreglo2[$i];
	            $class->consulta("UPDATE productos SET stock = '$cal' WHERE id = '".$arreglo1[$i]."'");
	            // fin
		    }

		    $data = 1;
			echo $data;
		}
		// fin
	} else {
		if ($_POST["select_tipo_comprobante"] == 21) {
			// guardar notas venta
			if (isset($_POST['btn_guardar']) == "btn_guardar") {
				$fecha = $class->fecha_hora();

				// modificar proformas
		        if ($_POST['id_proforma'] != "") {
		        	$class->consulta("UPDATE proforma SET estado = '0' WHERE id = '".$_POST['id_proforma']."'");
		        }
		        // fin

				// contador nota venta
				$id_nota = 0;
				$resultado = $class->consulta("SELECT max(id) FROM nota_venta");
				while ($row = $class->fetch_array($resultado)) {
					$id_nota = $row[0];
				}
				$id_nota++;
				// fin

				$resp = $class->consulta("INSERT INTO nota_venta VALUES (	'$id_nota',
																			'".$_SESSION['empresa']['id']."',
																			'$_POST[id_cliente]',
																			'".$_SESSION['user']['id']."',
																			'$id_nota',
																			'$_POST[fecha_actual]',
																			'$_POST[hora_actual]',
																			'$_POST[select_tipo_comprobante]',
																			'$_POST[select_tipo_precio]',
																			'$_POST[select_forma_pago]',
																			'$_POST[subtotal]',
																			'$_POST[tarifa_0]',
																			'$_POST[tarifa]',
																			'$_POST[iva]',
																			'$_POST[otros]',
																			'$_POST[total_pagar]',
																			'',
																			'1', 
																			'$fecha')");

				// datos detalle nota
				$campo1 = $_POST['campo1'];
			    $campo2 = $_POST['campo2'];
			    $campo3 = $_POST['campo3'];
			    $campo4 = $_POST['campo4'];
			    $campo5 = $_POST['campo5'];
			    $campo6 = $_POST['campo6'];
			    // Fin

			    // descomponer detalle nota
				$arreglo1 = explode('|', $campo1);
			    $arreglo2 = explode('|', $campo2);
			    $arreglo3 = explode('|', $campo3);
			    $arreglo4 = explode('|', $campo4);
			    $arreglo5 = explode('|', $campo5);
			    $arreglo6 = explode('|', $campo6);
			    $nelem = count($arreglo1);
			    // fin

			    for ($i = 1; $i < $nelem; $i++) {
			    	// contador detalle nota
					$id_detalle_nota = 0;
					$resultado = $class->consulta("SELECT max(id) FROM detalle_nota_venta");
					while ($row = $class->fetch_array($resultado)) {
						$id_detalle_nota = $row[0];
					}
					$id_detalle_nota++;
					// fin

					$resp = $class->consulta("INSERT INTO detalle_nota_venta VALUES(	'$id_detalle_nota',
																						'$id_nota',
																						'".$arreglo1[$i]."',
																						'".$arreglo2[$i]."',
																						'".$arreglo3[$i]."',
																						'".$arreglo4[$i]."',
																						'".$arreglo5[$i]."',
																						'".$arreglo6[$i]."',
																						'1', 
																						'$fecha')");
			    }

				echo $id_nota;
			}
			// fin	
		}
	}

	//cargar facturero
	if (isset($_POST['cargar_facturero'])) {
		$resultado = $class->consulta("SELECT * FROM facturero WHERE estado = '1'");
		while ($row = $class->fetch_array($resultado)) {
			$data_facturero = array(	'fecha_inicio' => $row[1],
							'fecha_caducidad' => $row[2],
							'inicio_facturero' => $row[3],
							'finaliza_facturero' => $row[4],
							'num_items' => $row[6]);
		}
		print_r(json_encode($data_facturero));
	}
	//fin

	//cargar ultima serie factura venta
	if (isset($_POST['cargar_series'])) {
		$resultado = $class->consulta("SELECT MAX(serie) FROM factura_venta GROUP BY id ORDER BY id asc");
		while ($row = $class->fetch_array($resultado)) {
			$data = array('serie' => $row[0]);
		}
		print_r(json_encode($data));
	}
	//fin

	// LLenar tipo comprobante
	if (isset($_POST['llenar_tipo_comprobante'])) {
		$resultado = $class->consulta("SELECT id, codigo ,nombre_tipo_comprobante, principal FROM tipo_comprobante WHERE estado = '1' order by id asc");
		print'<option value="">&nbsp;</option>';
		while ($row = $class->fetch_array($resultado)) {
			if($row['principal'] == 'Si') {
				print '<option value="'.$row['id'].'" selected>'.$row['codigo'].' - '.$row['nombre_tipo_comprobante'].'</option>';	
			} else {
				print '<option value="'.$row['id'].'">'.$row['codigo'].' - '.$row['nombre_tipo_comprobante'].'</option>';	
			}
		}
	}
	// fin

	//llenar cabezera factura venta
	if (isset($_POST['llenar_cabezera_factura'])) {
		$resultado = $class->consulta("SELECT F.id, F.fecha_actual, F.hora_actual, F.serie,  F.id_cliente, C.identificacion, C.nombres_completos, C.direccion, C.telefono2, C.correo, D.nombres_completos, F.tipo_comprobante, F.forma_pago, F.tipo_precio, F.porcentaje, F.tarifa0, F.tarifa, F.iva_venta, F.descuento_venta, F.porcentaje_director, F.total_venta, F.estado  FROM factura_venta F, clientes C, directores D WHERE F.id_cliente = C.id AND C.id_director = D.id AND F.id = '$_POST[id]'");
		while ($row = $class->fetch_array($resultado)) {
			$data = array(  'id_factura' => $row[0],
							'fecha_actual' => $row[1],
							'hora_actual' => $row[2],
							'serie' => $row[3],
							'id_cliente' => $row[4],
							'identificacion' => $row[5],
							'nombres_completos' => $row[6],
							'direccion' => $row[7],
							'telefono2' => $row[8],
							'correo' => $row[9],
							'director' => $row[10],
							'tipo_comprobante' => $row[11],
							'forma_pago' => $row[12],
							'tipo_precio' => $row[13],
							'porcentaje' => $row[14],
							'tarifa0' => $row[15],
							'tarifa' => $row[16],
							'iva' => $row[17],
							'descuento' => $row[18],
							'porcentaje_director' => $row[19],
							'total_pagar' => $row[20],
							'estado' => $row[21]);
		}
		print_r(json_encode($data));
	}
	//fin

	//llenar detalle factura venta
	if (isset($_POST['llenar_detalle_factura'])) {
		$resultado = $class->consulta("SELECT D.id_producto, U.codigo, U.descripcion, D.cantidad, D.precio, D.descuento, D.total, P.porcentaje, U.incluye_iva, D.pendientes FROM detalle_factura_venta D, factura_venta F, productos U, porcentaje_iva P  WHERE D.id_producto = U.id AND D.id_factura_venta = F.id AND U.id_porcentaje = P.id AND F.id = '".$_POST['id']."' ORDER BY D.id asc");
		while ($row = $class->fetch_array($resultado)) {
			$arr_data[] = $row['0'];
		    $arr_data[] = $row['1'];
		    $arr_data[] = $row['2'];
		    $arr_data[] = $row['3'];
		    $arr_data[] = $row['4'];
		    $arr_data[] = $row['5'];
		    $arr_data[] = $row['6'];
		    $arr_data[] = $row['7'];
		    $arr_data[] = $row['8'];
		    $arr_data[] = $row['9'];
		}
		echo json_encode($arr_data);
	}
	//fin

	//llenar cabezera nota
	if (isset($_POST['llenar_cabezera_nota'])) {
		$resultado = $class->consulta("SELECT N.id, N.fecha_actual, N.hora_actual, N.id_cliente, C.identificacion, C.nombres_completos, C.direccion, C.telefono2, C.correo, D.nombres_completos, N.tipo_comprobante, N.forma_pago, N.tipo_precio, N.tarifa0, N.tarifa, N.iva_nota, N.descuento_nota, N.total_nota FROM nota_venta N, clientes C, directores D WHERE N.id_cliente = C.id AND C.id_director = D.id AND N.id = '$_POST[id]'");
		while ($row = $class->fetch_array($resultado)) {
			$data = array(  'id_nota' => $row[0],
							'fecha_actual' => $row[1],
							'hora_actual' => $row[2],
							'id_cliente' => $row[3],
							'identificacion' => $row[4],
							'nombres_completos' => $row[5],
							'direccion' => $row[6],
							'telefono2' => $row[7],
							'correo' => $row[8],
							'director' => $row[9],
							'tipo_comprobante' => $row[10],
							'forma_pago' => $row[11],
							'tipo_precio' => $row[12],
							'tarifa0' => $row[13],
							'tarifa' => $row[14],
							'iva' => $row[15],
							'descuento' => $row[16],
							'total_pagar' => $row[17]);
		}
		print_r(json_encode($data));
	}
	//fin

	//llenar detalle nota
	if (isset($_POST['llenar_detalle_nota'])) {
		$resultado = $class->consulta("SELECT D.id_producto, U.codigo, U.descripcion, D.cantidad, D.precio, D.descuento, D.total, P.porcentaje, U.incluye_iva, D.pendientes FROM detalle_nota_venta D, nota_venta N, productos U, porcentaje_iva P  WHERE D.id_producto = U.id AND D.id_nota_venta = N.id AND U.id_porcentaje = P.id AND N.id = '".$_POST['id']."' ORDER BY D.id asc");
		while ($row = $class->fetch_array($resultado)) {
			$arr_data[] = $row['0'];
		    $arr_data[] = $row['1'];
		    $arr_data[] = $row['2'];
		    $arr_data[] = $row['3'];
		    $arr_data[] = $row['4'];
		    $arr_data[] = $row['5'];
		    $arr_data[] = $row['6'];
		    $arr_data[] = $row['7'];
		    $arr_data[] = $row['8'];
		    $arr_data[] = $row['9'];
		}
		echo json_encode($arr_data);
	}
	//fin

	//llenar cabezera proforma
	if (isset($_POST['llenar_cabezera_proforma'])) {
		$resultado = $class->consulta("SELECT P.id, P.fecha_actual, P.hora_actual, P.id_cliente, C.identificacion, C.nombres_completos, C.direccion, C.telefono2, C.correo, D.nombres_completos, P.tipo_precio, P.tarifa0, P.tarifa, P.iva_proforma, P.descuento_proforma, P.total_proforma FROM proforma P, clientes C, directores D WHERE P.id_cliente = C.id AND C.id_director = D.id AND P.id = '$_POST[id]'");
		while ($row = $class->fetch_array($resultado)) {
			$data = array(  'id_proforma' => $row[0],
							'fecha_actual' => $row[1],
							'hora_actual' => $row[2],
							'id_cliente' => $row[3],
							'identificacion' => $row[4],
							'nombres_completos' => $row[5],
							'direccion' => $row[6],
							'telefono2' => $row[7],
							'correo' => $row[8],
							'director' => $row[9],
							'tipo_precio' => $row[10],
							'tarifa0' => $row[11],
							'tarifa' => $row[12],
							'iva' => $row[13],
							'descuento' => $row[14],
							'total_pagar' => $row[15]);
		}
		print_r(json_encode($data));
	}
	//fin

	//llenar detalle proforma
	if (isset($_POST['llenar_detalle_proforma'])) {
		$resultado = $class->consulta("SELECT D.id_producto, U.codigo, U.descripcion, D.cantidad, D.precio, D.descuento, D.total, P.porcentaje, U.incluye_iva FROM detalle_proforma D, proforma N, productos U, porcentaje_iva P  WHERE D.id_producto = U.id AND D.id_proforma = N.id AND U.id_porcentaje = P.id AND N.id = '".$_POST['id']."' ORDER BY D.id asc");
		while ($row = $class->fetch_array($resultado)) {
			$arr_data[] = $row['0'];
		    $arr_data[] = $row['1'];
		    $arr_data[] = $row['2'];
		    $arr_data[] = $row['3'];
		    $arr_data[] = $row['4'];
		    $arr_data[] = $row['5'];
		    $arr_data[] = $row['6'];
		    $arr_data[] = $row['7'];
		    $arr_data[] = $row['8'];
		}
		echo json_encode($arr_data);
	}
	//fin

	//llenar detalle notas entrega
	function unique_multidim_array($array, $key) { 
	    $temp_array = array(); 
	    $i = 0; 
	    $key_array = array(); 
	    
	    foreach($array as $val) { 
	        if (!in_array($val[$key], $key_array)) { 
	            $key_array[$i] = $val[$key]; 
	            $temp_array[$i] = $val;
	           
	            $i++; 
	        } else {
	        	for ($j = 0; $j < count($temp_array) ; $j++) { 
	        		if ($temp_array[$j]['id'] == $val[$key]) {
	        			$temp_array[$j]['cantidad'] = intval($val['cantidad'])+intval($temp_array[$j]['cantidad']);
	        			break;
	        		}
	        	}
	        } 
	    } 
	    return $temp_array; 
	}

	if (isset($_POST['detalle_notas_entrega'])) {
		$campo1 = $_POST['ids'];

	    // descomponer detalle factura
		$arreglo1 = explode(',', $campo1);
	    $nelem = count($arreglo1);
	    // fin

	    $data_aux=[];
	    for ($i = 1; $i < $nelem; $i++) {
	    	$resultado = $class->consulta("SELECT D.id_producto, U.codigo, U.descripcion, D.cantidad, D.precio, D.descuento, D.total, P.porcentaje, U.incluye_iva, D.pendientes FROM detalle_nota_venta D, nota_venta N, productos U, porcentaje_iva P  WHERE D.id_producto = U.id AND D.id_nota_venta = N.id AND U.id_porcentaje = P.id AND N.id = '".$arreglo1[$i]."' ORDER BY D.id asc");
			while ($row = $class->fetch_array($resultado)) {
					$data_aux[] = array(
						            'id' => $row[0],
						            'codigo' => $row[1],
						            'descripcion' => $row[2],
						            'cantidad' => $row[3],
						            'precio' => $row[4],
						            'descuento' => $row[5],
						            'total' => $row[6],
						            'porcentaje' => $row[7],
						            'incluye_iva' => $row[8],
						            'pendientes' => $row[9]
						        );	
			}	
	    }

	    $resultado = unique_multidim_array($data_aux,'id');
	    $array_items = count($resultado);
		print_r(json_encode(array('datos' => $resultado, 'items' => $array_items, 'ids' => $campo1)));
	}
	//fin

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
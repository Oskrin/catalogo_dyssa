<?php        
	include_once('../../admin/class.php');
	$class = new constante();
	session_start(); 
	error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE);
	$fecha = $class->fecha_hora();

	// consultar SRI
	if(isset($_POST['consultar_sri'])) {
		// $data = "";
		$pAppDbg = "false";
		$clave_acceso = $_POST['txt_clave'];

		$slRecepWs = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantes?wsdl";
		$slAutorWs = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl";
		$alWsdl = array();
		$glDebug = isset($_GET['pAppDbg'])? $_GET['pAppDbg'] : false;

		$alWsdl[1] = array('recep'=>"https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantes?wsdl",
								  'autor'=>"https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl");
			 
		$alWsdl[2] = array('recep'=>"https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantes?wsdl",
							  'autor'=>"https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl");

	    $slUrl = $alWsdl[2]['autor'];	
		$olClient = new SoapClient($slUrl, array('encoding'=>'UTF-8'));				
		$olResp = $olClient->autorizacionComprobante(array('claveAccesoComprobante'=> $clave_acceso));		

		$estado = $olResp->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;

		if($estado == 'AUTORIZADO') {
			$cont = 0;
			$xmlComp = new SimpleXMLElement($olResp->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);

			$resultado = $class->consulta("SELECT * FROM repositorio_facturas WHERE clave_acceso = '".$xmlComp->infoTributaria->claveAcceso."'");
			while ($row = $class->fetch_array($resultado)) {
				$cont++;
			}

			if ($cont != 0) {
			    $data = 2;
			} else {
				// contador repositorio_facturas
				$id_repositorio = 0;
				$resultado = $class->consulta("SELECT max(id) FROM repositorio_facturas");
				while ($row = $class->fetch_array($resultado)) {
					$id_repositorio = $row[0];
				}
				$id_repositorio++;
				// fin

				$numeroAutorizacion = $olResp->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
				$fechaAutorizacion = $olResp->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
				$docuemnto = $xmlComp->infoTributaria->estab. '-'.$xmlComp->infoTributaria->ptoEmi. '-'.$xmlComp->infoTributaria->secuencial;

				$respuesta = $class->consulta("INSERT INTO repositorio_facturas VALUES ('$id_repositorio','".$xmlComp->infoTributaria->codDoc."','".$docuemnto."','".$xmlComp->infoFactura->fechaEmision."','".$xmlComp->infoTributaria->claveAcceso."','".$numeroAutorizacion."','".$fechaAutorizacion."','".$xmlComp->infoFactura->importeTotal ."','1','$fecha');");

				$nombre = "archivos/".$numeroAutorizacion.".xml";

				$xml = $class->generateValidXmlFromObj($olResp->RespuestaAutorizacionComprobante->autorizaciones);
				$doc = fopen($nombre,"w+");

				if(fwrite ($doc,$xml)) {
					fclose($doc);				
					$data = 1;	
				}
			}			 	
		} else {
			$data = 0;	
		}

		echo $data;
	}
	// fin

	// descargar archivo
	if($_GET['fn'] == '2') {
		// descargar_archivo();
		$file = "archivos/".$_GET['id'].".xml"; //file location

	   	// print_r($file); 
	   	
	   	if (!is_readable($file))
		    die('File is not readable or not exists!');
		 
		$filename = pathinfo($file, PATHINFO_BASENAME);
		 
		// get mime type of file by extension
		$mime_type = getMimeType($filename);
		 
		// set headers
		header('Pragma: public');
		header('Expires: -1');
		header('Cache-Control: public, must-revalidate, post-check=0, pre-check=0');
		header('Content-Transfer-Encoding: binary');
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Length: " . filesize($file));
		header("Content-Type: $mime_type");
		header("Content-Description: File Transfer");
		 
		// read file as chunk
		if ($fp = fopen($file, 'rb')) {
		    ob_end_clean();
		 
		    while(!feof($fp) and (connection_status() == 0)) {
		        print(fread($fp, 8192));
		        flush();
		    }
		    @fclose($fp);
		    exit;
		}	
	}
	// fin

	// function descargar_archivo() {				
	   	
	// }

	function getMimeType($filename) {
	    $ext = pathinfo($filename, PATHINFO_EXTENSION);
	    $ext = strtolower($ext);
	 
	    $mime_types = array(
	        "pdf"  => "application/pdf",
	        "txt"  => "text/plain",
	        "html" => "text/html",
	        "htm"  => "text/html",
	        "exe"  => "application/octet-stream",
	        "zip"  => "application/zip",
	        "doc"  => "application/msword",
	        "xls"  => "application/vnd.ms-excel",
	        "ppt"  => "application/vnd.ms-powerpoint",
	        "gif"  => "image/gif",
	        "png"  => "image/png",
	        "jpeg" => "image/jpg",
	        "jpg"  => "image/jpg",
	        "php"  => "text/plain",
	        "csv"  => "text/csv",
	        "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
	        "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
	        "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
	    );
	 
	    if(isset($mime_types[$ext])) {
	        return $mime_types[$ext];
	    } else {
	        return 'application/octet-stream';
	    }
	}
?>
<?php 
	session_start();
	if(!$_SESSION) {
		header('Location: ../login/');
	}
?>
<!DOCTYPE html>
<html ng-app="scotchApp" lang="es">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>.:CATÁLOGO.:</title>
		<meta name="description" content="3 styles with inline editable feature" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../dist/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../dist/css/font-awesome.min.css" />
		<link rel="stylesheet" href="../dist/css/style.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="../dist/css/animate.min.css" />
		<link rel="stylesheet" href="../dist/css/jquery.gritter.min.css" />
		<link rel="stylesheet" href="../dist/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="../dist/css/chosen.min.css" />
		<link rel="stylesheet" href="../dist/css/select2.min.css" />
		<link rel="stylesheet" href="../dist/css/ui.jqgrid.min.css" />
		<link rel="stylesheet" href="../dist/css/bootstrap-timepicker.min.css" />
		<link rel="stylesheet" href="../dist/css/daterangepicker.min.css" />
		<link rel="stylesheet" href="../dist/css/bootstrap-datetimepicker.min.css" />
		<link rel="stylesheet" href="../dist/css/bootstrap-datetimepicker-standalone.css" />
		<link rel="stylesheet" href="../dist/css/bootstrap-editable.min.css" />
		<link rel="stylesheet" href="../dist/css/daterangepicker.min.css" />
		<link rel="stylesheet" href="../dist/css/sweetalert.css" />

		<link rel="stylesheet" href="../dist/css/jquery-ui.custom.min.css" />
		<link rel="stylesheet" href="../dist/css/fileinput.css" media="all" type="text/css" />
		
		<!-- text fonts -->
		<link rel="stylesheet" href="../dist/css/fontdc.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="../dist/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!-- Angular js -->
		<script src="../dist/angular-1.5.0/angular.js"></script>
		<script src="../dist/angular-1.5.0/angular-route.js"></script>
		<script src="../dist/angular-1.5.0/angular-animate.js"></script>
		<script src="../dist/angular-1.5.0/ui-bootstrap-tpls-1.1.2.min.js"></script>
		<script src="../dist/angular-1.5.0/angular-resource.js"></script>
		<script src="../dist/js/ngStorage.min.js"></script>

		<script src="../dist/js/ace-extra.min.js"></script>

		<!-- controlador procesos angular -->
  		<script src="data/app.js"></script>
  		<script src="data/inicio/app.js"></script>
  		<script src="data/clientes/app.js"></script>
  		<script src="data/proformas/app.js"></script>
  		<script src="data/pedidos/app.js"></script>
  		

  		<style type="text/css">
			.control {
				background: #eff3f8;
				/*background: #87b87f;*/
				/*background: #4caf50;*/
				height: 60px;
			}

			.dimensiones {
				margin-top: 13px;
			}

			.posicion{ 
				margin-top: 9px;
				float: right;
				margin-left: -5px;
				margin-right: 10px;
			}

			.menu_superior {
				display: inline-block;
			    font-size: 16px;
			    color: #FFF;
			    text-align: center;
			    width: 20px;
			}
		</style>
	</head>

	<body ng-controller="mainController" class="no-skin">
		<div id="navbar" class="navbar navbar-default navbar-fixed-top">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
			<div class="navbar-container" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="" class="navbar-brand">
						<small>
							CATÁLOGO DYSSA
						</small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue">
							<a data-toggle="dropdown" href="" class="dropdown-toggle">
								<img class="nav-user-photo" src=<?php  print_r('../data/directores/imagenes/defaul.jpg'); ?> alt="" />
								<span class="user-info">
									<small>Bienvenido,</small>
									<?php  print_r($_SESSION['user']['name']); ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<!-- <li>
									<a href="#/cuenta">
										<i class="ace-icon fa fa-user"></i>
										Cuenta
									</a>
								</li> -->

								<li class="divider"></li>

								<li>
									<a href="" ng-click="salir()">
										<i class="ace-icon fa fa-power-off"></i>
										Salir
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<ul class="nav nav-list">
					<li ng-class="{active: $route.current.activetab == 'inicio'}">
						<a href="#/">
							<i class="menu-icon fa fa-home"></i>
							<span class="menu-text"> Inicio </span>
						</a>

						<b class="arrow"></b>
					</li>

					<li ng-class="{active: $route.current.activetab == 'clientes'}">
						<a href="#/clientes">
							<i class="menu-icon fa fa-caret-right"></i>
							Clientes
						</a>

						<b class="arrow"></b>
					</li>

					<li ng-class="{active: $route.current.activetab == 'proformas'}">
						<a href="#/proformas">
							<i class="menu-icon fa fa-caret-right"></i>
							Pedidos
						</a>

						<b class="arrow"></b>
					</li>

					<li ng-class="{active: $route.current.activetab == 'pedidos'}">
						<a href="#/pedidos">
							<i class="menu-icon fa fa-caret-right"></i>
							Reportes
						</a>

						<b class="arrow"></b>
					</li>
				</ul>

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<div class="main-content ng-view" id="main-container"></div>

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							Applicación &copy; 2016-2017
						</span>
					</div>
				</div>
			</div>

			<a href="" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div>

		<script type="text/javascript">
			window.jQuery || document.write("<script src='../dist/js/jquery.min.js'>"+"<"+"/script>");
		</script>

		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='../dist/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		
		<script src="../dist/js/jquery-ui.min.js"></script>
		<script src="../dist/js/jquery.ui.touch-punch.min.js"></script>
		<script src="../dist/js/jquery.easypiechart.min.js"></script>
		<script src="../dist/js/jquery.sparkline.min.js"></script>

		<script src="../dist/js/fileinput.js" type="text/javascript"></script>
		<script src="../dist/js/bootstrap.min.js"></script>
		<script src="../dist/js/jquery.form.js"></script>
		<script src="../dist/js/chosen.jquery.min.js"></script>

		<script src="../dist/js/jquery.validate.min.js"></script>
		<script src="../dist/js/jquery.gritter.min.js"></script>
		<script src="../dist/js/bootbox.min.js"></script>
		<script src="../dist/js/fuelux/fuelux.wizard.min.js"></script>
		<script src="../dist/js/additional-methods.min.js"></script>
		
		<script src="../dist/js/jquery.hotkeys.min.js"></script>
		<script src="../dist/js/bootstrap-wysiwyg.min.js"></script>
		<script src="../dist/js/select2.min.js"></script>
		<script src="../dist/js/fuelux/fuelux.spinner.min.js"></script>
		<script src="../dist/js/fuelux/fuelux.tree.min.js"></script>
		<script src="../dist/js/x-editable/bootstrap-editable.min.js"></script>
		<script src="../dist/js/x-editable/ace-editable.min.js"></script>
		<script src="../dist/js/jquery.maskedinput.min.js"></script>
		<script src="../dist/js/bootbox.min.js"></script>
		<script src="../dist/js/date-time/bootstrap-datepicker.min.js"></script>
		<script src="../dist/js/date-time/bootstrap-timepicker.min.js"></script>
		<script src="../dist/js/date-time/moment.min.js"></script>
		<script src="../dist/js/date-time/daterangepicker.min.js"></script>
		<script src="../dist/js/date-time/bootstrap-datetimepicker.min.js"></script>
		
		<!-- script de las tablas -->
		<script src="../dist/js/jqGrid/jquery.jqGrid.min.js"></script>
		<script src="../dist/js/jqGrid/i18n/grid.locale-en.js"></script>
		<script src="../dist/js/dataTables/jquery.dataTables.min.js"></script>
		<script src="../dist/js/dataTables/jquery.dataTables.bootstrap.min.js"></script>
		<script src="../dist/js/dataTables/dataTables.tableTools.min.js"></script>
		<script src="../dist/js/dataTables/dataTables.colVis.min.js"></script>

		<!-- ace scripts -->
		<script src="../dist/js/ace-elements.min.js"></script>
		<script src="../dist/js/ace.min.js"></script>
		<script src="../dist/js/lockr.min.js"></script>
		<script src="../dist/js/sweetalert.min.js"></script>
		<script src="../dist/js/jquery.blockUI.js"></script>
		<script src="../dist/js/x2js.min.js"></script>
		<script src="../dist/js/forge.min.js"></script>	
	</body>
</html>

app.controller('loginController', function($scope, $route, $http, $location, $timeout, $interval, $window) {
	
	$(function() {
		// estilos select
		$(".select2").css({
			width: '100%',
		    allow_single_deselect: true,
		    no_results_text: "No se encontraron resultados",
		    }).select2().on("change", function (e) {
			$(this).closest('form').validate().element($(this));
	    });

		$("#select_cargo").select2({
		  allowClear: true 
		});
		// fin
		
		$('#form_proceso').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				txt_nombre: {
					required: true				
				},
				txt_clave: {
					required: true				
				}			
			},
			messages: {
				txt_nombre: {
					required: "Por favor, Digíte nombre de usuario"
				},
				txt_clave: {
					required: "Por favor, Digíte password / clave"
				}			
			},
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
				$(e).remove();
			},
			errorPlacement: function (error, element) {
				if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
					var controls = element.closest('div[class*="col-"]');
					if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
					else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
				}
				else if(element.is('.select2')) {
					error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
				}
				else if(element.is('.chosen-select')) {
					error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
				}
				else error.insertAfter(element.parent());
			},

			submitHandler: function (form) {
				var form = $("#form_proceso");
				$.ajax({
					url:'app.php',
					type:'POST',
					dataType:'json',
					data:{consultar_login_user:'',txt_nombre:$('#txt_nombre').val(),txt_clave:$('#txt_clave').val(), ingreso:$('#select_cargo').val()},
					success:function(data) {
						if (data['status'] == 'ok_administrador') {
							$.blockUI({ css: { 
					            border: 'none', 
					            padding: '10px',
					            backgroundColor: '#000', 
					            '-webkit-border-radius': '10px', 
					            '-moz-border-radius': '10px', 
					            opacity: 0.5, 
					            color: '#fff' 
					        	},
					            message: '<h4><img style="width:100px;border-radius: 50%;" src="../data/fotos_usuario/imagenes/'+data['imagen']+'" />     BIENVENIDO: <span>'+data['name']+'</h4>',
					    	});

					    	$interval(function() {
					    		$.unblockUI();
					    		Lockr.set('users', data['privilegio']);
					    		$window.location = '../#/';	
					    	}, 2000);
						};
						if (data['status'] == 'ok_director') {
							$.blockUI({ css: { 
					            border: 'none', 
					            padding: '10px',
					            backgroundColor: '#000', 
					            '-webkit-border-radius': '10px', 
					            '-moz-border-radius': '10px', 
					            opacity: 0.5, 
					            color: '#fff' 
					        	},
					            message: '<h4><img style="width:100px;border-radius: 50%;" src="../data/directores/imagenes/defaul.jpg" />     BIENVENIDO: <span>'+data['name']+'</h4>',
					    	});

					    	$interval(function() {
					    		$.unblockUI();
					    		Lockr.set('users', data['privilegio']);
					    		$window.location = '../directores/#/';	
					    	}, 2000);
						}
						if (data['status'] == 'error') {
							$.blockUI({ css: { 
					            border: 'none', 
					            padding: '10px',
					            backgroundColor: '#000', 
					            '-webkit-border-radius': '10px', 
					            '-moz-border-radius': '10px', 
					            opacity: 0.5, 
					            color: '#fff' 
					        	},
					            message: '<h4><img style="width:100px;border-radius: 50%;" src="../data/fotos_usuario/imagenes/error.jpg" />     DATOS INCORRECTOS</h4>',
					    	});
					    	$interval(function() {	
					    		$.unblockUI();
					    	}, 1000);
						};
						// if (data['status'] != 'ok' && data['status'] != 'error') {
						// 	$.gritter.add({
						// 		title: '<span>Información Mensaje</span>',
						// 		text: '	<span class="fa fa-shield"></span>'
						// 					+' <span class="text-danger">ERROR PROCESO AUTENTIFICACIÓN<BR></span>'
						// 				+'<span class="fa fa-ban fa-stack-2x text-danger"></span>',
						// 		image: '../dist/avatars/avatar1.png', 
						// 		sticky: false,
						// 		time: 3000,												
						// 	});	
						// };
					}
				});
			},
			invalidHandler: function (form) {
				console.log('proceso invalido'+form);
			}
		});
	});
});
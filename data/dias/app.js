angular.module('scotchApp').controller('diasController', function ($scope, $route) {

	$scope.$route = $route;
	jQuery(function($) {
		//para la fecha del calendario
		$(".datepicker").datepicker({ 
			format: "yyyy-mm-dd",
	        autoclose: true
		}).datepicker("setDate","today");
		// fin

		// reset formularios
		function reset_form() {
			$(".datepicker").datepicker({ 
				format: "yyyy-mm-dd",
		        autoclose: true
			}).datepicker("setDate","today");

			$('#inicio_facturero').val(0);
			$('#finaliza_facturero').val(0);
			$('#num_items').val(0);
			$('#autorizacion').val('');
		}
		// fin

		// cargar ultimo codigo
		$('#btn_abrir').click(function() {
			reset_form();
			$('#btn_0').attr('disabled', false);
			$("#btn_0").text("");
	    	$("#btn_0").append("<i class='ace-icon fa fa-save'></i> Guardar");
		});
		// fin

		// guardar formulario
		$('#btn_0').click(function() {
			var respuesta = $('#form_registro').valid();

			if (respuesta == true) {
				var formulario = $("#form_registro").serialize();
				var texto = ($("#btn_0").text()).trim();

				if(texto == "Guardar") {
					var oper = "add";
					$.ajax({
				        url: "data/dias/app.php",
				        data: formulario + "&oper=" + oper,
				        type: "POST",
				        success: function (data) {
				        	var val = data;
				        	if(data == '1') {
				        		$.gritter.add({
									title: 'Mensaje',
									text: 'Registro Agregado correctamente <i class="ace-icon fa fa-spinner fa-spin green bigger-125"></i>',
									time: 1000				
								});

								$('#btn_0').attr('disabled', true);
								reset_form();
								jQuery("#grid-table").jqGrid().trigger("reloadGrid");
								$('#myModal').modal('hide');
					    	}                                                
				        },
				        error: function (xhr, status, errorThrown) {
					        alert("Hubo un problema!");
					        console.log("Error: " + errorThrown);
					        console.log("Status: " + status);
					        console.dir(xhr);
				        }
				    });
				} else {
					if(texto == "Modificar") {
						var oper = "edit";
						$.ajax({
					        url: "data/dias/app.php",
					        data: formulario + "&oper=" + oper,
					        type: "POST",
					        success: function (data) {
					        	var val = data;
					        	if(data == '2') {
					        		$.gritter.add({
										title: 'Mensaje',
										text: 'Registro Modificado correctamente <i class="ace-icon fa fa-spinner fa-spin green bigger-125"></i>',
										time: 1000				
									});

									$('#btn_0').attr('disabled', true);
									reset_form();
									jQuery("#grid-table").jqGrid().trigger("reloadGrid");
									$('#myModal').modal('hide');
						    	}                                               
					        },
					        error: function (xhr, status, errorThrown) {
						        alert("Hubo un problema!");
						        console.log("Error: " + errorThrown);
						        console.log("Status: " + status);
						        console.dir(xhr);
					        }
					    });
					}	
				}
			}
		});
	})

	
	jQuery(function($) {
		var grid_selector = "#grid-table";
	    var pager_selector = "#grid-pager";

	    //cambiar el tamaño para ajustarse al tamaño de la página
	    $(window).on('resize.jqGrid', function () {
	        $(grid_selector).jqGrid('setGridWidth', $(".page-content").width());
	    });
	    //cambiar el tamaño de la barra lateral collapse/expand
	    var parent_column = $(grid_selector).closest('[class*="col-"]');
	    $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
	        if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
	            //para dar tiempo a los cambios de DOM y luego volver a dibujar!!!
	            setTimeout(function() {
	                $(grid_selector).jqGrid('setGridWidth', parent_column.width());
	            }, 0);
	        }
	    });

	    jQuery(grid_selector).jqGrid({
	        url: 'data/dias/xml_dias.php',
	        autoencode: false,
	        datatype: "xml",
			colNames:['ID','LUNES','MARTES','MIERCOLES','JUVES','VIERNES','SÁBADO','DOMINGO','FECHA CREACIÓN'],
			colModel:[
				{name:'id',index:'id', width:60, sorttype:"int", editable: true, hidden: true, editoptions: {readonly: 'readonly'}},
	            {name:'lunes',index:'lunes',width:'', editable:true, editoptions:{size:"20",maxlength:"30"}, editrules: {required: true}},
	            {name:'martes',index:'martes',width:'', editable:true, editoptions:{size:"20",maxlength:"30"}, editrules: {required: true}},
	            {name:'miercoles',index:'miercoles',width:'', editable:true, editoptions:{size:"20",maxlength:"30"}, editrules: {required: true}},
	            {name:'jueves',index:'jueves',width:'', editable:true, editoptions:{size:"20",maxlength:"30"}, editrules: {required: true}},
	            {name:'viernes',index:'viernes',width:'', editable:true, editoptions:{size:"20",maxlength:"30"}, editrules: {required: true}},
	            {name:'sabado',index:'sabado',width:'', editable:true, editoptions:{size:"20",maxlength:"30"}, editrules: {required: true}},
	            {name:'domingo',index:'domingo',width:'', editable:true, editoptions:{size:"20",maxlength:"30"}, editrules: {required: true}},
	            {name:'fecha_creacion',index:'fecha_creacion', width:'', editable: true, search:false, hidden:true, editoptions:{size:"20",maxlength:"30",readonly: 'readonly'}}
			],
	        rownumbers: true,
	        rowNum:10,
	        rowList:[10,20,30],
	        pager : pager_selector,
	        height: 330,
	        sortname: 'id',
	        sortorder: 'asc',
	        altRows: true,
	        multiselect: false,
	        multiboxonly: false,
	        viewrecords : true,
	        loadComplete : function() {
	            var table = this;
	            setTimeout(function(){
	                styleCheckbox(table);
	                updateActionIcons(table);
	                updatePagerIcons(table);
	                enableTooltips(table);
	            }, 0);
	        },
	        ondblClickRow: function(rowid) {     	            	            
	            var gsr = jQuery(grid_selector).jqGrid('getGridParam','selrow');                                              
            	var ret = jQuery(grid_selector).jqGrid('getRowData',gsr);
            	var id = ret.id;

				$('#myModal').modal('show');
				$('#btn_0').attr('disabled', false);
				$("#btn_0").text("");
	    		$("#btn_0").append("<i class='ace-icon fa fa-edit'></i> Modificar");

				$('#id').val(ret.id);
				if(ret.lunes == 'SI') {
					$("#lunes").prop("checked", true);	
				} else {
					if (ret.lunes == 'NO') {
						$("#lunes").prop("checked",false);	
					}
				}

				if(ret.martes == 'SI') {
					$("#martes").prop("checked", true);	
				} else {
					if (ret.martes == 'NO') {
						$("#martes").prop("checked",false);	
					}
				}

				if(ret.miercoles == 'SI') {
					$("#miercoles").prop("checked", true);	
				} else {
					if (ret.miercoles == 'NO') {
						$("#miercoles").prop("checked",false);	
					}
				}

				if(ret.jueves == 'SI') {
					$("#jueves").prop("checked", true);	
				} else {
					if (ret.jueves == 'NO') {
						$("#jueves").prop("checked",false);	
					}
				}

				if(ret.viernes == 'SI') {
					$("#viernes").prop("checked", true);	
				} else {
					if (ret.viernes == 'NO') {
						$("#viernes").prop("checked",false);	
					}
				}

				if(ret.sabado == 'SI') {
					$("#sabado").prop("checked", true);	
				} else {
					if (ret.sabado == 'NO') {
						$("#sabado").prop("checked",false);	
					}
				}

				if(ret.domingo == 'SI') {
					$("#domingo").prop("checked", true);	
				} else {
					if (ret.domingo == 'NO') {
						$("#domingo").prop("checked",false);	
					}
				}            
	        },
	        editurl: "data/dias/app.php",
	        caption: "LISTA ACTIVAR DÍAS"
	    });
	    $(window).triggerHandler('resize.jqGrid');//cambiar el tamaño para hacer la rejilla conseguir el tamaño correcto

	    function aceSwitch( cellvalue, options, cell ) {
	        setTimeout(function(){
	            $(cell) .find('input[type=checkbox]')
	            .addClass('ace ace-switch ace-switch-5')
	            .after('<span class="lbl"></span>');
	        }, 0);
	    }
	    //enable datepicker
	    function pickDate( cellvalue, options, cell ) {
	        setTimeout(function(){
	            $(cell) .find('input[type=text]')
	            .datepicker({format:'yyyy-mm-dd' , autoclose:true}); 
	        }, 0);
	    }
	    //navButtons
	    jQuery(grid_selector).jqGrid('navGrid',pager_selector,
	    {   //navbar options
	        edit: false,
	        editicon : 'ace-icon fa fa-pencil blue',
	        add: false,
	        addicon : 'ace-icon fa fa-plus-circle purple',
	        del: true,
	        delicon : 'ace-icon fa fa-trash-o red',
	        search: false,
	        searchicon : 'ace-icon fa fa-search orange',
	        refresh: true,
	        refreshicon : 'ace-icon fa fa-refresh green',
	        view: false,
	        viewicon : 'ace-icon fa fa-search-plus grey'
	    },
	    {
	    	closeAfterEdit: true,
	        recreateForm: true,
	        viewPagerButtons: false,
	        overlay:false,
	        beforeShowForm : function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	            style_edit_form(form);
	        },
	        afterSubmit: function(response)  {
                retorno = response.responseText;
     //            if(retorno == '2'){
     //            	$.gritter.add({
					// 	title: 'Mensaje',
					// 	text: 'Registro Modificado correctamente <i class="ace-icon fa fa-spinner fa-spin green bigger-125"></i>',
					// 	time: 1000				
					// });
     //            } else {
     //            	if(retorno == '3') {
     //            		$("#nombre").val("");
	    //             	return [false,"Error.. El cargo ya fue agregado"];
	    //             }
     //            }
                return [true,'',retorno];
            },
	    },
	    {
	        closeAfterAdd: true,
	        recreateForm: true,
	        viewPagerButtons: false,
	        overlay:false,
	        beforeShowForm : function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
	            .wrapInner('<div class="widget-header" />')
	            style_edit_form(form);
	        },
	        afterSubmit: function(response)  {
                retorno = response.responseText;
     //            if(retorno == '1') {
     //            	$.gritter.add({
					// 	title: 'Mensaje',
					// 	text: 'Registro Agregado correctamente <i class="ace-icon fa fa-spinner fa-spin green bigger-125"></i>',
					// 	time: 1000				
					// });
     //            } else {
     //            	if(retorno == '3') {
     //            		$("#nombre").val("");
	    //             	return [false,"Error.. La Forma de pago ya fue agregada"];
	    //             }
     //            }
                return [true,'',retorno];
            },
	    },
	    {
	        //delete record form
	        recreateForm: true,
	        overlay:false,
	        beforeShowForm : function(e) {
	            var form = $(e[0]);
	            if(form.data('styled')) return false;
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	            style_delete_form(form);
	            form.data('styled', true);
	        },
	        onClick : function(e) {
	      
	        }
	    },
	    {
	        recreateForm: true,
	        overlay:false,
	        afterShowSearch: function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
	            style_search_form(form);
	        },
	        afterRedraw: function(){
	            style_search_filters($(this));
	        },
	        multipleSearch: false,
	        overlay: false,
	        sopt: ['eq', 'cn'],
	        defaultSearch: 'eq', 
	      },
	    {
	        recreateForm: true,
	        overlay:false,
	        beforeShowForm: function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
	        }
	    }
	)

	    function style_edit_form(form) {
	        //enable datepicker on "sdate" field and switches for "stock" field
	        form.find('input[name=sdate]').datepicker({format:'yyyy-mm-dd' , autoclose:true})
	        
	        form.find('input[name=stock]').addClass('ace ace-switch ace-switch-5').after('<span class="lbl"></span>');
	                
	        //update buttons classes
	        var buttons = form.next().find('.EditButton .fm-button');
	        buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
	        buttons.eq(0).addClass('btn-primary').prepend('<i class="ace-icon fa fa-check"></i>');
	        buttons.eq(1).prepend('<i class="ace-icon fa fa-times"></i>')
	        
	        buttons = form.next().find('.navButton a');
	        buttons.find('.ui-icon').hide();
	        buttons.eq(0).append('<i class="ace-icon fa fa-chevron-left"></i>');
	        buttons.eq(1).append('<i class="ace-icon fa fa-chevron-right"></i>');       
	    }

	    function style_delete_form(form) {
	        var buttons = form.next().find('.EditButton .fm-button');
	        buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
	        buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
	        buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>')
	    }
	    
	    function style_search_filters(form) {
	        form.find('.delete-rule').val('X');
	        form.find('.add-rule').addClass('btn btn-xs btn-primary');
	        form.find('.add-group').addClass('btn btn-xs btn-success');
	        form.find('.delete-group').addClass('btn btn-xs btn-danger');
	    }
	    function style_search_form(form) {
	        var dialog = form.closest('.ui-jqdialog');
	        var buttons = dialog.find('.EditTable')
	        buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'ace-icon fa fa-retweet');
	        buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'ace-icon fa fa-comment-o');
	        buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'ace-icon fa fa-search');
	    }
	    
	    function beforeDeleteCallback(e) {
	        var form = $(e[0]);
	        if(form.data('styled')) return false;
	        
	        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	        style_delete_form(form);
	        
	        form.data('styled', true);
	    }
	    
	    function beforeEditCallback(e) {
	        var form = $(e[0]);
	        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	        style_edit_form(form);
	    }

	    function styleCheckbox(table) { }
	    
	    function updateActionIcons(table) { }
	    
	    //replace icons with FontAwesome icons like above
	    function updatePagerIcons(table) {
	        var replacement = 
	            {
	            'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
	            'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
	            'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
	            'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
	        };
	        $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
	            var icon = $(this);
	            var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
	            
	            if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
	        })
	    }

	    function enableTooltips(table) {
	        $('.navtable .ui-pg-button').tooltip({container:'body'});
	        $(table).find('.ui-pg-div').tooltip({container:'body'});
	    }

	    $(document).one('ajaxloadstart.page', function(e) {
	        $(grid_selector).jqGrid('GridUnload');
	        $('.ui-jqdialog').remove();
	    });
	});
});
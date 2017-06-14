app.controller('repositorioController', function ($scope, $interval) {

	// procesos tab
	$scope.tab = 1;

    $scope.setTab = function(newTab) {
      $scope.tab = newTab;
    };

    $scope.isSet = function(tabNum) {
      return $scope.tab == tabNum;
    };
    // fin

	jQuery(function($) {
		// funcion validar solo numeros
		function ValidNum() {
		    if (event.keyCode < 48 || event.keyCode > 57) {
		        event.returnValue = false;
		    }
		    return true;
		}
		// fin

		// funciones inicio
		$("#txt_clave").focus();
		$("#txt_clave").keypress(ValidNum);
		// fin

		// consultar kardex
		$('#btn_consultar').click(function() {
			var formulario = $("#form_repositorio").serialize();
			$('#dynamic-table').dataTable().fnClearTable();

			if ($("#txt_clave").val() == "") {
	            $("#txt_clave").focus();
	            $.gritter.add({
					title: 'Error... Ingrese una Clave de Acceso',
					class_name: 'gritter-error gritter-center',
					time: 1000,
				});
	        } else {
	        	if ($("#txt_clave").val().length != 49) {
		            $("#txt_clave").focus();
		            $.gritter.add({
						title: 'Error... Mínimo 49 Caracteres',
						class_name: 'gritter-error gritter-center',
						time: 1000,
					});
		        } else {
		        	$.ajax({
						url: 'data/repositorio/app.php',
						type: 'post',
						data: formulario + "&consultar_sri=" + 'consultar_sri',
						dataType: 'json',
						beforeSend: function() {
		                	$.blockUI({ css: { 
					            border: 'none', 
					            padding: '15px', 
					            backgroundColor: '#000', 
					            '-webkit-border-radius': '10px', 
					            '-moz-border-radius': '10px', 
					            opacity: .5, 
					            color: '#fff' 
					        	},
					            message: '<h3>Consultando, Por favor espere un momento    ' + '<i class="fa fa-spinner fa-spin"></i>' + '</h3>'
					    	});
		                },
						success: function(response) {
							$.unblockUI();
							var val = response;
							if (response == 0) {
								$.gritter.add({
									title: 'Error... Clave de Acceso Incorrecta',
									class_name: 'gritter-error gritter-center',
									time: 1000,
								});
								$("#txt_clave").focus();
							} else {
								if (response == 1) {
									$.gritter.add({
										title: 'Clave de Acceso Correcta',
										class_name: 'gritter-success gritter-center',
										time: 1000,
									});
									$("#txt_clave").val('');
									jQuery("#table").jqGrid().trigger("reloadGrid");
								} else {
									if (response == 2) {
										$.gritter.add({
											title: 'Error... La Clave de Acceso ya fue Registrada',
											class_name: 'gritter-error gritter-center',
											time: 1000,
										});
										$("#txt_clave").focus();
										$("#txt_clave").val('');
									}
								}	
							}
						}
					});
		        }
	        }
		});
		// fin

		// abrir en una nueva ventana reporte factura
		$scope.methodopdf = function(id) { 
			var myWindow = window.open('data/reportes/factura_electronica.php?id='+id,'popup','width=900,height=650');
		} 
		// fin

		// descargar xml
		$scope.methodoxml = function(id) { 
			window.open("data/repositorio/app.php?id="+id+"&fn=2"); 
			console.log('test');
			// var myWindow = window.open('data/reportes/anticipos.php?id='+id,'popup','width=900,height=650');
		} 
		// fin
	});

	// tabla local
	jQuery(function($) {
	    var grid_selector = "#table";
	    var pager_selector = "#pager";
	    
	    //cambiar el tamaño para ajustarse al tamaño de la página
	    $(window).on('resize.jqGrid', function () {        
	        $(grid_selector).jqGrid( 'setGridWidth', $(".widget-main").width());
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

	    // buscador facturas
	    jQuery(grid_selector).jqGrid({	        
	        datatype: "xml",
	        url: 'data/repositorio/xml_repositorio.php',       
	        colNames: ['ID','N° DOCUMENTO','FECHA EMISIÓN','CLAVE ACCESO','N° AUTORIZACIÓN','FECHA AUTORIZACIÓN','TOTAL FACTURA','PDF','XML'],
	        colModel:[      
	            {name:'id',index:'id', frozen:true, align:'left', search:false, hidden: true},
	            {name:'num_documento',index:'num_documento',frozen : true,align:'left',search:true},
	            {name:'fecha_emision',index:'fecha_emision',frozen : true,align:'left',search:true},
	            {name:'clave_acceso',index:'clave_acceso',frozen : true,align:'left',search:false,width:390},
	            {name:'num_autorizacion',index:'num_autorizacion',frozen : true,align:'left',search:false, hidden: false,width:300},
	            {name:'fecha_autorizacion',index:'fecha_autorizacion',frozen : true,align:'left',search:false, hidden: false,width:190},
	            {name:'total_factura',index:'total_factura',frozen : true,align:'center',search:false, hidden: false, formatter: 'number',width: ''},
	            {name:'pdf',index:'pdf',frozen : true,align:'center',search:false, hidden: false,width:70},
	            {name:'xml',index:'xml',frozen : true,align:'center',search:false, hidden: false,width:70},
	        ], 
	        rownumbers: true,         
	        rowNum: 10,       
	        width: 'auto',
	        shrinkToFit :false,
	        height: 'auto',
	        rowList: [15,30,45],
	        pager: pager_selector,        
	        sortname: 'id',
	        sortorder: 'asc',
	        altRows: true,
	        multiselect: false,
	        multiboxonly: true,
	        viewrecords : true,
	        footerrow: true,
    		userDataOnFooter: true,
	        loadComplete : function() {
	        	var table = this;
	            setTimeout(function(){
	                styleCheckbox(table);
	                updateActionIcons(table);
	                updatePagerIcons(table);
	                enableTooltips(table);
	            }, 0);
	        	var colSum = $(this).jqGrid('getCol','total_factura',false,'sum');
	        	$(this).jqGrid('footerData','set',{num_documento:'TOTAL', total_factura:colSum});
	        },
	        gridComplete: function() {
	        	var ids = jQuery(grid_selector).jqGrid('getDataIDs');
				for(var i = 0;i < ids.length;i++) {
					var id_repositorio = ids[i];
					pdf = "<a onclick=\"angular.element(this).scope().methodopdf('"+id_repositorio+"')\" title='Factura Electrónica' ><i class='fa fa-file-pdf-o red2' style='cursor:pointer; cursor: hand'> PDF</i></a>"; 					
					xml = "<a onclick=\"angular.element(this).scope().methodoxml('"+id_repositorio+"')\" title='Descargar XML' ><i class='fa fa-file-code-o' style='cursor:pointer; cursor: hand'> XML</i></a>"; 					
					jQuery(grid_selector).jqGrid('setRowData',ids[i],{pdf: pdf, xml: xml});
				}		
			},
	        ondblClickRow: function(rowid) {     	            	            
	            var gsr = jQuery(grid_selector).jqGrid('getGridParam','selrow');                                              
            	var ret = jQuery(grid_selector).jqGrid('getRowData',gsr);
            	var id = ret.id;
	        },
	        
	        caption: "LISTA FACTURAS ELECTRÓNICAS"
	    });

	    $(window).triggerHandler('resize.jqGrid');//cambiar el tamaño para hacer la rejilla conseguir el tamaño correcto

	    function aceSwitch( cellvalue, options, cell ) {
	        setTimeout(function(){
	            $(cell) .find('input[type=checkbox]')
	            .addClass('ace ace-switch ace-switch-5')
	            .after('<span class="lbl"></span>');
	        }, 0);
	    }	    	   

	    jQuery(grid_selector).jqGrid('navGrid',pager_selector,
	    {   
	        edit: false,
	        editicon : 'ace-icon fa fa-pencil blue',
	        add: false,
	        addicon : 'ace-icon fa fa-plus-circle purple',
	        del: false,
	        delicon : 'ace-icon fa fa-trash-o red',
	        search: false,
	        searchicon : 'ace-icon fa fa-search orange',
	        refresh: true,
	        refreshicon : 'ace-icon fa fa-refresh green',
	        view: false,
	        viewicon : 'ace-icon fa fa-search-plus grey'
	    },
	    {	        
	        recreateForm: true,
	        beforeShowForm : function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	            style_edit_form(form);
	        }
	    },
	    {
	        closeAfterAdd: true,
	        recreateForm: true,
	        viewPagerButtons: false,
	        beforeShowForm : function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
	            .wrapInner('<div class="widget-header" />')
	            style_edit_form(form);
	        }
	    },
	    {
	        recreateForm: true,
	        beforeShowForm : function(e) {
	            var form = $(e[0]);
	            if(form.data('styled')) return false;      
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
	            style_delete_form(form); 
	            form.data('styled', true);
	        },
	        onClick : function(e) {}
	    },
	    {
	        recreateForm: true,
	        afterShowSearch: function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
	            style_search_form(form);
	        },
	        afterRedraw: function(){
	            style_search_filters($(this));
	        },

	        //multipleSearch: true
	        overlay: false,
	        sopt: ['eq', 'cn'],
            defaultSearch: 'eq',            	       
	      },
	    {
	        //view record form
	        recreateForm: true,
	        beforeShowForm: function(e) {
	            var form = $(e[0]);
	            form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
	        }
	    })	    
	    function style_edit_form(form) {
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

	    function styleCheckbox(table) {}
	    
	    function updateActionIcons(table) {}
	    
	    function updatePagerIcons(table) {
	        var replacement = {
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
	// fin
});
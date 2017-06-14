app.controller('pedidosController', function ($scope, $route) {

	$scope.$route = $route;

	/*jqgrid table buscador*/    
	jQuery(function($) {
		// abrir en una nueva ventana reporte facturas
		$scope.methodspdf = function(id) { 
			var myWindow = window.open('../data/reportes/proforma.php?hoja=A5&id='+id,'popup','width=900,height=650');
		} 
		// fin

		$scope.methodoshare = function(id) {
			var $exampleModal = $("#myModal2"),
		    $exampleModalClose = $(".modal-header button");

		    $exampleModal.on("shown.bs.modal", function() {
		        document.activeElement.blur();
		        
		        $('#link').val('http://localhost/facturacion/data/reportes/proforma.php?id='+id);
		        $('#link').focus();
		        $('#link').select();
		    });
		}
		
	    var grid_selector = "#grid-table";
	    var pager_selector = "#grid-pager";
	    
	    //cambiar el tamaño para ajustarse al tamaño de la página
	    $(window).on('resize.jqGrid', function () {
	        $(grid_selector).jqGrid('setGridWidth', $(".page-content").width());
	    });

	    var parent_column = $(grid_selector).closest('[class*="col-"]');
		$(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
			if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
				//setTimeout is for webkit only to give time for DOM changes and then redraw!!!
				setTimeout(function() {
					$(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
				}, 0);
			}
	    })

	    // buscador facturas
	    jQuery(grid_selector).jqGrid({	 
	    	datatype: "xml",
		    url: 'data/pedidos/xml_proformas.php',         
	        autoencode: false,
	        colNames: ['ID','IDENTIFICACIÓN','CLIENTE','DIRECCIÓN','FECHA EMISIÓN','TOTAL','PDF'],
	        colModel:[ 
			    {name:'id',index:'id', frozen:true, align:'left', search:false, hidden: true},   
	            {name:'C.identificacion',index:'C.identificacion', frozen:true, align:'left', search:true, hidden: false},
	            {name:'C.nombres_completos',index:'C.nombres_completos',frozen : true,align:'left', search:true, width: '300px'},
	            {name:'direccion',index:'direccion',frozen : true, hidden: true, align:'left', search:false,width: '300px'},
	            {name:'fecha_emision',index:'fecha_emision',frozen : true, align:'left', search:false,width: '120px'},
	            {name:'total_pagar',index:'total_pagar',frozen : true, align:'left', search:false,width: '100px'},
	            {name:'accion', index:'accion', editable: false, hidden: false, frozen: true, search:false, editrules: {required: true}, align: 'center', width: '80px'},
	            // {name:'share', index:'share', editable: false, hidden: false, frozen: true, editrules: {required: true}, align: 'center', width: '100px'}
	        ],          
	        rowNum: 10,       
	        width:600,
	        shrinkToFit: false,
	        height:330,
	        rowList: [10,20,30],
	        pager: pager_selector,        
	        sortname: 'id',
	        sortorder: 'asc',
	        altRows: true,
	        multiselect: false,
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
	        gridComplete: function() {
				var ids = jQuery(grid_selector).jqGrid('getDataIDs');
				for(var i = 0;i < ids.length;i++) {
					var id_proforma = ids[i];
					pdf = "<a onclick=\"angular.element(this).scope().methodspdf('"+id_proforma+"')\" title='Reporte Proforma' ><i class='fa fa-file-pdf-o red2' style='cursor:pointer; cursor: hand'> PDF</i></a>"; 
					share = "<a onclick=\"angular.element(this).scope().methodoshare('"+id_proforma+"')\" title='Compartir Anticipos' data-toggle='modal' data-target='#myModal2' ><i class='fa fa-share-alt' style='cursor:pointer; cursor: hand'> SHARE</i></a>"; 					
					jQuery(grid_selector).jqGrid('setRowData',ids[i],{accion:pdf});
				}	
			},
	        ondblClickRow: function(rowid) {     	            	            
	            var gsr = jQuery(grid_selector).jqGrid('getGridParam','selrow');                                              
            	var ret = jQuery(grid_selector).jqGrid('getRowData',gsr);
            	$("#table").jqGrid("clearGridData", true);

            	$.ajax({
					url: 'data/proformas/app.php',
					type: 'post',
					data: {llenar_cabezera_proforma:'llenar_cabezera_proforma',id: ret.id},
					dataType: 'json',
					success: function (data) {
						$('#id_proforma').val(data.id_proforma);
						$('#fecha_actual').val(data.fecha_actual);
						$('#hora_actual').val(data.hora_actual);
						$('#id_cliente').val(data.id_cliente);
						$('#ruc').val(data.identificacion);
						$('#cliente').val(data.nombres_completos);
						$('#direccion').val(data.direccion);
						$('#telefono').val(data.telefono2);
						$('#correo').val(data.correo);
						$("#select_tipo_precio").select2('val', data.tipo_precio).trigger("change");

						$('#subtotal').val(data.subtotal);
						$('#tarifa').val(data.tarifa);
						$('#tarifa_0').val(data.tarifa0);
						$('#iva').val(data.iva);
						$('#otros').val(data.descuento);
						$('#total_pagar').val(data.total_pagar);

						if(data.estado == "2") {
	                        $("#estado").append($("<h3>").text("Anulada"));
	                        $("#estado h3").css("color","red");
	                        $("#btn_2").attr("disabled", true);
	                    } else {
	                        $("#estado h3").remove();
	                        $("#btn_2").attr("disabled", false);
	                    }
					}
				});

				$.ajax({
					url: 'data/proformas/app.php',
					type: 'post',
					data: {llenar_detalle_proforma:'llenar_detalle_proforma',id: ret.id},
					dataType: 'json',
					success: function (data) {
						var tama = data.length;
						var descuento = 0;
	                    var desc = 0;
	                    var precio = 0;
	                    var multi = 0;
	                    var flotante = 0;
	                    var resultado = 0;
	                    var total = 0;
	                    var suma_total = 0;

						for (var i = 0; i < tama; i = i + 9) {
							desc = data[i + 5];
                            precio = (parseFloat(data[i + 4])).toFixed(3);
                            multi = (parseFloat(data[i + 3]) * parseFloat(precio)).toFixed(3);
                            descuento = ((multi * parseFloat(desc)) / 100);
                            flotante = parseFloat(descuento);
                            resultado = (Math.round(flotante * Math.pow(10,2)) / Math.pow(10,2)).toFixed(3);
                            total = (multi - resultado).toFixed(3);

							var datarow = {
                                id: data[i], 
                                codigo: data[i + 1], 
                                detalle: data[i + 2], 
                                cantidad: data[i + 3], 
                                precio_u: precio, 
                                descuento: desc,
                                cal_des: resultado, 
                                total: total,
                                iva: data[i + 7],
                                incluye: data[i + 8]
                            };

                            jQuery("#table").jqGrid('addRowData',data[i],datarow);
                            suma_total = suma_total + parseFloat(data[i + 3]);
						}
						var filas = jQuery("#table").jqGrid("getRowData");
	                    $("#items").val(filas.length);
	                    $("#num").val(suma_total);
					}
				});
 

				$('#myModal').modal('hide'); 
		        $('#btn_0').attr('disabled', true);
		        $("#btn_3").attr("disabled", false);           
	        },
	        caption: "LISTA PEDIDOS"
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
	    {   //navbar options
	        edit: false,
	        editicon : 'ace-icon fa fa-pencil blue',
	        add: false,
	        addicon : 'ace-icon fa fa-plus-circle purple',
	        del: false,
	        delicon : 'ace-icon fa fa-trash-o red',
	        search: true,
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
	        afterShowSearch: function(e){
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
	        beforeShowForm: function(e){
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
	// fin

	
});
// $(function(){
	
	// loadGridCosta();
	// redimGridHidden('xgridCostas',790,250,2);	
// });

//function loadGridCosta(){
Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button(); 
	
	cargarCosta();
	
	eventPagoTupa('C');
	loadAutocomplete();
	//$("#txtTotPag").val('0.00').autoNumeric();
	//sumapagos();
 
});


function loadAutocomplete(){
	$("#nom_pago_diver").autocomplete({
		source: function( request, response ) {
		$.ajax({
		  url: urljs + "bandecostas/autocompletar",
		  dataType: "json",
		  data: {
			featureClass: "P",
			style: "full",
			maxRows: 10,
			name_startsWith: request.term
		  },
		  success: function( data ) {
			//Limpia
			  $('#txttipo').val('');
			  $('#txtsubtipo').val('');
		
			if(data!=null){				
				response( $.map( data.rows, function( item ) {
				  return {
					label: item.measure,
					value: item.measure,
					code:  item.name
				  }
				}));
			}
			else{
				response({});
                return;
			}
		  }
		});
		},
		minLength: 2,
		select: function( event, ui ) {
			datos = ui.item.code.split('|');			
			$('#txttipo').val(datos[0]);
			$('#txtsubtipo').val(datos[1]);
			$('#txtprecio').val(datos[3]);
      	},
	  	open : function(){
			var posPopup = $('#popup_popCosta').position();
			var posText = $('#nom_pago_diver').position();
        	$(".ui-autocomplete:visible").css({top: posPopup.top+posText.top+48,left: posPopup.left+posText.left+5});
    	}
	});
}



function eventPagoTupa(act){
	switch(act){
		case 'A':
			var grid = Ext.getCmp('xgridCostas');
			var storePagosTupa = grid.getStore();
		
			//if(storePagosTupa.data.length ==0 ){
				$('#divCalculo').show();
				$('#rowSaveTupa').show();
				$('#rowCancelTupa').show();
				$('#rowAddTupa').hide();
				$('#rowEditTupa').hide();
				$('#btnDelTupa').attr('disabled',true);
				$('#btnImpPag').attr('disabled',true);
				disableButton('#btnDelTupa');
				$("#actTupa").val('A');

			//}else{
				//infoMessage('','No se puede agregar mas registros');
			//}
		break;
		case 'E':
			var grid = Ext.getCmp('xgridCostas');
			var storePagosTupa = grid.getStore();
			
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = storePagosTupa.getAt(storePagosTupa.indexOf(sm[0]));
				//eventPagoTupa('A');
				$('#divCalculo').show();
				$('#rowSaveTupa').show();
				$('#rowCancelTupa').show();
				$('#rowAddTupa').hide();
				$('#rowEditTupa').hide();
				$('#btnDelTupa').attr('disabled',true);
				disableButton('#btnDelTupa');
				$("#actTupa").val('A');
				$('#btnImpPag').attr('disabled',false);
				
				$("#txttipo").val(rec.get('tipo'));
				$("#txtsubtipo").val(rec.get('subtipo'));		
				$("#nom_pago_diver").val(rec.get('conceptos'));				
				$("#txtprecio").val(rec.get('uit1'));
				$("#txtcantidad").val(rec.get('cantidad'));
				$("#txttotal").val(rec.get('monto')); 
				$("#txtidrecibo").val(rec.get('idrecibo')); 
				$("#actTupa").val('E');
				//sumapagos();
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
			$('#divCalculo').hide();
			$('#rowAddTupa').show();
			$('#rowEditTupa').show();
			$('#rowSaveTupa').hide();
			$('#rowCancelTupa').hide();
			$('#btnDelTupa').attr('disabled',false);
			enableButton('#btnDelTupa');
			$('#btnImpPag').attr('disabled',false);
			$("#txtidrecibo").val('');
			$("#txttipo").val('');
			$("#txtsubtipo").val('');
			$("#txtprecio").val('0.00');
			$("#txtcantidad").val('0');
			$("#txtprecio").val('0.00');
			$("#txttotal").val('0.00');
			//$('#cmbtipos option:first').attr('selected', true);
			$("#nom_pago_diver").val('');
		break;
		case 'S':
			var correcto = true;
			
			if($('#txttipo').val()==''){
				correcto = false;
				infoMessagex('Guardando','Debe seleccionar concepto','#nom_pago_diver');
			}
			else
			if($('#txtprecio').val()==''){
				correcto = false;
				infoMessagex('Guardando','Debe ingresar el monto','#txtprecio');
			}
			else
			if($('#txtcantidad').val()==''){
				correcto = false;
				infoMessagex('Guardando','Debe ingresar la cantidad','#txtcantidad');
			}
			// else
			// if($('#txttotal').val()=='0.00'){
				// correcto = false;
				// infoMessagex('Guardando','El total debe ser mayor que 0.00 ','#txttotal');
			// }
			
			if(correcto){
				$.ajax({     
					type: "POST",  
					url: "bandecostas/gcostas",
					data: $('#frmcostass').serializeObject(),     
					success: function(data) { 					
						
						infoMessage('Guardando',data);
						
						cargarCosta();
						//sumapagos();
						eventPagoTupa('C');				
					}
				});
			}
			sumapagos();			
			
		break;
		case 'D':
			
			
		var grid = Ext.getCmp('xgridCostas');
		var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	
		var idrecibo = "";
		
		
		if(selectedRecordsArray.length>0){
		
			
		 var showResult = function(btn){
			if(btn=='yes'){
				
				var idList = [];
				Ext.each(selectedRecordsArray, function (item) {
					idList.push(
						{
							idrecibo: item.data.idrecibo
							
						});
				});
		
				Ext.Ajax.request({
		            url: urljs + "bandecostas/eliminacosta",
		            method: "POST",
		            params: {json: JSON.stringify(idList)},
		            success: function(response){								
						cargarCosta();	
						//sumapagos();						
						},
		            failure: function(response, opts){
		            	infoMessage('Alerta','Error');
						}
					});
				}
			};
 		
 		confirmMessage('Alerta','Seguro de eliminar el concepto?',showResult);
		
		}
			
			
			
		break;
	}
}






function addRowsCostas(data){
	var grid = Ext.getCmp('xgridCostas');
	var store = grid.getStore();
	var r = Ext.create('Costas', {
	
		conceptos: data.conceptos,
		monto: data.monto,
		cantidad: data.cantidad,
		uit1: data.uit1,
		tipo: data.tipo,
		subtipo: data.subtipo,
		idrecibo: data.idrecibo
		
	});
	store.insert(store.data.length, r);
}


function calcImporte(idx){
	var grid = Ext.getCmp('xgridCostas'),
		rec = grid.store.getAt(idx);
	
	monto = parseFloat(rec.get('multi'));
	cantidad = parseFloat(rec.get('cant'));
	montotal = cantidad*monto;
	
	conceptos = rec.get('descripcion');
	uit1 = rec.get('uit')
	cantidad2 = rec.get('cant')
	
	if(cantidad>0)
	{
	rec.set('monto',montotal.toFixed(2));
	rec.set('conceptos',conceptos);
	rec.set('uit1',uit1);
	rec.set('cantidad',cantidad2);
	}
	
}


$('#txtcantidad').blur(function(){

    var precio=Number($('#txtprecio').val());
	var cantidad=Number($('#txtcantidad').val());
	var totalpago=precio*cantidad;
	
	$('#txttotal').val(Math.round((totalpago)*100)/100).autoNumeric();
});

function sumapagos(){
	var grid = Ext.getCmp('xgridCostas');
	var storePagosTupa = grid.getStore();
	var total_sum=0;
	//$("#txtTotPag").val('0.00').autoNumeric()
	
	if(storePagosTupa.data.length > 0){
		storePagosTupa.each(function (rec) { 
			//alert(rec.get('monto'));
			total_sum += Number(rec.get('monto').replace(',',''));	
			total_sum=Math.round(total_sum*100)/100;
			$("#txtTotPag").val(total_sum);
		});
	}
}

function cargarCosta(){
	
	$('#gridCostas').html('');
	
	var codigo=$('#txtcodigo').val();
	var expediente=$('#txtexpediente').val();
	
	
	Ext.define('Costas', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'conceptos'},
			{name: 'uit1'},
			{name: 'cantidad'},
			{name: 'monto'},
			{name: 'tipo'},
			{name: 'subtipo'},
			{name: 'uit'},
			{name: 'cant'},
			{name: 'multi'},
			{name: 'idrecibo'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
         model: 'Costas',
         data: []
    });
		
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridCostas',
		store: store,
		width: '800px',
		height: '140px',   
	
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "bandecostas/gridcostas",
						  method: "POST",
						  params: {codigo: codigo,expediente: expediente},
						  success: function(response){			
							  	var totalPago=0;
								
								data = Ext.JSON.decode(response.responseText);
								
								for(var i=0 ; i < data.length; i++){									
										addRowsCostas(data[i]);
										totalPago += parseFloat(data[i].monto);
										
								}
								$("#txtTotPag").val(totalPago.toFixed(2));
						  }
					});					
            	}
            }
    	},
	
        columns: [ 
        {
            text: 'Descripcion',
			flex:1,
			dataIndex: 'conceptos'
        },
		{
            text: 'UIT',
			width: 40,
			dataIndex: 'uit1'
        },
		{
            text: 'Cantidad',
			width: 60,
			dataIndex: 'cantidad'
        },
		{
            text: 'Monto',
			width: 60,
			dataIndex: 'monto'
        },
		{
            text: 'tipo',
			width: 60,
			dataIndex: 'tipo',
			hidden:true
        },{
            text: 'subtipo',
			width: 60,
			dataIndex: 'subtipo',
			hidden:true
        },{
            text: 'idrecibo',
			width: 60,
			dataIndex: 'idrecibo',
			hidden:true
        }],
    });
    
    grid.render('gridCostas');
	
}

/*
function eliminacostas(){
	var grid = Ext.getCmp('xgridCostas');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	
		var idrecibo = "";
		
		
		if(selectedRecordsArray.length>0){
		
			
		 var showResult = function(btn){
			if(btn=='yes'){
				
				var idList = [];
				Ext.each(selectedRecordsArray, function (item) {
					idList.push(
						{
							idrecibo: item.data.idrecibo
							
						});
				});
		
				Ext.Ajax.request({
		            url: urljs + "bandecostas/eliminacosta",
		            method: "POST",
		            params: {json: JSON.stringify(idList)},
		            success: function(response){
						
						//data = Ext.JSON.decode(response.responseText);
							  	
						
		            	grid.getStore().loadPage(grid.getStore().currentPage);	
						//addRowsCostas(data);
						sumapagos();
						//imprimeOpPdf();
												
						},
		            failure: function(response, opts){
		            	infoMessage('Alerta','Error');
						}
					});
				}
			};
 		
 		confirmMessage('Alerta','Seguro de eliminar el concepto?',showResult);
		
		}
		
}
*/

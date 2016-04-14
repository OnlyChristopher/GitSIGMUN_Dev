Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button(); 
	
	cargarCosta();
	
	eventPagoTupa('C');
	loadAutocomplete(); 
	
	var estadocosta=$('#xestadocosta').val();
	var estadogasto=$('#xestadogasto').val();
	
	// if(estadocosta==1)
		// if(estadogasto==1)
			// $('#divestado').hide();
			
		if(estadocosta==0 && estadogasto==0)		
			$('#divestado').show();
		else
			$('#divestado').hide();
	
	
	
});


function loadAutocomplete(){
	$("#nom_pago_diver").autocomplete({
		source: function( request, response ) {
		$.ajax({
		  url: urljs + "bandecostascontri/autocompletar",
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
			var posPopup = $('#popup_popCostacontri').position();
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
				$("#txtidcosta").val(rec.get('idcosta')); 
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
			$("#txtidcosta").val('');
			$("#txtidrecibo").val('');
			$("#txttipo").val('');
			$("#txtsubtipo").val('');
			//$("#txtprecio").val('0.00');
			$("#txtcantidad").val('0');
			$("#txtprecio").val('0.00');
			$("#txttotal").val('0.00');
			//$('#cmbtipos option:first').attr('selected', true);
			$("#nom_pago_diver").val('');
			
		break;
		case 'S':
			var correcto = true;
			
			if($('#txtexpediente').val()==''){
				correcto = false;
				infoMessagex('Guardando','Debe ingresar un expediente','#txtexpediente');
			}			
			else
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
			
			if(correcto){
				$.ajax({     
					type: "POST",  
					url: "bandecostascontri/gcostas",
					data: $('#frmcostascontri').serializeObject(),     
					success: function(response) { 	
						var datos=response.split('|');
						$('#xidrecibo').val(datos[0]);										
						infoMessage('Guardando',datos[1]);			
						cargarCosta();
						eventPagoTupa('C');
						$('#txtexpediente').attr('readonly', true);
						//$( "txtexpediente" ).removeClass( "caja" ).addClass( "cajaoff" );
						//disableButton('#txtexpediente');
					}
				});
			}						
			
		break;
		case 'D':
			
			
		var grid = Ext.getCmp('xgridCostas');
		var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	
		var idcosta = "";
		
		
		if(selectedRecordsArray.length>0){
		
			
		 var showResult = function(btn){
			if(btn=='yes'){
				
				var idList = [];
				Ext.each(selectedRecordsArray, function (item) {
					idList.push(
						{
							idcosta: item.data.idcosta//,
							//idrecibo: item.data.idrecibo
						});
				});
		
				Ext.Ajax.request({
		            url: urljs + "bandecostascontri/eliminacosta",
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
		idcosta: data.idcosta,
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

//keyup
$('#txtcantidad, #txtprecio').keyup(function(){
    var precio=Number($('#txtprecio').val());
	var cantidad=Number($('#txtcantidad').val());
	var totalpago=precio*cantidad;
	
	$('#txttotal').val(Math.round((totalpago)*100)/100).autoNumeric();
});


$('#txtdeuda').blur(function(){


//function sumaDeuda(){

    var deudaanterior=Number($('#txtdeuda').val());
	var totalcosta=Number($('#txtTotPag').val());
	var montototal=deudaanterior+totalcosta;
	var porcentajemontototal=(5*montototal)/100;
	var total=montototal+porcentajemontototal;
	
	$('#txtMontotot').val(montototal).autoNumeric();
	$('#txtMontoporce').val(porcentajemontototal).autoNumeric();
	$('#txtTotalfin').val(total).autoNumeric();
});



function ingresedeuda(){
		//alert('45565');
		showPopup('bandecostascontri/deuda?codigo='+$('#txtcodigo').val(),'#popDeuda','300','120','Ingrese Deuda');
}


function cargarCosta(){
	
	$('#gridCostas').html('');
	
	var codigo=$('#txtcodigo2').val();
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
			{name: 'idcosta'},
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
		width: '650px',
		height: '210px',   
	
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {					
					Ext.Ajax.request({
						  url: urljs + "bandecostascontri/gridcostas",
						  method: "POST",
						  params: {codigo: codigo,expediente: expediente},
						  success: function(response){			
								var totalPago=0;
								
								data = Ext.JSON.decode(response.responseText);
								
								for(var i=0 ; i < data.length; i++){									
									addRowsCostas(data[i]);
									
								}							
						  }
					});
            	},
            	refresh: function(view) {
					setTimeout(function(){ calcDeuda(); },100);
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
			width: 60,
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
			dataIndex: 'monto',
			align: 'right'
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
            text: 'Idcosta',
			width: 60,
			dataIndex: 'idcosta',
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

function calcDeuda(){
	var idrecibo = '';
	var mDetalle = 0;
	var mDeuda = 0;
	var mTotal = 0;
	var mPorcent = 0;
	var mTFinal = 0;
	var mDifer = 0;
	var mGastos = 0;
	
	var gridCostas = Ext.getCmp('xgridCostas');
	
	if(gridCostas.getStore().data.length>0){
	
		gridCostas.getStore().each(function(rec) {
			//if(rec.get('tipo')!='25.31' || rec.get('subtipo')!='25.01')
			
			var var01 = rec.get('tipo');
			var var02 = rec.get('subtipo');
			
			if(	var01 == '25.31'){
				mDeuda += parseFloat(rec.get('monto'));
				idrecibo = rec.get('idrecibo');
			}else if( var02=='25.01' ){
				mGastos += parseFloat(rec.get('monto'));
			}else{
				mDetalle += parseFloat(rec.get('monto'));

			}

		});
		
	}

	$("#txtTotPag").val(mDetalle.toFixed(2));
	$("#xidrecibo").val(idrecibo);
	
	
		
	mTotal = mDetalle + mDeuda + mGastos;
	mPorcent = (5*mTotal)/100;
	mTFinal = mTotal + mPorcent;

	$('#txtMontotot').val(mTotal.toFixed(2));
	$('#txtDeuda').val(mDeuda.toFixed(2));
	$('#txtMontoporce').val(mPorcent.toFixed(2));
	$('#txtTotalfin').val(mTFinal.toFixed(2));
	$('#txtGastos').val(mGastos.toFixed(2));
	
	Ext.getCmp('xgridBandeja').getStore().load();
	
	//$('#ximp_insol').val(mDifer);
	//$('#xidcosta').val(idcosta);
	
}


function generar_cuenta(){
	var codigo= $('#txtcodigo2').val();
	var expediente= $('#txtexpediente').val();
	//var montogasto= parseFloat($('#txtGastos').val())+parseFloat($('#txtMontoporce').val());
	var montogasto= $('#txtGastos').val();
	var montocosta= $('#txtTotPag').val();
	
	var showResult = function(btn){
		if(btn=='yes'){
	
			Ext.Ajax.request({
				  url: urljs + "bandecostascontri/generarcuenta",
				  method: "POST",
				  params: {codigo: codigo,expediente: expediente,montogasto:montogasto,montocosta:montocosta},
				  success: function(response){			
						infoMessage('Alerta',response.responseText);		
				  }
			});
		}
	};
	
	confirmMessage('Alerta','Seguro de generar estado de cuenta?',showResult);
}



function imprimeLiquidacion(){
	var codigo=$('#txtcodigo2').val();
	var num_docu=$('#txtexpediente').val();
	
	showPopupReport('tipo=pdf&nombrereporte=rpt_liquidacion&param=codigo^'+codigo+'|num_docu^'+num_docu,'popupdocumentoexpe',700,600,'Impresion de Liquidación');
	
}


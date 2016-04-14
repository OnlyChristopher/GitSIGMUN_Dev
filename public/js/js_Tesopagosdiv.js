$('#cmbtipos').change(function(){

    var tipopago=($(this).val().substring(0,5));
	var monto=($(this).val().replace(tipopago,""));
	
    $('#txttipo').val(tipopago);
	$('#txtprecio').val(monto).autoNumeric();
});

$('#txtcantidad').blur(function(){

    var precio=Number($('#txtprecio').val());
	var cantidad=Number($('#txtcantidad').val());
	var totalpago=precio*cantidad;
	
	$('#txttotal').val(Math.round((totalpago)*100)/100).autoNumeric();
});

Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();    
    
    	Ext.define('modelPagosTupa', {
            extend: 'Ext.data.Model',
            fields: [
    			{name: 'tipo'},
    			{name: 'tipo_rec'},
    			{name: 'concepto'},
    			{name: 'precio'},
    			{name: 'cantidad'},
    			{name: 'pordesc'},
    			{name: 'totales'}
            ]
        });
        
        var storePagosTupa = Ext.create('Ext.data.ArrayStore', {
            model: 'modelPagosTupa',
            data: []
        });
        
        var grid = Ext.create('Ext.grid.Panel', {
        	id: 'xgridPagosTupa',
    		columnLines: true,
    		store: storePagosTupa,
    		width: '1150px',
    		height: '150px',
            columns: [ 
            {
            	text: 'Tipo',
            	width: 50,
                dataIndex: 'tipo'
            },{
                dataIndex: 'tipo_rec',
    			hidden: true
            },{
                text: 'Concepto',
                width: 850,		
                dataIndex: 'concepto'
            },{
    			text: 'Precio',
                width: 80,
                dataIndex: 'precio',
				align:'right',
            },{
    			text: 'Cantidad',
                width: 80,
				align:'right',
                dataIndex: 'cantidad'
            },{
    			text: '% Dscto.',
                width: 80,
                dataIndex: 'pordesc',
				hidden: true
            },{
    			text: 'Total',
                width: 80,
				align:'right',
                dataIndex: 'totales'
            }]
        });
        grid.render('gridPagDiversos');
		eventPagoTupa('C');
		loadAutocomplete();
		$('#btnImpPag').attr('disabled',true);
		if($("#txtCodigo").val()=='9999999'){
			$('#txtNombre').attr('readonly', false);		
			$('#txtNombre').removeClass("cajaoff").addClass('caja');
		}
		$("#txtTotPag").val('0.00').autoNumeric();
});


function loadAutocomplete(){
	$("#nom_pago_diver").autocomplete({
		source: function( request, response ) {
		$.ajax({
		  url: urljs + "tesopagosdiv/autocompletar",
		  dataType: "json",
		  data: {
			featureClass: "P",
			style: "full",
			maxRows: 10,
			//name_type: $('#req_tipo').val(),
			name_startsWith: request.term
		  },
		  success: function( data ) {
			//Limpia
			$('#txttipo').val('');
		
			if(data!=null){				
				response( $.map( data.rows, function( item ) {
				  return {
					label: item.id + ' | ' + item.name,
					value: item.name,
					code:  item.id + '|' + item.measure
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
//			$('#cant_bien_serv').focus();
      	},
	  	open : function(){
			var posPopup = $('#popup_popPagosDiv').position();
			var posText = $('#nom_pago_diver').position();
        	$(".ui-autocomplete:visible").css({top: posPopup.top+posText.top+48,left: posPopup.left+posText.left+5});
    	}
	});
}

function eventPagoTupa(act){
	switch(act){
		case 'A':
			var grid = Ext.getCmp('xgridPagosTupa');
			var storePagosTupa = grid.getStore();
		
			if(storePagosTupa.data.length ==0 ){
				$('#divAddTupa').show();
				$('#rowSaveTupa').show();
				$('#rowCancelTupa').show();
				$('#rowAddTupa').hide();
				$('#rowEditTupa').hide();
				$('#btnDelTupa').attr('disabled',true);
				$('#btnImpPag').attr('disabled',true);
				disableButton('#btnDelTupa');
				$("#actTupa").val('A');
			}else{
				infoMessage('','No se puede agregar mas registros');
			}
		break;
		case 'E':
			var grid = Ext.getCmp('xgridPagosTupa');
			var storePagosTupa = grid.getStore();
			
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = storePagosTupa.getAt(storePagosTupa.indexOf(sm[0]));
				//eventPagoTupa('A');
				$('#divAddTupa').show();
				$('#rowSaveTupa').show();
				$('#rowCancelTupa').show();
				$('#rowAddTupa').hide();
				$('#rowEditTupa').hide();
				$('#btnDelTupa').attr('disabled',true);
				disableButton('#btnDelTupa');
				$("#actTupa").val('A');
				$('#btnImpPag').attr('disabled',false);
				
				$("#txttipo").val(rec.get('tipo'));
				$("#txtprecio").val(rec.get('precio'));
				$("#txtcantidad").val(rec.get('cantidad'));
				$("#txttotal").val(rec.get('totales'));
				$("#nom_pago_diver").val(rec.get('concepto'));
				$("#actTupa").val('E');
				sumapagos();
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddTupa').hide();
			$('#rowAddTupa').show();
			$('#rowEditTupa').show();
			$('#rowSaveTupa').hide();
			$('#rowCancelTupa').hide();
			$('#btnDelTupa').attr('disabled',false);
			enableButton('#btnDelTupa');
			$('#btnImpPag').attr('disabled',false);
			$("#txttipo").val('');
			$("#txtprecio").val('0.00');
			$("#txtcantidad").val('0');
			$("#txtprecio").val('0.00');
			$("#txttotal").val('0.00');
			//$('#cmbtipos option:first').attr('selected', true);
			$("#nom_pago_diver").val('');
		break;
		case 'S':
			
			if($("#txttotal").val()==0.00){
				infoMessage('SIGMUN','El monto total debe ser mayor a cero');
				return;
			}
			
			var grid = Ext.getCmp('xgridPagosTupa');
			var storePagosTupa = grid.getStore();
			
			if($('#txttipo').val()!=''){
				if($("#actTupa").val()=='A'){
					var r = Ext.create('modelPagosTupa', {
		    			tipo:$('#txttipo').val(),
		    			tipo_rec:$('#txttipo').val(),
		    			//concepto:($('#cmbtipos :selected').text()=='[Seleccione]') ? '' : $('#cmbtipos :selected').text(),
						concepto:$("#nom_pago_diver").val(),
		    			precio:$('#txtprecio').val(),
		    			cantidad:$('#txtcantidad').val(),
		    			pordesc:$('#txtprecio').val(),
		    			totales:$('#txttotal').val()
					});
					storePagosTupa.insert(storePagosTupa.data.length, r);
				}else{
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(storePagosTupa.indexOf(sm[0])).set('tipo', $('#txttipo').val());
					grid.getStore().getAt(storePagosTupa.indexOf(sm[0])).set('tipo_rec', $('#txttipo').val());
					//grid.getStore().getAt(storePagosTupa.indexOf(sm[0])).set('concepto', ($('#cmbtipos :selected').text()=='[Seleccione]') ? '' : $('#cmbtipos :selected').text());
					grid.getStore().getAt(storePagosTupa.indexOf(sm[0])).set('concepto', $("#nom_pago_diver").val());
					grid.getStore().getAt(storePagosTupa.indexOf(sm[0])).set('precio', $('#txtprecio').val());
					grid.getStore().getAt(storePagosTupa.indexOf(sm[0])).set('cantidad', $('#txtcantidad').val());
					grid.getStore().getAt(storePagosTupa.indexOf(sm[0])).set('pordesc', $('#txtprecio').val());
					grid.getStore().getAt(storePagosTupa.indexOf(sm[0])).set('totales', $('#txttotal').val());

					grid.getStore().sync();				
				}
				eventPagoTupa('C');
				sumapagos();
			}else
				infoMessage('Guardar Concepto de pago','Debe seleccionar el concepto de pago!');
		break;
		case 'D':
			var grid = Ext.getCmp('xgridPagosTupa');
			var storePagosTupa = grid.getStore();			
			if(storePagosTupa.data.length > 0){
				var showResult = function(btn){
					if(btn=='yes'){
						var sm = grid.getSelectionModel();
						storePagosTupa.remove(sm.getSelection());
						sumapagos();
					}
				};
				confirmMessage('Eliminar','Desea eliminar el registro seleccionado?',showResult);
			}
		break;
	}
}

function addRowsTupa(data){
	var grid = Ext.getCmp('xgridPagosTupa');
	var storePagosTupa = grid.getStore();
	var r = Ext.create('modelPagosTupa', {
		tipo:data.tipo,
		tipo_rec:data.tipo_rec,
		concepto:data.concepto,
		precio:data.precio,
		cantidad:data.cantidad,
		pordesc:data.pordesc,
		totales:data.totales
	});
	storePagosTupa.insert(storePagosTupa.data.length, r);
}

function sumapagos(){
	var grid = Ext.getCmp('xgridPagosTupa');
	var storePagosTupa = grid.getStore();
	var total_sum=0;
	$("#txtTotPag").val('0.00').autoNumeric()
	
	if(storePagosTupa.data.length > 0){
		storePagosTupa.each(function (rec) { 
			//alert(rec.get('tipo_rec'));
			total_sum += Number(rec.get('totales').replace(',',''));	
			total_sum=Math.round(total_sum*100)/100;
			$("#txtTotPag").val(total_sum);
		});
	}
}

function enviadeudaTupa(){
	var totalpagar=0.00;
	
	var totalpagar=$('#txtTotPag').val().replace(',','');

	if(totalpagar==0.00){
//	alert(totalpagar);
		infoMessage('SIGMUN','El monto seleccionado debe ser mayor ');
	}else{
		showPopup('tesopagosdiv/prepagodiv?totalpagar='+totalpagar,'#popdeudaTupa','300','350','Cancelar Deuda');
	}
	
}

function restarmontosapagarTupa()
{	
	var monto1 = $("#txtCobrar").val().replace(',','');
	var monto2 = $("#txtEfectivo").val().replace(',','');
		if(monto2 == '' || monto2 == null){
			monto2 = 0.00;
		}
	var rest = Math.abs(monto2-monto1);
	$("#txtDevolucion").val(rest.toFixed(2));
}

function getcancelarTupa(){

	var rdformaPago = $('input[name*="rdformaPago"]:checked').val();
	var grid = Ext.getCmp('xgridPagosTupa');
	var storePagosTupa = grid.getStore();
	var codigo=$("#txtCodigo").val();
	var nombre=$('#txtNombre').val();

	var	cmbTarjeta	= $('#cmbTarjeta').val();
	var	cmbBanco	= $('#cmbBanco').val();
	var	txtCheque	= $('#txtCheque').val();
	var	txtFecCobro	= $('#txtFecCobro').val();
	var	txtObservacion	= $('#txtObservacion').val();
	var	txtCobrar	= $('#txtCobrar').val().replace(',','');
	
	if(storePagosTupa.data.length > 0){
			var showResult = function(btn){
			if(btn=='yes'){
			
				$("#btnAceptarPago").attr('disabled',true);
				$("#rdformaPago1").attr('disabled',true);
				$("#rdformaPago2").attr('disabled',true);
				$("#rdformaPago3").attr('disabled',true);
				
				var idList = [];
				storePagosTupa.each(function (rec){ 
					idList.push(
						{
							tipo:rec.get('tipo'),
							tipo_rec:rec.get('tipo_rec'),
							concepto:rec.get('concepto'),
							precio:rec.get('precio').replace(',',''),
							cantidad:rec.get('cantidad').replace(',',''),
							pordesc:rec.get('pordesc').replace(',',''),
							totales:rec.get('totales').replace(',','')
						});
				});

				//console.log(idList);
				
				Ext.Ajax.request({
		            url: urljs + "tesopagosdiv/registrapagotupa",
		            method: "POST",
		            params: {json: JSON.stringify(idList),rdformaPago:rdformaPago,codigo:codigo,nombre:nombre,cmbTarjeta:cmbTarjeta,cmbBanco:cmbBanco,txtCheque:txtCheque,txtFecCobro:txtFecCobro,txtObservacion:txtObservacion,txtCobrar:txtCobrar},
		            success: function(response){
						getEnabledPago();
						$('#btnImpPag').attr('disabled',true);
						$("#div_pintaboton").html(response.responseText);
						$("#imprimir").button();
		            },
		            failure: function(response, opts){
		            	infoMessage('Contribuyentes','Error al intentar cobrar... ');
		            }
		        });
			}
 		};
 		confirmMessage('Contribuyentes','Seguro de Cancelar?',showResult);
	}else{
		infoMessage('Contribuyentes','Seleccione al menos un registro!');
	}
}

function getEnabledPago(){
	$('#divAddTupa').hide();
	$('#rowAddTupa').hide();
	$('#rowEditTupa').hide();
	$('#rowSaveTupa').hide();
	$('#rowCancelTupa').hide();
	$('#btnDelTupa').hide();
}

function getTipoPagoTupa(){
var rdformaPago = $('input[name*="rdformaPago"]:checked').val();

if(rdformaPago=='E')
	{
		$('#cmbTarjeta').val('0');
		$('#cmbBanco').val('0');
		$('#cmbTarjeta').attr('disabled', true);
		$('#cmbBanco').attr('disabled', true);
		$('#txtGirador').attr('readonly', true);
		$('#txtCheque').attr('readonly', true);
		$('#txtBanco').attr('readonly', true);
		$('#txtFecCobro').attr('readonly', true);
		$('#txtObservacion').attr('readonly', true);
		
		$('#txtGirador').removeClass("caja").addClass('cajaoff');
		$('#txtCheque').removeClass("caja").addClass('cajaoff');
		$('#txtBanco').removeClass("caja").addClass('cajaoff');
		$('#txtFecCobro').removeClass("caja").addClass('cajaoff');
		$('#txtObservacion').removeClass("caja").addClass('cajaoff');
	}
else if(rdformaPago=='C')
	{
		$('#cmbTarjeta').val('0');
		$('#cmbTarjeta').attr('disabled', true);
		$('#cmbBanco').attr('disabled', false);
		
		$('#txtGirador').attr('readonly', false);
		$('#txtCheque').attr('readonly', false);
		$('#txtBanco').attr('readonly', false);
		$('#txtFecCobro').attr('readonly', false);
		$('#txtObservacion').attr('readonly', false);
		
		$('#txtGirador').removeClass("cajaoff").addClass('caja');
		$('#txtCheque').removeClass("cajaoff").addClass('caja');
		$('#txtBanco').removeClass("cajaoff").addClass('caja');
		$('#txtFecCobro').removeClass("cajaoff").addClass('caja');
		$('#txtObservacion').removeClass("cajaoff").addClass('caja');
	}
else if(rdformaPago=='T')
	{
		$('#cmbBanco').val('0');
		$('#cmbTarjeta').attr('disabled', false);
		$('#cmbBanco').attr('disabled', true);
		
		$('#txtGirador').attr('readonly', true);
		$('#txtCheque').attr('readonly', true);
		$('#txtBanco').attr('readonly', true);
		$('#txtFecCobro').attr('readonly', true);
		$('#txtObservacion').attr('readonly', false);
		
		$('#txtGirador').removeClass("caja").addClass('cajaoff');
		$('#txtCheque').removeClass("caja").addClass('cajaoff');
		$('#txtBanco').removeClass("caja").addClass('cajaoff');
		$('#txtFecCobro').removeClass("caja").addClass('cajaoff');
		$('#txtObservacion').removeClass("cajaoff").addClass('caja');
	}
}
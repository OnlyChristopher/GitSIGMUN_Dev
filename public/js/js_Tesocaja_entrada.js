$('#txtcantidad').blur(function(){
	mostrarTotal();
});

$('#txtprecio').blur(function(){
	mostrarTotal();
});
$('#txtcantidad').keyup(function(){
	mostrarTotal();
});
$('#txtprecio').keyup(function(){
	mostrarTotal();
});
$('#cmbtipopagos').change(function(){
	tipo=$('#cmbtipopagos').val().substring(0,5);

	xmontox=$('#cmbtipopagos').val().replace(tipo,'')
	
	$('#txtprecio').val(xmontox);
	
	mostrarTotal();

});

function mostrarTotal(){
    var precio=Number($('#txtprecio').val());
	var cantidad=Number($('#txtcantidad').val());
	var totalpago=precio*cantidad;
	
	$('#txttotal').val(Math.round((totalpago)*100)/100).autoNumeric();
}

Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
});

function getcancelarEntradaNuevo(){

	var rdformaPago = $('input[name*="rdformaPago"]:checked').val();
	//var nrodocu=$("#nrodocu").val();
	var contribuyente=$("#contribuyente").val();
	var referencia=$('#referencia').val();
	var observaciones=$('#observaciones').val();
	
	var	cmbtarjeta	= $('#cmbtarjeta').val();
	var	tipooculto	= $('#cmbtipopagos').val().substring(0,5);
	var	txtprecio	= $('#txtprecio').val().replace(',','');
	var	txtcantidad	= $('#txtcantidad').val().replace(',','');
	var	txttotal	= $('#txttotal').val().replace(',','');

	if(contribuyente==''){
		infoMessagex('SIGMUN-Tesoreria','Ingrese en Nombre del contribuyente','#contribuyente');
		return;
	}

	if(txttotal > 0){
			var showResult = function(btn){
			if(btn=='yes'){
			
				$("#btnPagar").attr('disabled',true);
				$("#rdformaPago1").attr('disabled',true);
				$("#rdformaPago2").attr('disabled',true);
				$("#rdformaPago3").attr('disabled',true);
				
				//console.log(idList);
				
				Ext.Ajax.request({
		            url: urljs + "tesopagosdiv/registrapagotupa02",
		            method: "POST",
		            params: {cmbtarjeta:cmbtarjeta,tipooculto:tipooculto,txtprecio:txtprecio,txtcantidad:txtcantidad,txttotal:txttotal,rdformaPago:rdformaPago,contribuyente:contribuyente,referencia:referencia,idx:2,tiporecoculto:tipooculto,observacion:observaciones},
		            success: function(response){
						$("#contribuyente").attr('disabled',true);
						$("#referencia").attr('disabled',true);
						$("#observaciones").attr('disabled',true);
						$("#cmbtarjeta").attr('disabled',true);
						
						$("#cmbtipopagos").attr('disabled',true);
						$("#txtprecio").attr('disabled',true);
						$("#txtcantidad").attr('disabled',true);
						$("#div_pintaboton").html(response.responseText);
		            },
		            failure: function(response, opts){
		            	infoMessage('Contribuyentes','Error al intentar cobrar... ');
		            }
		        });
			}
 		};
 		confirmMessage('Contribuyentes','Seguro de Cancelar?',showResult);
	}else{
		infoMessage('Contribuyentes','Ingresar Monto a cobrar!');
	}
}
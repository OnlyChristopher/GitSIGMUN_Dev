Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
/*
	$('#frmVehiculo').validate({
		rules: {
			'txtplaca': { required: true, digits: true },
			'lote_01': { required: true, digits: true },
			'lote_02': { required: true, digits: false }
		},
		messages: {
			'txtplaca': { required: 'Ingrese nro de ficha', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			'lote_01': { required: 'Ingrese nro de correlativo de ficha', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			'lote_02': { required: 'Ingrese nro de total de fichas', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){}
	});
*/
});

function guardarvehiculo(est){

	$.ajax({
		type: "POST",     
		url: urljs + "transporte/guardarvehiculo",
		data: $('#frmVehiculo').serializeObject(),     
		success: function(data) { 
			infoMessage('SIGMUN	-',data);
			closePopup('#popvehiculo');
			buscarVehiculos();
		},     
		error: function() {
		} 
	});
}

	// Recupero Valores de Mantpers
	window.muestraDatosSol = function(obj,t) {

	if($('#txtaction').val()=='1'){
		$('#txtpcodigox').val(obj.get('codigo'));
		$('#txtpnombrex').val(obj.get('nombres'));
	}else{
		$('#txtecodigo').val(obj.get('codigo'));
		$('#txtenombre').val(obj.get('nombres'));	
	}

/*
    if($.trim(obj.get('tipo_doc'))== 'DNI')
    {
      $('#txtNroDoc').val($.trim(obj.get('documento')));
      $('#txtNroRuc').val('');
    }else{
      $('#txtruc').val($.trim(obj.get('documento')));
    }
*/
};
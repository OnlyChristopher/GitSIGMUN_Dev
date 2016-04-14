$(function(){
	
	$("input[type='button']").button();
	
	//Valida y envía form años hr
	$('#frmhr').validate({
		rules: {
			'txthrhasta': { required: true, digits: true },
			//'txtnrodecla': { required: true, digits: true },
			'txtfecdecla': { required: true, digits: false }
		},
		messages: {
			'txthrhasta': { required: 'Ingrese año de vigencia', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			//'txtnrodecla': { required: 'Ingrese nro de declaracion', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			'txtfecdecla': { required: 'Ingrese la fecha de declaracion', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){
			//var myMask = new Ext.LoadMask(Ext.get('popup1'), {msg:"Guardando..."});
			
	        var controles={
	                txtcodigo:$('#txtcodigo').val(),
	                txtanno:$('#txtanno').val(),
	                txthrdesde:$('#txthrdesde').val(),
	                txthrhasta:$('#txthrhasta').val(),
	               // txtnrodecla:$('#txtnrodecla').val(),
	                txtbaseleg:$('#txtbaseleg').val(),
	                txtdesde:$('#txtdesde').val(),
	                txthasta:$('#txthasta').val(),
	                txtnroexp:$('#txtnroexp').val(),
	                chkemision:$('#chkemision').is(':checked'),
	                txtfecresol:$('#txtfecresol').val(),
	                cmbRegimen:$('#cmbRegimen').val(),
	                cmbMotivo:$('#cmbMotivo').val(),
	                txtnroresol:$('#txtnroresol').val(),
	                txtobserva:$('#txtobserva').val(),
	                txtfecdecla:$('#txtfecdecla').val()
	            };
	        
			$.ajax({     
				type: "POST",     
				url: urljs + "rentasdecjurada/grabar",
				data: controles,     
				success: function(data) { 
					infoMessage('SIGMUN	- Generar Hoja de Resumen',data);
					closePopup('#popeditarhr');
					Ext.getCmp('xtreePanel').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}
	});
	
});
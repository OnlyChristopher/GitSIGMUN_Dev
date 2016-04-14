$(function(){
	$("input[type='button']").button();
	//Valida y envía form años hr
	$('#frmAnioHr').validate({
		rules: {
			'txtHrDesde': { required: true, digits: true, minlength: 4 },
			'txtHrHasta': { required: true, digits: true, minlength: 4 }
		},
		messages: {
			'txtHrDesde': { required: 'Ingrese desde', digits: 'Ingrese s&oacute;lo n&uacute;meros', minlength:'Ingrese periodo valido' },
			'txtHrHasta': { required: 'Ingrese hasta', digits: 'Ingrese s&oacute;lo n&uacute;meros', minlength:'Ingrese periodo valido' }
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){
			
			var myMask = new Ext.LoadMask(Ext.get('popup_popnuevohr'), {msg:"Guardando..."});
			
	        var controles={
	    			desde:$('#txtHrDesde').val(),
	    			hasta:$('#txtHrHasta').val()
	            };
	        
			$.ajax({     
				type: "POST",     
				url: urljs + "rentasdecjurada/generar",
				data: controles,//$('#frmAnioHr').serializeObject(),
				success: function(data) {					
					closePopup('#popnuevohr');					
					showPopup('rentasdecjurada/hr?actionHr=2','#popeditarhr','600','500','Editar Hoja de Resumen');
					//infoMessage('Generar Hr',data);
				},     
				error: function() {
				} 
			}); 
		}
	});
	
});
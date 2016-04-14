$(function(){ 
$("input[type='button']").button();
	
	//Valida y envía form contribuyente
	$('#frm_caja').validate({
		rules: {
			'txtCajaUsuario': 'required'
			
		},
		messages: {
			'txtCajaUsuario': 'Debe ingresar la caja'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
	
				$.ajax({     
					type: "POST",  
					url: urljs + "mantusuario/insertcaja",
					data: $('#frm_caja').serializeObject(),     
					success: function(data) { 
						infoMessage('Alerta',data);
						closePopup('#popNuevaUsuCaj');
					}
				}); 				
		}
	});
});

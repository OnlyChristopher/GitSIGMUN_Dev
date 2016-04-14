$(function(){ 
	$("input[type='button']").button();
	
	//-------------------------------------
	$('#frm_password').validate( {
		rules : {
			'txtNuevaContr' : 'required',
			'txtConfirContr': { required: true, equalTo: "#txtNuevaContr" }
		},
		messages : {
			'txtNuevaContr' : 'Ingrese nueva contrase&ntilde;a',
			'txtConfirContr': { required: 'Ingrese contrase�a', equalTo: "Contrase&ntilde;as no coinciden" }
		},
		debug : true,
		errorElement : 'div',
		submitHandler : function(form) {
			$.ajax( {
				type : "POST",
				url : "mantusuario/grabarpass",///--------> nombre de la funcion
				data : $('#frm_password').serializeObject(),
				success : function(data) {
					infoMessage('Guardar Contrase�a', data);				
					closePopup('#popNuevaUsupass');
				}
			});
		}
	});
});

Ext.onReady(function(){
    Ext.QuickTips.init();
	
	$("input[type='button']").button();
	
	/*
	$.validator.addMethod("dni", function(value, element) {
		var params = "numedoc="+$('#numedoc').val()+"&old_numedoc="+$('#old_numedoc').val();
		return callAjax('cataspersona/valida',params,'','param');
	}, "Este nro. de documento ya existe" );
	*/
	$('#frmcpersonx').validate( {
		rules : {
			'nombresxx': 'required',
			'apaternxx': 'required',
			'amaternxx': 'required'
			//'numedoc': { required: true, dni: true }
		},
		messages : {
			'nombresxx': 'Debe ingresar el nombre',
			'apaternxx': 'Debe ingresar el apellido paterno',
			'amaternxx': 'Debe ingresar el apellido materno'
			//'numedoc': { required: 'Ingrese el nro. de documento' }
		},
		debug : true,
		errorElement : 'div',
		submitHandler : function(form) {
			$.ajax({     
				type: "POST",     
				url: urljs + "cataspersona/grabarx",
				data: $(form).serializeObject(),     
				success: function(response) { 
					//alert(data);
					infoMessage('Guardar Persona',response);
					closePopup('#popcpersonx');
					Ext.getCmp('xgridCPersony').getStore().load();
				},     
				error: function() {
				} 
			});
		}
	});
	
	//Todos a mayúscula
	$('.cajac').blur(function(event){
		this.value = this.value.toUpperCase();
	});
	
	$('#cidcont').focus();
});
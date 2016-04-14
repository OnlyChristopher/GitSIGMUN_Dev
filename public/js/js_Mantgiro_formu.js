$(function(){	

	$("input[type='button']").button();
	
	//Valida y env�a form contribuyente
	$('#frmgiro').validate({
		rules: {
			'txtCodGiro': 'required',
			'txtDesGiro': 'required',
			//'txtApeMatContri': 'required',
			//'cmbDocContri': 'required',
			//'txtNumDoc': 'required',
			//'cmbDisContri': 'required',
		},
		messages: {
			'txtCodGiro': 'Debe ingresar codigo de giro',
			'txtDesGiro': 'Debe ingresar descripcion al giro',
			//'txtApeMatContri': 'Debe ingresar el apellido materno',
			//'cmbDocContri': 'Seleccione el tipo de documento',
			//'txtNumDoc': { required: 'Ingrese el documento', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			//'cmbDisContri': 'Seleccione el distrito'
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){
			$.ajax({     
				type: "POST",     
				url: urljs + "mantgiro/grabar",
				data: $('#frmgiro').serializeObject(),     
				success: function(data) { 
					//alert(data);
					//infoMessage('Guardar Giro',data);
					closePopup('#popNgiro');
					Ext.getCmp('xgridBusGiro').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}
	});
	
	//changeDisContri('003');
});




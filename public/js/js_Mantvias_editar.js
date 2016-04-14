$(function(){
	$("input[type='button']").button();
	
	//Valida y envía form contribuyente
	$('#frm_editarvia').validate({		
			rules: {
			'cb_tipovia': 'required',
			'txtcuadra':'required',
			'txtnombre':'required'			
			},
			messages: {
			'cb_tipovia': 'Debe seleccionar el tipo de via',
			'txtcuadra':'Debe ingresar el numero de la cuadra',
			'txtnombre':'Debe ingresar le nombre de la via'
			//'txtNumDoc': { required: 'Ingrese el documento', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
			},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){			
			$.ajax({     
				type: "POST",     
				url: urljs + "mantvias/grabar",
				data: $('#frm_editarvia').serializeObject(),     
				success: function(data) { 
					//alert(data);
					infoMessage('Guardar Vias',data);
					closePopup('#popEditVias');
					Ext.getCmp('xgridVias').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}
	});
    //INGRESA SOLO MAYUSCULA
   /* $('#txtnombre').keypress(function (e) {    	
    		var tecla = document.all ? tecla = e.keyCode : tecla = e.which;
    		return ((tecla > 64 && tecla < 91));
    });
    */
});
//$('#txtcuadra').autotab({format: 'numeric'});
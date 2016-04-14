$(function(){
	$("input[type='button']").button();
	
	//Valida y envía form contribuyente
	$('#frm_editarancel').validate({
		rules: {		
		'txtarancel1': 'required'
		},
		messages: {
		'txtarancel1': {required:'Ingrese el arancel'}
		},
		
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){
			$.ajax({     
				type: "POST",     
				url: urljs + "mantvias/actualizaeditarancel",
				data: $('#frm_editarancel').serializeObject(),     
				success: function(data) { 
					//alert(data);
					infoMessage('Guardar Arancel',data);
					closePopup('#popEditArancel');
					Ext.getCmp('xgridArancel').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}
	});
	
	
	
	
});
//$('#txtarancel').autotab({format: 'number'})


$(function(){

	$("input[type='button']").button();
	//Valida y envía form contribuyente//
	
	$('#frmpoblado').validate({
		rules: {
		'txtArancel': 'required'
		},	
		messages: {
		'txtArancel': 'Ingrese el arancel correspondiente'
		
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			
				$.ajax({     
					type: "POST",  
					url: "mantpoblado/grabararancel",
					data: $('#frmpoblado').serializeObject(),     
					success: function(data) { 					
						
						infoMessage('Guardando Arancel',data);
						
						closePopup('#popEditArancel');
						Ext.getCmp('xgridVias').getStore().load();
					},     
					error: function() {
					
					} 
				}); 				
					
		}
	});
});
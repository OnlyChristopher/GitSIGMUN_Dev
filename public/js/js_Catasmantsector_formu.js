$(function(){
	
	$("input[type='button']").button();
	
	//Valida y envía form contribuyente
	$('#frmcatasector').validate({
		rules: {
		},
		messages: {
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			//var carga='true/Guardandoder Centro Poblado';
			var carga=validarSector();
			array=carga.split('/');
			errorMessage('Error',array[0]);
			if (array[0]=='false')
			{
				errorMessage('Alerta',array[1]);
			}
			else{
				infoMessage('Guardando Sector',array[1]);
				$.ajax({     
					type: "POST",  
					url: "catasmantsector/grabar",
					data: $('#frmcatasector').serializeObject(),     
					success: function(data) { 
						//alert(data);
						infoMessage('Guardando Sector',data);
						$("#MSG_ERROR").html(data);
						closePopup('#popsectorcata');
						Ext.getCmp('xgridSector').getStore().load();
					},     
					error: function() {
					} 
				}); 				
			}			
		}
	});
});

function validarSector(){
	var txtCodcatasector = $('#txtCodcatasector').val().trim();
	var txtNomsector = $('#txtNomsector').val().trim();
	var retorno='true/Guardando Sector';
	
	if(txtCodcatasector!=""){
		if (txtNomsector==""){
			retorno='false/Por favor ingrese el nombre del sector';
			return(retorno);
		}
	}else{
			retorno='false/Por favor ingrese el codigo del sector';
			return(retorno);
		}
	return(retorno);
}

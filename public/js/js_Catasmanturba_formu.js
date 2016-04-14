$(function(){
	
	$("input[type='button']").button();
	
	//Valida y envía form contribuyente
	$('#frmcataurbani').validate({
		rules: {
		},
		messages: {
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			//var carga='true/Guardandoder Centro Poblado';
			var carga=validarUrbanizacion();
			array=carga.split('/');
			errorMessage('Error',array[0]);
			if (array[0]=='false')
			{
				errorMessage('Alerta',array[1]);
			}
			else{
				infoMessage('Guardando Urbanizacion',array[1]);
				$.ajax({     
					type: "POST",  
					url: "catasmanturbanizacion/grabar",
					data: $('#frmcataurbani').serializeObject(),     
					success: function(data) { 
						//alert(data);
						infoMessage('Guardando Urbanizacion',data);
						$("#MSG_ERROR").html(data);
						closePopup('#popurbacata');
						Ext.getCmp('xgridUrbani').getStore().load();
					},     
					error: function() {
					} 
				}); 				
			}			
		}
	});
});

function validarUrbanizacion(){
	var txtCodcataurba = $('#txtCodcataurba').val().trim();
	var cmbTipourba = $('#cmbTipourba').val().trim();
	var txtNomurba = $('#txtNomurba').val().trim();
	var retorno='true/Guardando Urbanizacion';
	
	if(txtCodcataurba!=""){
		if (cmbTipourba==""){
			retorno='false/Por favor seleccione el tipo de urbanización';
			return(retorno);
		}
		if (txtNomurba==""){
			retorno='false/Por favor ingrese el nombre de la urbanización';
			return(retorno);	
		}
	}else{
			retorno='false/Por favor ingrese el codigo de la urbanización';
			return(retorno);
		}
	return(retorno);
}

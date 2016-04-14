$(function(){
	
	$("input[type='button']").button();
	
	//Valida y envía form contribuyente
	$('#frmcatavia').validate({
		rules: {
		},
		messages: {
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			//var carga='true/Guardandoder Centro Poblado';
			var carga=validarvia();
			array=carga.split('/');
			errorMessage('Error',array[0]);
			if (array[0]=='false')
			{
				errorMessage('Alerta',array[1]);
			}
			else{
				infoMessage('Guardando Via',array[1]);
				$.ajax({     
					type: "POST",  
					url: "catasmantvia/grabar",
					data: $('#frmcatavia').serializeObject(),     
					success: function(data) { 					
					//infoMessage('Guardando Via',array[1]);
						infoMessage('Guardando Via',data);
						$("#MSG_ERROR").html(data);
						closePopup('#popviacata');
						Ext.getCmp('xgridVia').getStore().load();
					},     
					error: function() {
					} 
				}); 				
			}			
		}
	});
});

function validarvia(){
	var txtCodcatavia = $('#txtCodcatavia').val().trim();
	var cmbTipovia = $('#cmbTipovia').val().trim();
	var txtNomvia = $('#txtNomvia').val().trim();
	var retorno='true/Guardando Via';
	
	if(txtCodcatavia!=""){
		if (cmbTipovia==""){
			retorno='false/Por favor ingrese el tipo de via';
			return(retorno);
		}
		if (txtNomvia==""){
			retorno='false/Por favor ingrese el nombre de via';
			return(retorno);	
		}
	}else{
			retorno='false/Por favor ingrese el codigo de via';
			return(retorno);
		}
	return(retorno);
}

$('#txtCodcatavia').blur(function(){
    var numCeros = '000000'; // pon el nº de ceros que necesites
    var valor =  $('#txtCodcatavia').val();
    var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
    $('#txtCodcatavia').val(valor2);  //obj.value = valor;
    
});

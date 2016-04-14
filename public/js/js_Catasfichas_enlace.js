$(function(){
	
	$("input[type='button']").button();
	
	$('#frmenlace').validate({
		rules: {
			'txtTipoficha'	: 'required',
			'txtNroficha'	: 'required',
			'txtDestficha'	: 'required'
		},
		messages: {
			'txtTipoficha'	: 'Ingrese el tipo de ficha',
			'txtNroficha'	: 'Ingrese el nro de ficha',
			'txtDestficha'	: 'Ingrese el nro de ficha destino'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			$.ajax({     
				type: "POST",  
				url: "catasfichas/grabarenlace",
				data: $(form).serializeObject(),     
				success: function(response) {
					infoMessage('Enlazar Ficha',response);
					closePopup('#popcatasenlace');
				}
			}); 				
		}
	});
	
	$('#txtDestficha').focus();
});
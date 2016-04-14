Ext.onReady(function(){
    Ext.QuickTips.init();

	var getvalue = $('#txtidrq').val();
	$('#txtidrq01').val(getvalue);

	var getvalue2 = $('#txtaniohr').val();
	$('#txtAnio').val(getvalue2);
	
    $("input[type='button']").button();
	$("#btnGrabarPredio").button({icons:{primary:"ui-icon-print"}});
	$("#btnCerrarPredio").button({icons:{primary:"ui-icon-print"}});

	
	//$('#txtAnio').mask('00/00/0000');
	
	$("#btnGrabarPredio").click(function(){
		goToFormulario("formCrearPredio");
	});
	//Valida y envia form contribuyente
		
	$('#formCrearPredio').validate({
		rules: {
			'txtAnio': 'required',
			'cbPredio': 'required'
		},
		messages: {
			'txtAnio': 'Debe ingresar el año',
			'cbPredio': 'Debe seleccionar el tipo de predio'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
				$.ajax({     
					type: "POST",  
					url: urljs + "fiscalizacionpredio/crearpredio",
					data: $('#formCrearPredio').serializeObject(),
					dataType : 'json',
					success: function(data){
						MostrarPredios();
					},     
					error: function(data) {
						infoMessage('Error',data);
					} 

				}); 				
			}
		
	});
})
Ext.onReady(function(){
    Ext.QuickTips.init();
	
	$("input[type='button']").button();

	$("#btnGrabarPiso").button({icons:{primary:"ui-icon-print"}});
	$("#btnCerrarPiso").button({icons:{primary:"ui-icon-print"}});

	$("#btnGrabarPiso").click(function(){
		goToFormulario("formModificarPiso");
	});
	//Valida y envia form contribuyente
		
	$('#formModificarPiso').validate({
		rules: {
			'txtpiso': 'required'
		},
		messages: {
			'txtpiso': 'Debe seleccionar el contribuyente'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
				$.ajax({     
					type: "POST",  
					url: urljs + "fiscalizacionpredio/grabarpiso",
					data: $('#formModificarPiso').serializeObject(),
					dataType : 'json',
					success: function(data){
						var getvalue = $('#txtidpi').val();
						closePopup('#popmodificarpiso');
						showPopup('fiscalizacionpredio/modificarpiso?idpi='+getvalue,'#popmodificarpiso','550','320','Modificar Piso...');
						MostrarPisos();
					},     
					error: function(data) {
						infoMessage('Error',data);
					} 

				}); 				
			}
		
	});

});

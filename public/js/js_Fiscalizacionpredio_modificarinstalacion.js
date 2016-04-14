Ext.onReady(function(){
    Ext.QuickTips.init();
	
	$("input[type='button']").button();

	$("#btnGrabarInstalacion").button({icons:{primary:"ui-icon-print"}});
	$("#btnCerrarInstalacion").button({icons:{primary:"ui-icon-print"}});

	$("#btnGrabarInstalacion").click(function(){
		goToFormulario("formModificarInstalacion");
	});
	//Valida y envia form contribuyente
		
	$('#formModificarInstalacion').validate({
		rules: {
			'cbInstalacion': 'required'
		},
		messages: {
			'cbInstalacion': 'Debe seleccionar la instalacion'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
				$.ajax({     
					type: "POST",  
					url: urljs + "fiscalizacionpredio/grabarinstalacion",
					data: $('#formModificarInstalacion').serializeObject(),
					dataType : 'json',
					success: function(data){
						var getvalue = $('#txtidpin').val();
						var getanio  = $('#txtanioin').val();
						closePopup('#popmodificarinstalacion');
						showPopup('fiscalizacionpredio/modificarinstalacion?idpin='+getvalue+'&anio='+getanio,'#popmodificarinstalacion','550','320','Modificar Instalacion...');
						MostrarInstalacion();
					},     
					error: function(data) {
						infoMessage('Error',data);
					} 

				}); 				
			}
		
	});

});

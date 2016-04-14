$(function(){
	
	$("input[type='button']").button();
	
	//Valida y envía form años hr
	$('#frmbajapredio').validate({
		rules: {
			'cmbDescargo': { required: true },
			'txtfecha': { required: true },
			'txtPropiedad': { required: true },
			'txtGlosa': { required: true },
			'txtAdquiriente': { required: true },
			'cmbNotaria': { required: true }
		},
		messages: {
			'cmbDescargo': { required: 'Seleccione el motivo de descargo'},
			'txtfecha': { required: 'Ingrese la fecha transferencia'},
			'txtPropiedad': { required: 'Ingrese el porcentaje propiedad'},
			'txtGlosa': { required: 'Ingrese la glosa'},
			'txtAdquiriente': { required: 'Seleccione el adquiriente'},
			'cmbNotaria': { required: 'Seleccione la notaria'}
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){

			$('#hd_direccion').val($('#hd_direccion').val().replace("'","''"))
			//var myMask = new Ext.LoadMask(Ext.get('popup1'), {msg:"Guardando..."});
	        var controles={
	                txtcodigo:$('#hd_ECodigo').val(),
	                txtcodpred:$('#hd_CodPred').val(),
					txtanexo:$('#hd_SAnexo').val(),
					txtanno:$('#hd_anno').val(),
					txtdireccion:$('#hd_direccion').val(),
					cmbDescargo:$('#cmbDescargo').val(),
					txtPropiedad:$('#txtPropiedad').val(),
					txtGlosa:$('#txtGlosa').val(),
					txtfecha:$('#txtfecha').val(),
					cmbNotaria:$('#cmbNotaria').val(),
					txtAdquiriente:$('#txtAdquiriente').val()
	            	};
			$.ajax({     
				type: "POST",     
				url: urljs + "rentasdecjurada/bajapredio",
				data: controles,     
				success: function(data) { 
					infoMessage('SIGMUN	- BAJA DE PREDIO',data);
					closePopup('#bajapredio');
					Ext.getCmp('xgridtblpredio').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}
	});
	
});


window.muestraDatosBajaPred = function(obj) { 
	 $('#txtSub').val(obj.get('subpersona'));
	 $('#txtAdquiriente').val(obj.get('codigo'));
	 $('#txtTipoDocumento').val(obj.get('tipodoc'));
	 $('#txtPersona').val(obj.get('tipopersona'));	
	 $('#txtNro').val(obj.get('documento'));
   	 $('#txtRazon').val(obj.get('nombres'));  
};



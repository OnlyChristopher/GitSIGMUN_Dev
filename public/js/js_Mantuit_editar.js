$(function(){
	$("input[type='button']").button();
	///funcion validar : aki se valida el campo anno(si existe o no exoste en la grilla) y se crea una funcione n el controler
	$.validator.addMethod("v_anno", function(value, element) {
		//alert();
		var params = "anno="+$('#anno').val()+"&actionuit="+$('#actionuit').val();
		return callAjax('mantuit/valida',params,'','param');
		//return false;
	}, "Este a�o ya existe" );
	
	//Valida y env�a form editar_uit
	$('#frm_editarUIT').validate({
		rules: {
		'anno': { required: true, v_anno: true },
		'tipo': 'required',
		'valor_uit': 'required'		

		},
		messages: {
		'anno': {required:'Ingresar el a�o'},
		'tipo': 'Debe ingresar el tipo',
		'valor_uit': 'Ingresar el valor'/* digits: 'Ingrese s&oacute;lo n&uacute;meros'*/
		
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){
			var myMask = new Ext.LoadMask(Ext.get('popup_popupuit'), {msg:"Guardando..."});			
			$.ajax({     
				type: "POST",     
				url: urljs + "mantuit/grabar",
				data: $('#frm_editarUIT').serializeObject(),     
				success: function(data) { 
					//alert(data);
					infoMessage('Guardar Valor UIT',data);
					closePopup('#popupuit');
					///---->actualiza la grilla<-----///
					Ext.getCmp('xgridUIT').getStore().load();
					///------->><<----------///
				},     
				error: function() {
				} 
			}); 
		}
	});
	
});

$('#anno').autotab({format: 'numeric'});
$('#tipo').autotab({format: 'number'});
$('#valor_uit').autotab({format: 'number'});
$('#imp_min').autotab({format: 'number'});
$('#imp_max').autotab({format: 'number'});
$('#costo_emision').autotab({format: 'number'});
$('#costo_adicional').autotab({format: 'number'});
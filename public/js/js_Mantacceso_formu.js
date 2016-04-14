$(function(){

	$("input[type='button']").button();
	
	$.validator.addMethod("v_accesso", function(value, element) {
		var params = "idacceso="+$('#txtidacceso').val()+"&idacceso_old="+$('#txtidacceso_old').val();
		return callAjax('mantacceso/validacceso',params,'','param');
	}, "El id de acceso ya existe" );
	
	$('#frmacceso').validate({
		rules : {
			'cmbTipox' : 'required',
			'cmbMenux' : { required : function(element) { return ($('#cmbTipox').val() == 'O'); }},
			'cmbPantallax' : { required : function(element) { return ($('#cmbTipox').val() == 'O'); }},
			'txtidacceso' : { required: true, v_accesso: true }
		},
		messages : {
			'cmbTipox' : 'Debe seleccionar el tipo',
			'cmbMenux' : 'Debe seleccionar el men&uacute',
			'cmbPantallax' : 'Debe seleccionar la pantalla',
			'txtidacceso' : { required: 'Debe ingresar el acceso' }
		},
		debug : true,
		errorElement : 'div',
		submitHandler : function(form) {

			$.ajax( {
				type : "POST",
				url : "mantacceso/grabar",
				data : $(form).serializeObject(),
				success : function(response) {
					infoMessage('Guardar Acceso', response);
					closePopup('#popNuevoAcceso');
					Ext.getCmp('xgridAcceso').getStore().load();
				}
			});
		}
	});
	
	$('#txtDoform').blur(function(event){
		this.value = this.value.toLowerCase();
	});
	
	$('#txtNomacceso').blur(function(event){
		this.value = toTitleCase(this.value);
	});
	
});

function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

function changeTipox(val){
	switch(val){
		case 'O':
			$('#cmbMenux').attr('disabled',false);
			$('#cmbPantallax').attr('disabled',false);
			$('#txtIdobjeto').attr('disabled',false);
			$('#txtIcono').attr('disabled',true);			
			$('#txtIcono').val('');
			$('#txtDoform').attr('disabled',true);			
			$('#txtDoform').val('');
		break;
		case 'M':
			$('#cmbMenux').attr('disabled',true);			
			$('#cmbMenux').val('');
			$('#cmbPantallax').attr('disabled',true);
			$('#cmbPantallax').val('');
			$('#txtIdobjeto').attr('disabled',true);
			$('#txtIdobjeto').val('');
			$('#txtIcono').attr('disabled',false);
			$('#txtDoform').attr('disabled',false);
		break;
	}
}

function changeCombox(opt,valor){
	$.ajax({     
		type: "POST",     
		url: urljs + "mantacceso/combos",
		data: "opt="+opt+"&valor="+valor,
		success: function(response) { 
			$('#cmbPantallax').html(response);
		} 
	}); 
}

Ext.onReady(function(){
    Ext.QuickTips.init();
	
	$("input[type='button']").button();
	
	/*
	$.validator.addMethod("dni", function(value, element) {
		var params = "numedoc="+$('#numedoc').val()+"&old_numedoc="+$('#old_numedoc').val();
		return callAjax('cataspersona/valida',params,'','param');
	}, "Este nro. de documento ya existe" );
	*/
	$('#frmcperson').validate( {
		rules : {
			'cidpersx': 'required'
			//'numedoc': { required: true, dni: true }
		},
		messages : {
			'cidpersx': 'Seleccione el tipo de titular'
			//'numedoc': { required: 'Ingrese el nro. de documento' }
		},
		debug : true,
		errorElement : 'div',
		submitHandler : function(form) {
			$.ajax({     
				type: "POST",     
				url: urljs + "cataspersona/grabar",
				data: $(form).serializeObject(),     
				success: function(response) { 
					var datos = response.split('|');
					infoMessage('Guardar Contribuyente',datos[0]);
					closePopup('#popcperson');
					//Actualiza grilla según destino abierto
					switch(dest){
						case '1': Ext.getCmp('xgridCPersonx').getStore().load(); break;
						case '2': refreshPerson(datos[1],datos[2],datos[3],datos[4],datos[5]); break;
						case '3': Ext.getCmp('xgridCotix').getStore().load(); break;
					}
				},     
				error: function() {
				} 
			});
		}
	});
	
	//Todos a mayúscula
	$('.cajac').blur(function(event){
		this.value = this.value.toUpperCase();
	});
	
	$('#cidcont').focus();
	
});


function cargaUbigeo(dest,busc,codidep,codipro){
	var contentAjax = $(dest).html('...');
	$.ajax({
		type: "POST",
		url: urljs + "cataspersona/ubigeo",
		data: "busc="+busc+"&codidep="+codidep+"&codipro="+codipro,
		success: function(response){
			contentAjax.html(response);
		}
	});
}

function selectConyu(obj){
	$('#cidconyu').val(obj.get('idsigma'));
	$('#txtTipDocCon').val(obj.get('tipodoc'));
	$('#txtNumDocCon').val(obj.get('numedoc'));
	$('#txtNomCon').val(obj.get('nombre'));
	$('#txtApePatCon').val(obj.get('apatern'));
	$('#txtApeMatCon').val(obj.get('amatern'));
}

function ActCriterio(opt){
	$('#C,#N,#R,#D,#F,#P').hide();
	$('#'+opt).show();
}
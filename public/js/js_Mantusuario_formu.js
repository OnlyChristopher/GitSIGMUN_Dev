Ext.onReady(function(){
    Ext.QuickTips.init();

	$("input[type='button']").button();
	
	$.validator.addMethod("v_login", function(value, element) {
		//alert();
		var params = "vlogin="+$('#txtVlogUsuario').val()+"&vloginOld="+$('#txtVlogOld').val();
		return callAjax('mantusuario/validalogin',params,'','param');
		//return false;
	}, "El usuario ya existe" );
	
	$('#frm_mantenimiento').validate( {
		rules : {
			'txtNomUsuario' : 'required', // CAJA DE TEXTO DONDE INGRESO // NOMBRE FORMU.PHTML
			'txtApeUsuario' : 'required', // CAJA DE TEXTO DONDE INGRESO // APELLIDO FORMU.PHTML
			'txtdocUsuario' : 'required',
			'txtVlogUsuario': { required: true, v_login: true },			
			'txtContraUsuario' : 'required',
			'txtConfirUsuario' : 'required',
			'cmbArea' : 'required',			
			'cmbDoc' : 'required',
			'cmbPerfil' : 'required'
		},
		messages : {
			'txtNomUsuario' : 'Debe ingresar el nombre', // MUESTRA EL // MENSAJE CUANDO NO // INGRESO LOS DATOS
			'txtApeUsuario' : 'Debe  ingresar el apellido',
			'txtdocUsuario' : 'Debe ingresar el numero del documento',
			'txtVlogUsuario' : { required: 'Debe ingresar el usuario' },
			'txtContraUsuario' : 'Debe ingresar la contrase&ntilde;a',
			'txtConfirUsuario' : 'Debe repetir la contrase&ntilde;a',
			'cmbArea' : 'Debe seleccionar el tipo de area',
			'cmbDoc' : 'Debe seleccionar el tipo de documento',
			'cmbPerfil' : 'Debe seleccionar el perfil'
		},
		debug : true,
		errorElement : 'div',
		submitHandler : function(form) {
			$.ajax( {
				type : "POST",
				url : "mantusuario/grabar",
				data : $('#frm_mantenimiento').serializeObject(),
				success : function(response) {					
					$("#txtUsuUsuario").val(response);					
					var users = $("#txtUsuUsuario").val().trim();
					if(users!='')
						infoMessage('Guardar Usuario', 'Datos guardados correctamente!');
					Ext.getCmp('xgridUsuario').getStore().load();					
				}
			});
		}
	});
	
});

function claveUsuario(){
	if($('#txtUsuUsuario').val().length) 
		showPopup('mantusuario/password?idUsuario='+$('#txtUsuUsuario').val(),'#popNuevaUsupass','300','120','Generar Clave'); 
	else 
		infoMessage('Datos Usuario','Primero debe ingresar el usuario!');
}
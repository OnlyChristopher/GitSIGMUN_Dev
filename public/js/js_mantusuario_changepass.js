$(function(){
	$("input[type='button']").button();

	$('#frmusupass').validate({
		rules: {
		'old_pass': { required: true, remote: urljs + 'mantusuario/oldpass' },
		'new_pass': 'required',
		'rep_pass': { required: true, equalTo: "#new_pass" }
		},
		messages: {
		'old_pass': { required: 'Debe ingresar la antigua contrase&ntilde;a', remote: 'Contrase&ntilde;a actual incorrecta' },
		'new_pass': 'Debe ingresar la nueva contrase&ntilde;a',
		'rep_pass': { required: 'Debe repetir la nueva contrase&ntilde;a', equalTo: "Las nueva contrase&ntilde;a no coincide" }
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			$.ajax({     
				type: "POST",     
				url: urljs + "mantusuario/newpass",
				data: $(form).serializeObject(),     
				success: function(data) { 
					infoMessage('Cambiar Contrase&ntilde;a',data);
					cancelar();
				}
			}); 
		}
	});
	
	$("#txtOldPass").focus();
});

function aceptar(){
	goToFormulario('frmusupass');
}

function cancelar(){
	var data = {id:'', hrefTarget:urljs + 'mantusuario/changepass', cls:'Cambiar Contrase&ntilde;a'};
	getNode(data);
}
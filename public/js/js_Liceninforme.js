Ext.onReady(function(){
    Ext.QuickTips.init();
	
	var condefensa = $('#defensas').val();
	
    $("input[type='button']").button();
    $('#frmliceninforme').validate({
        rules: {
            'txtinfor': 'required',
            'txtfechinf' : 'required'
        },
        messages: {
            'txtinfor':     'Debe ingresar Nro. Informe.',
            'txtfechinf' : 'Fecha del Informe'
        },
        debug: true,
        errorElement: 'div',
        ignore: [],
        submitHandler: function(form){
            $.ajax({
                type: "POST",
                url: urljs + "Liceninforme/grabar",
                data: $('#frmliceninforme').serializeObject(),
                success: function(data) {
                    infoMessage('Guardar',data);
                        closePopup('#popGenInforme');
						closePopup('#popGenerarInforme');
                    Ext.getCmp('xgridLicen').getStore().load();
                },
                error: function() {
                }
            });
        }
    });
		

	if(condefensa =='CD')
	{
		$("#hidesindefensa").hide();
		
		$("#sexo").hide();
		$("#cargo").hide();
		
	}
	else
	{
		$("#hidecondefensa").hide();
		$("#hideconcertificado").hide();
		
	}

});





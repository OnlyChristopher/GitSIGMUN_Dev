Ext.onReady(function(){
    Ext.QuickTips.init();
	

    $("input[type='button']").button();
    $('#frmresolucion').validate({
        rules: {
            'txtresol': 'required',
            'txtfechresol' : 'required',
           // 'txtmemo': 'required',
           // 'txtfechmemo': 'required'
        },
        messages: {
            'txtresol':     'Debe ingresar Nro. Resolucion',
            'txtfechresol' : 'Fecha del Informe',
           // 'txtmemo':  'Debe ingresar Nro. Memorando',
           // 'txtfechmemo':  'Fecha del Memorando'
        },
        debug: true,
        errorElement: 'div',
        ignore: [],
        submitHandler: function(form){
            $.ajax({
                type: "POST",
                url: urljs + "Licenresolucion/grabar",
                data: $('#frmresolucion').serializeObject(),
                success: function(data) {
                    infoMessage('Guardar',data);
                        closePopup('#popGenerarResolucion');
						//closePopup('#popGenerarResolucion');
                    Ext.getCmp('xgridLicen').getStore().load();
                },
                error: function() {
                }
            });
        }
    });
		
});





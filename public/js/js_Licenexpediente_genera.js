Ext.onReady(function(){
    Ext.QuickTips.init();

    $("input[type='button']").button();
    $('#frmlicengeneraexpediente').validate({
        rules: {
         //   'txtcertL': 'required',
          //  'txtresoDL' : 'required',
          //  'txtexpL': 'required'
        },
        messages: {
          //  'txtcertL':     'Debe ingresar Nro. Lic.',
         //   'txtresoDL' : 'Debe seleccionar un tipo',
          //  'txtexpL':  'Debe ingresar una fecha'
        },
        debug: true,
        errorElement: 'div',
        ignore: [],
        submitHandler: function(form){
            $.ajax({
                type: "POST",
                url: urljs + "licenexpediente/generaexpediente",
                data: $('#frmlicengeneraexpediente').serializeObject(),
                success: function(data) {
                    infoMessage('Guardar',data);
                        closePopup('#popGenerarExpediente');
                    Ext.getCmp('xgridLicen').getStore().load();
                },
                error: function() {
                }
            });
        }
    });

});







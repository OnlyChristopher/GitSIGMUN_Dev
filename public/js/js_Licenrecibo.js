Ext.onReady(function(){
    Ext.QuickTips.init();

    $("input[type='button']").button();
    $('#frmlicenrecibo').validate({
        rules: {
            'txtinfor': 'required',
            'txtfechinf' : 'required',
            'txtmemo': 'required',
            'txtfechmemo': 'required'
        },
        messages: {
            'txtinfor':     'Debe ingresar Nro. Informe.',
            'txtfechinf' : 'Fecha del Informe',
            'txtmemo':  'Debe ingresar Nro. Memorando',
            'txtfechmemo':  'Fecha del Memorando'
        },
        debug: true,
        errorElement: 'div',
        ignore: [],
        submitHandler: function(form){
            $.ajax({
                type: "POST",
                url: urljs + "Licenrecibo/grabar",
                data: $('#frmlicenrecibo').serializeObject(),
                success: function(data) {
                    infoMessage('Guardar',data);
                        closePopup('#popGenerarRecibo');
                    Ext.getCmp('xgridLicen').getStore().load();
                },
                error: function() {
                }
            });
        }
    });


});


function Generar(){
    var showResult = function(btn){  // ELIMINAR POR MEDIO DEL BOTON
        if(btn=='yes')
        {
            goToFormulario('frmlicenrecibo');
        }

           /* $.ajax({
                type: "GET",
                url: 'proveedor/eliminar',
                data: 'provee_cod='+rec.get('provee_cod'),
                success: function(data){
                    infoMessage('Eliminado ',data);
                    grid.getStore().load(grid.getStore().currentPage);
                }
            });
            */
    };
    confirmMessage('Alerta','Seguro de Generar?',showResult);

}




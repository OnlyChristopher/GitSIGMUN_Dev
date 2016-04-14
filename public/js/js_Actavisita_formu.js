Ext.onReady(function(){
	$("input[type='button']").button();
	
	$("#btnCancelActaVisita").click(function(){
		closePopup('#popupactavisita');
	});

	$("#btnSaveActaVisita").click(function(){
    
      goToFormulario("formActaVisita");
    
    });
    
	//Valida y env�a form contribuyente
	$('#formActaVisita').validate({
		
        debug: true,
		errorElement: 'div',
		errorContainer: $('#errores'),
		submitHandler: function(form){
	           $.ajax({     
                    type: "POST",  
                    url: urljs + "actavisita/grabar",
                    data: $('#formActaVisita').serializeObject(),     
                    success: function(data) {
                       
                            infoMessage('Acta de visita',data);
                            closePopup('#popupactavisita');

                    },     

                    error: function(data) {
                        infoMessage('Error',data);                      
                    } 

                }); 
        }
    		
		
    });
//chkOtrosDoc
    $("#chk10").click(function(){
        
        if($('#chk10').attr('checked'))
        {
            
            $('#txtotrosDoc').removeClass('cajaoff').addClass('caja');
            $("#txtotrosDoc").attr("readonly", false);

        }else{

            $('#txtotrosDoc').val('');
            $('#txtotrosDoc').removeClass('caja').addClass('cajaoff');         
            $("#txtotrosDoc").attr("readonly", true);

        }

    });

    $('#btnImprimirActaVisita').click(function(){
    showPopupReport('schema=&tipo=pdf&nombrereporte=CartaReq_actavisita&param=idCartaReq^'+$('#hcodCartaReq').val(),'reporteActaVisita',700,600,'Reporte Acta de Visita');
    //showPopupReport('schema=&tipo=pdf&nombrereporte=rptdescargo&param=codigo^'+codigo+'|anno^'+anno+'|cod_pred^'+cod_pred+'|anexo^'+anexo+'|sub_anexo^'+sub_anexo,'reportebajapredio',700,600,'Baja de Predio');    
    });


    verificaCheckOtros();
    verificaComboInspector();

});

function verificaCheckOtros(){
    if($('#chk10').attr('checked')){
         $('#txtotrosDoc').removeClass('cajaoff').addClass('caja');
         $("#txtotrosDoc").attr("readonly", false);
    }
}
$( "#cbInspectorII" ).change(function() {
      
        $("#rdIIFirmaInspecSi").removeAttr('checked');
        $('#rdIIFirmaInspecNo').attr('checked',true);
        $("#l1").removeClass('ui-state-active');
        $("#l2").addClass('ui-state-active');
    });
$( "#cbInspectorTI" ).change(function() {
      
        $("#rdTIFirmaInspecSi").removeAttr('checked');
        $('#rdTIFirmaInspecNo').attr('checked',true);
        $("#l3").removeClass('ui-state-active');
        $("#l4").addClass('ui-state-active');
    });

function verificaComboInspector(){
    var valor = $('#cbInspectorII').val();
    if (valor!='') {
        $("#rdIIFirmaInspecNo").removeAttr('checked');        
    };

    var valor = $('#cbInspectorTI').val();
    if (valor!='') {
        $("#rdTIFirmaInspecNo").removeAttr('checked');        
    };
}
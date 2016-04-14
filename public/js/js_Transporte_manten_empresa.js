Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();

});

function guardarempresa(){

	$.ajax({     
		type: "POST",     
		url: urljs + "transporte/guardarempresa",
		data: $('#frmEmpresa').serializeObject(),     
		success: function(data) { 
			infoMessage('SIGMUN	-',data);
			closePopup('#popempresa');
			buscarVehiculos();
			//Ext.getCmp('xtreePanel').getStore().load();
		},     
		error: function() {
		} 
	});
}

function openconductores(){

		var txtid = $('#txtid').val();
		var txtcodigo = $('#txtcodigo').val();
		var txtnombre = $('#txtnombre').val();
		var txtruc = $('#txtruc').val();
		
		showPopup('transporte/buscadorconductores?txtid='+txtid+'&txtcodigo='+txtcodigo+'&txtnombre='+txtnombre+'&txtruc='+txtruc,'#popBuscadorconductores','800','495','Buscador de Personas');
}

// Recupero Valores de Mantpers
window.muestraDatosSol = function(obj) {
	$('#txtcodigo').val(obj.get('codigo'));
	$('#txtnombre').val(obj.get('nombres'));

    if($.trim(obj.get('tipo_doc'))== 'DNI')
    {
      $('#txtNroDoc').val($.trim(obj.get('documento')));
      $('#txtNroRuc').val('');
    }else{
      $('#txtruc').val($.trim(obj.get('documento')));
    }

};
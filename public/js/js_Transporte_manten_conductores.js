Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();

});

function guardarconductores(){

	$.ajax({     
		type: "POST",     
		url: urljs + "transporte/guardarconductores",
		data: $('#frmConductor').serializeObject(),     
		success: function(data) { 
			infoMessage('SIGMUN	-',data);
			buscarConductores();
			closePopup('#popConductores');
			//Ext.getCmp('xtreePanel').getStore().load();
		},     
		error: function() {
		} 
	});
}

// Recupero Valores de Mantpers
window.muestraDatosSol = function(obj) {
	$('#txtconcodigo').val(obj.get('codigo'));
	$('#txtconnombre').val(obj.get('nombres'));
};
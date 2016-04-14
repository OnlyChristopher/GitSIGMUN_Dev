$(function(){
	$("input[type='button']").button();
	$('#txtefectivo').val('0.00').autoNumeric();
	$('#txtcheque').val('0.00').autoNumeric();
	$('#txtcajeroefectivo').val('0.00').autoNumeric();
	$('#txtsistemaefectivo').val('0.00').autoNumeric();

	$('#txtcajerocheque').val('0.00').autoNumeric();
	$('#txtsistemacheque').val('0.00').autoNumeric();

});


function grabarMontos(){

	var efectivo = $('#txtefectivo').val().replace(',','');
	var cheque = $('#txtcheque').val().replace(',','');
	var txtfechas = $('#txtfechas').val();

	Ext.Ajax.request({
		url: urljs + "tesocierre/grabarmontos",
		method: "POST",
		params: {efectivo:efectivo,cheque:cheque,txtfechas:txtfechas},
		success: function(response){
			goToInterno(urljs + "tesocierre/vermontos",'Tesorería Municipal');
		},
		failure: function(response, opts){
			infoMessage('Apertura y Cierre','Error al Ejecutar funcion... ');
		}
	});

}

function abreCierre(){
	goToInterno(urljs + "tesocierre/index",'Tesorería Municipal');
}
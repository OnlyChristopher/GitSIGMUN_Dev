$(function(){
	$("input[type='button']").button();
});

function getcancelar(){
	
	var rdformaPago = $('input[name*="rdformaPago"]:checked').val();
	//--------manuel--------
	var criterio=$('#txtmicriterio').val();
	console.log(criterio);
	//----------------------
	var	cmbTarjeta	= $('#cmbTarjeta').val();
	var	cmbBanco	= $('#cmbBanco').val();
	var	txtCheque	= $('#txtCheque').val();
	var	txtFecCobro	= $('#txtFecCobro').val();
	var	txtObservacion	= $('#txtObservacion').val();
	var	txtCobrar	= $('#txtCobrar').val().replace(',','');
	var txtEfectivo	= $('#txtEfectivo').val().replace(',','');
	/*
	console.log(rdformaPago);
	console.log(cmbTarjeta);
	console.log(cmbBanco);
	console.log(txtCheque);
	console.log(txtObservacion);
	*/
	var grid = Ext.getCmp('xgridRecContri');
	//var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	var selectedRecordsArray = grid.getStore();
	var msgpago='Seguro de Cancelar?';
	
	if(txtEfectivo < txtCobrar && txtEfectivo > 0 ){
		msgpago='Se realizara pago a cuenta, Seguro de Cancelar?';
	};
	if(selectedRecordsArray.data.length > 0){
	//if(selectedRecordsArray.length > 0){
		
		 var showResult = function(btn){
			if(btn=='yes'){
			
				$("#btnAceptarPago").attr('disabled',true)
				$("#rdformaPago1").attr('disabled',true)
				$("#rdformaPago2").attr('disabled',true)
				$("#rdformaPago3").attr('disabled',true)
				
				var idList = [];
				//Ext.each(selectedRecordsArray, function (rec) {
				selectedRecordsArray.each(function (rec){ 
					idList.push(
						{
							num_ingr: 0,
							idrecibo: rec.get('idrecibo'),
							montotal: rec.get('total'),
							codigo:rec.get('codigo'),
							anno: rec.get('anno'), 
							cod_pred: rec.get('cod_pred'),
							anexo:rec.get('anexo'),
							sub_anexo:rec.get('sub_anexo'),
							tipo: rec.get('tipo'),
							tipo_rec: rec.get('tipo_rec'),
							periodo: rec.get('periodo'), 
							imp_insol: rec.get('imp_insol').replace(',',''),
							fact_reaj: rec.get('fact_reaj'), 
							imp_reaj: rec.get('imp_reaj').replace(',',''), 
							fact_mora: rec.get('fact_mora'), 
							imp_mora: rec.get('mora').replace(',',''), 
							costo_emis: rec.get('costo_emis').replace(',',''), 
							observacion:'',
							operador:'',
							estacion:'',
							fech_ing:'01/01/2010',
							descuento: rec.get('benefic').replace(',','')
						});
				});

				Ext.Ajax.request({
		            url: urljs + "tesocajareccontri/registrapago",
		            method: "POST",
		            params: {json: JSON.stringify(idList),rdformaPago:rdformaPago,cmbTarjeta:cmbTarjeta,cmbBanco:cmbBanco,txtCheque:txtCheque,txtFecCobro:txtFecCobro,txtObservacion:txtObservacion,txtCobrar:txtCobrar,txtEfectivo:txtEfectivo,criterio:criterio},
		            success: function(response){
						$("#div_pintaboton").html(response.responseText);
						$("#imprimir").button();
						 mostrarRecContri();
						 closePopup('#popprintdeuda');
		            },
		            failure: function(response, opts){
		            	infoMessage('SIGMUN','Error al intentar cobrar... ');
		            }
		        });
			}
 		};
 		confirmMessage('SIGMUN',msgpago,showResult);
	}else{
		infoMessage('SIGMUN','Seleccione al menos un registro!');
	}
}

function restarmontosapagar()
{
	var monto1 = $("#txtCobrar").val().replace(',','');
	var monto2 = $("#txtEfectivo").val().replace(',','');
	
	if(monto2 == '' || monto2 == null){
		monto2 = 0.00;
	}
	
	var rest = monto2-monto1;
	
	if(rest.toFixed(2) <= 0){
		rest = 0.00;
	}

	$("#txtDevolucion").val(rest.toFixed(2));
}

function getTipoPago(){

var rdformaPago = $('input[name*="rdformaPago"]:checked').val();

if(rdformaPago=='E')
	{
		$('#cmbTarjeta').val('0');
		$('#cmbBanco').val('0');
		$('#cmbTarjeta').attr('disabled', true);
		$('#cmbBanco').attr('disabled', true);
		$('#txtGirador').attr('readonly', true);
		$('#txtCheque').attr('readonly', true);
		$('#txtBanco').attr('readonly', true);
		$('#txtFecCobro').attr('readonly', true);
		$('#txtObservacion').attr('readonly', true);
		
		$('#txtGirador').removeClass("caja").addClass('cajaoff');
		$('#txtCheque').removeClass("caja").addClass('cajaoff');
		$('#txtBanco').removeClass("caja").addClass('cajaoff');
		$('#txtFecCobro').removeClass("caja").addClass('cajaoff');
		$('#txtObservacion').removeClass("caja").addClass('cajaoff');
	}
else if(rdformaPago=='C')
	{
		$('#cmbTarjeta').val('0');
		$('#cmbTarjeta').attr('disabled', true);
		$('#cmbBanco').attr('disabled', false);
		
		$('#txtGirador').attr('readonly', false);
		$('#txtCheque').attr('readonly', false);
		$('#txtBanco').attr('readonly', false);
		$('#txtFecCobro').attr('readonly', false);
		$('#txtObservacion').attr('readonly', false);
		
		$('#txtGirador').removeClass("cajaoff").addClass('caja');
		$('#txtCheque').removeClass("cajaoff").addClass('caja');
		$('#txtBanco').removeClass("cajaoff").addClass('caja');
		$('#txtFecCobro').removeClass("cajaoff").addClass('caja');
		$('#txtObservacion').removeClass("cajaoff").addClass('caja');
	}
else if(rdformaPago=='T')
	{

		$('#cmbBanco').val('0');
		$('#cmbTarjeta').attr('disabled', false);
		$('#cmbBanco').attr('disabled', true);
		
		$('#txtGirador').attr('readonly', true);
		$('#txtCheque').attr('readonly', true);
		$('#txtBanco').attr('readonly', true);
		$('#txtFecCobro').attr('readonly', true);
			$('#txtObservacion').attr('readonly', false);
		
		$('#txtGirador').removeClass("caja").addClass('cajaoff');
		$('#txtCheque').removeClass("caja").addClass('cajaoff');
		$('#txtBanco').removeClass("caja").addClass('cajaoff');
		$('#txtFecCobro').removeClass("caja").addClass('cajaoff');
		$('#txtObservacion').removeClass("cajaoff").addClass('caja');
	}
}
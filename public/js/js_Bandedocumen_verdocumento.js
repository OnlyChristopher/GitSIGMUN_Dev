/*$('#chkdocumento').click(function(){
	//$('#fieldobservacion').hide();
	 alert($('#chkdocumento').val());
});
*/

function mostrardetalle(val,anno)
{
	//alert(val);
	//alert(anno);
	
	if(val)
	{
		$('.chk'+anno).show();
		
	}
	else
	{
		$('.chk'+anno).hide();
	}
		
}

function imprimeOpPdf(){
	var id_valor=$('#txtValordocu').val();
	var num_val=$('#txtNumdocu').val();
	var ano_val=$('#txtAnodocu').val();
	//showPopupReport('tipo=pdf&nombrereporte=rpt_orpa&param=id_valor^'+id_valor+'|num_val^'+num_val+'|ano_val^'+ano_val,'popupdocumento',700,600,'Impresion Orden de Pago');
	
	if(id_valor=='01')
	{
	showPopupReport('tipo=pdf&nombrereporte=rpt_orpa&param=id_valor^'+id_valor+'|num_val^'+num_val+'|ano_val^'+ano_val,'popupdocumento',700,600,'Impresion Orden de Pago');
	}
	if(id_valor=='02')
	{
	showPopupReport('tipo=pdf&nombrereporte=rptRD&param=id_valor^'+id_valor+'|num_val^'+num_val+'|ano_val^'+ano_val,'popupdocumento',700,600,'Impresion de Resolucion de Determinación');
	}
	
}







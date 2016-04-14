
$("input[type='button']").button();


function imprimeExigibilidad(){
	var numexig=$('#txtNroexpe').val();
	var anoexig=$('#txtAniosexpe').val();
	//alert(numexig);
	
	showPopupReport('tipo=pdf&nombrereporte=rptExigibilidad&param=numexig^'+numexig+'|anoexig^'+anoexig,'popupdocumentoj',700,600,'Impresion Orden de Pago');
		
}







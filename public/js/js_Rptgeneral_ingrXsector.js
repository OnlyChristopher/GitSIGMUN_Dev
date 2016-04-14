Ext.onReady(function(){
    Ext.QuickTips.init();
	$("input[type='button']").button();
	
	var fecha = new Date();
	var anio = fecha.getFullYear();
	
	callAjax('rptgeneraling/muestrameses2?anio='+anio,'','#divMeses','html');
	callAjax('rptgeneraling/muestraobligacion','','#divObligacion','html');
	callAjax('rptgeneraling/muestrasector','','#divSector','html');
	
	//todos los meses
	$('input[name^="mes"]').live("click", function(){		
    	selectChecks('#chkTotObligacion','mes[]');
	});
	//concepto
	$('input[name^="chkTotArbitrio"]').live("click", function(){
    	selectChecks('#chkTotArbitrio','chkarbitrio[]');
	});
	//sector
	$('input[name^="chkTotArbitrio"]').live("click", function(){
    	selectChecks('#chkTotArbitrio','chkarbitrio[]');
	});
	//anio
	$('input[name^="chkTotObligacion"]').live("click", function(){
    	selectChecks('#chkTotArbitrio','anio[]');
	});
	
});

$("#btnProcesar_ingre_X_Sector").click(function() {
	
	Procesar_RPT_IngreXSector();
	
});

function Procesar_RPT_IngreXSector()
{
	// el anio de pago
	var anio=$('#cb_anno').val();
	
	//array para los anios de deuda
	var listObligacion = [];
	$('input[name*="anio"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	listObligacion.push($(this).val());
        }         
    });
	
	//array para los meses seleccionados
	var listMeses 	   = [];
	$('input[name*="mes"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	listMeses.push($(this).val());
        }         
    });
	
	//array para los sectores seleccionados 
	var listSector     = [];
	$('input[name*="sector"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	listSector.push($(this).val());
        }         
    });
	
	//array para los arbitrios seleccionados 
	var listArbitrio   = [];
	$('input[name*="chkarbitrio"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	listArbitrio.push($(this).val());
        }         
    });
	 
	 data=[anio,listObligacion,listMeses,listSector,listArbitrio];
	 
	 callAjax('Rptgeneraling/reporte06',{json:JSON.stringify(data)},'#ReporteIngreXSector','html')
	 	
	 
}


$( "#cb_anno" ).change(function() {
	annio=$( "#cb_anno" ).val();
		callAjax('rptgeneraling/muestrameses2?anio='+annio,'','#divMeses','html');
});

function Ver_Detallado(anio_pago,anio_obl,id_zona,id_mes)
{
	//alert(anio_pago+'/'+anio_obl+'/'+id_zona+'/'+id_mes);
	
	showPopup('rptgeneraling/detalleing?anio_pago='+anio_pago+'&anio_obl='+anio_obl+'&id_zona='+id_zona+'&id_mes='+id_mes,'#popDetalleIng','700','400','Detalle Ingreso');
	
	
}



function ReporteIngresoGraph()
{
	//var cb_anno=$('#cb_anno').val();
	//alert(cb_anno); 
	showPopupReportHtml('rptgeneraling/graprpting?anio='+cb_anno,'pouprptgraph01','GRAFICO --> INGRESOS');
}
	
	

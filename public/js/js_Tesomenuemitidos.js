$(function(){
	$("input[type='button']").button();
});

function RecibosEmitidos(){

	var cmbcajas= $('#cmbcajas').val();
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	
	var tipooperacion=$('#tipooperacion').val();
	var extornada=$('#extornada').val();
	var txtcodigo=$('#txtcodigo').val();
	var txtrecibo=$('#txtrecibo').val();

	showPopupReportHtml('tesoreportes/reporterecibosemitidos?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas+'&tipooperacion='+tipooperacion+'&extornada='+extornada+'&txtcodigo='+txtcodigo+'&txtrecibo='+txtrecibo,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function RecibosEmitidosContrib(){

	var cmbcajas= $('#cmbcajas').val();
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	
	var tipooperacion=$('#tipooperacion').val();
	var extornada=$('#extornada').val();
	var txtcodigo=$('#txtcodigo').val();
	var txtrecibo=$('#txtrecibo').val();

	showPopupReportHtml('tesoreportes/reporterecibosemitidoscontrib?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas+'&tipooperacion='+tipooperacion+'&extornada='+extornada+'&txtcodigo='+txtcodigo+'&txtrecibo='+txtrecibo,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function RecibosPartidas(){

	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	var cmbcajas= $('#cmbcajas').val();

	var tipooperacion=$('#tipooperacion').val();
	var cmbnivel=$('#cmbnivel').val();
	
	showPopupReportHtml('tesoreportes/reportexpartida?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas+'&tipooperacion='+tipooperacion+'&cmbnivel='+cmbnivel,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function RecaudacionCajas(){

	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();

	showPopupReportHtml('tesoreportes/reporterecaudacioncajas?desde='+tesodesde+'&hasta='+tesohasta,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function ResumenConceptos(){

	var cmbcajas= $('#cmbcajas').val();
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	
	var tipooperacion=$('#tipooperacion').val();
	var extornada=$('#extornada').val();
	var txtcodigo=$('#txtcodigo').val();
	var txtrecibo=$('#txtrecibo').val();

	showPopupReportHtml('tesoreportes/reporteresumenconceptos?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas+'&tipooperacion='+tipooperacion+'&extornada='+extornada+'&txtcodigo='+txtcodigo+'&txtrecibo='+txtrecibo,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function ResumenConceptosxanio(){

	var cmbcajas= $('#cmbcajas').val();
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	
	var tipooperacion=$('#tipooperacion').val();
	var extornada=$('#extornada').val();
	var txtcodigo=$('#txtcodigo').val();
	var txtrecibo=$('#txtrecibo').val();

	showPopupReportHtml('tesoreportes/reporteresumenconceptosxanio?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas+'&tipooperacion='+tipooperacion+'&extornada='+extornada+'&txtcodigo='+txtcodigo+'&txtrecibo='+txtrecibo,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function ReporteEntrada(){

	var cmbcajas= $('#cmbcajas').val();
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	
	var tipooperacion=$('#tipooperacion').val();
	var extornada=$('#extornada').val();
	var cmbConceptos=$('#cmbConceptos').val();
	var txtrecibo=$('#txtrecibo').val();

	showPopupReportHtml('tesoreportes/reporteentrada?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas+'&tipooperacion='+tipooperacion+'&extornada='+extornada+'&txtrecibo='+txtrecibo+'&cmbConceptos='+cmbConceptos,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function RecibosEmitidosDetallado(){


	var txtmovimiento=$('#txtmovimiento').val();

	showPopupReportHtml('tesoreportes/reporterecibosemitidosdetallado?txtmovimiento='+txtmovimiento,'pouprptemitidos','Reporte de Recibos Emitidos Detallado');
	
}

function ReporteTupaEntrada(){

	var tipooperacion=$('#cmbOperacion').val();
	var cmbOperacionDetalle= $('#cmbOperacionDetalle').val();
	
	if (tipooperacion=='')
		return;
		
	if (cmbOperacionDetalle=='')
		return;
		
	var cmbDescripcionDetalle= $('#cmbOperacionDetalle  option:selected').text();
	//$('#Crd option:selected').text();
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	
	showPopupReportHtml('tesoreportes/reportetupaentrada?desde='+tesodesde+'&hasta='+tesohasta+'&tipooperacion='+tipooperacion+'&cmbOperacionDetalle='+cmbOperacionDetalle+'&cmbDescripcionDetalle='+cmbDescripcionDetalle,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function ReporteIngresoxGerencia(){

	var tipooperacion=$('#cmbOperacion').val();
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	
	showPopupReportHtml('tesoreportes/reporteingresoxgerencia?desde='+tesodesde+'&hasta='+tesohasta+'&tipooperacion='+tipooperacion,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function ReporteIngresoxMultas(){

	var tipooperacion=$('#cmbOperacion').val();
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	
	showPopupReportHtml('tesoreportes/menureportevehiculosmenores?desde='+tesodesde+'&hasta='+tesohasta+'&tipooperacion='+tipooperacion,'pouprptemitidos','Reporte de Recibos Emitidos');
	
}

function ReporteVehiculosMenores(){

	//var tipooperacion=$('#cmbOperacion').val();
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	
	showPopupReportHtml('tesoreportes/reportevehiculosmenores?desde='+tesodesde+'&hasta='+tesohasta,'pouprptemitidos','Reporte de Vehiculos Menores');
	
}



$('#cmbOperacion').change(function(){
    var valOperacion=($(this).val());
	    
    $.ajax({     
		type: "POST",     
		url: "tesoreportes/conceptos",
		data: 'valOperacion='+valOperacion,
		success: function(data) { 
			$('#cmbOperacionDetalle').html(data);		
		}
	});
});
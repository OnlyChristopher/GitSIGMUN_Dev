$(function(){
	$("input[type='button']").button();
});

function abrirventana(idmenu){

	switch(idmenu){
		case  1: goToInterno(urljs + "rptgeneralcont/saldosmensual",'Reportes Contabilidad'); break
	}
}


function GenerarEstadosdeCajas(){
	showPopupReportHtml('tesoreportes/estadosdecajas','pouprptemitidos','Estado de Cajas');
}
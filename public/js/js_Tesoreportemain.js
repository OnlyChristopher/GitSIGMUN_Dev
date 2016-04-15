$(function(){
	$("input[type='button']").button();
});

function abrirventana(idmenu){

	switch(idmenu){
		case  1: goToInterno(urljs + "tesoreportes/menureporteemitidos",'Tesorer&iacute;a Municipal'); break
		case 11: goToInterno(urljs + "tesoreportes/menureportexpartidas",'Tesorer&iacute;a Municipal'); break
		case  5: GenerarEstadosdeCajas(); break
		case 13: goToInterno(urljs + "tesoreportes/menureporteemitidoscontrib",'Tesorer&iacute;a Municipal'); break
		case 14: goToInterno(urljs + "tesoreportes/menureporterecaudacioncajas",'Tesorer&iacute;a Municipal'); break
		case  4: goToInterno(urljs + "tesoreportes/menureporteresumenconceptos",'Tesorer&iacute;a Municipal'); break
		case  3: goToInterno(urljs + "tesoreportes/menureporteresumenconceptosxanio",'Tesorer&iacute;a Municipal'); break
		case 12: goToInterno(urljs + "tesoreportes/menureporteentrada",'Tesorer&iacute;a Municipal'); break
		case  2: goToInterno(urljs + "tesoreportes/menureporteemitidosdetallado",'Tesorer&iacute;a Municipal'); break
		case 16: goToInterno(urljs + "tesoreportes/menureportetupaentrada",'Tesorer&iacute;a Municipal'); break
		case 17: goToInterno(urljs + "tesoreportes/menureporteingresoxgerencia",'Tesorer&iacute;a Municipal'); break
        case  7: goToInterno(urljs + "tesoreportes/menureportereciboemitidosdetallado",'Tesorer&iacute;a Municipal'); break
		case 18: goToInterno(urljs + "tesoreportes/menureportevehiculosmenores",'Tesorer&iacute;a Municipal'); break
		case 19: goToInterno(urljs + "tesoreportes/menureportediario",'Tesorer&iacute;a Municipal'); break
	}
}


	function GenerarEstadosdeCajas(){
	showPopupReportHtml('tesoreportes/estadosdecajas','pouprptemitidos','Estado de Cajas');
}
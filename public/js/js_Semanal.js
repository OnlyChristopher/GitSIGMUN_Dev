$(function(){
	$("input[type='button']").button();
});

function abrirventana(idmenu){

	switch(idmenu){
		case  1: exportarsaldos();break//goToInterno(urljs + "Semanal/Exportasaldos",'Gerencia de Rentas'); break
		case  2: goToInterno(urljs + "Semanal/menureporteingresos",'mod=19.98.00'); break /*exportaringresos();break//*/
		case  3: exportarFraccionamientos();break//goToInterno(urljs + "Semanal/Exportafraccionamientos",'Gerencia de Rentas'); break
	}
}


function exportarsaldos()
{

	var showResult = function(btn){
		if(btn=='yes'){
			
			window.open(urljs+"semanal/exportarrptsaldos");
		}
	};
	confirmMessage('Exportar','Desea exportar los Saldos?',showResult);	

}

function exportaringresos()
{

	var showResult = function(btn){
		if(btn=='yes'){

			Ext.MessageBox.prompt('Ingresos', 'Ingrese el numero del mes:', showResultText);
			//window.open(urljs+"semanal/exportarrptingresos");
		}
	};
	confirmMessage('Exportar','Desea exportar los Ingresos?',showResult);

}

function exportaringresosxrango()
{

	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();

	window.open(urljs+'semanal/exportarrptingresos?desde='+tesodesde+'&hasta='+tesohasta);

}

function exportarFraccionamientos()
{
	var showResult = function(btn){
		if(btn=='yes'){
			
			window.open(urljs+"semanal/exportarrptfracc");
		}
	};
	confirmMessage('Exportar','Desea exportar los fraccionamientos?',showResult);	
}

function showResultText(btn, text){

    var mes=parseInt(text);
    if(mes>0 && mes<13)
    {
    	window.open(urljs+"semanal/exportarrptingresos?mes="+mes);	
    }
	else
	{
		infoMessage('Ingresos','El mes ingresado no es valido');
	}


};

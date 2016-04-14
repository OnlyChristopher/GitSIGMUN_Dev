/*
$(function(){
	
	$("input[type='button']").button();

});

function mostrarRecContri(){

	var listPeriodos = [];	
	$('input[name*="chkperiodo"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	listPeriodos.push($(this).val());
        }         
    });
	
	var listAnios = [];	
	$('input[name*="chkanio"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	listAnios.push($(this).val());
        }         
    });
	var listConceptos = [];	
	$('input[name*="conceptos"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	listConceptos.push($(this).val());
        }         
    });
	
	var listArbitrios = [];	
	$('input[name*="chkarbitrio"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	listArbitrios.push($(this).val());
        }         
    });
	
	var listPredioss = [];	
	$('input[name*="predio"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	listPredioss.push($(this).val());
        }         
    });
	
	data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss];
	
	showPopupData(data,'tesoprepago/index','#popdeudapagar','1000','550','Recibos del Contribuyente');
	
}
function devol()
{
	var a
	Math.round
	a=Number(document.montos.efectivo.value)-Number(document.montos.total.value)
	document.montos.devolucion.value=Math.round(a*100)/100
	}
function ocultaNom(id){
	 var elDiv = document.getElementById(id); //se define la variable "elDiv" igual a nuestro div
	 elDiv.style.display='none'; //damos un atributo display:none que oculta el div	 
	 document.forms.gestion.criterio.value='';
	}
/*function muestra(id){
	 var elDiv = document.getElementById(id); //se define la variable "elDiv" igual a nuestro div
	 elDiv.style.display='block';//damos un atributo display:block que  el div	 
	}*/
	/*
function ocultaCod(id){
	 var elDiv = document.getElementById(id); //se define la variable "elDiv" igual a nuestro div
	 elDiv.style.display='none'; //damos un atributo display:none que oculta el div	 
	 document.forms.gestion.Bcodigo.value='';
	}
function showDiv(div)
{
document.getElementById("1").className = "invisible";
document.getElementById(div).className = "visible";
}
	
function validaFloat(numero){
		
	if (!/^([0-9])*[.]?[0-9]*$/.test(numero)){
	alert("El valor " + numero + " no es un numero");
	return false;

	}else if (!/^([0-9])*[.]?[0-9]*$/.test(numero)){
	alert("El valor " + numero + " no es un numero");
	return false;

	}else if (!/^([0-9])*[.]?[0-9]*$/.test(numero)){
	alert("El valor " + numero + " no es un numero");
	return false;

	}else if(!/^([0-9])*[.]?[0-9]*$/.test(numero)){
	alert("El valor " + numero + " no es un numero");
	return false;

	}else if(!/^([0-9])*[.]?[0-9]*$/.test(numero)){
	alert("El valor " + numero + " no es un numero");
	return false;
	}
}
function muestra(val){
	
	
	//div=val;
	
	efectiv=document.getElementById(val);
	efectiv.style.display ='';

	efectiv.enabled='false';
	
}
function oculta(val){
	//div=val;
	efectiv=document.getElementById(val);
	efectiv.style.display ='none';

	efectiv.enabled='false';
	
}
*/
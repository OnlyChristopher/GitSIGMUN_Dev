Ext.onReady(function(){
    Ext.QuickTips.init();
    $("input[type='button']").button();
});

function pulsar(e) { 
	  tecla = (document.all) ? e.keyCode :e.which; 
	  return (tecla!=13); 
	} 
function mostrbotones() {

	botones = document.getElementById('divboton');
	boton=document.getElementById('boton');//
	radioss=document.getElementById('radios');
	botones.style.display = 'none';
	boton.style.display = '';
	radioss.style.display = '';
	
	botones.enabled='false';
	boton.enabled='false';
	radioss.enabled='false';	}

function ocultabotones() {

	botones = document.getElementById('divboton');
	boton=document.getElementById('boton');
	radioss=document.getElementById('radios');

	botones.style.display = 'none';
	boton.style.display = 'none';
	radioss.style.display = 'none';
	
	botones.enabled='false';
	boton.enabled='false';
	radioss.enabled='false'; 
	}
function efectivo(){
	efectiv=document.getElementById('divboton');
	efectiv.style.display = 'none';
	efectiv.enabled='false';
	
	}
function cheque(){
	efectiv=document.getElementById('divboton');
	efectiv.style.display ='';

	efectiv.enabled='false';
	
}

function prepago(){
	data = [1];
	showPopupData(data,'tesoprepago/index','#popprepago','800','400','Pago');
}

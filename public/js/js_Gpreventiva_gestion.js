$(function(){
	
	$("input[type='button']").button();
	
	DeshabilitaSubsanacion();
	
	//Valida y envía form contribuyente
$('#frmgestion').validate({
		rules: {
		'rdCriterioTipo': 'required',
		'cmbUbicacion': 'required',
		'txtCuadra': 'required',
		'cmbZona': 'required',
		'rdTipozona':'required',
		'txtNombre':'required'
		},
		messages: {
		'rdCriterioTipo': 'Debe seleccionar un criterio',
		'cmbUbicacion': 'Especifique la ubicación',
		'txtCuadra': 'Ingrese una cuadra',
		'cmbZona': 'Especifique la zona',
		'rdTipozona':'Seleccione el tipo de zona',
		'txtNombre':'Digite el nombre'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
		
		var showResult = function(btn){
		if(btn=='yes')
		{
				//*************************************************************************
				//PrograsBar
				//********************
				Ext.MessageBox.show({
				   title: 'En Progreso',
				   msg: 'Generando Resolucion...',
				   progressText: 'Iniciando...',
				   width:300,
				   progress:true,
				   closable:false,
				   //animateTarget: 'btnGrabaGestion'
			   });

			   // this hideous block creates the bogus progress
			   var f = function(v){
					return function(){
						if(v == 12){
							Ext.MessageBox.hide();
							
							var codigo=$('#codigo').val();
							var anno_notif=$('#annonotif').val();
							var id_notif=$('#idnotif').val();
							var t_notif=$('#tnotif').val();
							
							closePopup('#popgestion');
							Ext.getCmp('xgridGpreventiva').getStore().load();
				
							showPopupReport('schema=&tipo=pdf&nombrereporte=rpt_notificamulta&param=CODIGO^'+codigo+'|ANNO^'+anno_notif+'|ID_NOTIF^'+id_notif+'|T_NOTIF^'+t_notif,'pouprptvias',700,600,'Resolucion de Multas Administrativas');
											
							
						}else{
							var i = v/11;
							Ext.MessageBox.updateProgress(i, Math.round(100*i)+'% completado');
						}
				   };
			   };
			   for(var i = 1; i < 13; i++){
				   setTimeout(f(i), i*500);
			   }
			//**************
			//fin PrograsBar
			//********************************************************************
				$.ajax({     
					type: "POST",  
					url: "gpreventiva/grabargestion",
					data: $('#frmgestion').serializeObject(),       
					error: function() {
					} 
				}); 
				//alert('grabo');
		}		
		};	
		confirmMessage('Gestionar','Seguro de Generar la Resolucion?',showResult);               
			
		}
	});
	
	
	if($('#chkResoluciones').is(':checked'))
	{
	HabilitaSubsanacion();
	}
	
});


function DeshabilitaSubsanacion(){
	$("#txtFechResolucion").attr("disabled", "disabled");
	$("#txtFechCarta").attr("disabled", "disabled");
	$("#txtSustento").attr("disabled", "disabled");
	$("#txtNCarta").attr("disabled", "disabled");
	$("#txaObservacion").attr("disabled", "disabled");
}

function HabilitaSubsanacion(){
	$("#txtFechResolucion").removeAttr("disabled");
	$("#txtFechCarta").removeAttr("disabled");
	$("#txtSustento").removeAttr("disabled");
	$("#txtNCarta").removeAttr("disabled");
	$("#txaObservacion").removeAttr("disabled");
}

$('#chkResoluciones').click(function() {
	if($('#chkResoluciones').is(':checked'))
	{
		HabilitaSubsanacion();
	}
	else
	{
		DeshabilitaSubsanacion();
	}	
});







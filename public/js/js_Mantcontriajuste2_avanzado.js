// Ext.onReady(function(){
    // Ext.QuickTips.init();
    
	// $("input[type='button']").button();
// });

$(function(){
	
	$("input[type='button']").button();
	
	//Valida y envía form contribuyente
	$('#frmavanzado').validate({
		rules: {
		'cmb_tipo': 'required',
		'txtnumero': 'required'
		},
		messages: {
		'cmb_tipo': 'Debe seleccionar un criterio',
		'txtnumero': 'Especifique documento'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			
			var opcion=$('#txtopcion').val();
			alert($('#in_sal_nuevo').val());
			
			var imp=$('#in_sal_nuevo').val();
			imp=Math.round(imp*100)/100;
			var de=$('#de_sal_nuevo').val();
			de=Math.round(de*100)/100;
			var reaj=$('#re_sal_nuevo').val();
			reaj=Math.round(reaj*100)/100;
			var sal=$('#mo_sal_nuevo').val();
			sal=Math.round(sal*100)/100;
			
			if(opcion=='1')
			{
				$.ajax({     
					type: "POST",  
					url: 'mantcontriajuste2/procesar?idrecibo='+$('#txtidrecibo').val()+'&codigo='+$('#txtcodigo').val()+'&imp='+imp+'&de='+de+'&reaj='+reaj+'&sal='+sal,
					data: $('#frmavanzado').serializeObject(),     
					success: function(data) { 					
						
						infoMessage('Ajuste generado',data);
						
					},     
					error: function() {
					} 
				}); 				
			}
			else
			{
				$.ajax({     
					type: "POST",  
					url: "mantcontriajuste2/baja",
					data: $('#frmavanzado').serializeObject(),     
					success: function(data) { 					
						
						infoMessage('Generando baja',data);
						
					},     
					error: function() {
					} 
				});
			}
			
			closePopup('#popcatasmapa');
			//$('#gridCuentacte').html('');
			Ext.getCmp('xgridCuentacte').getStore().load();
		}
	});
});



$('#btnCalcular').click(function(){
	
	
	var res1=$('#in_deb_nuevo').val()-$('#in_cre_nuevo').val();
	res1=Math.round(res1*100)/100;
	var res2=$('#de_deb_nuevo').val()-$('#de_cre_nuevo').val();
	res2=Math.round(res2*100)/100;
	var res3=$('#re_deb_nuevo').val()-$('#re_cre_nuevo').val();
	res3=Math.round(res3*100)/100;
	var res4=$('#mo_deb_nuevo').val()-$('#mo_cre_nuevo').val();
	res4=Math.round(res4*100)/100;
	
	var res5=res1+res2+res3+res4;
	
	res5=Math.round(res5*100)/100;
	
	$('#in_sal_nuevo').val(res1);
	$('#de_sal_nuevo').val(res2);
	$('#re_sal_nuevo').val(res3);
	$('#mo_sal_nuevo').val(res4);
	$('#to_sal_nuevo').val(res5);

});

function RealizaOperacion(flag){
	alert(flag);
	$('#txtopcion').val('');
	$('#txtopcion').val(flag);
	
	goToFormulario('frmavanzado');

};

function ActionBaja(){
	//alert('Baja');
	$('#txtopcion').val('2');
};


function mostrarRecContri(criterio){

	$('#txt_total').val('0.00');
	$('#txtemis').val('0.00');
	$('#txtmora').val('0.00');
	$('#txtinso').val('0.00');
	$('#txtreaj').val('0.00');
	
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
	
	var codigo=$('#divCodigo').html();
	var rdestado = $('input[name*="rdformaPago"]:checked').val();
	//------------Agrego Manuel----------//
	var flag=criterio;
	//alert (flag);
	//-----------------------------------//
	data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss,codigo,rdestado,flag];
	
	var grid = Ext.getCmp('xgridCuentacte');
	var store = grid.getStore();
	store.removeAll();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {json: JSON.stringify(data)};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}



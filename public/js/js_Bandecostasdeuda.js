Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button(); 
	
});

function grabardeuda(){		
	$.ajax({     
		type: "POST",  
		url: "bandecostascontri/gcostasdeuda",
		data: $('#frmdeuda').serializeObject(),     
		success: function() {					
			var monto = $('#txtdeudacalc').val();
			
			$('#xdeuda').val(parseFloat(monto));
			
			cargarCosta();
			closePopup('#popDeuda');			
		}
	});
}
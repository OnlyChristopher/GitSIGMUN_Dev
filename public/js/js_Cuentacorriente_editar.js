$(function(){
	$("input[type='button']").button();
	
	$('#frmultedit').validate({
		rules: {
			'codigo_autoridad_municipal': 	{ required: true },
			'fecha_multa': 	{ required: true },
			'preimpreso': 	{ required: true },
			'estado': 	{ required: true },
			'codigo_area': 	{ required: true },
			'tipo_infraccion': 	{ required: true },
			'codigo_infraccion': 	{ required: true }
		},
		messages: {
			'codigo_autoridad_municipal': 	{ required: 'Seleccione una autoridad' },
			'fecha_multa': 	{ required: 'Ingrese la fecha' },
			'preimpreso': 	{ required: 'Ingrese el preimpreso' },
			'estado': 	{ required: 'Seleccione el estado' },
			'codigo_area': 	{ required: 'Seleccione el 1° campo de infraccion' },
			'tipo_infraccion': 	{ required: 'Seleccione el 2° campo de infraccion' },
			'codigo_infraccion': 	{ required: 'Seleccione el 3° campo de infraccion' },
			
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			$.ajax({     
				type: "POST",     
				url: urljs + "multasadmin/grabar",
				data: $(form).serializeObject(),     
				success: function(data) { 
					infoMessage('Guardar Multas Administrativas',data);
					closePopup('#popnewmulta');					
					Ext.getCmp('xgridMultas').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}
	});
	
	//Todo a mayúscula
	$('.caja').blur(function(event){
		this.value = this.value.toUpperCase();
	});
	
	calcUIT($('#fecha_multa').val());
	$('#cantidad').change(function(){
		calcTotal($('#codigo_infraccion').val());
	});
	
	
});
//click en cmb codigo_area y me pinta mi otro combo tipo_infraccion, filtrando el codigo
function FiltraCodArea(val){
	var contentAjax = $('#tipo_infraccion').html('...');
	$.ajax({
		type: "POST",
		url: urljs + "multasadmin/selectarea",
		data: "codigo_area="+val,
		success: function(response){
			contentAjax.html(response);			
		}
	});
} 

function FiltraTipInfrac(val){
	var contentAjax = $('#codigo_infraccion').html('...');
	$.ajax({
		type: "POST",
		url: urljs + "multasadmin/selecttipo",
		data: "tipo_infraccion="+val,
		success: function(response){
			contentAjax.html(response);			
		}
	});
}

function calcUIT(val){
	var anno = val.substr(6,4);
	$.ajax({
		type: "POST",
		url: urljs + "multasadmin/selectuit",
		data: "anno="+anno,
		success: function(response){
			$('#base').val(response);
			calcTotal($('#codigo_infraccion').val());		
		}
	});
	
}

function calcTotal(val){
	var partes = val.split(':');
	var factor = parseFloat(partes[6]);
	
	var cantidad = parseFloat($('#cantidad').val());
	var base = parseFloat($('#base').val());
	
	var total = factor*base/100*cantidad;
	
	if(!isNaN(factor))
		$('#factor').val(factor.toFixed(2));
	else
		$('#factor').val('000');
	
	if(!isNaN(factor))	
		$('#montoTotal').val(total.toFixed(2));
	else
		$('#montoTotal').val('000');
}
$('#preimpreso').autotab({ format: 'numeric'});
$('#cantidad').autotab({ format: 'numeric'});
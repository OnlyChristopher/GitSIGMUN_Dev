$("#btnBusquedacri").click(function(){
	var valor=$("#txtBusqueda").val();
	if(valor==""){
		infoMessage('Buscar','Por favor ingrese el nombre a buscar');
	}
	else{
	 $("input[type='button']").button();
	 txtBusqueda=$("#txtBusqueda").val();
	 txtBusqueda=$("#txtBusqueda").val();
		$.ajax({     
			type: "POST",     
			url: "/mantbusqueda/busqueda",
			data: 'txtCriterio='+txtBusqueda,     
			success: function(data) { 
				$("#detallesBusqueda").html(data);
			},     
			error: function() {
			} 
		}); 
	}
});

function mostrarZona(codigoVia,codZona,nombreZona,codUrbanizacion,nombreUrbanizacion,nombrevia){
	$("#txtViacodigo").val(codigoVia);
	$("#txtZonacodigo").val(codZona);
	$("#txtZona").val(nombreZona);
	$("#txtUrbacodigo").val(codUrbanizacion);
	$("#txtUrbanizacion").val(nombreUrbanizacion);
	$("#txtViacontri").val(nombrevia);
	closePopup('#popbusqueda');
}

function selectVia(nombre,cod,cp,arancel){
	$("#txtVia").val(nombre);
	$("#txtCvia").val(cod);
	$("#txtCp").val(cp);
	$("#txtArancel").val(arancel);
	closePopup('#popup5');
	//$('#popup5').dialog('close');
}

function mostrarTodo(codigoVia,codZona,nombreZona,codUrbanizacion,nombreUrbanizacion,nombrevia,nombre,cod,cp,arancel)
{
	var a=$("#hd_opcion").val();

	if (a==1)
	{
		selectVia(nombre,cod,cp,arancel);
	}
	else
	{
		mostrarZona(codigoVia,codZona,nombreZona,codUrbanizacion,nombreUrbanizacion,nombrevia);
	}
}

$("input[type='button']").button();

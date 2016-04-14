$(function(){
  
	disabledFormulario();
	ocultarDiv('btnSavePu');
	$("input[type='button']").button();

	$('#frmpredios').validate({
		rules: {
	
		//'txtDpto': { required: false, digits: false },
		//'txtMza': { required: false, digits: false }
	},
		messages: {
		
//		'txtDpto': { required: '', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
//		'txtMza': { required: '', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
	},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			var carga='true/Guardando Datos';
			var carga=validadatos();
			array=carga.split('/');
			errorMessage('Error',array[0]);
			if (array[0]=='false')
			{
				errorMessage('Error',array[1]);
			}
			else
			{
				infoMessage('Mensaje',array[1]);
				$.ajax({     
						type: "POST",     
						url: "rentas/gpredios",
						data: $("#frmpredios").serializeObject(),     
						success: function(data) { 
							//alert(data);
							$("#MSG_ERROR").html(data);
							closePopup('#popuppred');
						},     
						error: function() {
						} 
					});
			} 
		}
	});
	
	$('#btnAddDetPisos').trigger('click');
});

function validadatos(){
	var txtDir = $('#txtDir').val().trim();
	var txtNro = $('#txtNro').val().trim();
	var txtLte = $('#txtLte').val().trim();
	var txtSubLte = $('#txtSubLte').val().trim();
	var txtFrontis = $('#txtFrontis').val().trim();
	var cmbUso = $('#cmbUso').val().trim();
	var cmbTipPredio = $('#cmbTipPredio').val().trim();
	var cmbEstadoConst = $('#cmbEstadoConst').val().trim();
	var cmbCondicion = $('#cmbCondicion').val().trim();
	var txtAreaTerreno = $('#txtAreaTerreno').val().trim();
	var txtAreaComun = $('#txtAreaComun').val().trim();
	var txtPorcenPropiedad = $('#txtPorcenPropiedad').val().trim();
	var txtFecAdqui = $('#txtFecAdqui').val().trim();
	var txtFecTrans = $('#txtFecTrans').val().trim();
	var cbAfectDiaDesde = $('#cbAfectMesDesde').val().trim();
	var cbAfectDiaHasta = $('#cbAfectMesHasta').val().trim();
	var txtUbiPar = $('#txtUbiPar').val().trim();
	var cb_limpieza = $('#cb_limpieza').val().trim();
	var cb_relleno = $('#cb_barrido').val().trim();
	var cb_parque = $('#cb_parque').val().trim();
	var cb_serenazgo = $('#cb_serenazgo').val().trim();
	var ck_AfecArbitrio=$('#txtArbAfecto').is(":checked")
	var retorno='true/Guardando Datos';
	
	if(txtDir!=""){
		if (txtNro==""){
			retorno='false/Debe Ingresar Nro';
			return(retorno);
		}
		if (txtLte==""){
			retorno='false/Debe Ingresar Lote';
			return(retorno);	
		}
		if (cmbUso==""){
			retorno='false/Debe Seleccionar Uso del Predio';
			return(retorno)	;
		}
		if (cmbTipPredio==""){
			retorno='false/Debe Seleccionar Tipo del Predio';
			return(retorno);	
		}
		if (cmbEstadoConst==""){
			retorno='false/Debe Seleccionar Estado de Construccion del Predio';
			return(retorno);	
		}
		if (cmbCondicion==""){
			retorno='false/Debe Seleccionar Condicion de la Propiedad del Predio';
			return(retorno);	
		}
		if (txtAreaTerreno==""){
			retorno='false/Debe Ingresar el Area de Terreno del Predio';
			return(retorno);	
		}
		if (txtAreaComun==""){
			retorno='false/Debe Ingresar un Valor al Area Comun'
			return(retorno);	
		} 
		if (txtPorcenPropiedad==""){
			retorno='false/Debe Ingresar Porcentage de la Propiedad'
			return(retorno);	
		}
		if (txtFecAdqui==""){
			retorno='false/Debe Ingresar Fecha de Adquisicion del Predio';
			return(retorno);	
		}
		if (ck_AfecArbitrio==true)
		{
			if (cbAfectDiaDesde==""){
				retorno='false/Debe Seleccionar Mes de Inicio para Arbitrios';
				return(retorno);	
			}
			if (cbAfectDiaHasta==""){
				retorno='false/Debe Seleccionar Mes Final para Arbitrios';
				return(retorno);	
			}
		}
		if (cb_limpieza==""){
			retorno='false/Debe Seleccionar uso para Limpieza Publica';
			return(retorno);	
		}
		if (cb_relleno==""){
			retorno='false/Debe Seleccionar uso para Relleno Sanitario'
			return(retorno);	
		}
		if (cb_parque==""){
			retorno='false/Debe Seleccionar uso para Parques y Jardines';
			return(retorno);	
		}
		if (cb_serenazgo==""){
			retorno='false/Debe Seleccionar uso para Serenazgo';
			return(retorno)	;
		}
	}
	else
	{
		retorno='false/Debe Ingresar Referencia en la Direccion';
		return(retorno)
	}
	return(retorno);
}

//Eventos Predios
function ShowTabPisos(val){
	if(val=='01')
		$('#tab2').hide();
	else
		$('#tab2').show();
}

$("#cmbEstadoConst").change(function() {
	var val=$(this).val();
	if(val=='01')
		$('#tab2').hide();
	else
		$('#tab2').show();
});

//Eventos Pisos
function addRowPisos(){	
	var strHtmlTr = "";
	var IdRow = $('#cDetPisos').val();
	var retorno=EvaluarNivel(IdRow);
	array=retorno.split('/');
	
	if (array[0]=='false')
	{
		errorMessage('Error',array[1]);
	}
	else
	{
		var pisos="style='text-transform:uppercase;' onkeypress='return valoresUnitarios(event,"+'"pisos"'+")' onblur='this.value = this.value.toUpperCase()'";
		var unitar="style='text-transform:uppercase;' onkeypress='return valoresUnitarios(event,"+'"letras"'+")' onblur='this.value = this.value.toUpperCase()'";
		var material="style='text-transform:uppercase;' onkeypress='return valoresUnitarios(event,"+'"material"'+")' onblur='this.value = this.value.toUpperCase()'";
		var estados="style='text-transform:uppercase;' onkeypress='return valoresUnitarios(event,"+'"estados"'+")' onblur='this.value = this.value.toUpperCase()'";
		var clasificacion="style='text-transform:uppercase;' onkeypress='return valoresUnitarios(event,"+'"clasificacion"'+")' onblur='this.value = this.value.toUpperCase()'";
		
		IdRow++;
		strHtmlTr += "<tr id='rowPisos" + IdRow + "'>";
		strHtmlTr += "<td><input type='text' name='detallesPisos[0][]' "+pisos+" id='nivel_"+IdRow+"' class='caja' size='3' maxlength='3' onclick='cargapiso("+IdRow+")'/></td>";
	    strHtmlTr += "<td><input type='text' name='detallesPisos[1][]' "+clasificacion+" id='c_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
		strHtmlTr += "<td><input type='text' name='detallesPisos[2][]' "+material+" id='m_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
		strHtmlTr += "<td><input type='text' name='detallesPisos[3][]' "+estados+" id='e_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
		strHtmlTr += "<td><input type='text' name='detallesPisos[4][]' id='mes_"+IdRow+"' class='caja' size='2' maxlength='2' onclick='cargapiso("+IdRow+")'/></td>";
		strHtmlTr += "<td><input type='text' name='detallesPisos[5][]' id='anoc_"+IdRow+"' class='caja' size='4' maxlength='4' onclick='cargapiso("+IdRow+")'/></td>";
		strHtmlTr += "<td><input type='text' name='detallesPisos[6][]' "+unitar+" id='muro_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
		strHtmlTr += "<td><input type='text' name='detallesPisos[7][]' "+unitar+" id='techo_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
		strHtmlTr += "<td><input type='text' name='detallesPisos[8][]' "+unitar+" id='piso_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
		strHtmlTr += "<td><input type='text' name='detallesPisos[9][]' "+unitar+" id='puerta_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
	    strHtmlTr += "<td><input type='text' name='detallesPisos[10][]' "+unitar+" id='revestim_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
	    strHtmlTr += "<td><input type='text' name='detallesPisos[11][]' "+unitar+" id='bano_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
	    strHtmlTr += "<td><input type='text' name='detallesPisos[12][]' "+unitar+" id='inst_"+IdRow+"' class='caja' size='1' maxlength='1' onclick='cargapiso("+IdRow+")'/></td>";
	    strHtmlTr += "<td><input type='text' name='detallesPisos[13][]' id='areacons_"+IdRow+"' class='caja' size='8' /></td>";
	    strHtmlTr += "<td><input type='text' name='detallesPisos[14][]' id='areacom_"+IdRow+"' class='caja' size='8' onclick='cargapiso("+IdRow+")'/><input type='hidden' name='detallesPisos[15][]' value='0' id='itempiso_"+IdRow+"' class='caja'/></td>";
	    strHtmlTr += "<td align='center'>";
	    strHtmlTr += "<input type='button' class='btnDelDetPisos' value='Eliminar' onclick='delRowPisos("+IdRow+")'/>";
	    strHtmlTr += "</td>";
	    strHtmlTr += "</tr>";
		
		$("#detallePisos").append(strHtmlTr);
		$('input[name*="detallesPisos[13][]"]').autoNumeric();
		$('input[name*="detallesPisos[14][]"]').autoNumeric();
		$('.btnDelDetPisos').button();
		$('#cDetPisos').val(IdRow);
	}
}
function calcValorPiso(id,e){
	
	var nivel = $('#nivel_'+id).val();
	var c = $('#c_'+id).val();
	var m = $('#m_'+id).val();
	var e = $('#e_'+id).val();
	var mes = $('#mes_'+id).val();
	var anoc = $('#anoc_'+id).val();
	var muro = $('#muro_'+id).val();
	var techo = $('#techo_'+id).val();
	var piso = $('#piso_'+id).val();
	var puerta = $('#puerta_'+id).val();
	var revestim = $('#revestim_'+id).val();
	var bano = $('#bano_'+id).val();
	var inst = $('#inst_'+id).val();
	var areacons = $('#areacons_'+id).val();
	var areacom = $('#areacom_'+id).val();
	var anno=$('#hd_anno').val();
	var cad="";
	
var retorno=valores_pisos(nivel,c,m,e,mes,anoc,muro,techo,piso,puerta,revestim,bano,inst,areacons,areacom,anno);
if(retorno==true){	
	$.ajax({     
		type: "POST",     
		url: "rentas/valorpiso",
		data: 'nivel='+nivel+'&c='+c+'&m='+m+'&e='+e+'&mes='+mes+'&anoc='+anoc+'&muro='+muro+'&techo='+techo+'&piso='+piso+'&puerta='+puerta+'&revestim='+revestim+'&bano='+bano+'&inst='+inst+'&areacons='+areacons+'&areacom='+areacom+'&anno='+anno,     
		success: function(data) { 
			
			if(data=='*'){
				infoMessage('Alerta','Datos Incompletos para Visualizar el Valor del Piso');
			}
			else{
				array=data.split('/');
				$("#txtValUni").val(array[0]);
				$("#txtIncre").val(array[1]);
				$("#txtDeprec").val(array[2]);
				$("#txtValUnitDeprec").val(array[3]);
				$("#txtValAreaConst").val(array[4]);
				$("#txtValorPiso").val(array[4]);
				$("#txtValorAreaCom").val(0.00);
			}		
		},     
		error: function() {
		} 
	}); 
	}
	else{
				$("#txtValUni").val('');
				$("#txtIncre").val('');
				$("#txtDeprec").val('');
				$("#txtValUnitDeprec").val('');
				$("#txtValAreaConst").val('');
				$("#txtValorPiso").val('');
				$("#txtValorAreaCom").val('');
	}		
}
function cargapiso(i){
	calcValorPiso(i);
}

function evenRowsPisos(){
	/*$('input[name*="detallesPisos[7][]"]').autotab({ maxlength: 14, format: 'vpisos' });
	$('input[name*="detallesPisos[8][]"]').autotab({ maxlength: 14, format: 'vpisos' });
	$('input[name*="detallesPisos[9][]"]').autotab({ maxlength: 14, format: 'vpisos' });*/
	
/*	$('select[name*="detallesPisos[0][]"]').bind('change', function() {
  		var id = $(this).attr('id');
		id = id.split('_');
		//changeMed(id[1]);
		alert(id[1]);
	}); 
	
	$('input[name*="detallesPisos[14][]"]').bind('focus', function() { 
		var id = $(this).attr('id');
		//alert(id);
		id = id.split('_');
		//calcValorPiso(id[1]);
	});
	*/
}

function delRowPisos(sRow){
	if(confirm("Seguro de eliminar?"))
	{
		var codigo=$('#hd_codigo').val().trim();
		var anno=$('#hd_anno').val().trim();
		var id_item=$('#itempiso_'+sRow).val().trim();
		
		if (id_item>0){
			$.ajax({     
			type: "POST",     
			url: "rentas/eliminapiso",
			data: 'codigo='+codigo+'&anno='+anno+'&id_item='+id_item,
			success: function(data) { 
				$("#msg_error_pisos").html(data);
				},     
			error: function() {
				}
			}); 	
		
		}
		$("#rowPisos" + sRow).remove();	
	}
}

//Eventos Instalaciones
function addRowInstal(){
	
	var strHtmlTr = "";
	var IdRow = $('#cDetInstal').val();
	IdRow++;
	
	strHtmlTr += "<tr id='rowInstal" + IdRow + "'>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[0][]' id='install01_"+IdRow+"'  class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[1][]' id='install02_"+IdRow+"' class='cajaoff' size='50' maxlength='50'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[2][]' id='install03_"+IdRow+"' class='cajaoff' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[3][]' id='install04_"+IdRow+"' class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[4][]' id='install05_"+IdRow+"' class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[5][]' id='install06_"+IdRow+"' class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[6][]' id='install07_"+IdRow+"' class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[7][]' id='install08_"+IdRow+"' class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[8][]' id='install09_"+IdRow+"' class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[9][]' id='install10_"+IdRow+"' class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[10][]' id='install1_"+IdRow+"' class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[11][]' id='install12_"+IdRow+"' class='caja' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='text' name='detallesInstal[12][]' id='install13_"+IdRow+"' class='caja off' size='1' maxlength='14'/></td>";
	strHtmlTr += "<td><input type='checkbox' name='detallesInstal[13][]' id='install14_"+IdRow+"' class='caja' size='1' maxlength='14'/><input type='hidden' name='detallesInstal[14][]' id='install15_"+IdRow+"' value='0'/></td>";
	strHtmlTr += "<td align='center'>";
    strHtmlTr += "<input type='button' class='btnDelDetInstal' value='Eliminar' onclick='delRowInstal("+IdRow+")'/>";
    strHtmlTr += "</td>";
    strHtmlTr += "</tr>";
	
	$("#detalleInstal").append(strHtmlTr);
	$('input[name*="detallesInstal[6][]"]').autoNumeric();
	$('input[name*="detallesInstal[7][]"]').autoNumeric();
	$('input[name*="detallesInstal[8][]"]').autoNumeric();
	$('.btnDelDetInstal').button();
	$('#cDetInstal').val(IdRow);
	evenRowsInstal();
}

function evenRowsInstal(){

	$('input[name*="detallesInstal[7][]"]').autotab({ maxlength: 14, format: 'number' });
	$('input[name*="detallesInstal[8][]"]').autotab({ maxlength: 14, format: 'number' });
	$('input[name*="detallesInstal[9][]"]').autotab({ maxlength: 14, format: 'number' });

}

function delRowInstal(sRow){
	if(confirm("Seguro de eliminar?"))
	{
		$("#rowInstal" + sRow).remove();	
	}
}
function valores_pisos(nivel,c,m,e,anoc,muro,techo,piso,puerta,revestim,bano,inst,areacons,areacom,anno){
	var retorno=true;
	if(nivel==''){
		retorno=false;
	}
	if(c==''){
		retorno=false;
	}
	if(m==''){
		retorno=false;
	}
	if(e==''){
		retorno=false;
	}if(anoc==''){
		retorno=false;
	}
	if(muro==''){
		retorno=false;
	}
	if(techo==''){
		retorno=false;
	}
	if(piso==''){
		retorno=false;
	}if(puerta==''){
		retorno=false;
	}
	if(revestim==''){
		retorno=false;
	}
	if(bano==''){
		retorno=false;
	}
	if(inst==''){
		retorno=false;
	}
	if(areacons==''){
		retorno=false;
	}
	if(areacom==''){
		retorno=false;
	}
	if(anno==''){
		retorno=false;
	}
	return retorno;
}
function valores_pisosNivel(nivel,c,m,e,anoc,muro,techo,piso,puerta,revestim,bano,inst,areacons,areacom,anno){
	var retorno='true/Datos Correctos';
	if(nivel==''){
		retorno='false/Debe Ingresar un Nivel';
	}
	if(c==''){
		retorno='false/Debe Ingresar Tipo de Construción';
	}
	if(m==''){
		retorno='false/Debe Ingresar Tipo de Materiales';
	}
	if(e==''){
		retorno='false/Debe Ingresar Tipo de Estado de Depreciación';
	}if(anoc==''){
		retorno='false/Debe Ingresar el Año de Construcción';
	}
	if(muro==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Muros';
	}
	if(techo==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Techos';
	}
	if(piso==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Piso';
	}
	if(puerta==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Puertas';
	}
	if(revestim==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Revestim';
	}
	if(bano==''){
		retorno='false/Debe Ingresar el Tipo de Edifacion para Baño';
	}
	if(inst==''){
		retorno='false/Debe Ingresar el Tipo de Edifación para Instalación';
	}
	if(areacons==''){
		retorno='false/Debe Ingresar el Tipo de Edifación para Techos';
	}
	if(areacom==''){
		retorno='false/Debe Ingresar el Area Construida';
	}
	if(anno==''){
		retorno='false/Debe Ingresar el Año';
	}
	return retorno;
}
function EvaluarNivel(id){
	
	var nivel = $('#nivel_'+id).val();
	var c = $('#c_'+id).val();
	var m = $('#m_'+id).val();
	var e = $('#e_'+id).val();
	var mes = $('#mes_'+id).val();
	var anoc = $('#anoc_'+id).val();
	var muro = $('#muro_'+id).val();
	var techo = $('#techo_'+id).val();
	var piso = $('#piso_'+id).val();
	var puerta = $('#puerta_'+id).val();
	var revestim = $('#revestim_'+id).val();
	var bano = $('#bano_'+id).val();
	var inst = $('#inst_'+id).val();
	var areacons = $('#areacons_'+id).val();
	var areacom = $('#areacom_'+id).val();
	var anno=$('#hd_anno').val();
	var cad="";
	cad=valores_pisosNivel(nivel,c,m,e,anoc,muro,techo,piso,puerta,revestim,bano,inst,areacons,areacom,anno);
	return cad;
}
function disabledFormulario(){
	$('#tabs-1').find('input, textarea, button, select').attr('disabled','disabled');
	$('#tabs-2').find('input, textarea, button, select').attr('disabled','disabled');
	$('#tabs-3').find('input, textarea, button, select').attr('disabled','disabled');
	$('#tabs-4').find('input, textarea, button, select').attr('disabled','disabled');
}
function RemovDisabledFormulario(){
	$('#tabs-1').find('input, textarea, button, select').removeAttr("disabled");
	$('#tabs-2').find('input, textarea, button, select').removeAttr("disabled");
	$('#tabs-3').find('input, textarea, button, select').removeAttr("disabled");
	$('#tabs-4').find('input, textarea, button, select').removeAttr("disabled");
}
function ocultarDiv(obj){
	$('#'+obj).hide();
}
function mostrarDiv(obj){
	$('#'+obj).show();
}
function editarPu(){
	RemovDisabledFormulario();
	ocultarDiv('btnEdtPu');
	mostrarDiv('btnSavePu');
}

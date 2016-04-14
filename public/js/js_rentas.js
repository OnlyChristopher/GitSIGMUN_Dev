$(function(){
		$("input[type='button']").button();
		$('#mes').blur(function(){$(this).val(rellenarceros($(this).val(), 2));});
		$('#nropiso').blur(function() {$(this).val(rellenarceros($(this).val(), 3));});
		$('#mes_cons').blur(function() {$(this).val(rellenarceros($(this).val(), 2));});
			
		if($("#cmbEstadoConst").val()=='01'){$('#tab3').hide();}else{$('#tab3').show();}
		if($("#hd_tipomov").val()=='M'){$('#divCondominante').show();} 

		if($("#cmbCondicion").val()=='05'){
			$('#txtNroCond').removeClass('cajaoff');
				$('#txtNroCond').addClass('caja');
				$("#txtNroCond").attr("readonly", false);
		}
		else{
			$('#txtNroCond').removeClass('caja');
				$('#txtNroCond').addClass('cajaoff');
				$("#txtNroCond").attr("readonly", true);
		}
		
		/*$('#txtNroCond').keyup(function (e) {
			var cantidad=0;
			cantidad=$(this).val();
			
			if (cantidad<=1){
				$('#txtPorcenPropiedad').val($('#txtHiddenPorcen').val());
			}
			else
			{
				$('#txtPorcenPropiedad').val($('#txtHiddenPorcen').val()/cantidad);
			}			
		});*/
		
	$('#frmpredios').validate({
		rules: {
	},
		messages: {
	},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			$('#btnSavePu').hide();
			var carga='true/Guardando Datos';
			var carga=validadatos();
			array=carga.split('/');
			
			if (array[0]=='false')
			{
				errorMessage('Error',array[1]);
				$('#btnSavePu').show();
			}
			else
			{
				data = $("#frmpredios").serializeObject();
				
				var dataConst = new Array();			
				var gridConst = Ext.getCmp('xgridFicIndConst');
				if(gridConst.getStore().data.length>0){
					var i=0;
					gridConst.getStore().each(function(rec) {
						var rowsConst = {
							idpisos: rec.get('idpisos'),
							cidindi: rec.get('cidindi'),
							nropiso: rec.get('nropiso'),
							mescons: rec.get('mescons'),
							aniocons: rec.get('aniocons'),
							iddepcl: rec.get('iddepcl'),
							iddepma: rec.get('iddepma'),
							iddepco: rec.get('iddepco'),
							esmuros: rec.get('esmuros'),
							estecho: rec.get('estecho'),
							acapiso: rec.get('acapiso'),
							acapuer: rec.get('acapuer'),
							acareve: rec.get('acareve'),
							acabanio:rec.get('acabanio'),
							instele: rec.get('instele'),
							arconde: rec.get('arconde'),
							arconve: rec.get('arconve'),
							uconant: rec.get('uconant'),
							umedida: rec.get('umedida'),
							referencia: rec.get('referencia')
						}
						dataConst[i] = rowsConst;
						i++;
					});
					
					data['Const'] = dataConst;
				}
				
			var dataInstal = new Array();			
			var gridInstal = Ext.getCmp('xgridFicIndInstal');
			if(gridInstal.getStore().data.length>0){
				var i=0;
				gridInstal.getStore().each(function(rec) {
					var rowsInstal = {
						idinsta: rec.get('idinsta'),
						cidindi: rec.get('cidindi'),
						cidinst: rec.get('cidinst'),
						mescons: rec.get('mescons'),
						aniocons: rec.get('aniocons'),
						iddepcl: rec.get('iddepcl'),
						iddepma: rec.get('iddepma'),
						iddepco: rec.get('iddepco'),
						dmlargo: rec.get('dmlargo'),
						dmancho: rec.get('dmancho'),
						dmaltos: rec.get('dmaltos'),
						protota: rec.get('protota'),
						vunimed: rec.get('vunimed'),
						vdescri: rec.get('vdescri'),
						referenciainst: rec.get('referenciainst')
					}
					dataInstal[i] = rowsInstal;
					i++;
				});
				data['Instal'] = dataInstal;
			}
			
			var dataDoc = new Array();			
			var gridInstal = Ext.getCmp('xgridFicIndDoc');
			if(gridInstal.getStore().data.length>0){
				var i=0;
				gridInstal.getStore().each(function(rec) {
					var rowsDoc = {
						iddoc: rec.get('iddoc'),
						idreg: rec.get('idreg'),
						docnombre: rec.get('docnombre'),
						docdetalle: rec.get('docdetalle')
					}
					dataDoc[i] = rowsDoc;
					i++;
				});
				data['Doc'] = dataDoc;
			}
				
				Ext.Ajax.request({
				  url: urljs + "Rentas/gpredios",
				  method: "POST",
				  params: {json: JSON.stringify(data)},
				  success: function(response){
					  infoMessage('Caracteriscas Predio',response.responseText +' Se Registro Correctamente');
					  closePopup('#popuppred');
					  Ext.getCmp('xgridtblpredio').getStore().reload();
				  }
			});

			}
		}
	});
	
	
	ocultarDiv('btnSavePu');
	
	eventConst('C');
	loadGridConst();
	redimGridHidden('xgridFicIndConst',700,300,2);
		
	eventInstal('C');
	loadGridInstal();
	redimGridHidden('xgridFicIndInstal',790,250,3);
	
	eventDoc('C');
	loadGridDocumentos();
	redimGridHidden('xgridFicIndDoc',790,400,3);
	
	$('#hd_valores_pisos').hide();
});
var tipo_mov=$('#hd_tipomov').val();
	
		switch(tipo_mov){
			case 'N'://Nuevo
					mostrarDiv('btnSavePu');
					ocultarDiv('btnEdtPu');
					$("#btnEdtPu").click();
				break;
			case 'I':
					mostrarDiv('btnSavePu');
					ocultarDiv('btnEdtPu');
					$("#btnEdtPu").click();
				break;
			case 'E'://EDITAR
					disabledFormulario();
					ocultarDiv('btnSavePu');
					mostrarDiv('btnEdtPu');
					ocultarDiv('btnBuspred');
				break;
			case 'M':
					mostrarDiv('btnSavePu');
					mostrarDiv('btnBuspred');
					ocultarDiv('btnEdtPu');
					$("#btnEdtPu").click();
				break;
		}

function validadatos(){
	var txtDir = $('#txtDir').val();
	var txtNro = $('#txtNro').val();
	var txtLte = $('#txtLte').val();
	var txtSubLte = $('#txtSubLte').val();
	var txtFrontis = $('#txtFrontis').val();
	var cmbUso = $('#cmbUso').val();
	var cmbTipPredio = $('#cmbTipPredio').val();
	var cmbEstadoConst = $('#cmbEstadoConst').val();
	var cmbCondicion = $('#cmbCondicion').val();
	var txtAreaTerreno = $('#txtAreaTerreno').val();
	var txtAreaComun = $('#txtAreaComun').val();
	var txtPorcenPropiedad = $('#txtPorcenPropiedad').val();
	var txtFecAdqui = $('#txtFecAdqui').val();
	var txtFecTrans = $('#txtFecTrans').val();
	var cbAfectDiaDesde = $('#cbAfectMesDesde').val();
	var cbAfectDiaHasta = $('#cbAfectMesHasta').val();
	var txtUbiPar = $('#txtUbiPar').val();
	var cb_limpieza = $('#cb_limpieza').val();
	var cb_relleno = $('#cb_barrido').val();
	var cb_parque = $('#cb_parque').val();
	var cb_serenazgo = $('#cb_serenazgo').val();
	var ck_AfecArbitrio=$('#txtArbAfecto').is(":checked");
	var txtNroCond=$("#txtNroCond").val();
	var retorno='true/Guardando Datos';
	
	if(cmbUso!=""){
		
		if (cmbEstadoConst==""){
			retorno='false/Debe Seleccionar Estado de Construccion del Predio';
			return(retorno);	
		}
		if (cmbCondicion==""){
			retorno='false/Debe Seleccionar Condicion de la Propiedad del Predio';
			return(retorno);	
		}
		/*if(cmbCondicion=="05" ){
			if(txtNroCond<2){
			retorno='false/Debe Ingresar como minimo 2 condominantes';
			}
		}*/
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
/*		if (txtFecAdqui==""){
			retorno='false/Debe Ingresar Fecha de Adquisicion del Predio';
			return(retorno);	
		}*/
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
			if(Number(cbAfectDiaDesde)>=Number(cbAfectDiaHasta)){
				retorno='false/El Ultimo Mes de Afectacion no puede ser menor al mes inicial';
				return(retorno);	
			}
		}
		if (cb_limpieza==""){
			retorno='false/Debe Seleccionar uso para Limpieza Publica';
			return(retorno);	
		}
		if (cb_relleno==""){
			retorno='false/Debe Seleccionar uso para Relleno Sanitario';
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
		retorno='false/Debe Seleccionar Tipo del Predio';
		return(retorno)
	}
	return(retorno);
}

function ShowTabPisos(val){
	if(val=='01'){
		$('#tab3').hide();
	}
	else{
		$('#tab3').show();
	}
}

$("#cmbEstadoConst").change(function() {
	var val=$(this).val();
	if(val=='01'){
		$('#tab3').hide();
	}
	else{
		$('#tab3').show();
	}
});

$("#cmbCondicion").change(function() {
	var val=$(this).val();
	if(val=='05'){
		$('#txtNroCond').removeClass('cajaoff');
		$('#txtNroCond').addClass('caja');
		 $("#txtNroCond").attr("readonly", false);
		 }
	else{
		$('#txtNroCond').removeClass('caja');
		$('#txtNroCond').addClass('cajaoff');
		$("#txtNroCond").attr("readonly", true);
		}
});

//Eventos Pisos

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
	//calcValorPiso(i);
}

//Eventos Instalaciones
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
		return retorno;
	}
	if(c==''){
		retorno=false;
		return retorno;
	}
	if(m==''){
		retorno=false;
		return retorno;
	}
	if(e==''){
		retorno=false;
		return retorno;
	}if(anoc==''){
		retorno=false;
		return retorno;
	}
	if(muro==''){
		retorno=false;
		return retorno;
	}
	if(techo==''){
		retorno=false;
		return retorno;
	}
	if(piso==''){
		retorno=false;
		return retorno;
	}if(puerta==''){
		retorno=false;
		return retorno;
	}
	if(revestim==''){
		retorno=false;
		return retorno;
	}
	if(bano==''){
		retorno=false;
		return retorno;
	}
	if(inst==''){
		retorno=false;
		return retorno;
	}
	if(areacons==''){
		retorno=false;
		return retorno;
	}
	if(areacom==''){
		retorno=false;
		return retorno;
	}
	if(anno==''){
		retorno=false;
		return retorno;
	}
	return retorno;
}
function valores_pisosNivel(nivel,tipo,c,m,e,anoc,muro,techo,piso,puerta,revestim,bano,inst,areacons,areacom,anno){
	var retorno='true/Datos Correctos';
	if(nivel=='' || nivel=='000'){
		retorno='false/Debe Ingresar un Nivel que no sea 0';
		return(retorno);
	}
	if(tipo==''){
		retorno='false/Debe Seleccionar un Tipo de Nivel';
		return(retorno);
	}
	if(c==''){
		retorno='false/Debe Seleccionar un Tipo de Construci�n';
		return(retorno);
	}
	if(m==''){
		retorno='false/Debe Seleccionar un Tipo de Materiales';
		return(retorno);
	}
	if(e==''){
		retorno='false/Debe Seleccionar un Tipo de Estado de Depreciaci�n';
		return(retorno);
	}if(anoc=='' || anoc<0 || anoc==0 ){
		retorno='false/Debe Ingresar el A�o de Construcci�n';
		return(retorno);
	}
	if(muro==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Muros';
		return(retorno);
	}
	if(techo==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Techos';
		return(retorno);
	}
	if(piso==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Piso';
		return(retorno);
	}
	if(puerta==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Puertas';
		return(retorno);
	}
	if(revestim==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci&oacute;n para Revestim';
		return(retorno);
	}
	if(bano==''){
		retorno='false/Debe Ingresar el Tipo de Edifacion para Ba�o';
		return(retorno);
	}
	if(inst==''){
		retorno='false/Debe Ingresar el Tipo de Edifaci�n para Instalaci�n';
		return(retorno);
	}
	if(areacons=='' || areacons<=0){
		retorno='false/Debe Ingresar el Area de Construccion';
		return(retorno);
	}
	if(areacom==''){
		retorno='false/Debe Ingresar el Area Comun Construida';
		return(retorno);
	}
	if(anno==''){
		retorno='false/Debe Ingresar el A�o';
		return(retorno);
	}
	return(retorno);
}

function EvaluarNivel(){
	
	var nivel = $('#nropiso').val();
	var tipo = $('#cb_tiponivel').val();
	var c = $('#cb_clasifica').val();
	var m = $('#cb_material').val();
	var e = $('#cb_estado').val();
	var mes = $('#mes').val();
	var anoc = $('#anoc').val();
	var muro = $('#muro').val();
	var techo = $('#techo').val();
	var piso = $('#piso').val();
	var puerta = $('#puerta').val();
	var revestim = $('#revestim').val();
	var bano = $('#bano').val();
	var inst = $('#inst').val();
	var areacons = $('#areacons').val();
	var areacom = $('#areacom').val();
	var anno=$('#hd_anno').val();
	var cad="";
	cad=valores_pisosNivel(nivel,tipo,c,m,e,anoc,muro,techo,piso,puerta,revestim,bano,inst,areacons,areacom,anno);
	//alert(cad);
	return cad;
}
function valida_instalacion(){
	var retorno='true/Datos Correctos';
	var detalle_inst = $('#detalle_inst').val();
	var id_materia_i = $('#id_materia_i').val();
	var id_estados_i = $('#id_estados_i').val();
	var id_clafica_i = $('#id_clafica_i').val();
	var alto = $('#alto').val();
	var largo = $('#largo').val();
	var ancho = $('#ancho').val();
	var ano_cons = $('#ano_cons').val();
	var cantidad = $('#cantidad').val();
	
	
	if(detalle_inst==''){
		retorno='false/Debe seleccionar una instalaci&oacute;n';
		return retorno;
	}
	if(id_materia_i==''){
		retorno='false/Debe seleccionar el tipo de materiales';
		return retorno;
	}
	if(id_estados_i==''){
		retorno='false/Debe seleccionar el estado de depreciaciaci&oacute;n';
		return retorno;
	}
	if(id_clafica_i==''){
		retorno='false/Debe seleccionar el tipo de clasificaci&oacute;n';
		return retorno;
	}
	if(alto==''){
		retorno='false/Debe ingresar el alto de la instalaci&oacute;n';
		return retorno;
	}
	if(largo==''){
		retorno='false/Debe ingresar el largo de la instalaci&oacute;n';
		return retorno;
	}
	if(ancho==''){
		retorno='false/Debe ingresar el ancho de la instalaci&oacute;n';
		return retorno;
	}
	if(ano_cons==''){
		retorno='false/Debe ingresar el a�o de la instalaci&oacute;n';
		return retorno;
	}
	if(cantidad==''){
		retorno='false/Debe ingresar el cantidad de la instalaci&oacute;n';
		return retorno;
	}
	return retorno;
}
function disabledFormulario(){
	$('#tabs-1').find('input, textarea, button, select').attr('disabled','disabled');
	$('#tabs-2').find('input, textarea, button, select').attr('disabled','disabled');
	$('#tabs-3').find('input, textarea, button, select').attr('disabled','disabled');
	$('#tabs-4').find('input, textarea, button, select').attr('disabled','disabled');	
	$('#tabs-5').find('input, textarea, button, select').attr('disabled','disabled');	
	$('#tabs-6').find('input, textarea, button, select').attr('disabled','disabled');	
}
function RemovDisabledFormulario(){
	$('#tabs-1').find('input, textarea, button, select').removeAttr("disabled");
	$('#tabs-2').find('input, textarea, button, select').removeAttr("disabled");
	$('#tabs-3').find('input, textarea, button, select').removeAttr("disabled");
	$('#tabs-4').find('input, textarea, button, select').removeAttr("disabled");
	$('#tabs-5').find('input, textarea, button, select').removeAttr("disabled");
	$('#tabs-6').find('input, textarea, button, select').removeAttr("disabled");	
	ocultarDiv('btnEdtPu');
	mostrarDiv('btnSavePu');
}
function RemovDisabledGrilla(){
	var xgridInstal = Ext.getCmp('xgridFicIndInstal');
	var xgridConst = Ext.getCmp('xgridFicIndConst');
	var xgridDoc = Ext.getCmp('xgridFicIndDoc');
	
	xgridInstal.setDisabled(false);
	xgridConst.setDisabled(false);
	xgridDoc.setDisabled(false);
}
function RemoveDisabled(){
RemovDisabledFormulario();
RemovDisabledGrilla();
}

function ocultarDiv(obj){
	$('#'+obj).hide();
}
function mostrarDiv(obj){
	$('#'+obj).show();
}
function agrega_valor(obj){
	array=obj.split('-');
	$('#codigo_inst').val(array[0]);
	$('#uni_med').val(array[1]);
}

window.muestraDatos = function(obj) { 
	$('#txtVia').val(obj.get('nomvia'));
	$('#txtCvia').val(obj.get('codigo'));
	$('#txtCp').val(obj.get('nomurba'));
	$('#txtSector').val(obj.get('nomzona'));
	$('#txtArancel').val(obj.get('arancel'));
	
};
//PISOS
function eventConst(act){
	switch(act){
		case 'A':
			
			$("input[type='button']").button();
			$('#divAddConst').show();
			$('#rowSaveConst').show();
			$('#rowCancelConst').show();
			$('#rowAddConst').hide();
			$('#rowEditConst').hide();
			$('#btnDelConst').hide();
			
			$("#divMuro").hide();	
			$("#divTecho").hide();
			$("#divPiso").hide();
			$("#divVentana").hide();
			$("#divRevestimiento").hide();
			$("#divBanos").hide();
			$("#divInstalaciones").hide();
			
			
			//$('#rowSaveConst').show();
			//enableButton('#rowSaveConst');
			//disableButton('#btnDelConst');
			$("#actConst").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicIndConst');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventConst('A');
				
				$("#itempiso").val(rec.get('idpisos'));
				//alert(rec.get('arconde'));
				$("#nropiso").val(rec.get('nropiso'));
				//$("#cb_tiponivel").val(rec.get('tiponivel'));
				$("#cb_tiponivel option[value="+ rec.get('cidindi') +"]").attr("selected",true);
				$("#mes").val(rec.get('mescons'));
				$("#anoc").val(rec.get('aniocons'));
				//$('#cb_clasifica').val(rec.get('iddepcl'));
				$("#cb_clasifica option[value="+ rec.get('iddepcl') +"]").attr("selected",true);
				//$("#cb_material").val(rec.get('iddepma'));
				$("#cb_material option[value="+ rec.get('iddepma') +"]").attr("selected",true);
				//$('#cb_estado').val(rec.get('iddepco'));
				$("#cb_estado option[value="+ rec.get('iddepco') +"]").attr("selected",true);
				$('#muro').val(rec.get('esmuros'));
				$('#techo').val(rec.get('estecho'));
				$('#piso').val(rec.get('acapiso'));
				$('#puerta').val(rec.get('acapuer'));
				$('#revestim').val(rec.get('acareve'));
				$('#bano').val(rec.get('acabanio'));
				$('#inst').val(rec.get('instele'));
				$('#areacons').val(rec.get('arconde'));
				//$('#areacom').val(rec.get('arconve'));
				$('#areacom').val(rec.get('uconant'));
				$('#referencia').val(rec.get('referencia'));
				$("#cb_unidad_medida option[value="+ rec.get('umedida') +"]").attr("selected",true);
				$("#actConst").val('E');
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddConst').hide();
			$('#rowAddConst').show();
			$('#rowEditConst').show();
			$('#rowSaveConst').hide();
			$('#rowCancelConst').hide();
			$('#btnDelConst').show();
			$("#itempiso").val('');
			$("#nropiso").val('');
			$('#cb_tiponivel option:first').attr('selected', true);
			$("#mes").val('');
			$("#anoc").val('');
			$('#cb_clasifica option:first').attr('selected', true);
			$("#cb_material option:first").attr('selected', true);
			$('#cb_estado option:first').attr('selected', true);
			$('#muro').val('');
			$('#techo').val('');
			$('#piso').val('');
			$('#puerta').val('');
			$('#revestim').val('');
			$('#bano').val('');
			$('#inst').val('');
			$('#areacons').val('');
			$('#areacom').val('');
			$('#referencia').val('');
			$('#cb_unidad_medida option:first').attr('selected', true);
			
			$("#divMuro").hide();	
			$("#divTecho").hide();
			$("#divPiso").hide();
			$("#divVentana").hide();
			$("#divRevestimiento").hide();
			$("#divBanos").hide();
			$("#divInstalaciones").hide();
			
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicIndConst');
			var store = grid.getStore();
			var retorno="";
			    retorno=EvaluarNivel();
				
				array=retorno.split('/');
				//alert(array[1])
			if (array[0]=='false')
			{infoMessage('Guardar Piso',array[1]);
			}
			if(array[0]=='true'){
				if($("#actConst").val()=='A')
				{
					var r = Ext.create('Construc', {
						idpisos: $('#itempiso').val(),
						nropiso: $('#nropiso').val(),
						cidindi: $('#cb_tiponivel').val(),
						mescons: $('#mes').val(),
						aniocons: $('#anoc').val(),
						iddepcl: $('#cb_clasifica').val(),
						iddepma: $('#cb_material').val(),
						iddepco: $('#cb_estado').val(),
						esmuros: $('#muro').val(),
						estecho: $('#techo').val(),
						acapiso: $('#piso').val(),
						acapuer: $('#puerta').val(),
						acareve: $('#revestim').val(),
						acabanio: $('#bano').val(),
						instele: $('#inst').val(),
						arconde: $('#areacons').val(),
					
						uconant: $('#areacom').val(),
						referencia: $('#referencia').val(),
						umedida: $("#cb_unidad_medida").val()
					
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idpisos', $('#itempiso').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nropiso', $('#nropiso').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidindi', $('#cb_tiponivel').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('mescons', $('#mes').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('aniocons', $('#anoc').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepcl', $('#cb_clasifica').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepma', $('#cb_material').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepco', $('#cb_estado').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('esmuros', $('#muro').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('estecho', $('#techo').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('acapiso', $('#piso').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('acapuer', $('#puerta').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('acareve', $('#revestim').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('acabanio', $('#bano').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('instele', $('#inst').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('arconde', $('#areacons').val());
				
					grid.getStore().getAt(store.indexOf(sm[0])).set('uconant', $('#areacom').val());	
					
					grid.getStore().getAt(store.indexOf(sm[0])).set('referencia', $('#referencia').val());
					
					grid.getStore().getAt(store.indexOf(sm[0])).set('umedida', $('#cb_unidad_medida').val());
				
					grid.getStore().sync();				
				}
				eventConst('C');
			}
				
		break;
		case 'D':
		
		
			var grid = Ext.getCmp('xgridFicIndConst');
			var store = grid.getStore();			
			if (grid.getSelectionModel().selected.length == 1){
			var showResult = function(btn){
					if(btn=='yes'){
						var sm = grid.getSelectionModel().getSelection();
						var rec = store.getAt(store.indexOf(sm[0]));
						var cad=rec.get('idpisos');
						
						if(cad==''){
							store.remove(grid.getSelectionModel().getSelection());		
							infoMessage('Eliminar','Piso Eliminado!');
						}
						else{
						Ext.Ajax.request({
						  url: urljs + "Rentas/eliminapiso",
						  method: "POST",
						  params: {codigo: $('#hd_codigo').val(),anno: $('#hd_anno').val(),
									cod_pred: $('#hd_idanexo').val(),anexo: $('#hd_anexo').val(),
									sub_anexo: $('#hd_subanexo').val(),id_item: cad},
						  success: function(response){	
							  	data = response.responseText;
								if(data=='1'){
									//var dm = grid.getSelectionModel();
									store.remove(grid.getSelectionModel().getSelection());	
									infoMessage('Eliminar','Piso Eliminado!');
								}		
								
						  }
						});
						}
						}
				};
				confirmMessage('Eliminar','Desea eliminar el registro seleccionado?',showResult);
			}
			else{
				infoMessage('Eliminar','Debe seleccionar un registro!');
			}
		break;
	}
}

	function loadGridConst(){
	var codigo=$('#hd_codigo').val();
	var anno=$('#hd_anno').val();
	var cod_pred="";
	var anexo="";
	var sub_anexo="";
	var tipo_mov=$('#hd_tipomov').val();
	
	switch(tipo_mov){
		case 'N':
			cod_pred="";
			anexo="";
			sub_anexo="";
			break;
		case 'I':
			cod_pred= $('#hd_idanexo').val();
			anexo= $('#hd_anexo').val();
			sub_anexo= $('#hd_subanexo2').val();		
			break;
		case 'E':
			cod_pred= $('#hd_idanexo').val();
			anexo= $('#hd_anexo').val();
			sub_anexo= $('#hd_subanexo').val();		
			break;
		case 'M':
			codigo=$('#hd_codigo2').val();
			cod_pred=$('#hd_idanexo2').val();
			anexo=$('#hd_anexo2').val();
			sub_anexo=$('#hd_subanexo2').val();
			break;
	}
	
	
	Ext.define('Construc', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idpisos', type: 'string'},
			{name: 'cidindi', type: 'string'},
			{name: 'nropiso', type: 'string'},
			{name: 'tipon', type: 'string'},
			{name: 'mescons', type: 'string'},
			{name: 'aniocons', type: 'string'},
			{name: 'iddepcl', type: 'string'}, //MEP
			{name: 'iddepma', type: 'string'}, //ECS
			{name: 'iddepco', type: 'string'}, //ECC
			{name: 'esmuros', type: 'string'},
			{name: 'estecho', type: 'string'},
			{name: 'acapiso', type: 'string'},
			{name: 'acapuer', type: 'string'},
			{name: 'acareve', type: 'string'},
			{name: 'acabanio', type: 'string'},
			{name: 'instele', type: 'string'},
			{name: 'arconde', type: 'string'}, //Area Dec
			{name: 'uconant', type: 'string'},
			{name: 'referencia', type: 'string'},
			{name: 'umedida', type: 'string'}
			//Uca
			/*
			'iddepcl'=>trim($row[9]),--id_clasifica
			'iddepma'=>trim($row[10]),--id_materia
			'iddepco'=>trim($row[11]),--id_estados
			*/
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Construc',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicIndConst',
		columnLines: true,
		store: store,
		width: '700px',
		height: '140px',        
		viewConfig: {
        	listeners: {   
				itemclick: 
            	function(s,r) {
					Ext.Ajax.request({
						  url: urljs + "Rentas/valorpiso",
						  method: "POST",
						  params: {nivel : r.data.nropiso,c : r.data.iddepcl,m : r.data.iddepma,e : r.data.iddepco,anoc : r.data.aniocons,muro : r.data.esmuros,techo : r.data.estecho,piso : r.data.acapiso,puerta : r.data.acapuer,
									revestim : r.data.acareve,bano : r.data.acabanio,inst : r.data.instele,areacons : r.data.arconde,areacom : r.data.uconant,anno : $('#hd_anno').val()},
						  success: function(response){	
								data=response.responseText;
								if(data=='*'){
									infoMessage('Alerta','Datos Incompletos para Visualizar el Valor del Piso');
								}
								else{
									$('#hd_valores_pisos').show();
									array=data.split('/');
									$("#txtValUni").val(array[0]);
									$("#txtIncre").val(array[1]);
									$("#txtDeprec").val(array[2]);
									$("#txtValUnitDeprec").val(array[3]);
									$("#txtValAreaConst").val(array[4]);
									$("#txtValorPiso").val(array[4]);
									$("#txtValorAreaCom").val(0.00);
								}		
							  
						  }
					});	
					
			},
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "Rentas/gridpisos",
						  method: "POST",
						  params: {codigo: codigo,anno: anno,cod_pred: cod_pred,anexo: anexo,sub_anexo: sub_anexo},
						  success: function(response){	
//alert(response.responseText);						  
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idpisos + '|';
									addRowsConst(data[i]);
								}
								//Para ver si se elimina
								$('#oldConItems').val(strItems);
						  }
					});					
            	}
            }
    	},
        columns: [ 
        {
            dataIndex: 'idpisos',
			hidden: true
        },{
            text: 'Nivel ',
            width: 50,		
            dataIndex: 'nropiso'
        },{
			text: 'Tipo ',
			width: 50,
			dataIndex: 'cidindi'
		},{
			text: 'Mes',
            width: 50,
            dataIndex: 'mescons'
        },{
			text: 'A&ntilde;o',
            width: 40,
            dataIndex: 'aniocons'
        },{
			text: 'MEP',
            width: 50,
            dataIndex: 'iddepcl'
        },{
			text: 'ECS',
            width: 50,
            dataIndex: 'iddepma'
        },{
			text: 'ECC',
            width: 50,
            dataIndex: 'iddepco'
        },{
			text: '(1)',
            width: 30,
            dataIndex: 'esmuros'
        },{
			text: '(2)',
            width: 30,
            dataIndex: 'estecho'
        },{
			text: '(3)',
            width: 30,
            dataIndex: 'acapiso'
        },{
			text: '(4)',
            width: 30,
            dataIndex: 'acapuer'
        },{
			text: '(5)',
            width: 30,
            dataIndex: 'acareve'
        },{
			text: '(6)',
            width: 30,
            dataIndex: 'acabanio'
        },{
			text: '(7)',
            width: 30,
            dataIndex: 'instele'
        },{
			text: 'Area Con.',
            width: 70,
            dataIndex: 'arconde'
        },{
			text: 'A. Comun',
            width: 70,
			dataIndex: 	'uconant'
        },{
			text: 'U.Medid.',
            width: 60,
			dataIndex: 	'umedida'
        },{
			text: 'Referencia',
            width: 100,
			dataIndex: 	'referencia'
        }]
    });
    
    grid.render('gridFicIndConst');
	grid.setDisabled(true);
}
function addRowsConst(data){
	var grid = Ext.getCmp('xgridFicIndConst');
	var store = grid.getStore();
	var r = Ext.create('Construc', {
		idpisos: data.idpisos,
		cidindi: data.cidindi,
		nropiso: data.nropiso,
		mescons: data.mescons,
		aniocons: data.aniocons,
		iddepcl: data.iddepcl,
		iddepma: data.iddepma,
		iddepco: data.iddepco,
		esmuros: data.esmuros,
		estecho: data.estecho,
		acapiso: data.acapiso,
		acapuer: data.acapuer,
		acareve: data.acareve,
		acabanio: data.acabanio,
		instele: data.instele,
		arconde: data.arconde,
		arconve: data.arconve,
		uconant: data.uconant,
		vdescri: data.vdescri,
		referencia: data.referencia,
		umedida: data.umedida
	});
	store.insert(store.data.length, r);
}
//INSTALACIONES
function loadGridInstal(){

	var codigo=$('#hd_codigo').val();
	var anno=$('#hd_anno').val();
	var cod_pred="";
	var anexo="";
	var sub_anexo="";
	var tipo_mov=$('#hd_tipomov').val();
	
	switch(tipo_mov){
		case 'N':
			cod_pred="";
			anexo="";
			sub_anexo="";
			break;
		case 'I':
			cod_pred= $('#hd_idanexo').val();
			anexo= $('#hd_anexo').val();
			sub_anexo= $('#hd_subanexo2').val();		
			break;
		case 'E':
			cod_pred= $('#hd_idanexo').val();
			anexo= $('#hd_anexo').val();
			sub_anexo= $('#hd_subanexo').val();		
			break;
		case 'M':
			codigo=$('#hd_codigo2').val();
			cod_pred=$('#hd_idanexo2').val();
			anexo=$('#hd_anexo2').val();
			sub_anexo=$('#hd_subanexo2').val();	
			break;
	}
	
	Ext.define('Instal', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idinsta', type: 'string'},
			{name: 'cidindi', type: 'string'},
			{name: 'cidinst', type: 'string'},
			{name: 'cidnomb', type: 'string'},
			{name: 'mescons', type: 'string'},
			{name: 'aniocons', type: 'string'},
			{name: 'iddepcl', type: 'string'}, //MEP
			{name: 'iddepma', type: 'string'}, //ECS
			{name: 'iddepco', type: 'string'}, //ECC
			{name: 'dmlargo', type: 'string'},
			{name: 'dmancho', type: 'string'},
			{name: 'dmaltos', type: 'string'},
			{name: 'protota', type: 'string'},
			{name: 'vunimed', type: 'string'},
			{name: 'vdescri', type: 'string'},
			{name: 'referenciainst'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Instal',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicIndInstal',
		columnLines: true,
		store: store,
		width: '950px',
		height: '140px',        
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "rentas/gridinstalacion",
						  method: "POST",
						  params: {codigo: codigo,anno: anno,cod_pred: cod_pred,anexo: anexo,sub_anexo: sub_anexo},
						  success: function(response){			
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idinsta + '|';
									addRowsInstal(data[i]);
								}
								//Para ver si se elimina
								$('#oldInsItems').val(strItems);
						  }
					});					
            	}
            }
    	},
        columns: [ 
        {
            dataIndex: 'idinsta',
			hidden: true
        },{
            dataIndex: 'cidindi',
			hidden: true
        },{
            text: 'Nivel',
			width: 50,
			dataIndex: 'cidinst'
        },{
            text: 'Descripci&oacute;n',
            width: 200,		
            dataIndex: 'cidnomb'
        },{
			text: 'Mes',
            width: 35,
            dataIndex: 'mescons'
        },{
			text: 'A&ntilde;o',
            width: 40,
            dataIndex: 'aniocons'
        },{
			text: 'MEP',
            width: 50,
            dataIndex: 'iddepcl'
        },{
			text: 'ECS',
            width: 50,
            dataIndex: 'iddepma'
        },{
			text: 'ECC',
            width: 50,
            dataIndex: 'iddepco'
        },{
			text: 'Largo',
            width: 50,
            dataIndex: 'dmlargo'
        },{
			text: 'Ancho',
            width: 50,
            dataIndex: 'dmancho'
        },{
			text: 'Alto',
            width: 50,
            dataIndex: 'dmaltos'
        },{
			text: 'Cant.',
            width: 37,
            dataIndex: 'protota'
        },{
			text: 'Med.',
            width: 37,
            dataIndex: 'vunimed'
        },{
			text: 'Valor Total',
            width: 80,
            dataIndex: 'vdescri'
        },{
			text: 'Referencia',
            width: 80,
            dataIndex: 'referenciainst'
        }]
    });
    
    grid.render('gridFicIndInstal');
	grid.setDisabled(true);
}

function eventInstal(act){
	switch(act){
		case 'A':
			$('#divAddInstal').show();
			$('#rowSaveInstal').show();
			$('#rowCancelInstal').show();
			$('#rowAddInstal').hide();
			$('#rowEditInstal').hide();
			//$('#btnDelInstal').attr('disabled',true);
			//disableButton('#btnDelInstal');
			$("#actInstal").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicIndInstal');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventInstal('A');
				
				$("#id_reginstal").val(rec.get('idinsta'));
				$("#detalle_inst option[value="+rec.get('cidinst')+"]").attr("selected",true);
				$("#item_instalacion").val(rec.get('cidindi'));
				$("#codigo_inst").val(rec.get('cidinst'));
				$("#mes_cons").val(rec.get('mescons'));
				$("#ano_cons").val(rec.get('aniocons'));
				$("#id_clafica_i option[value="+ rec.get('iddepcl')+"]").attr("selected",true);
				$("#id_materia_i option[value="+ rec.get('iddepma')+"]").attr("selected",true);
				$("#id_estados_i option[value="+ rec.get('iddepco')+"]").attr("selected",true);
				$('#largo').val(rec.get('dmlargo'));
				$('#ancho').val(rec.get('dmancho'));
				$('#alto').val(rec.get('dmaltos'));
				$('#cantidad').val(rec.get('protota'));
				$('#uni_med').val(rec.get('vunimed'));
				$('#val_instalac').val(rec.get('vdescri'));
				$("#referenciainst").val(rec.get('referenciainst'));
				
				
				$("#actInstal").val('E');
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
		//alert('A');
			$('#divAddInstal').hide();
			$('#rowAddInstal').show();
			$('#rowEditInstal').show();
			$('#rowSaveInstal').hide();
			$('#rowCancelInstal').hide();
			$('#btnDelInstal').attr('disabled',false);
			enableButton('#btnDelInstal');
			$("#id_reginstal").val('');
			$("#codigo_inst").val('');
			$('#detalle_inst option:first').attr('selected', true);
			$("#mes_cons").val('');
			$("#ano_cons").val('');
			$('#id_clafica_i  option:first').attr('selected', true);
			$("#id_materia_i  option:first").attr('selected', true);
			$('#id_estados_i  option:first').attr('selected', true);
			$('#largo').val('');
			$('#ancho').val('');
			$('#alto').val('');
			$('#cantidad').val('');
			$('#uni_med').val('');
			$('#item_instalacion').val('');
			$('#codigo_inst').val('');
			$('#val_instalac').val('');
			$('#referenciainst').val('');
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicIndInstal');
			var store = grid.getStore();
			var retorno_i=""
			    retorno_i=valida_instalacion();
				
				array_i=retorno_i.split('/');
				//alert(array[1])
			if (array_i[0]=='false')
			{infoMessage('Guardar Instalacion',array_i[1]);
			}
			if(array_i[0]=='true'){
			
			//if($('#id_reginstal').val()!=''){
				if($("#actInstal").val()=='A')
				{
					var r = Ext.create('Instal', {
						idinsta: '',
						cidindi: $('#item_instalacion').val(),
						cidinst: $('#codigo_inst').val(),
						cidnomb: $('#detalle_inst option:selected').html(),
						mescons: $('#mes_cons').val(),
						aniocons: $('#ano_cons').val(),
						iddepcl: $('#id_clafica_i').val(),
						iddepma: $('#id_materia_i').val(),
						iddepco: $('#id_estados_i').val(),
						dmlargo: $('#largo').val(),
						dmancho: $('#ancho').val(),
						dmaltos: $('#alto').val(),
						protota: $('#cantidad').val(),
						vunimed: $('#uni_med').val(),
						vdescri: $('#val_instalac').val(),
						referenciainst: $('#referenciainst').val()
					});
					
					store.insert(store.data.length, r);
				}
				else{			
					var detalle_inst=$("#detalle_inst").val();
					array=detalle_inst.split('-');
					
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idinsta', $('#id_reginstal').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidinst', array[0]);
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidnomb', $('#detalle_inst option:selected').html());
					grid.getStore().getAt(store.indexOf(sm[0])).set('mescons', $('#mes_cons').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('aniocons', $('#ano_cons').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepcl', $("#id_clafica_i").val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepma', $('#id_materia_i').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepco', $('#id_estados_i').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('dmlargo', $('#largo').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('dmancho', $('#ancho').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('dmaltos', $('#alto').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('protota', $('#cantidad').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('vunimed', $('#uni_med').val());				
					grid.getStore().getAt(store.indexOf(sm[0])).set('vdescri', $('#val_instalac').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('referenciainst', $('#referenciainst').val());
					grid.getStore().sync();	
									
				}
				eventInstal('C');
			}
			/*else{
				infoMessage('Guardar Instalac&oacute;n','Debe seleccionar un registro de instalaciones!');
				}*/
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicIndInstal');
			var store = grid.getStore();		
			if (grid.getSelectionModel().selected.length == 1){		
				if(store.data.length>0){
					var showResult = function(btn){
						if(btn=='yes'){
							var sm = grid.getSelectionModel().getSelection();
							var rec = store.getAt(store.indexOf(sm[0]));
							var cad=rec.get('cidindi');
							
							if(cad==''){
									//var dm = grid.getSelectionModel();
									store.remove(grid.getSelectionModel().getSelection());	
									infoMessage('Eliminar','Instalacion Eliminada !');
							}
							else{
								Ext.Ajax.request({
								url: urljs + "Rentas/eliminainstalacion",
								method: "POST",
								params: {codigo: $('#hd_codigo').val(),anno: $('#hd_anno').val(),
										cod_pred: $('#hd_idanexo').val(),anexo: $('#hd_anexo').val(),
										sub_anexo: $('#hd_subanexo').val(),id_item: cad},
								success: function(response){	
								
									data = response.responseText;
									
									if(data=='1'){
										//var dm = grid.getSelectionModel();
										store.remove(grid.getSelectionModel().getSelection());	
										infoMessage('Eliminar','Instalacion Eliminada !');
									}		
									
								}
								});
							}
						}
					};
					confirmMessage('Eliminar','Desea eliminar el registro seleccionado?',showResult);
				}
			}
			else{
				infoMessage('Eliminar','Debe seleccionar un registro!');
			}
		break;
	}
}

function addRowsInstal(data){
	var grid = Ext.getCmp('xgridFicIndInstal');
	var store = grid.getStore();
	var r = Ext.create('Instal', {
		idinsta: data.idinsta,
		cidindi: data.cidindi,
		cidinst: data.cidinst,
		cidnomb: data.cidnomb,
		mescons: data.mescons,
		aniocons: data.aniocons,
		iddepcl: data.iddepcl,
		iddepma: data.iddepma,
		iddepco: data.iddepco,
		dmlargo: data.dmlargo,
		dmancho: data.dmancho,
		dmaltos: data.dmaltos,
		protota: data.protota,
		vunimed: data.vunimed,
		vdescri: data.vdescri,
		referenciainst: data.referenciainst
	});
	store.insert(store.data.length, r);
}
function mostrarVias(){
	var anno_bvia=$('#hd_anno').val();
	showPopup('rentas/busvias?anno='+anno_bvia,'#popbuscar','700','280','Mantenimiento de Predios');
}
function buscarPredio(){
	var anno_bvia=$('#hd_anno').val();
	showPopup('rentas/buspredio?anno='+anno_bvia,'#buspredio','1100','630','Busqueda de Predios','buspredios');
}
//DOCUMENTOS ANEXOS
function loadGridDocumentos(){
	var codigo=$('#hd_codigo').val();
	var anno=$('#hd_anno').val();
	var cod_pred="";
	var anexo="";
	var sub_anexo="";
	var tipo_mov=$('#hd_tipomov').val();
	
	switch(tipo_mov){
		case 'N':
			cod_pred="";
			anexo="";
			sub_anexo="";
			break;
		case 'I':
			cod_pred= $('#hd_idanexo').val();
			anexo= $('#hd_anexo').val();
			sub_anexo= $('#hd_subanexo2').val();		
			break;
		case 'E':
			cod_pred= $('#hd_idanexo').val();
			anexo= $('#hd_anexo').val();
			sub_anexo= $('#hd_subanexo').val();		
			break;
		case 'M':
			codigo=$('#hd_codigo2').val();
			cod_pred=$('#hd_idanexo2').val();
			anexo=$('#hd_anexo2').val();
			sub_anexo=$('#hd_subanexo2').val();
			break;
	}
	
	Ext.define('Doc', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'iddoc'},
			{name: 'idreg'},
			{name: 'docnombre'},
			{name: 'docdetalle'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Doc',
        data: []
    });
    
	 var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });

    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicIndDoc',
		columnLines: true,
		store: store,
		width: '950px',
		height: '140px',        
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "rentas/griddocumentos",
						  method: "POST",
						  params: {codigo: codigo,anno: anno,cod_pred: cod_pred,anexo: anexo,sub_anexo: sub_anexo},
						  success: function(response){			
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idinsta + '|';
									addRowsDocumento(data[i]);
								}
								//Para ver si se elimina
								$('#oldDocItems').val(strItems);
						  }
					});					
            	}
            }
    	},
        columns: [ 
        {
            dataIndex: 'iddoc',
			hidden: true
        },{
            dataIndex: 'idreg',
			hidden: true
        },{
            text: 'Documento',
			width: 300,
			dataIndex: 'docnombre'
        },{
            text: 'Detalle',
            width: 450,		
            dataIndex: 'docdetalle',
			tdCls: 'mayuscula',
			editor: {
                allowBlank: true
            }
        }],
		plugins: [cellEditing]
    });
    
    grid.render('gridFicIndDoc');
	grid.setDisabled(true);
}

function eventDoc(act){
	switch(act){
		case 'A':
			
			$('#rowSaveDoc').show();
			$('#rowCancelDoc').show();
			$('#rowEditDoc').hide();
			$("#actDoc").val('A');
			$('#divAddDoc').show();
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicIndDoc');
			
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventDoc('A');
				$("#iddoc").val(rec.get('iddoc'));
				$("#idreg").val(rec.get('idreg'));
				$("#nom_documento").html(rec.get('docnombre'));
				$("#detalle_documento").val(rec.get('docdetalle'));
				$("#actDoc").val('E');
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
		
			$('#divAddDoc').hide();
			$('#rowAddDoc').show();
			$('#rowEditDoc').show();
			$('#rowSaveDoc').hide();
			$('#rowCancelDoc').hide();
			$('#btnDelDoc').attr('disabled',false);
			enableButton('#btnDelDoc');
			$("#iddoc").val('');
			$("#idreg").val('');
			$("#nom_documento").val('');
			$("#detalle_documento").val('');
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicIndDoc');
			var store = grid.getStore();
			var retorno_i=""
			    retorno_i=valida_documento();
				
				array_i=retorno_i.split('/');
				//alert(array[1])
			if (array_i[0]=='false')
			{infoMessage('Guardar Instalacion',array_i[1]);
			}
			if(array_i[0]=='true'){
			
			//if($('#id_reginstal').val()!=''){
				if($("#actDoc").val()=='A')
				{
					var r = Ext.create('Instal', {
						iddoc: $("#iddoc").val(''),
						idreg: $("#idreg").val(''),
						nom_documento: $("#nom_documento").val(''),
						detalle_documento: $("#detalle_documento").val('')
					});
					
					store.insert(store.data.length, r);
				}
				else{			
					var detalle_inst=$("#detalle_inst").val();
					array=detalle_inst.split('-');
					
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idinsta', $('#id_reginstal').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidinst', array[0]);
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidnomb', $('#detalle_inst option:selected').html());
					grid.getStore().getAt(store.indexOf(sm[0])).set('mescons', $('#mes_cons').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('aniocons', $('#ano_cons').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepcl', $("#id_clafica_i").val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepma', $('#id_materia_i').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepco', $('#id_estados_i').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('dmlargo', $('#largo').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('dmancho', $('#ancho').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('dmaltos', $('#alto').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('protota', $('#cantidad').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('vunimed', $('#uni_med').val());				
					grid.getStore().getAt(store.indexOf(sm[0])).set('vdescri', $('#val_instalac').val());
					grid.getStore().sync();	
									
				}
				eventInstal('C');
			}
			else{
				infoMessage('Guardar Instalac&oacute;n','Debe seleccionar un registro de instalaciones!');
				}
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicIndDoc');
			var store = grid.getStore();		
			if (grid.getSelectionModel().selected.length == 1){		
				if(store.data.length>0){
					var showResult = function(btn){
						if(btn=='yes'){
							var sm = grid.getSelectionModel().getSelection();
							var rec = store.getAt(store.indexOf(sm[0]));
							var cad=rec.get('cidindi');
							
							if(cad==''){
									//var dm = grid.getSelectionModel();
									store.remove(grid.getSelectionModel().getSelection());	
									infoMessage('Eliminar','Instalacion Eliminada !');
							}
							else{
								Ext.Ajax.request({
								url: urljs + "Rentas/eliminainstalacion",
								method: "POST",
								params: {codigo: $('#hd_codigo').val(),anno: $('#hd_anno').val(),
										cod_pred: $('#hd_idanexo').val(),anexo: $('#hd_anexo').val(),
										sub_anexo: $('#hd_subanexo').val(),id_item: cad},
								success: function(response){	
								
									data = response.responseText;
									
									if(data=='1'){
										//var dm = grid.getSelectionModel();
										store.remove(grid.getSelectionModel().getSelection());	
										infoMessage('Eliminar','Instalacion Eliminada !');
									}		
									
								}
								});
							}
						}
					};
					confirmMessage('Eliminar','Desea eliminar el registro seleccionado?',showResult);
				}
			}
			else{
				infoMessage('Eliminar','Debe seleccionar un registro!');
			}
		break;
	}
}

function addRowsDocumento(data){
	var grid = Ext.getCmp('xgridFicIndDoc');
	var store = grid.getStore();
	var r = Ext.create('Doc', {
		iddoc: data.iddoc,
		idreg: data.idreg,
		docnombre: data.docnombre,
		docdetalle: data.docdetalle
	});
	store.insert(store.data.length, r);
}
function valida_documento(){
	var retorno='true/';
	var detalle_documento =$.trim($('detalle_documento').val());
	if(detalle_documento.lenght<=0){
		retorno='false/Debe Ingresar el detalle del documento';
	}
	
	return retorno;
}
function addDatosPredio(data){
	
	var chbLicencia='';
	var chbConformidad='';
	var decl_fabr='';

	$("#hd_codigo2").val(data.codigo);
	$("#hd_idanexo2").val(data.cod_pred);
	$("#hd_idanexo").val(data.cod_pred);
	$('#divSAnexo').html(data.cod_pred);
	$("#hd_anexo2").val(data.anexo);
	$("#hd_subanexo2").val(data.sub_anexo);
	$("#txtArancel").val(data.arancel);
	$('#txtTerreno').val(data.vterreno);
	$('#txtConstruccion').val(data.vcontruccion);
	$('#txtInstalaciones').val(data.vinstalaciones);
	$('#txtAutovaluo').val(data.autoavaluo);
	$('#txtVia').val(data.nombre_cp);
	$('#txtCvia').val(data.cod_via);
	$('#txtCp').val(data.nombre_via);
	$('#txtDir').val(data.direccion);
	$('#txtNro').val(data.nro);
	$('#txtDpto').val(data.num_dpto);
	$('#txtMza').val(data.num_mza);
	$('#txtLte').val(data.num_lote);
	$('#txtSubLte').val(data.num_sublote);
	$('#txtFrontis').val(data.frontis);
	$('#txtAreaTerreno').val(data.area_terreno);
	$('#txtAreaComun').val(data.area_comun);
	$('#txtPorcenPropiedad2').val(data.txtPorcenPropiedad);
	$('#txtNroCond').val(data.num_condo);
	$('#txtLuz').val(data.sum_luz);
	$('#txtAgua').val(data.sum_agua);
	$('#txtSector').val(data.nom_zona);
	$('#txtAreaUso').val(data.area_uso);
	
	
	$('#txtNro2').val(data.nro2);
	$('#txtLetra').val(data.letra);
	$('#txtLetra2').val(data.letra2);
	$('#txtFondo').val(data.fondo);
	
	
	chbLicencia=data.lice_cons=="0" ? 'true' : 'false';
	chbConformidad=data.conf_obra=="0" ? 'true' : 'false';
	chbDeclaracionFab=data.decl_fabr=="0" ? 'true' : 'false';
	
	$('#chbLicencia').attr('checked', chbLicencia);
	$('#chbConformidad').attr('checked', chbConformidad);
	$('#chbDeclaracionFab').attr('checked', chbDeclaracionFab);
	
	$('#txtFecAdqui').val(data.fec_compra);
	$('#txtFecTrans').val(data.fec_venta);
	$('#txtObs').val(data.obs);
	$('#txtNroPiso').val(data.num_piso);
	
	$("#cmbUso option[value="+ data.cmbUso +"]").attr("selected",true);
	$("#cmbTipPredio option[value="+ data.cmbTipPredio +"]").attr("selected",true);
	$("#cmbEstadoConst option[value="+ data.cmbEstadoConst +"]").attr("selected",true);
	$("#cmbCondicion option[value="+ data.cmbCondicion +"]").attr("selected",true);
	$("#cmbTipoAdqui option[value="+ data.cmbTipoAdqui +"]").attr("selected",true);
	$("#cb_parque option[value="+ data.cbParque +"]").attr("selected",true);
	
	
	$("#cmbInterior option[value="+ data.cmbInterior +"]").attr("selected",true);
	$("#cmbTipoEdificio option[value="+ data.tipo_edificio_id +"]").attr("selected",true);
	$('#txtNomEdificio').val(data.nombre_edificio);
	$('#txtPiso').val(data.piso);
	$('#txtNumeroInterno').val(data.numero_interno);
	$('#txtLetraInterno').val(data.letra_interno);
	$("#cmbTipoIngreso option[value="+ data.tipo_ingreso_id +"]").attr("selected",true);
	$('#txtNomIngreso').val(data.nombre_ingreso);
	$("#cmbTipoAgrupamiento option[value="+ data.tipo_agrupamiento_id +"]").attr("selected",true);
	$('#txtNomAgrupamiento').val(data.nombre_agrupamiento);
		
	$('#gridFicIndConst').html('');
	$('#gridFicIndInstal').html('');
	$('#gridFicIndDoc').html('');
	
	eventConst('C');
	loadGridConst();
	redimGridHidden('xgridFicIndConst',700,300,2);
		
	eventInstal('C');
	loadGridInstal();
	redimGridHidden('xgridFicIndInstal',790,250,3);
	
	eventDoc('C');
	loadGridDocumentos();
	redimGridHidden('xgridFicIndDoc',790,400,3);
	closePopup('#buspredio');
	RemovDisabledGrilla();
}

$('#muro').click(function() {
	$("#divMuro").show();	
	$("#divTecho").hide();
	$("#divPiso").hide();
	$("#divVentana").hide();
	$("#divRevestimiento").hide();
	$("#divBanos").hide();
	$("#divInstalaciones").hide();	
});


$('#techo').click(function() {	
	$("#divMuro").hide();	
	$("#divTecho").show();
	$("#divPiso").hide();
	$("#divVentana").hide();
	$("#divRevestimiento").hide();
	$("#divBanos").hide();
	$("#divInstalaciones").hide();	
});

$('#piso').click(function() {	
	$("#divMuro").hide();	
	$("#divTecho").hide();
	$("#divPiso").show();
	$("#divVentana").hide();
	$("#divRevestimiento").hide();
	$("#divBanos").hide();
	$("#divInstalaciones").hide();	
});

$('#puerta').click(function() {	
	$("#divMuro").hide();	
	$("#divTecho").hide();
	$("#divPiso").hide();
	$("#divVentana").show();
	$("#divRevestimiento").hide();
	$("#divBanos").hide();
	$("#divInstalaciones").hide();	
});

$('#revestim').click(function() {	
	$("#divMuro").hide();	
	$("#divTecho").hide();
	$("#divPiso").hide();
	$("#divVentana").hide();
	$("#divRevestimiento").show();
	$("#divBanos").hide();
	$("#divInstalaciones").hide();	
});

$('#bano').click(function() {	
	$("#divMuro").hide();	
	$("#divTecho").hide();
	$("#divPiso").hide();
	$("#divVentana").hide();
	$("#divRevestimiento").hide();
	$("#divBanos").show();
	$("#divInstalaciones").hide();	
});

$('#inst').click(function() {	
	$("#divMuro").hide();	
	$("#divTecho").hide();
	$("#divPiso").hide();
	$("#divVentana").hide();
	$("#divRevestimiento").hide();
	$("#divBanos").hide();
	$("#divInstalaciones").show();	
});

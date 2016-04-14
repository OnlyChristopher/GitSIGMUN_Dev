$(function(){
	$("input[type='button']").button();
	
    Ext.define('ModelCuotas', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'cuota', type: 'string'},			  
			{name: 'anno', type: 'string'},
			{name: 'total_deuda', type: 'string'},
			{name: 'cuota_ini', type: 'string'},
			{name: 'saldo_deuda', type: 'string'},
			{name: 'monto_cuota', type: 'string'},
			{name: 'intereses', type: 'string'},
			{name: 'cuota_total', type: 'string'},
			{name: 'total_frac', type: 'string'},
			{name: 'cuotas', type: 'string'},
			{name: 'fec_gen', type: 'string'}
        ]
    });
    
    var storeCuotas = Ext.create('Ext.data.Store', {
    	model: 'ModelCuotas',
    	autoLoad: true,
        proxy: {
            type: 'ajax',
            url : 'fraccionar/muestracuotas',
            reader: {
               type: 'json',
               root: 'rows'
            }, 
            actionMethods: {
                create : 'POST',
                read   : 'POST',
                update : 'POST',
                destroy: 'POST'
            },
            extraParams: {json: JSON.stringify(data1)}
        }
    });
	
    var gridCuotas = Ext.create('Ext.grid.Panel', {
    	id: 'xgridCuotas',
        store: storeCuotas,        
        title: 'Detalle de Cuotas',
		width: 510,
        height: 310,

        columns: [ 
        {
            text: 'Cuotas',
            width: 80,            
            dataIndex: 'cuota',
            hidden:	false
        },{        
        	text: 'anno',
            width:55,
        	dataIndex: 'anno',
			hidden:	true
        },{        
        	text: 'total_deuda',
            width:55,
        	dataIndex: 'total_deuda',
        	hidden:	true
        },{
            text: 'cuota_ini',
            width: 40,
            dataIndex: 'cuota_ini',
            hidden:	true
        },{
            text: 'saldo_deuda',
            width: 65,
            dataIndex: 'saldo_deuda',
        	hidden:	true
        },{
            text: 'Monto Cuota',
            width: 100,
            dataIndex: 'monto_cuota',
            hidden:	false
        },{
            text: 'Intereses',
            width: 90,
            dataIndex: 'intereses',
            hidden:	false
        },{
            text: 'cuota_total',
            width: 55,
            dataIndex: 'cuota_total',
            hidden:	true
        },{
            text: 'total_frac',
            width: 85,
			align:'right',
            dataIndex: 'total_frac',
            hidden:	true
        },{
            text: 'Monto Total',
            width: 100,
			align:'right',
            dataIndex: 'cuotas'
        },{
            text: 'Vencimientos',
            width: 100,
			align:'right',
            dataIndex: 'fec_gen'
        }]
    });
    
    gridCuotas.render('divgridCuotas');
  
	if($('#txtFracc').val().replace(',','')<=100){
		infoMessage('SIGMUN','La Deuda a Fraccionar no puede ser menor al 15% de la UIT actual');
		closePopup('#popfraccionardeuda');
	}
});
function generasimulado(){
	var cuotas=$('#txtNumero').val().replace(',','');
	var total_deuda=$('#txtFracc').val().replace(',','');
	var total_inici=$('#txtInicial').val().replace(',','');
	var fec_gen=$('#txtFecha').val();
	var fec_cuo=$('#txtVencimiento').val();
	var gridCuotalval = Ext.getCmp('xgridCuotas');
	

	if(gridCuotalval.getStore().data.length > 0){
		var grid = Ext.getCmp('xgridRecContri');
		var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
		
		if(selectedRecordsArray.length>0){
		if($("#txtPorcentaje").val().replace(',','')<10){	
			infoMessage('Fraccionamiento','El porcentage no puede ser menos del 10%');
		}
		else{
			 var showResult = function(btn){
				if(btn=='yes'){
					
					var datasimulado = $("#frmfraccionamiento").serializeObject();
					
					
					var idList = [];
					Ext.each(selectedRecordsArray, function (item) {
						idList.push(
							{
								idrecibo: item.data.idrecibo,
								montotal: item.data.total.toString().replace(',',''),
								codigo:item.data.codigo,
								anno: item.data.anno, 
								cod_pred: item.data.cod_pred,
								anexo:item.data.anexo,
								sub_anexo:item.data.sub_anexo,
								tipo: item.data.tipo,
								tipo_rec: item.data.tipo_rec,
								periodo: item.data.periodo, 
								imp_insol: item.data.imp_insol.toString().replace(',',''),
								fact_reaj: item.data.fact_reaj, 
								imp_reaj: item.data.imp_reaj.toString().replace(',',''), 
								fact_mora: item.data.fact_mora, 
								imp_mora: item.data.mora.toString().replace(',',''), 
								costo_emis: item.data.costo_emis.toString().replace(',',''),
								ubica: item.data.ubica
							});
					});
					
					var idcodigo=[]
					idcodigo.push(
								{codigoc:$('#divCodigo').html()}
								);
					
					datasimulado['deuda'] = idList;
					datasimulado['codigo']=idcodigo;
					
					showPopupData(datasimulado,'fraccionar/simuladofrac','#popupsimulado','800','460','Simulado de Convenio');
					
				}
	 		};
	 		
	 		confirmMessage('Contribuyentes','Seguro de Genera el simulado del Convenio?',showResult);
		}
		}else{
			infoMessage('Contribuyentes','Seleccione al menos un registro!');
		}
	}else{
		infoMessage('SIGMUN','Calcula las cuotas del convenio antes de generar');
	}
}
function generaconvenio(){
	
	var cuotas=$('#txtNumero').val().replace(',','');
	var total_deuda=$('#txtFracc').val().replace(',','');
	var total_inici=$('#txtInicial').val().replace(',','');
	var fec_gen=$('#txtFecha').val();
	var fec_cuo=$('#txtVencimiento').val();
	var gridCuotalval = Ext.getCmp('xgridCuotas');
	

	if(gridCuotalval.getStore().data.length > 0){
		var grid = Ext.getCmp('xgridRecContri');
		var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
		
		if(selectedRecordsArray.length>0){
		if($("#txtPorcentaje").val().replace(',','')<10){	
			infoMessage('Fraccionamiento','El porcentage no puede ser menos del 10%');
		}
		else{
			 var showResult = function(btn){
				if(btn=='yes'){
					
					var idList = [];
					Ext.each(selectedRecordsArray, function (item) {
						idList.push(
							{
								idrecibo: item.data.idrecibo,
								montotal: item.data.total.toString().replace(',',''),
								codigo:item.data.codigo,
								anno: item.data.anno, 
								cod_pred: item.data.cod_pred,
								anexo:item.data.anexo,
								sub_anexo:item.data.sub_anexo,
								tipo: item.data.tipo,
								tipo_rec: item.data.tipo_rec,
								periodo: item.data.periodo, 
								imp_insol: item.data.imp_insol.toString().replace(',',''),
								fact_reaj: item.data.fact_reaj, 
								imp_reaj: item.data.imp_reaj.toString().replace(',',''), 
								fact_mora: item.data.fact_mora, 
								imp_mora: item.data.mora.toString().replace(',',''), 
								costo_emis: item.data.costo_emis.toString().replace(',',''),
								ubica: item.data.ubica
							});
					});
	
					Ext.Ajax.request({
			            url: urljs + "fraccionar/generaconvenio",
			            method: "POST",
			            params: {json: JSON.stringify(idList),cuotas: cuotas,total_deuda:total_deuda,total_inici:total_inici,total_inici:total_inici,fec_gen:fec_gen,fec_cuo:fec_cuo},
			            success: function(response){
			            	var varcodigo=$('#divCodigo').html();
			            	var varconvenio=response.responseText;
			            	$("#btnConvenio").attr('disabled',true)
							$("#btnDeudas").attr('disabled',true)
							$('#txtInicial').attr('readonly', true);
							$('#txtInicial').removeClass("caja").addClass('cajaoff');
							$('#txtNumero').attr('readonly', true);
							$('#txtNumero').removeClass("caja").addClass('cajaoff');
							mostrarRecContri();
							showPopupReport('tipo=pdf&nombrereporte=ReporteConvenio&param=PCODIGO^'+varcodigo+'|PCONVENIO^'+varconvenio,'pouprptconvenio',700,600,'Convenio');
			            },
			            failure: function(response, opts){
			            	infoMessage('Contribuyentes','Error al intentar cobrar... ');
			            }
			        });
				}
	 		};
	 		
	 		confirmMessage('Contribuyentes','Seguro de Genera el Convenio?',showResult);
		}
		}else{
			infoMessage('Contribuyentes','Seleccione al menos un registro!');
		}
	}else{
		infoMessage('SIGMUN','Calcula las cuotas del convenio antes de generar');
	}
}

function mostrarcuotas(){
	var cuotas=$('#txtNumero').val().replace(',','');
	var total_deuda=$('#txtFracc').val().replace(',','');
	var total_inici=$('#txtInicial').val().replace(',','');
	var fec_gen=$('#txtFecha').val();
	var fec_cuo=$('#txtVencimiento').val();

	if((Math.round(total_inici*100)/100) > (Math.round(total_deuda*100)/100) ){
		$('#txtInicial').val('0.00');
		infoMessage('Fraccionamiento','El monto de la cuota inicial no puede ser mayor al monto total... ');
		return;
	}
	
if($('#txtInicial').val()!='0.00'){
	var gridCuotas = Ext.getCmp('xgridCuotas');
	var storeCuotas = gridCuotas.getStore();
	var proxy = storeCuotas.getProxy();
	
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {cuotas: cuotas,total_deuda:total_deuda,total_inici:total_inici,total_inici:total_inici,fec_gen:fec_gen,fec_cuo:fec_cuo};
	
	storeCuotas.currentPage = 1;	
	storeCuotas.load({
		callback: function(documents, options, success){
		var total_cuota=0;
		var total_inter=0;
		var interes=0;
		var intere01=0;
		
		storeCuotas.each(function (rec) {
			if (rec.get('cuota')=='01'){
				intere01=rec.get('intereses');
			}
			total_cuota += Number(rec.get('monto_cuota'));	
    		total_inter += Number(rec.get('cuotas'));				
		});
		$('#txtSininteres').val(Math.round(total_cuota*100)/100).autoNumeric();
		$('#txtConinteres').val(Math.round(total_inter*100)/100).autoNumeric();
		//$('#txtInteres').val(intere01);
		$('#txtFact').val(intere01);
	}
	});
}else{
	infoMessage('SIGMUN','Ingrese el monto de la cuota inicial');
}
//----------------------------------------------------------------
	//Datos grilla ubicación
	//var dataUbica = new Array();
	/*
	var cuota01i=0.00
	var cuota01n=0.00
	var intere01=0.00
	
	var gridSuma = Ext.getCmp('xgridCuotas');
	if(gridSuma.getStore().data.length > 0){
		var i=0;
		gridSuma.getStore().each(function(rec) {
			if (rec.get('cuota')=='01'){
				intere01=rec.get('intereses');
			}
			//alert(rec.get('monto_cuota'));
			cuota01n += Number(rec.get('monto_cuota')) ;
			cuota01i += Number(rec.get('cuotas')) ;
			i++;
		});
	}

	cuota01i=Math.round(cuota01i*100)/100;
	cuota01n=Math.round(cuota01n*100)/100;
	
	$('#txtSininteres').val(cuota01n);
	$('#txtConinteres').val(cuota01i);
	$('#txtFact').val(intere01);
	*/
}

function getporcentaje()
{	
	var txtInicial = $("#txtInicial").val().replace(',','');
	var txtFracc = $("#txtFracc").val().replace(',','');
	/*	if(txtInicial == '' || txtFracc == null){
			monto2 = 0.00;
		}*/
	var resultado = (txtInicial/txtFracc)*100;
	var resusaldo = (txtFracc-txtInicial);
	$("#txtPorcentaje").val(resultado.toFixed(2)).autoNumeric();
	$("#txtSaldo").val(resusaldo.toFixed(2)).autoNumeric();
	var cuota=($('#txtSaldo').val().replace(',','')/$('#txtNumero').val()).toFixed(2);
	
	if(Number($("#txtPorcentaje").val().replace(',',''))<10  ){
		infoMessage('Fraccionamiento','El porcentage no puede ser menos del 10%');
		$("#txtInicial").val(0);
		return;
	}
	/*if(Number(cuota)<35  ){
		infoMessage('Fraccionamiento','La cuota no puede tener valor menor a 35.00 Soles');
		$("#txtInicial").val(0);
		return;
	}*/
	
}

function getTipoPago(){

var rdformaPago = $('input[name*="rdformaPago"]:checked').val();

if(rdformaPago=='E' || rdformaPago=='T')
	{
		$('#txtGirador').attr('readonly', true);
		$('#txtCheque').attr('readonly', true);
		$('#txtBanco').attr('readonly', true);
		$('#txtFecCobro').attr('readonly', true);
		$('#txtObservacion').attr('readonly', true);
		
		$('#txtGirador').removeClass("caja").addClass('cajaoff');
		$('#txtCheque').removeClass("caja").addClass('cajaoff');
		$('#txtBanco').removeClass("caja").addClass('cajaoff');
		$('#txtFecCobro').removeClass("caja").addClass('cajaoff');
		$('#txtObservacion').removeClass("caja").addClass('cajaoff');
	}
else if(rdformaPago=='C')
	{
		$('#txtGirador').attr('readonly', false);
		$('#txtCheque').attr('readonly', false);
		$('#txtBanco').attr('readonly', false);
		$('#txtFecCobro').attr('readonly', false);
		$('#txtObservacion').attr('readonly', false);
		
		$('#txtGirador').removeClass("cajaoff").addClass('caja');
		$('#txtCheque').removeClass("cajaoff").addClass('caja');
		$('#txtBanco').removeClass("cajaoff").addClass('caja');
		$('#txtFecCobro').removeClass("cajaoff").addClass('caja');
		$('#txtObservacion').removeClass("cajaoff").addClass('caja');
	}
}
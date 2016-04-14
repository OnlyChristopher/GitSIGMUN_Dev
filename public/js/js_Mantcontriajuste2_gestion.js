Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
	callAjax('mantcontriajuste2/periodos?codigocaja='+codigocaja,'','#boxCajaPeriodo','html');

    callAjax('mantcontriajuste2/predios?codigocaja='+codigocaja,'','#divPredios','html');
    
    callAjax('mantcontriajuste2/anios?codigocaja='+codigocaja,'','#divAnios','html');
    
    $('input[name^="chkperiodo"]').live("click", function(){		
    	selectChecks('#chkTotPeriodos','chkperiodo[]');
	});
    
    $('input[name^="chkanio"]').live("click", function(){		
    	selectChecks('#chkTotAnios','chkanio[]');
	});
    
    $('input[name^="concept"]').live("click", function(){		
    	selectChecks('#chkTotConceptos','conceptos[]');
	});
    
    $('input[name^="chkarbitrio"]').live("click", function(){		
    	selectChecks('#chkTotArbitrio','chkarbitrio[]');
	});
    
    $('input[name^="pred"]').live("click", function(){		
    	selectChecks('#chkTotPreddd','predio[]');
	});
	
    Ext.define('Cuentacte', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idrecibo'},
			{name: 'codigo', type: 'string'},
			{name: 'des_tipo', type: 'string'},
			{name: 'tipo', type: 'string'},
			{name: 'anno', type: 'string'},
			{name: 'cod_pred', type: 'string'},
			{name: 'anexo', type: 'string'},
			{name: 'sub_anexo', type: 'string'},
			{name: 'tipo', type: 'string'},
			{name: 'tipo_rec', type: 'string'},
			{name: 'periodo', type: 'string'},
			{name: 'imp_insol', type: 'string'},
			{name: 'fact_reaj', type: 'string'},
			{name: 'imp_reaj', type: 'string'},
			{name: 'fact_mora', type: 'string'},
			{name: 'mora', type: 'string'},
			{name: 'cost_emis', type: 'string'},
			{name: 'estado', type: 'string'},			
			{name: 'ubica', type: 'string'},
			{name: 'total', type: 'string'},
			{name: 'fec_venc', type: 'string'},
			{name: 'fec_pago', type: 'string'},
			{name: 'num_ingr', type: 'string'},			
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Cuentacte',
    	autoLoad: true,
        proxy: {
            type: 'ajax',
            url : 'cuentacorriente/conreccontri',
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
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridCuentacte',
        store: store,        
        title: 'Recibos',
		width:970,
        height: 250,
        viewConfig: {
        	loadMask: {msg: 'Listado de Recibos...'},
			stripeRows: false,
        	getRowClass: function(record, index){
				
				var clsRow = '';
				switch(record.get('ubica').trim()){
					case 'VT': clsRow='xrow-valortrib'; break
					case 'EC': clsRow='xrow-coactivo'; break
					case 'FR': clsRow='xrow-fraccionamiento'; break
					case 'FT': clsRow='xrow-fiscalizacion'; break
					case 'EM-*': clsRow='xrow-cancelado'; break
					case 'FE-*': clsRow='xrow-cancelado'; break
				}
				return clsRow;
			}

    	},
            selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode: 'MULTI',
        	checkOnly: true
        }),
        columns: [ 
        {
            text: 'Recibo',
            width: 100,            
            dataIndex: 'idrecibo',
            hidden:	false
        },{        
        	text: 'Codigo',
        	//flex: 1,
            width:55,
        	dataIndex: 'codigo',
			hidden:	false
        },{        
        	text: 'Tributo',
        	//flex: 1,
            width:55,
        	dataIndex: 'des_tipo'
        },{
            text: 'A&ntildeo',
            width: 40,
            dataIndex: 'anno'            
        },{
            text: 'Predio',
            width: 80,
            dataIndex: 'cod_pred',
        	hidden:	false
        },{
            text: 'Anexo',
            width: 60,
            dataIndex: 'anexo'
        },{
            text: 'Sub Anexo',
            width: 70,
            dataIndex: 'sub_anexo'
        },{
			 text: 'Tipo',
            width: 80,
            dataIndex: 'tipo',
        	hidden:	false
        },{
			 text: 'tipo Rec',
            width: 80,
            dataIndex: 'tipo_rec',
        	hidden:	false
        },{
            text: 'Periodo',
            width: 55,
            dataIndex: 'periodo'
        },{
            text: 'Imp. Insol',
            width: 85,
			align:'right',
            dataIndex: 'imp_insol'
        },{
            text: 'Fact. Reaj',
            width: 80,
			align:'right',
            dataIndex: 'fact_reaj'
        },{
            text: 'Imp. Reaj',
            width: 80,
			align:'right',
            dataIndex: 'imp_reaj'
        },{
            text: 'Fact Mora.',
            width: 80,
			align:'right',
            dataIndex: 'fact_mora'
        },{
            text: 'Interes',
            width: 80,
			align:'right',
            dataIndex: 'mora'
        },{
            text: 'Costo Emis.',
            width: 80,
			align:'right',
            dataIndex: 'cost_emis'
        },{
			text: 'Estado',
            width: 80,
            dataIndex: 'estado',
        	hidden:	false
        },{
			text: 'ubica',
            width: 40,
			align:'center',
            dataIndex: 'ubica',
			hidden: false
        },{
            text: 'Total',
            width: 80,
			align:'right',
            dataIndex: 'total'
        },{        
        	text: 'Fec venc',
            width: 60,
            dataIndex: 'fec_venc',
            hidden:	false            	
        },{
            text: 'Fec pago',
            width: 60,
            dataIndex: 'fec_pago',
            hidden:	false  
		},{
			text: 'num_ingr',
            width: 60,
            dataIndex: 'num_ingr',
            hidden:	false
		},{
			xtype:'actioncolumn',
			width:30,
			items:[{
				icon:urljs+'img/reload.png',
				tooltip:'Ajuste',
				handler: function(grid, rowIndex, colIndex){
				var record = grid.getStore().getAt(rowIndex);
				
				var idrecibo	=	record.get('idrecibo');
				var codigo		= 	record.get('codigo');
				var des_tipo	= 	record.get('des_tipo');
				var tipo		=	record.get('tipo');
				var anno		=	record.get('anno');
				var cod_pred	= 	record.get('cod_pred');
				var anexo		= 	record.get('anexo');
				var sub_anexo	= 	record.get('sub_anexo');
				var tipo		= 	record.get('tipo');
				var tipo_rec	= 	record.get('tipo_rec');
				var periodo		= 	record.get('periodo');	
				var imp_insol	= 	record.get('imp_insol');
				var fact_reaj	= 	record.get('fact_reaj');
				var imp_reaj	=	record.get('imp_reaj');
				var fact_mora	= 	record.get('fact_mora');
				var mora		= 	record.get('mora');
				var cost_emis	= 	record.get('cost_emis');
				var estado		= 	record.get('estado');
				var ubica		= 	record.get('ubica');
				var total		=	record.get('total');
				var fec_venc	= 	record.get('fec_venc');
				var fec_pago	= 	record.get('fec_pago');
				var num_ingr	= 	record.get('num_ingr');
				
				
				showPopup('mantcontriajuste2/avanzado?idrecibo='+idrecibo+'&codigo='+codigo+'&des_tipo='+des_tipo+'&cod_pred='+cod_pred+'&anexo='+anexo+'&sub_anexo='+sub_anexo+'&tipo_rec='+tipo_rec+'&periodo='+periodo+'&imp_insol='+imp_insol+'&imp_reaj='+imp_reaj+'&mora='+mora+'&cost_emis='+cost_emis+'&total='+total+'&fec_venc='+fec_venc+'&estado='+estado,'#popcatasmapa','750','450','Ajuste Avanzado');
				
			}
			
		}]
        }]
		,
        listeners: { 
			dblclick: function(model, record, index) {
            alert('papa');
			},
			deselect: function(model, record, index) {
				deuda(false, record);
			},
			select: function(model, record, index) {
				
			
				deuda(true, record);
			}
    	}
    });
    
    grid.render('gridCuentacte');
    
});

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

function cambiaGrupo(chk) {
	var cadena='';//agregado
    var padreDIV=chk;
    while( padreDIV.nodeType==1 && padreDIV.tagName.toUpperCase()!="DIV" )
        padreDIV=padreDIV.parentNode;
    var padreDIVinputs=padreDIV.getElementsByTagName("input");
    for(var i=0; i<padreDIVinputs.length; i++) {
        if( padreDIVinputs[i].getAttribute("type")=="checkbox" )
            padreDIVinputs[i].checked = chk.checked;
    }

}

function mostrarRecibos(){
	var codigo=$('#divCodigo').html();
	showPopup('cuentacorriente/mostrarrecibos?codigo='+codigo,'#poplistarec','950','350','Recibos del Contribuyente');
}

function imprimeCuentaPdf(){
	var codigo=$('#divCodigo').html();
	var usuario='RENTAS';
	showPopupReport('tipo=pdf&nombrereporte=rptEstCta&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
}


function deuda(est, obj){

		var dt=0;
		
	if ( obj.get('ubica').trim()=='EM-*' || obj.get('ubica').trim()=='FR'){
		//alert(obj.get('ubica'));
	}
	else{
		if(est==true){
		//txtinso
			dt = Number($('#txtinso').val());
			var txtinsoluto=obj.get('imp_insol').toString();
			dt += Number(txtinsoluto.replace(',','')) ;
			dt=Math.round(dt*100)/100;
			$('#txtinso').val(dt);
		}else{
			dt=Number($('#txtinso').val());
			var txtinsoluto=obj.get('imp_insol').toString();
			dt=Number(dt)-Number(txtinsoluto.replace(',',''));
			dt=Math.round(dt*100)/100;
			$('#txtinso').val(dt);
		}
		
		var dt=0;
			
		if(est==true){
		//txtreaj
			dt = Number($('#txtreaj').val());
			var txtimp_reaj=obj.get('imp_reaj').toString();
			dt += Number( txtimp_reaj.replace(',','')) ;
			dt=Math.round(dt*100)/100;
			$('#txtreaj').val(dt);
		}else{
		//txtreaj
			dt=Number($('#txtreaj').val());
			var txtimp_reaj=obj.get('imp_reaj').toString();
			dt=Number(dt)-Number(txtimp_reaj.replace(',',''));
			dt=Math.round(dt*100)/100;
			$('#txtreaj').val(dt);
		}

		var dt=0;
		
		if(est==true){
		//txtmora
			dt = Number($('#txtmora').val());
			var txtmora=obj.get('mora').toString();
			dt += Number(txtmora.replace(',','')) ;
			dt=Math.round(dt*100)/100;
			$('#txtmora').val(dt);
		}else{
		//txtmora
			dt=Number($('#txtmora').val());
			var txtmora=obj.get('mora').toString();
			dt=Number(dt)-Number(txtmora.replace(',',''));
			dt=Math.round(dt*100)/100;
			$('#txtmora').val(dt);
		}

		var dt=0;
		
		if(est==true){
		//txtemis
			dt = Number($('#txtemis').val());
			var txtcosto_emis=obj.get('cost_emis').toString();
			dt += Number(txtcosto_emis.replace(',','')) ;
			dt=Math.round(dt*100)/100;
			$('#txtemis').val(dt);
		}else{
		//txtmora
			dt=Number($('#txtemis').val());
			var txtcosto_emis=obj.get('cost_emis').toString();
			dt=Number(dt)-Number(txtcosto_emis.replace(',',''));
			dt=Math.round(dt*100)/100;
			$('#txtemis').val(dt);
		}
		
		var dt=0;
		if(est==true){
		//txttotal
			dt = Number($('#txt_total').val());
			var txttotal=obj.get('total').toString();
			dt += Number(txttotal.replace(',',''));
			dt = Math.round(dt*100)/100;
			$('#txt_total').val(dt);
		}else{
		//txttotal
			dt=Number($('#txt_total').val());
			var txttotal=obj.get('total').toString();
			dt=Number(dt)-Number(txttotal.replace(',',''));
			dt=Math.round(dt*100)/100;
			$('#txt_total').val(dt);
		}
	}
}

function fraccionar(){
	var totalpagar=0.00;
	
	var totalpagar=$('#txt_total').val().replace(',','');

	if(totalpagar==0.00){
//	alert(totalpagar);
		infoMessage('SIGMUN','El monto seleccionado debe ser mayor ');
	}else{
		showPopup('fraccionar/index?totalpagar='+totalpagar,'#popfraccionardeuda','990','400','Fraccionar Deuda');
	}	
}

function imputar(){
	var correcto = true;
	
	if($('#nro_doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Ingrese el n&uacutemero!','#nro_doc_sustento');
		correcto = false;
	}else
	if($('#doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Ingrese el documento!','#doc_sustento');
		correcto = false;
	}else
	if($('#tipo_doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Seleccione el tipo de documento!','#tipo_doc_sustento');
		correcto = false;
	}
	
	if(correcto){
		
		$('#compensar_o_baja').val('1');
		/*$('#nro_doc_sustento').val();
		$('#doc_sustento').val();
		$('#tipo_doc_sustento').html();*/
		
		//datos de la grilla
		var datax = new Array();
		var dataRecibo = new Array();
		var gridRecibo = Ext.getCmp('xgridCuentacte');
		
		if(gridRecibo.getSelectionModel().getSelection().length>0){
			
			var count = gridRecibo.getSelectionModel().getSelection().length;
			
			 datax = $('#frmctacte').serializeObject();
			 
			for(i=0;i<count;i++){
				var rec = gridRecibo.getSelectionModel().getSelection()[i];
				//alert(row.get('des_tipo'));
				var rowsRecibo = {
					idrecibo: rec.get('idrecibo'),
					codigo: rec.get('codigo'),
					anno: rec.get('anno'),
					cod_pred: rec.get('cod_pred'),
					anexo: rec.get('anexo'),
					sub_anexo: rec.get('sub_anexo'),
					tipo: rec.get('tipo'),
					tipo_rec: rec.get('tipo_rec'),
					periodo: rec.get('periodo'),		
					imp_insol: rec.get('imp_insol'),
					fact_reaj: rec.get('fact_reaj'),
					fact_mora: rec.get('fact_mora'),
					mora: rec.get('mora'),
					cost_emis: rec.get('cost_emis'),
					estado: rec.get('estado'),
					ubica: rec.get('ubica'),
					fec_venc: rec.get('fec_venc'),
					fec_pago: rec.get('fec_pago'),
					num_ingr: rec.get('num_ingr'),
					fech_ing: rec.get('fech_ing')
				}
				dataRecibo[i] = rowsRecibo;
			}
			
			datax['Recibo'] = dataRecibo;
			
			//Envía data para guardar
			Ext.Ajax.request({
				url: urljs + "cuentacorriente/grabarimputar",
				method: "POST",
				params: {json: JSON.stringify(datax)},
				success: function(response){
					infoMessage('Guardar','Recibos inputados correctamente!');
					mostrarRecContri();
				}
			});		
		}
	}
}

	
function imputardeuda(){
	var totalpagar=0.00;
	
	var totalpagar=$('#txt_total').val().replace(',','');

	if(totalpagar==0.00){
		infoMessage('SIGMUN','El monto seleccionado debe ser mayor ');
	}else{
		//showPopup('tesoprepago/index?totalpagar='+totalpagar,'#popprintdeuda','300','350','Cancelar Deuda');
	}
	
}	

function darbaja(){
	
	var correcto = true;
	
	if($('#nro_doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Ingrese el n&uacutemero!','#nro_doc_sustento');
		correcto = false;
	}else
	if($('#doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Ingrese el documento!','#doc_sustento');
		correcto = false;
	}else
	if($('#tipo_doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Seleccione el tipo de documento!','#tipo_doc_sustento');
		correcto = false;
	}
	
	if(correcto){
	$('#compensar_o_baja').val('2');
	/*$('#nro_doc_sustento').val();
	$('#doc_sustento').val();
	$('#tipo_doc_sustento').html();*/
	
	//datos de la grilla
	var datab = new Array();  
	var dataRecibo = new Array();
	var gridRecibo = Ext.getCmp('xgridCuentacte');
	
	if(gridRecibo.getSelectionModel().getSelection().length>0){
		var count = gridRecibo.getSelectionModel().getSelection().length;
		
		 datab = $('#frmctacte').serializeObject();
		 
		for(i=0;i<count;i++){			
			var rec = gridRecibo.getSelectionModel().getSelection()[i];
			//alert(row.get('des_tipo'));
			var rowsRecibo = {
				idrecibo: rec.get('idrecibo'),
				codigo: rec.get('codigo'),
				anno: rec.get('anno'),
				cod_pred: rec.get('cod_pred'),
				anexo: rec.get('anexo'),
				sub_anexo: rec.get('sub_anexo'),
				tipo: rec.get('tipo'),
				tipo_rec: rec.get('tipo_rec'),
				periodo: rec.get('periodo'),		
				imp_insol: rec.get('imp_insol'),
				fact_reaj: rec.get('fact_reaj'),
				fact_mora: rec.get('fact_mora'),
				mora: rec.get('mora'),
				cost_emis: rec.get('cost_emis'),
				estado: rec.get('estado'),
				ubica: rec.get('ubica'),
				fec_venc: rec.get('fec_venc'),
				fec_pago: rec.get('fec_pago'),
				num_ingr: rec.get('num_ingr'),
				fech_ing: rec.get('fech_ing')
			}
			dataRecibo[i] = rowsRecibo;
		}
		
		datab['Recibo'] = dataRecibo;
		
		//Envía data para guardar
		Ext.Ajax.request({
			url: urljs + "cuentacorriente/grabarbaja",
			method: "POST",
			params: {json: JSON.stringify(datab)},
			success: function(response){
				infoMessage('Guardar','Se dio de baja correctamente!');
				mostrarRecContri();
			}
		});	
	}
	}
}
function getAjustecuenta(){


	var correcto = true;
	
	if($('#nro_doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Ingrese el n&uacutemero!','#nro_doc_sustento');
		correcto = false;
	}else
	if($('#doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Ingrese el documento!','#doc_sustento');
		correcto = false;
	}else
	if($('#tipo_doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Seleccione el tipo de documento!','#tipo_doc_sustento');
		correcto = false;
	}
	
	if(correcto){
	var nro_doc_sustento=$('#nro_doc_sustento').val();
	var doc_sustento=$('#doc_sustento').val();
	var tipo_doc_sustento=$('#tipo_doc_sustento').val();
	
	$('#compensar_o_baja').val('2');
	/*$('#nro_doc_sustento').val();
	$('#doc_sustento').val();
	$('#tipo_doc_sustento').html();*/
	
	var	txtMontoajustar	= $('#txtMontoajustar').val();
	var txt_total	= $('#txt_total').val();
	
	var msgpago='Seguro de Cancelar?';

	if(txt_total > txtMontoajustar && txtMontoajustar > 0 ){
		msgpago='Se realizara pago a cuenta, Seguro de Cancelar?';
	};
	
	var datab = new Array();  
	var dataRecibo = new Array();
	var gridRecibo = Ext.getCmp('xgridCuentacte');
	var selectedRecordsArray = gridRecibo.getView().getSelectionModel().getSelection();
	
	if(gridRecibo.getSelectionModel().getSelection().length>0){
	
	
	var showResult = function(btn){
		if(btn=='yes'){
			var count = gridRecibo.getSelectionModel().getSelection().length;
			
			 datab = $('#frmctacte').serializeObject();
			 var idList = [];
			 
				Ext.each(selectedRecordsArray, function (item) {
							
			idList.push({
				idrecibo: item.data.idrecibo,
				codigo: item.data.codigo,
				anno: item.data.anno,
				cod_pred: item.data.cod_pred,
				anexo: item.data.anexo,
				sub_anexo: item.data.sub_anexo,
				tipo: item.data.tipo,
				tipo_rec: item.data.tipo_rec,
				periodo: item.data.periodo,		
				imp_insol: item.data.imp_insol.replace(',',''),
				fact_reaj: item.data.fact_reaj.replace(',',''),
				imp_reaj: item.data.imp_reaj.replace(',',''),
				fact_mora: item.data.fact_mora.replace(',',''),
				imp_mora: item.data.mora.replace(',',''),
				costo_emis: item.data.cost_emis.replace(',',''),
				estado: item.data.estado,
				ubica: item.data.ubica,
				fec_venc: item.data.fec_venc,
				fec_pago: item.data.fec_pago,
				num_ingr: item.data.num_ingr,
				fech_ing: item.data.fech_ing,
				montotal: item.data.total.replace(',','')
			});
							
		});
						
			//Envía data para guardar
			Ext.Ajax.request({
				url: urljs + "mantcontriajuste2/grabarbaja",
				method: "POST",
				params: {json: JSON.stringify(idList),dataform:datab,txtMontoajustar:txtMontoajustar,txt_total:txt_total,nro_doc_sustento:nro_doc_sustento,doc_sustento:doc_sustento,tipo_doc_sustento:tipo_doc_sustento},
				success: function(response){
					infoMessage('Guardar','Se dio de baja correctamente!');
					mostrarRecContri();
				}
				
			});	
			
			};
 		
	}
	
	confirmMessage('SIGMUN',msgpago,showResult);
	
	}
	else {
		infoMessage('Guardar','Debe seleccionar deuda!');
	}
}
}

function getCompensacuenta(){


	var correcto = true;
	
	if($('#nro_doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Ingrese el n&uacutemero!','#nro_doc_sustento');
		correcto = false;
	}else
	if($('#doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Ingrese el documento!','#doc_sustento');
		correcto = false;
	}else
	if($('#tipo_doc_sustento').val()==''){
		infoMessagex('Cuentas corrientes','Seleccione el tipo de documento!','#tipo_doc_sustento');
		correcto = false;
	}
	
	if(correcto){
	var nro_doc_sustento=$('#nro_doc_sustento').val();
	var doc_sustento=$('#doc_sustento').val();
	var tipo_doc_sustento=$('#tipo_doc_sustento').val();
	
	$('#compensar_o_baja').val('2');
	/*$('#nro_doc_sustento').val();
	$('#doc_sustento').val();
	$('#tipo_doc_sustento').html();*/
	
	var	txtMontoajustar	= $('#txtMontoajustar').val();
	var txt_total	= $('#txt_total').val();
	
	var msgpago='Seguro de Cancelar?';

	if(txt_total > txtMontoajustar && txtMontoajustar > 0 ){
		msgpago='Se realizara pago a cuenta, Seguro de Cancelar?';
	};
	
	var datab = new Array();  
	var dataRecibo = new Array();
	var gridRecibo = Ext.getCmp('xgridCuentacte');
	var selectedRecordsArray = gridRecibo.getView().getSelectionModel().getSelection();
	
	if(gridRecibo.getSelectionModel().getSelection().length>0){
	
	
	var showResult = function(btn){
		if(btn=='yes'){
			var count = gridRecibo.getSelectionModel().getSelection().length;
			
			 datab = $('#frmctacte').serializeObject();
			 var idList = [];
			 
				Ext.each(selectedRecordsArray, function (item) {
							
			idList.push({
				idrecibo: item.data.idrecibo,
				codigo: item.data.codigo,
				anno: item.data.anno,
				cod_pred: item.data.cod_pred,
				anexo: item.data.anexo,
				sub_anexo: item.data.sub_anexo,
				tipo: item.data.tipo,
				tipo_rec: item.data.tipo_rec,
				periodo: item.data.periodo,		
				imp_insol: item.data.imp_insol.replace(',',''),
				fact_reaj: item.data.fact_reaj.replace(',',''),
				imp_reaj: item.data.imp_reaj.replace(',',''),
				fact_mora: item.data.fact_mora.replace(',',''),
				imp_mora: item.data.mora.replace(',',''),
				costo_emis: item.data.cost_emis.replace(',',''),
				estado: item.data.estado,
				ubica: item.data.ubica,
				fec_venc: item.data.fec_venc,
				fec_pago: item.data.fec_pago,
				num_ingr: item.data.num_ingr,
				fech_ing: item.data.fech_ing,
				montotal: item.data.total.replace(',','')
			});
							
		});
						
			//Envía data para guardar
			Ext.Ajax.request({
				url: urljs + "mantcontriajuste2/grabarcompensacion",
				method: "POST",
				params: {json: JSON.stringify(idList),dataform:datab,txtMontoajustar:txtMontoajustar,txt_total:txt_total,nro_doc_sustento:nro_doc_sustento,doc_sustento:doc_sustento,tipo_doc_sustento:tipo_doc_sustento},
				success: function(response){
					infoMessage('Guardar','Se dio de baja correctamente!');
					mostrarRecContri();
				}
				
			});	
			
			};
 		
	}
	
	confirmMessage('SIGMUN',msgpago,showResult);
	
	}
	else {
		infoMessage('Guardar','Debe seleccionar deuda!');
	}
}
}




/*
$('#txtcuadra').autotab({format: 'numeric'});
$('#doc_sustento').autotab({format: 'alpha'});
$('#nro_doc_sustento').autotab({format:'alpha'});
$('#tipo_doc_sustento').autotab({format:'alpha'});*/
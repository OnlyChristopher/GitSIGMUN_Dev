Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
	callAjax('rentascaja/periodos?codigocaja='+codigocaja,'','#boxCajaPeriodo','html');

    callAjax('rentascaja/predios?codigocaja='+codigocaja,'','#divPredios','html');
    
    callAjax('rentascaja/anios?codigocaja='+codigocaja,'','#divAnios','html');
    
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
	
    Ext.define('RecContri', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idrecibo'},
			{name: 'codigo', type: 'string'},			  
			{name: 'tipo', type: 'string'},
			{name: 'anno', type: 'string'},
			{name: 'cod_pred', type: 'string'},
			{name: 'anexo', type: 'string'},
			{name: 'sub_anexo', type: 'string'},
			{name: 'tipo_rec', type: 'string'},
			{name: 'periodo', type: 'string'},
			{name: 'imp_insol', type: 'string'},
			{name: 'fact_reaj', type: 'string'},
			{name: 'imp_reaj', type: 'string'},
			{name: 'fact_mora', type: 'string'},
			{name: 'mora', type: 'string'},
			{name: 'costo_emis', type: 'string'},
			{name: 'des_tipo', type: 'string'},
			{name: 'ubica', type: 'string'},
			{name: 'total', type: 'number'},
			{name: 'des_cabecera', type: 'string'},
			{name: 'benefic', type: 'number'},
			{name: 'det_anexo', type: 'string'},
			{name: 'tot_pagado', type: 'number'}
			
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'RecContri',
    	autoLoad: true,
        proxy: {
            type: 'ajax',
            url : 'rentascajareccontriproyec/conreccontri',
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
			timeout : 60000,
            extraParams: {json: JSON.stringify(data1)}
        },		
		groupField: 'des_cabecera'
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridRecContri',
        store: store,        
        title: 'Recibos',
		width:970,
        height: 225,
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
					case 'VT-*': clsRow='xrow-cancelado'; break
					case 'CC-*': clsRow='xrow-compensado';break
				}
				return clsRow;
			}

    	},
		features: [{
            id: 'group',
            ftype: 'groupingsummary',
            groupHeaderTpl: '{name}',
            hideGroupedHeader: true,
            enableGroupingMenu: false
        }],
		selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode: 'MULTI',
        	checkOnly: true
        }),
        columns: [ 
        {
            text: 'Recibo',
            width: 100,            
            dataIndex: 'idrecibo',
            hidden:	true
        },{        
        	text: 'Codigo',
        	//flex: 1,
            width:55,
        	dataIndex: 'codigo',
			hidden:	true
        },{        
        	text: 'descri',
			width:100,
        	dataIndex: 'des_cabecera'
        },{        
        	text: 'Tributo',
            width:55,
        	dataIndex: 'des_tipo'
        },{
            text: 'A&ntildeo',
            width: 40,
            dataIndex: 'anno',
			//summaryType: 'max'
        },{
            text: 'Predio',
            width: 73,
            dataIndex: 'cod_pred',
			//summaryType: 'max'
        },{
            text: 'Anexo',
            width: 60,
            dataIndex: 'anexo',
			hidden: true
			//summaryType: 'max'
        },{
            text: 'Sub Anexo',
            width: 70,
            dataIndex: 'sub_anexo',
			hidden: true
			//summaryType: 'max'
        },{
            text: 'Anexo',
            width: 65,
            dataIndex: 'det_anexo'
			//summaryType: 'max'
        },{
            text: 'Peri.',
            width: 47,
            dataIndex: 'periodo'
        },{
            text: 'Imp. Insol',
            width: 100,
			align:'right',
            dataIndex: 'imp_insol'
        },{
            text: 'Fact. Reaj',
            width: 150,
			align:'right',
            dataIndex: 'fact_reaj',
			hidden:	true
        },{
            text: 'Imp. Reaj',
            width: 110,
			align:'right',
            dataIndex: 'imp_reaj'
        },{
            text: 'Fact Mora.',
            width: 80,
			align:'right',
            dataIndex: 'fact_mora',
			hidden:	true
        },{
            text: 'Inter&eacute;s',
            width: 110,
			align:'right',
            dataIndex: 'mora'
        },{
            text: 'Costo Emis.',
            width: 70,
			tdCls: 'xrow-total',
			align:'right',
            dataIndex: 'costo_emis',
			summaryType: 'sum',
            summaryRenderer: function(value, summaryData, dataIndex) {
				return 'TOTAL:';
            }
        },{
            text: 'Tot. Pago',
            width: 90,
			align:'right',
            dataIndex: 'tot_pagado',
			summaryType: 'sum',
			renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
				return Ext.util.Format.number(value, '0.00');
            },
            summaryRenderer: function(value, summaryData, dataIndex) {
				return Ext.util.Format.number(value, '0.00');
            },
            field: {
                xtype: 'numberfield'
            }
        }
		,{
            text: '(Benf)',
            width: 90,
			align:'right',
            dataIndex: 'benefic',
			summaryType: 'sum',
			renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
				return Ext.util.Format.number(value, '0.00');
            },
            summaryRenderer: function(value, summaryData, dataIndex) {
				return Ext.util.Format.number(value, '0.00');
            },
            field: {
                xtype: 'numberfield'
            }
        },{
            text: 'Total',
            width: 80,
			tdCls: 'xrow-total',
			align:'right',
            dataIndex: 'total',
			summaryType: 'sum',
			renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
				return Ext.util.Format.number(value, '0.00');
            },
            summaryRenderer: function(value, summaryData, dataIndex) {
				return Ext.util.Format.number(value, '0.00');
            },
            field: {
                xtype: 'numberfield'
            }
        },{        
        	text: 'Tipo',
            width:60,
        	dataIndex: 'tipo',
        	hidden:	true
        } ,{
            text: 'Tipo Recibo',
            width: 60,
            dataIndex: 'tipo_rec',
            	hidden:	true
            	
        },{
            text: 'ubica',
            width: 40,
			align:'center',
            dataIndex: 'ubica',
			hidden: true
        }],
		listeners: {    		
			deselect: function(model, record, index) {
				deuda(false, record);
			},
			select: function(model, record, index) {
				
			
				deuda(true, record);
			}
    	}
    });
    
    grid.render('gridRecContri');
    
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
	var fechaproyec=$('#txtFechaProyec').val();
	var rdestado = $('input[name*="rdformaPago"]:checked').val();
	
	data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss,codigo,rdestado,criterio,fechaproyec];
	
	var grid = Ext.getCmp('xgridRecContri');
	var store = grid.getStore();
	store.removeAll();
	var proxy = store.getProxy();
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {json: JSON.stringify(data)};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){

		
		}
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
	showPopup('tesocaja/mostrarrecibos?codigo='+codigo,'#poplistarec','650','350','Recibos del Contribuyente');
}

function imprimeCuentaPdf(){
	var codigo=$('#divCodigo').html();
	var chkresumen=0;
	if($('#chkresumen').is(':checked')==true){chkresumen=1;}
	var chkdetalle=0;
	if($('#chkdetalle').is(':checked')==true){chkdetalle=1;}
	var chkagrupar=0;
	if($('#chkagrupar').is(':checked')==true){chkagrupar=1;}
	
	var speriodos='';
	$('input[name*="chkperiodo"]').each(function() { 
        if ( $(this).attr('checked')){ 
			speriodos=speriodos+$(this).val()+',';
        }         
    });
	
	var sanios='';
	$('input[name*="chkanio"]').each(function() { 
        if ( $(this).attr('checked')){ 
			sanios=sanios+$(this).val()+',';
        }         
    });
	var sconceptos='';
	$('input[name*="conceptos"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	sconceptos=sconceptos+$(this).val()+',';
        }         
    });
	
	sarbitrios = '';
	$('input[name*="chkarbitrio"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	sarbitrios=sarbitrios+$(this).val()+',';
        }         
    });
	
	spredios='';
	$('input[name*="predio"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	spredios=spredios+$(this).val()+',';
        }         
    });
	
	var rdestado = $('input[name*="rdformaPago"]:checked').val();
	
	speriodos=speriodos.substring(0, speriodos.length-1);
	sanios=sanios.substring(0, sanios.length-1);
	sconceptos=sconceptos.substring(0, sconceptos.length-1);
	sarbitrios=sarbitrios.substring(0, sarbitrios.length-1);
	spredios=spredios.substring(0, spredios.length-1);
	if (rdestado=='%')
	{
		rdestado='3';
	}
	var usuario='RENTAS';
	var fechaproy=$('#txtFechaProyec').val().replace("/","n").replace("/","n");
	showPopupReport('tipo=pdf&nombrereporte=rptEstCtaMaestroContrib_proy&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario+'|PRESUMEN^'+chkresumen+'|PDETALLE^'+chkdetalle+'|PAGRUPAR^'+chkagrupar+'|PPERIODO^'+speriodos+'|PANIOS^'+sanios+'|PCONCEPTOS^'+sconceptos+'|PARBITRIOS^'+sarbitrios+'|PPREDIO^'+spredios+'|ESTADO^'+rdestado+'|FECHA_PROY^'+fechaproy,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
}


function deuda(est, obj){

		var dt=0;
		var ctotal='';
		
	if ( obj.get('ubica').trim()=='EM-*' || obj.get('ubica').trim()=='FR'){
		//alert(obj.get('ubica'));
	}
	else{
		if(est==true){
		//txtinso
			dt = Number($('#txtinso').val());
			dt += Number( obj.get('imp_insol').replace(',','')) ;
			dt=Math.round(dt*100)/100;
			$('#txtinso').val(dt);
		}else{
			dt=Number($('#txtinso').val());
			dt=Number(dt)-Number(obj.get('imp_insol').replace(',',''));
			dt=Math.round(dt*100)/100;
			$('#txtinso').val(dt);
		}
		
		var dt=0;
			
		if(est==true){
		//txtreaj
			dt = Number($('#txtreaj').val());
			dt += Number( obj.get('imp_reaj').replace(',','')) ;
			dt=Math.round(dt*100)/100;
			$('#txtreaj').val(dt);
		}else{
		//txtreaj
			dt=Number($('#txtreaj').val());
			dt=Number(dt)-Number(obj.get('imp_reaj').replace(',',''));
			dt=Math.round(dt*100)/100;
			$('#txtreaj').val(dt);
		}

		var dt=0;
		
		if(est==true){
		//txtmora
			dt = Number($('#txtmora').val());
			dt += Number(obj.get('mora').replace(',','')) ;
			dt=Math.round(dt*100)/100;
			$('#txtmora').val(dt);
		}else{
		//txtmora
			dt=Number($('#txtmora').val());
			dt=Number(dt)-Number(obj.get('mora').replace(',',''));
			dt=Math.round(dt*100)/100;
			$('#txtmora').val(dt);
		}

		var dt=0;
		
		if(est==true){
		//txtemis
			dt = Number($('#txtemis').val());
			dt += Number(obj.get('costo_emis').replace(',','')) ;
			dt=Math.round(dt*100)/100;
			$('#txtemis').val(dt);
		}else{
		//txtmora
			dt=Number($('#txtemis').val());
			dt=Number(dt)-Number(obj.get('costo_emis').replace(',',''));
			dt=Math.round(dt*100)/100;
			$('#txtemis').val(dt);
		}
		
		var dt=0;
		if(est==true){
		//txttotal
			dt = Number($('#txt_total').val());
			
			ctotal=obj.get('total').toString();
			dt += Number(ctotal.replace(',',''));
			dt = Math.round(dt*100)/100;
			$('#txt_total').val(dt);
		}else{
		//txttotal
			ctotal=obj.get('total').toString();
			dt=Number($('#txt_total').val());
			dt=Number(dt)-Number(ctotal.replace(',',''));
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
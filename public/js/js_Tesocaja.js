Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
	callAjax('tesocaja/periodos?codigocaja='+codigocaja,'','#boxCajaPeriodo','html');

    callAjax('tesocaja/predios?codigocaja='+codigocaja,'','#divPredios','html');
    
    callAjax('tesocaja/anios?codigocaja='+codigocaja,'','#divAnios','html');
    
    $('input[name^="periodos"]').live("click", function(){		chkperiodo
    	selectChecks('#chkTotPeriodos','chkperiodo[]');
	});
    
    $('input[name^="anios"]').live("click", function(){		
    	selectChecks('#chkTotAnios','chkanio[]');
	});
    
    $('input[name^="concept"]').live("click", function(){		
    	selectChecks('#chkTotConceptos','conceptos[]');
	});
    
    $('input[name^="arbit"]').live("click", function(){		
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
			{name: 'benefic', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store',{
    	model: 'RecContri',
    	autoLoad: false,
        proxy: {
            type: 'ajax',
            url : 'tesocajareccontri/conreccontri',
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
        },
		groupField: 'des_cabecera'
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridRecContri',
        store: store,        
        //title: 'Recibos',
        height:225,
        viewConfig: {
        	loadMask: {msg: 'Listado de Recibos...'},
			stripeRows: false,
        	getRowClass: function(record, index){
				var clsRow = '';
				
				switch(record.get('ubica').trim()){
					case 'VT': clsRow='xrow-valortrib'; break
					case 'CC': clsRow='xrow-coactivo'; break
					case 'FR': clsRow='xrow-fraccionamiento'; break
					case 'FT': clsRow='xrow-fiscalizacion'; break
				}
				return clsRow;
			}

    	},
		/*
        selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode: 'MULTI',
        	checkOnly: true
        }),
		*/
		features: [{
            id: 'group',
            ftype: 'groupingsummary',
            groupHeaderTpl: '{name}',
            hideGroupedHeader: true,
            enableGroupingMenu: false
        }],
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
            dataIndex: 'anno'
			//summaryType: 'max'
        },{
            text: 'Predio',
            width: 80,
            dataIndex: 'cod_pred'
			//summaryType: 'max'
        },{
            text: 'Anexo',
            width: 60,
            dataIndex: 'anexo'
			//summaryType: 'max'
        },{
            text: 'Sub Anexo',
            width: 70,
            dataIndex: 'sub_anexo'
			//summaryType: 'max'
        },{
            text: 'Periodo',
            width: 55,
            dataIndex: 'periodo'
			//summaryType: 'max'
        },{
            text: 'Imp. Insol',
            width: 100,
			align:'right',
            dataIndex: 'imp_insol'
			//summaryType: 'sum'
        },{
            text: 'Fact. Reaj',
            width: 150,
			align:'right',
            dataIndex: 'fact_reaj',
			hidden:	true
			//summaryType: 'max'
        },{
            text: 'Imp. Reaj',
            width: 110,
			align:'right',
            dataIndex: 'imp_reaj'
			//summaryType: 'max'
        },{
            text: 'Fact Mora.',
            width: 110,
			align:'right',
            dataIndex: 'fact_mora',
			hidden:	true
			//summaryType: 'max'
        },{
            text: 'Inter&eacute;s',
            width: 110,
			align:'right',
            dataIndex: 'mora'
        },{
            text: 'Costo Emis.',
            width: 110,
			tdCls: 'xrow-total',
			align:'right',
            dataIndex: 'costo_emis',
			summaryType: 'sum',
            summaryRenderer: function(value, summaryData, dataIndex) {
				return 'TOTAL:';
            }
        },{
            text: '(Benf)',
            width: 90,
			align:'right',
            dataIndex: 'benefic'
        },{
            text: 'Total',
            width: 90,
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
        }]
        
    });
    
    grid.render('gridRecContri');
    
});

function mostrarRecContri(criterio){

	$('#txt_total').val('0.00');
	$('#txtinso').val('0.00');
	$('#txtreaj').val('0.00');
	$('#txtmora').val('0.00');
	$('#txtemis').val('0.00');

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
	
	

	
	//data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss,codigo,criterio];
	data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss,codigo,rdestado,criterio];
	
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
		
		/*	var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		txttotalsum += Number(rec.get('total').replace(',',''));
				txt_total += Number(rec.get('total').replace(',',''));
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();*/
			
			var txtinsoluto=0;
			var txtreajuste=0;
			var txtinterese=0;
			var txtcostoemi=0;
			var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		
				txt_total += parseFloat(rec.get('total'));
				
				txtinsoluto += Number(rec.get('imp_insol').replace(',',''));
				
				txtreajuste += Number(rec.get('imp_reaj').replace(',',''));
				
				txtinterese += Number(rec.get('mora').replace(',',''));
				
				txtcostoemi += Number(rec.get('costo_emis').replace(',',''));
				
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();
			
			$('#txtinso').val(Math.round(txtinsoluto*100)/100).autoNumeric();
			$('#txtreaj').val(Math.round(txtreajuste*100)/100).autoNumeric();
			$('#txtmora').val(Math.round(txtinterese*100)/100).autoNumeric();
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();

		}
	});
}

function mostrarRecPagafacil(criterio){
	
	$('#txt_total').val('0.00');
	$('#txtemis').val('0.00');
	$('#txtmora').val('0.00');
	$('#txtinso').val('0.00');
	$('#txtreaj').val('0.00');
	
	var listPeriodos = [];	
	
	$('input[name*="chkperiodo"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	listPeriodos.push($(this).val());
        }*/         
    });
	
	var listAnios = [];	
	$('input[name*="chkanio"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	listAnios.push($(this).val());
        }*/         
    });
	var listConceptos = [];	
	$('input[name*="conceptos"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	listConceptos.push($(this).val());
        }*/         
    });
	listConceptos.push("*02.01*");
	listConceptos.push("*11.00*");
	listConceptos.push("*90.29*");
	
	var listArbitrios = [];	
	$('input[name*="chkarbitrio"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	listArbitrios.push($(this).val());
        }*/         
    });
	

	
	var listPredioss = [];	
	$('input[name*="predio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	listPredioss.push($(this).val());
        } */        
    });
	
	var codigo=$('#divCodigo').html();
	var rdestado = $('input[name*="rdformaPago"]:checked').val();
	
	data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss,codigo,rdestado,criterio];
	
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
			/*	var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		txttotalsum += Number(rec.get('total').replace(',',''));
				txt_total += Number(rec.get('total').replace(',',''));
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();*/
			
			var txtinsoluto=0;
			var txtreajuste=0;
			var txtinterese=0;
			var txtcostoemi=0;
			var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		
				txt_total += parseFloat(rec.get('total'));
				
				txtinsoluto += Number(rec.get('imp_insol').replace(',',''));
				
				txtreajuste += Number(rec.get('imp_reaj').replace(',',''));
				
				txtinterese += Number(rec.get('mora').replace(',',''));
				
				txtcostoemi += Number(rec.get('costo_emis').replace(',',''));
				
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();
			
			$('#txtinso').val(Math.round(txtinsoluto*100)/100).autoNumeric();
			$('#txtreaj').val(Math.round(txtreajuste*100)/100).autoNumeric();
			$('#txtmora').val(Math.round(txtinterese*100)/100).autoNumeric();
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();

		}
	});
}

function mostrarRecPagafacil2(criterio){
	
	$('#txt_total').val('0.00');
	$('#txtemis').val('0.00');
	$('#txtmora').val('0.00');
	$('#txtinso').val('0.00');
	$('#txtreaj').val('0.00');
	
	var listPeriodos = [];	
	
	$('input[name*="chkperiodo"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	listPeriodos.push($(this).val());
        }*/         
    });
	
	var listAnios = [];	
	$('input[name*="chkanio"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	listAnios.push($(this).val());
        }*/         
    });
	var listConceptos = [];	
	$('input[name*="conceptos"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	listConceptos.push($(this).val());
        }*/         
    });
	listConceptos.push("*90.29*");
	
	var listArbitrios = [];	
	$('input[name*="chkarbitrio"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	listArbitrios.push($(this).val());
        }*/         
    });
	
	var listPredioss = [];	
	$('input[name*="predio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	listPredioss.push($(this).val());
        } */        
    });
	
	var codigo=$('#divCodigo').html();
	var rdestado = $('input[name*="rdformaPago"]:checked').val();
	

	
	data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss,codigo,rdestado,criterio];
	
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
		/*	var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		txttotalsum += Number(rec.get('total').replace(',',''));
				txt_total += Number(rec.get('total').replace(',',''));
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();*/
			
			var txtinsoluto=0;
			var txtreajuste=0;
			var txtinterese=0;
			var txtcostoemi=0;
			var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		
				txt_total += parseFloat(rec.get('total'));
				
				txtinsoluto += Number(rec.get('imp_insol').replace(',',''));
				
				txtreajuste += Number(rec.get('imp_reaj').replace(',',''));
				
				txtinterese += Number(rec.get('mora').replace(',',''));
				
				txtcostoemi += Number(rec.get('costo_emis').replace(',',''));
				
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();
			
			$('#txtinso').val(Math.round(txtinsoluto*100)/100).autoNumeric();
			$('#txtreaj').val(Math.round(txtreajuste*100)/100).autoNumeric();
			$('#txtmora').val(Math.round(txtinterese*100)/100).autoNumeric();
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();

		}
	});
}

function mostrarRecPagafacil3(criterio){
	
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
	
	data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss,codigo,rdestado,criterio];
	
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
		/*	var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		txttotalsum += Number(rec.get('total').replace(',',''));
				txt_total += Number(rec.get('total').replace(',',''));
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();*/
			
			var txtinsoluto=0;
			var txtreajuste=0;
			var txtinterese=0;
			var txtcostoemi=0;
			var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		
				txt_total += parseFloat(rec.get('total'));
				
				txtinsoluto += Number(rec.get('imp_insol').replace(',',''));
				
				txtreajuste += Number(rec.get('imp_reaj').replace(',',''));
				
				txtinterese += Number(rec.get('mora').replace(',',''));
				
				txtcostoemi += Number(rec.get('costo_emis').replace(',',''));
				
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();
			
			$('#txtinso').val(Math.round(txtinsoluto*100)/100).autoNumeric();
			$('#txtreaj').val(Math.round(txtreajuste*100)/100).autoNumeric();
			$('#txtmora').val(Math.round(txtinterese*100)/100).autoNumeric();
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();

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
/*
function anulaRecibos(){
	var codigo=$('#divCodigo').html();
	showPopup('tesocaja/anularrecibo','#popanularec','550','150','Extornar recibo');
}
*/
function imprimeCuentaPdf(){
	var codigo=$('#divCodigo').html();
	var usuario='RENTAS';
	showPopupReport('tipo=pdf&nombrereporte=rptEstCta&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
}

function aceptarRecibos(){

	if($('#txtRecibo').val().length>0){
		var showResult = function(btn){
			if(btn=='yes'){
				$.ajax({
					type: "POST",     
					url: urljs + "tesocaja/aceptarrecibo",
					data: $("#frmanular").serializeObject(),     
					success: function(data) { 
						infoMessage('SIGMUN	- Anular recibo',data);
						closePopup('#frmanular');
					},     
					error: function() {
					}
				}); 
			}
		}
		confirmMessage('Anular recibo','Seguro de Anular recibo?',showResult);
	}else{
		infoMessage('Anular recibo','Ingrese un recibo.....');
		$('#txtRecibo').focus();
	}
}

function deuda(est, obj){

	var dt=0;

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
		dt += Number(obj.get('total').replace(',',''));
		dt = Math.round(dt*100)/100;
		$('#txt_total').val(dt);
	}else{
	//txttotal
		dt=Number($('#txt_total').val());
		dt=Number(dt)-Number(obj.get('total').replace(',',''));
		dt=Math.round(dt*100)/100;
		$('#txt_total').val(dt);
	}
	
}
function enviadeuda(){
	var totalpagar=0.00;
	
	var totalpagar=$('#txt_total').val().replace(',','');
	
	var criterio=$('#txtverifica').val();
	console.log(criterio);
	if(totalpagar==0.00){
//	alert(totalpagar);
		infoMessage('SIGMUN','El monto seleccionado debe ser mayor ');
	}else{
		showPopup('tesoprepago/index?totalpagar='+totalpagar+'&criterio='+criterio,'#popprintdeuda','300','350','Cancelar Deuda');
	}
	
}

function cerrar_indicador(){
	$.ajax({
		type: "POST",     
		url: urljs + "tesocaja/btnindicador",
		data: 'id_registro='+$('#id_registro').val(),     
		success: function(data) { 
				//alert(data);
				closePopup('#poptesore')
		},     
		error: function() {
		}
	});
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

function mostrarRecContri_criterio(criterio){

	$('#txt_total').val('0.00');
	$('#txtinso').val('0.00');
	$('#txtreaj').val('0.00');
	$('#txtmora').val('0.00');
	$('#txtemis').val('0.00');

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
	var busqueda="";
	data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss,codigo,criterio];
	
	var grid = Ext.getCmp('xgridRecContri');
	var store = grid.getStore();
	store.removeAll();
	var proxy = store.getProxy();
	var params = proxy.extraParams;
	delete params;

	  if($("#chFr").is(':checked')) {  
           busqueda="1";
        } else {  
           busqueda="2";
        }  
	
	proxy.extraParams = {json: JSON.stringify(data),busqueda: busqueda,txtBuscodigo: $('#txtBuscodigo').val()};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){
		
		/*	var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		txttotalsum += Number(rec.get('total').replace(',',''));
				txt_total += Number(rec.get('total').replace(',',''));
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();*/
			
			var txtinsoluto=0;
			var txtreajuste=0;
			var txtinterese=0;
			var txtcostoemi=0;
			var txttotalsum=0;
			var txt_total=0;
			
	    	store.each(function (rec){
	    		
				txt_total += parseFloat(rec.get('total'));
				
				txtinsoluto += Number(rec.get('imp_insol').replace(',',''));
				
				txtreajuste += Number(rec.get('imp_reaj').replace(',',''));
				
				txtinterese += Number(rec.get('mora').replace(',',''));
				
				txtcostoemi += Number(rec.get('costo_emis').replace(',',''));
				
			});
	    	$('#txttotalsum').val(Math.round(txttotalsum*100)/100).autoNumeric();
			$('#txt_total').val(Math.round(txt_total*100)/100).autoNumeric();
			
			$('#txtinso').val(Math.round(txtinsoluto*100)/100).autoNumeric();
			$('#txtreaj').val(Math.round(txtreajuste*100)/100).autoNumeric();
			$('#txtmora').val(Math.round(txtinterese*100)/100).autoNumeric();
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();

		}
	});
}


	//--------------------------
	$('#btnPagafaci2014a').click(function(){
		$('#txtverifica').val('6');
	});
		
	$('#btnPagafaci2014b').click(function(){
		$('#txtverifica').val('7');
	});
	
	$('#btnPagafaci2014c').click(function(){
		$('#txtverifica').val('8');
	});
	
	//====================================
	
	$('#btnMostrar').click(function(){
		$('#txtverifica').val('0');
	});
	$('#btnProntoPago').click(function(){
		$('#txtverifica').val('0');
	});
	$('#btnProntoPagoMulta').click(function(){
		$('#txtverifica').val('5');
	});
	$('#btnProntoPagoCoactivo').click(function(){
		$('#txtverifica').val('0');
	});
	$('#btnPagarRecibos').click(function(){
		$('#txtverifica').val('0');
	});
	$('#btnVerRec').click(function(){
		$('#txtverifica').val('0');
	});
	
	//--------------------------
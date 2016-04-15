Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	

	/*para visualizar los fraccionamientos */
		$( "#btnViewFraccionamientos" ).button().click(function() {
			$('#btnPrintMenu1').show();
      	})
      	.next().button({        
			text: false,
            icons: { primary: "ui-icon-triangle-1-s"
		           }
        }).click(function() {
          var menu = $( this ).parent().next().show().position({
            my: "left top",
            at: "left bottom",
            of: this
          });
		  
          $( document ).one( "click", function() {
            $('#btnPrintMenu1').hide();
          });

          return false;

         })

        .parent()
          .buttonset()
          .next()
            .hide()
            .menu();
	/*fin de la visualizacion de fraccionamientos*/
	
	
	
	
	/*para visualizar los pagos */
		$( "#btnViewPagos" ).button().click(function() {
			$('#btnPrintMenu2').show();
      	})
      	.next().button({        
			text: false,
            icons: { primary: "ui-icon-triangle-1-s"
		           }
        }).click(function() {
          var menu = $( this ).parent().next().show().position({
            my: "left top",
            at: "left bottom",
            of: this
          });
		  
          $( document ).one( "click", function() {
            $('#btnPrintMenu2').hide();
          });

          return false;

         })

        .parent()
          .buttonset()
          .next()
            .hide()
            .menu();
	/*fin de la visualizacion de pagos*/



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
            url : 'rentascajareccontri/conreccontri',
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
					case 'CC': clsRow='xrow-coactivo'; break
					case 'FR': clsRow='xrow-fraccionamiento'; break
					case 'FT': clsRow='xrow-fiscalizacion'; break
					case 'EM-*': clsRow='xrow-cancelado'; break
					case 'FE-*': clsRow='xrow-cancelado'; break
					case 'VT-*': clsRow='xrow-cancelado'; break
					case 'DC-*': clsRow='xrow-compensado';break
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


//PARA MOSTRAR LOS FRACCIONAMIENTOS
function print_fracc(flag){

	var codigo=$('#divCodigo').html();

	if (flag==1){
	showPopup('fraccionar/detallefrac?codigo='+codigo+'&flag='+flag,'#poplistafrac','990','400','Convenios realizados con el SIGMUN');
	}
	else if (flag==2){
	showPopup('fraccionar/detallefracinfo?codigo='+codigo+'&flag='+flag,'#poplistafrac','990','400','Convenios realizados con el INFOSAT');
	}
}

//PARA MOSTRAR LOS PAGOS REALIZADOS
function print_recibos(flag){
	var codigo=$('#divCodigo').html();
	showPopup('rentascaja/verpagos?codigo='+codigo+'&flag='+flag,'#poplistafrac','990','400','Recibos');
}


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
		/*	var txtinsoluto=0;
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
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();*/
		
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
		/*	var txtinsoluto=0;
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
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();*/
		
		}
	});
	
	imprimeProntoPago1();
	
	
	
}

//============================================================
function mostrarRecPagafacila(criterio){
	
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
		/*	var txtinsoluto=0;
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
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();*/
		
		}
	});
	if(criterio==7)
	{
		//alert(criterio);
		imprimeProntoPagoa(criterio);
	}
	else if(criterio==8)
	{
		//alert(criterio);
		imprimeProntoPagoa(criterio);
	}
	else
	{
		//alert(criterio);
		imprimeProntoPagoa(criterio);
	}
}

//============================================================


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
		/*	var txtinsoluto=0;
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
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();*/
		
		}
	});
	
		imprimeProntoPago2();
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
		/*	var txtinsoluto=0;
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
			$('#txtemis').val(Math.round(txtcostoemi*100)/100).autoNumeric();*/
		
		}
	});
	
		imprimeProntoPago3();
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
	var usuario=$('#hd_usuario').val();
	
	speriodos=speriodos.substring(0, speriodos.length-1);
	sanios=sanios.substring(0, sanios.length-1);
	sconceptos=sconceptos.substring(0, sconceptos.length-1);
	sarbitrios=sarbitrios.substring(0, sarbitrios.length-1);
	spredios=spredios.substring(0, spredios.length-1);
	
	if (rdestado=='%')
	{
		rdestado='3';
	}
	//var usuario='RENTAS';
	showPopupReport('tipo=pdf&nombrereporte=rptEstCtaMaestroContrib&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario+'|PRESUMEN^'+chkresumen+'|PDETALLE^'+chkdetalle+'|PAGRUPAR^'+chkagrupar+'|PPERIODO^'+speriodos+'|PANIOS^'+sanios+'|PCONCEPTOS^'+sconceptos+'|PARBITRIOS^'+sarbitrios+'|PPREDIO^'+spredios+'|ESTADO^'+rdestado+'|CRITERIO^'+0,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
}

//=============================================
function imprimeProntoPagoa(criterio){
	var codigo=$('#divCodigo').html();
	var chkresumen=0;
	if($('#chkresumen').is(':checked')==true){chkresumen=1;}
	var chkdetalle=0;
	if($('#chkdetalle').is(':checked')==true){chkdetalle=1;}
	var chkagrupar=0;
	if($('#chkagrupar').is(':checked')==true){chkagrupar=1;}
	
	var speriodos='';
	$('input[name*="chkperiodo"]').each(function() { 
     /*   if ( $(this).attr('checked')){ 
			speriodos=speriodos+$(this).val()+',';
        }         */
    });
	
	var sanios='';
	$('input[name*="chkanio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
			sanios=sanios+$(this).val()+',';
        } */        
    });
	var sconceptos='';
	$('input[name*="conceptos"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	sconceptos=sconceptos+$(this).val()+',';
        } */        
    });
	sconceptos='*02.01*,*11.00*,*90.29*';
	
	sarbitrios = '';
	$('input[name*="chkarbitrio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	sarbitrios=sarbitrios+$(this).val()+',';
        } */        
    });
	
	spredios='';
	$('input[name*="predio"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	spredios=spredios+$(this).val()+',';
        }*/         
    });
	
	var rdestado = $('input[name*="rdformaPago"]:checked').val();
	var usuario=$('#hd_usuario').val();
	
	speriodos=speriodos.substring(0, speriodos.length-1);
	sanios=sanios.substring(0, sanios.length-1);
	//sconceptos=sconceptos.substring(0, sconceptos.length-1);
	sarbitrios=sarbitrios.substring(0, sarbitrios.length-1);
	spredios=spredios.substring(0, spredios.length-1);
	if (rdestado=='%')
	{
		rdestado='3';
	}
	//var usuario='RENTAS';
	

	
	if(criterio==7)
	{
		//alert(criterio);
		showPopupReport('tipo=pdf&nombrereporte=rptEstCtaMaestroContrib&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario+'|PRESUMEN^'+chkresumen+'|PDETALLE^'+chkdetalle+'|PAGRUPAR^'+chkagrupar+'|PPERIODO^'+speriodos+'|PANIOS^'+sanios+'|PCONCEPTOS^'+sconceptos+'|PARBITRIOS^'+sarbitrios+'|PPREDIO^'+spredios+'|ESTADO^'+rdestado+'|CRITERIO^'+7,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
	}
	else if(criterio==8)
	{
		//alert(criterio);
		showPopupReport('tipo=pdf&nombrereporte=rptEstCtaMaestroContrib&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario+'|PRESUMEN^'+chkresumen+'|PDETALLE^'+chkdetalle+'|PAGRUPAR^'+chkagrupar+'|PPERIODO^'+speriodos+'|PANIOS^'+sanios+'|PCONCEPTOS^'+sconceptos+'|PARBITRIOS^'+sarbitrios+'|PPREDIO^'+spredios+'|ESTADO^'+rdestado+'|CRITERIO^'+8,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
	}
	else
	{
		//alert(criterio);
		showPopupReport('tipo=pdf&nombrereporte=rptEstCtaMaestroContrib&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario+'|PRESUMEN^'+chkresumen+'|PDETALLE^'+chkdetalle+'|PAGRUPAR^'+chkagrupar+'|PPERIODO^'+speriodos+'|PANIOS^'+sanios+'|PCONCEPTOS^'+sconceptos+'|PARBITRIOS^'+sarbitrios+'|PPREDIO^'+spredios+'|ESTADO^'+rdestado+'|CRITERIO^'+9,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
	}
	
	
	
	}
//=============================================


function imprimeProntoPago1(){
	var codigo=$('#divCodigo').html();
	var chkresumen=0;
	if($('#chkresumen').is(':checked')==true){chkresumen=1;}
	var chkdetalle=0;
	if($('#chkdetalle').is(':checked')==true){chkdetalle=1;}
	var chkagrupar=0;
	if($('#chkagrupar').is(':checked')==true){chkagrupar=1;}
	
	var speriodos='';
	$('input[name*="chkperiodo"]').each(function() { 
     /*   if ( $(this).attr('checked')){ 
			speriodos=speriodos+$(this).val()+',';
        }         */
    });
	
	var sanios='';
	$('input[name*="chkanio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
			sanios=sanios+$(this).val()+',';
        } */        
    });
	var sconceptos='';
	$('input[name*="conceptos"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	sconceptos=sconceptos+$(this).val()+',';
        } */        
    });
	sconceptos='*02.01*,*11.00*,*90.29*';
	
	sarbitrios = '';
	$('input[name*="chkarbitrio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	sarbitrios=sarbitrios+$(this).val()+',';
        } */        
    });
	
	spredios='';
	$('input[name*="predio"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	spredios=spredios+$(this).val()+',';
        }*/         
    });
	
	var rdestado = $('input[name*="rdformaPago"]:checked').val();
	var usuario=$('#hd_usuario').val();
	
	speriodos=speriodos.substring(0, speriodos.length-1);
	sanios=sanios.substring(0, sanios.length-1);
	//sconceptos=sconceptos.substring(0, sconceptos.length-1);
	sarbitrios=sarbitrios.substring(0, sarbitrios.length-1);
	spredios=spredios.substring(0, spredios.length-1);
	if (rdestado=='%')
	{
		rdestado='3';
	}
	//var usuario='RENTAS';
	showPopupReport('tipo=pdf&nombrereporte=rptEstCtaMaestroContrib&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario+'|PRESUMEN^'+chkresumen+'|PDETALLE^'+chkdetalle+'|PAGRUPAR^'+chkagrupar+'|PPERIODO^'+speriodos+'|PANIOS^'+sanios+'|PCONCEPTOS^'+sconceptos+'|PARBITRIOS^'+sarbitrios+'|PPREDIO^'+spredios+'|ESTADO^'+rdestado+'|CRITERIO^'+4,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
}

function imprimeProntoPago2(){
	var codigo=$('#divCodigo').html();
	var chkresumen=0;
	if($('#chkresumen').is(':checked')==true){chkresumen=1;}
	var chkdetalle=0;
	if($('#chkdetalle').is(':checked')==true){chkdetalle=1;}
	var chkagrupar=0;
	if($('#chkagrupar').is(':checked')==true){chkagrupar=1;}
	
	var speriodos='';
	$('input[name*="chkperiodo"]').each(function() { 
     /*   if ( $(this).attr('checked')){ 
			speriodos=speriodos+$(this).val()+',';
        }         */
    });
	
	var sanios='';
	$('input[name*="chkanio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
			sanios=sanios+$(this).val()+',';
        } */        
    });
	var sconceptos='';
	$('input[name*="conceptos"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	sconceptos=sconceptos+$(this).val()+',';
        } */        
    });
	sconceptos='*90.29*';
	
	sarbitrios = '';
	$('input[name*="chkarbitrio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	sarbitrios=sarbitrios+$(this).val()+',';
        } */        
    });
	
	spredios='';
	$('input[name*="predio"]').each(function() { 
       /* if ( $(this).attr('checked')){ 
        	spredios=spredios+$(this).val()+',';
        }*/         
    });
	
	var rdestado = $('input[name*="rdformaPago"]:checked').val();
	var usuario=$('#hd_usuario').val();
	
	speriodos=speriodos.substring(0, speriodos.length-1);
	sanios=sanios.substring(0, sanios.length-1);
	//sconceptos=sconceptos.substring(0, sconceptos.length-1);
	sarbitrios=sarbitrios.substring(0, sarbitrios.length-1);
	spredios=spredios.substring(0, spredios.length-1);
	if (rdestado=='%')
	{
		rdestado='3';
	}
	//var usuario='RENTAS';
	showPopupReport('tipo=pdf&nombrereporte=rptEstCtaMaestroContrib&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario+'|PRESUMEN^'+chkresumen+'|PDETALLE^'+chkdetalle+'|PAGRUPAR^'+chkagrupar+'|PPERIODO^'+speriodos+'|PANIOS^'+sanios+'|PCONCEPTOS^'+sconceptos+'|PARBITRIOS^'+sarbitrios+'|PPREDIO^'+spredios+'|ESTADO^'+rdestado+'|CRITERIO^'+6,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
}

function imprimeProntoPago3(){
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
	var usuario=$('#hd_usuario').val();
	
	speriodos=speriodos.substring(0, speriodos.length-1);
	sanios=sanios.substring(0, sanios.length-1);
	sconceptos=sconceptos.substring(0, sconceptos.length-1);
	sarbitrios=sarbitrios.substring(0, sarbitrios.length-1);
	spredios=spredios.substring(0, spredios.length-1);
	if (rdestado=='%')
	{
		rdestado='3';
	}
	//var usuario='RENTAS';
	showPopupReport('tipo=pdf&nombrereporte=rptEstCtaMaestroContrib&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario+'|PRESUMEN^'+chkresumen+'|PDETALLE^'+chkdetalle+'|PAGRUPAR^'+chkagrupar+'|PPERIODO^'+speriodos+'|PANIOS^'+sanios+'|PCONCEPTOS^'+sconceptos+'|PARBITRIOS^'+sarbitrios+'|PPREDIO^'+spredios+'|ESTADO^'+rdestado+'|CRITERIO^'+5,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
}




function imprimeCuentaPdfPpago(criterio){
	var codigo=$('#divCodigo').html();
	var chkresumen=0;
	if($('#chkresumen').is(':checked')==true){chkresumen=1;}
	var chkdetalle=0;
	if($('#chkdetalle').is(':checked')==true){chkdetalle=1;}
	var chkagrupar=0;
	if($('#chkagrupar').is(':checked')==true){chkagrupar=1;}
	
	var speriodos='';
	$('input[name*="chkperiodo"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
			speriodos=speriodos+$(this).val()+',';
        } */        
    });
	
	var sanios='';
	$('input[name*="chkanio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
			sanios=sanios+$(this).val()+',';
        } */        
    });
	var sconceptos='';
	$('input[name*="conceptos"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	sconceptos=sconceptos+$(this).val()+',';
        } */        
    });
	
	sarbitrios = '';
	$('input[name*="chkarbitrio"]').each(function() { 
        if ( $(this).attr('checked')){ 
        	sarbitrios=sarbitrios+$(this).val()+',';
        }         
    });
	
	spredios='';
	$('input[name*="predio"]').each(function() { 
        /*if ( $(this).attr('checked')){ 
        	spredios=spredios+$(this).val()+',';
        } */        
    });
	
	var rdestado = $('input[name*="rdformaPago"]:checked').val();
	var usuario=$('#hd_usuario').val();
	
	speriodos=speriodos.substring(0, speriodos.length-1);
	sanios=sanios.substring(0, sanios.length-1);
	sconceptos=sconceptos.substring(0, sconceptos.length-1);
	sarbitrios=sarbitrios.substring(0, sarbitrios.length-1);
	spredios=spredios.substring(0, spredios.length-1);
	if (rdestado=='%')
	{
		rdestado='3';
	}
	//var usuario='RENTAS';
	showPopupReport('tipo=pdf&nombrereporte=rptEstCtaMaestroContrib&param=PCODIGO^'+codigo+'|PUSUARIO^'+usuario+'|PRESUMEN^'+chkresumen+'|PDETALLE^'+chkdetalle+'|PAGRUPAR^'+chkagrupar+'|PPERIODO^'+speriodos+'|PANIOS^'+sanios+'|PCONCEPTOS^'+sconceptos+'|PARBITRIOS^'+sarbitrios+'|PPREDIO^'+spredios+'|ESTADO^'+rdestado,'pouprptemitido',700,600,'Impresion de Estado de Cuenta');
}


function deuda(est, obj){
		var dt=0;
		var ctotal='';
		
	if ( obj.get('ubica').trim()=='EM-*' || obj.get('ubica').trim()=='FR'){
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

//CAPTURAMOS EL TIPO DE OPCION DE FRACCIONAMIENTO
// 1 ==> CONVENIO NORMAL
// 2 ==> CONVENIO CON BENEFICIO (10% DESC CASA HABITACION)

var flag_fracc=$('#txtflag_mostrar').val();


//EVALUAMOS CONDICIONES DE CONVENIO PARA EL CONTRIBUYENTE
		
		var codigo=$('#divCodigo').html();

			Ext.Ajax.request({
				url: urljs + "rentascaja/condicionfrac?codigo="+codigo+'&param='+$('#txtflag_mostrar').val(),
				method: "POST",
				success: function(response){
		
					 var parametros=response.responseText;

					 var array=parametros.split('*');				 

					 var estado=array[0];
					 var porc_fracc=array[1];
					 var monto_min=array[2];
					 var porc_ini=array[3];
					 var max_cuotas=array[4];
					 var condicion_id=array[5];


					 var total_deuda= parseFloat($('#txt_total').val());



						if(total_deuda>=monto_min)
						{
							//infoMessage('SIGMUN','Exito Convenio Especial!!!');
							
							//CAPTURAMOS TODOS LOS RECIBOS DEL FRACCIONAMIENTO

							var grid = Ext.getCmp('xgridRecContri');
							var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();

							if(selectedRecordsArray.length>0)
							{
								var cant=0;
								var total_deuda_IP=0;
								var total_deuda_ARB=0;
								Ext.each(selectedRecordsArray, function (item) {		  	
									ubica = item.data.ubica; 
									tipo = item.data.tipo; 
									imp_reaj = item.data.imp_reaj;
									mora= item.data.mora;
									costo_emis = item.data.costo_emis;
									
									if (ubica=='FE  ' || tipo=='12.23'){
										cant=cant+1;
									}
									if(tipo=='90.09' || tipo =='90.00' ){
										cant=cant+1;
									}
									if(tipo=='90.33' || tipo=='90.32'){
										cant=cant+1;
									}
									if(tipo=='02.01')
									{
										total_deuda_IP = total_deuda_IP+Number(imp_reaj) + Number(costo_emis) + Number(mora);
										total_deuda_IP = Math.round(total_deuda_IP*100)/100;
									}
									if(tipo=='11.00')
									{
										total_deuda_ARB = total_deuda_ARB+(Number(imp_reaj) + Number(costo_emis) + Number(mora))*0.90;
										total_deuda_ARB = Math.round(total_deuda_ARB*100)/100;
									}
								});
								if(cant==0){

									var totalpagar=0.00;
							
									var totalpagar=$('#txt_total').val().replace(',','');

									if(totalpagar==0.00){
										infoMessage('SIGMUN','El monto seleccionado debe ser mayor ');
										}
									else{
												if(typeof (max_cuotas) == "undefined"){max_cuotas=0 };
												if(typeof (condicion_id) == "undefined"){condicion_id=''};
											if(flag_fracc==2)
											{
												totalpagar=total_deuda_IP+total_deuda_ARB;
											    showPopup('fraccionar/index?totalpagar='+totalpagar+'&param='+flag_fracc+'&porc_ini='+porc_ini+'&max_cuotas='+max_cuotas+'&condicion_id='+condicion_id+'&estado='+estado,'#popfraccionardeuda','960','380','Fraccionar Deuda ==> Beneficio Arbitrios (Casa Habitacion) [10%]');
											}
											else{


												if(estado==0)
												{
													showPopup('fraccionar/index?totalpagar='+totalpagar+'&param='+flag_fracc+'&porc_ini='+porc_ini+'&max_cuotas='+max_cuotas+'&condicion_id='+condicion_id+'&estado='+estado,'#popfraccionardeuda','960','380','Fraccionar Deuda');     	
												}	
												else
												{
													var showResult = function(btn){
								                    	if(btn=='yes')
								                          showPopup('fraccionar/index?totalpagar='+totalpagar+'&param='+flag_fracc+'&porc_ini='+porc_ini+'&max_cuotas='+max_cuotas+'&condicion_id='+condicion_id+'&estado='+estado,'#popfraccionardeuda','960','380','Fraccionar Deuda');     	
								                    };
								                    confirmMessage('Excepcion Fraccionamiento',' Se Otorga un Porcentaje Inicial ( '+porc_ini+'% ) y maximo de Cuotas ('+max_cuotas+' ) al codigo : '+$('#divCodigo').html()+'.  Desea Continuar con la Operacion?',showResult);
												}
											}
										}	
								}
								else{
									infoMessage('SIGMUN','Desmarque Fraccionamiento, Gastos y Costas');
								}
							}
							else {
								infoMessage('SIGMUN','Debe Seleccionar un registro ');
							}

						}
						else
						{
							var error_mensaje='';
							error_mensaje='La Deuda a Fraccionar no puede ser menor al '+porc_fracc+'% de la UIT actual';
							infoMessage('SIGMUN',error_mensaje);
						}
				},
				failure: function(response, opts){
					infoMessage('Alerta','Error al consultar Las condiciones de fraccionamiento del contribuyente');
				}
					
			});



}

function verfraccionamiento(ruta){
	var codigo=$('#divCodigo').html();
	showPopup(ruta+'?codigo='+codigo,'#poplistafrac','990','400','Fraccionar Deuda');
}
function recibos(ruta){
	var codigo=$('#divCodigo').html();
	showPopup(ruta+'?codigo='+codigo,'#poplistafrac','990','400','Recibos');
}
////////////
function ReciboModificado(){

	var grid = Ext.getCmp('xgridRecContri');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0)
	{
		//showPopup('recibomodificado/recibomodi','#popuprecibomodi','410','160','Modificar Recibo');
		
			var cant=0;
			Ext.each(selectedRecordsArray, function (item) {		  	
				ubica = item.data.ubica; 
				tipo = item.data.tipo; 
				if ($.trim(ubica)=='FE' || tipo=='12.23'){
					cant=cant+1;
				}
				if ($.trim(ubica)=='CC'){
					cant=cant+1;
				}
				
			});
			if(cant==0){
				showPopup('recibomodificado/recibomodi','#popuprecibomodi','410','160','Modificar Recibo');	
			}
			else{
				infoMessage('SIGMUN','Desmarque Fraccionamiento, Gastos y Costas');
			}
		
	}
	else{
	
		
		infoMessage('Alerta','Seleccione al menos un registro!!!');
	}		
		
}

function GenerarReciboModificado(){

	var txtNumdocu = $('#txtNumdocu').val();
	var txtGlosa = $('#txtGlosa').val();	

	var grid = Ext.getCmp('xgridRecContri');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    
	
	
	 if(selectedRecordsArray.length>0){			
		
		// if(txtNumdocu==''){
				// infoMessage('Alerta','Debe ingresar un numero documento');	
		// }
		
		// else if(txtGlosa==''){
				 // infoMessage('Alerta','Debe ingresar una observacion');	
		// }
		
		if(txtGlosa==''){
				 infoMessage('Alerta','Debe ingresar una observacion');	
		}
		
		else	
		{
		
			var showResult = function(btn){
				if(btn=='yes'){
						
					var lsRecibo = [];
					
						Ext.each(selectedRecordsArray, function (item) {
							
							lsRecibo.push({
								num_ingr: 0,
								idrecibo: item.data.idrecibo,						
								codigo:item.data.codigo,
								anno: item.data.anno, 
								cod_pred: item.data.cod_pred,
								anexo:item.data.anexo,
								sub_anexo:item.data.sub_anexo,
								tipo_rec: item.data.tipo_rec,
								periodo: item.data.periodo, 
								fact_reaj: item.data.fact_reaj, 
								fact_mora: item.data.fact_mora, 
								observacion:'',
								operador:'',
								estacion:'',
								fech_ing:'01/01/2010',
								ubica:item.data.ubica
							});
							
						});

							
						Ext.Ajax.request({
							url: urljs + "recibomodificado/grabarrecibo",
							method: "POST",
							params: {json: JSON.stringify(lsRecibo),txtNumdocu:txtNumdocu,txtGlosa:txtGlosa},
							success: function(response){
							
										 closePopup('#popuprecibomodi');	
										 grid.getStore().loadPage(grid.getStore().currentPage);		
										 
										
							},
							failure: function(response, opts){
								infoMessage('Alerta','Error');
							}
						});
				}
			};
			confirmMessage('Alerta','Seguro de Generar Recibo?',showResult);
	}
	
	}
	else{
		infoMessage('Alerta','Seleccione al menos un registro');
	}
}

	$('#btnMostrar_Ben_Frac').click(function() { 
        $('#txtflag_mostrar').val('2');     
    });
	
	$('#btnMostrar').click(function() { 
        $('#txtflag_mostrar').val('1');     
    });







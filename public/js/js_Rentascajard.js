Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	/*
	callAjax('rentascajaop/periodos?codigocaja='+codigocaja,'','#boxCajaPeriodo','html');

    callAjax('rentascajaop/predios?codigocaja='+codigocaja,'','#divPredios','html');
    
    callAjax('rentascajaop/anios?codigocaja='+codigocaja,'','#divAnios','html');
    
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
	*/
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
			{name: 'total', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'RecContri',
    	autoLoad: true,
        proxy: {
            type: 'ajax',
            //url : 'rentascajareccontrird/conreccontri?codigo='+codigocaja,
			url : 'rentascajareccontrird/conreccontri',
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
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridRecContri',
        store: store,        
        title: 'Recibos',
		width:970,
        height: 350,
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
            hidden:	true
        },{        
        	text: 'Codigo',
        	//flex: 1,
            width:55,
        	dataIndex: 'codigo',
			hidden:	true
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
            dataIndex: 'costo_emis'
        },{
            text: 'Total',
            width: 80,
			align:'right',
            dataIndex: 'total'
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

function mostrarRecContri(){

	disableButton('#btnProyeccionrd');

	$('#txt_total').val('0.00');
	$('#txtemis').val('0.00');
	$('#txtmora').val('0.00');
	$('#txtinso').val('0.00');
	$('#txtreaj').val('0.00');
	/*
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
	*/
	var codigo=$('#divCodigo').html();
	var fechaproyec=$('#txtFechaproyec').val();
	//var rdestado = $('input[name*="rdformaPago"]:checked').val();
	
	//data = [listPeriodos,listAnios,listConceptos,listArbitrios,listPredioss,codigo,rdestado];
	data = [codigo,fechaproyec];
	
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
			enableButton('#btnProyeccionrd');
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

function addDatosRD(data)
{

$("#txtidvalor").val(data.idvalor);
$("#txtnumval").val(data.numval);
$("#txtanoval").val(data.anoval);

showPopupReport('tipo=pdf&nombrereporte=rptRD&param=id_valor^'+data.idvalor+'|num_val^'+data.numval+'|ano_val^'+data.anoval,'pouprptemitido',700,600,'Impresion de Resolucion de Determinación');

}

function generarrd(){
	//alert(508);
	
	var divCodigo = $('#divCodigo').html();
	var divDocu = $('#divDocu').html();
	var divContri = $('#divContri').html();
	var divDirec = $('#divDirec').html();
	var divFechaproyec = $('#txtFechaproyec').val();
	
	var grid = Ext.getCmp('xgridRecContri');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	
	if(selectedRecordsArray.length>0){
		
		 var showResult = function(btn){
			if(btn=='yes'){
				
				var idList = [];
				Ext.each(selectedRecordsArray, function (item) {
					idList.push(
						{
							num_ingr: 0,
							idrecibo: item.data.idrecibo,
							montotal: item.data.total.replace(',',''),
							codigo:item.data.codigo,
							anno: item.data.anno, 
							cod_pred: item.data.cod_pred,
							anexo:item.data.anexo,
							sub_anexo:item.data.sub_anexo,
							tipo_rec: item.data.tipo_rec,
							periodo: item.data.periodo, 
							imp_insol: item.data.imp_insol.replace(',',''),
							fact_reaj: item.data.fact_reaj, 
							imp_reaj: item.data.imp_reaj.replace(',',''), 
							fact_mora: item.data.fact_mora, 
							imp_mora: item.data.mora.replace(',',''), 
							costo_emis: item.data.costo_emis.replace(',',''), 
							observacion:'',
							operador:'',
							estacion:'',
							fech_ing:'01/01/2010'
						});
				});

				Ext.Ajax.request({
		            url: urljs + "tesocajareccontrird/registrapago",
		            method: "POST",
		            params: {json: JSON.stringify(idList),divCodigo:divCodigo,divDocu:divDocu,divContri:divContri,divDirec:divDirec,divFechaproyec:divFechaproyec},
		            success: function(response){
					
						mostrarRecContri();
	
						
						$("#div_hrgeneral").html(response.responseText);
						
						//grid.getStore().loadPage(grid.getStore().currentPage);
						//response.Text;
						//response.Text;
						/*
						data = Ext.JSON.decode(response.responseText);
							  	for(var i=0 ; i < 1; i++){
									addDatosRD(data[i]);
								}
						
						grid.getStore().loadPage(grid.getStore().currentPage);
							*/					
						//Ext.getCmp('xgridRecContri').getStore().load();
		            },
		            failure: function(response, opts){
		            	infoMessage('Contribuyentes','Error AS ');
		            }
		        });
			}
 		};
 		
 		confirmMessage('Contribuyentes','Seguro de Generar RD?',showResult);
		
	}
	else{
		infoMessage('Contribuyentes','Seleccione al menos un registro!!');
	}
	
}

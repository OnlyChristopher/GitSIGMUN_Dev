Ext.require([
'Ext.grid.*',
'Ext.data.*',
'Ext.selection.CheckboxModel'
]);
Ext.onReady(function(){
	//alert("sdfsadasdasdsadas");
    Ext.QuickTips.init();
	//var DataEnvias = [];
	//alert("HELP")
    $("input[type='button']").button();
	$("#btnEliminarSeleccionado").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");
	$("#btnGenerarExpediente").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");
	$("#btnEnviar").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");	
	$("#tabelemtentos div").hide()
	
	/*
	$("#divCodigo").hide();
	$("#divContribuyente").hide();
	$("#divRango1").hide();
	$("#divRango2").hide();
	$("#divRango3").hide();
	$("#divExpediente").hide();
	*/
	
	
    /*
	var val_recepcion = $('#hdnFlag').val();
	
	
	if(val_recepcion==0){
		setenableButton('#btnRecepcionar');
		setenableButton('#btnDevolver');
		setdisableButton('#btnExpendiente');
		setdisableButton('#btnEnviar');
		setdisableButton('#btnExigibilidad');
	}else if(val_recepcion==1){
		setdisableButton('#btnRecepcionar');
		setdisableButton('#btnDevolver');
		setenableButton('#btnExpendiente');
		setenableButton('#btnEnviar');
		setenableButton('#btnExigibilidad');
	}
	*/ 
	

	
	
	
	
	  Ext.define('Bandeja', {
        extend: 'Ext.data.Model',
        fields: [
		
            {name: 'desvalor'},
            {name: 'numerval'},
			{name: 'fechaemi'},
            {name: 'cidpers'},            
            {name: 'nombre'},
            {name: 'montotal', type: 'decimal'},
			{name: 'cancelado', type: 'decimal'},
			{name: 'pendiente', type: 'decimal'},
			{name: 'num_exp'},
			{name: 'idvalor'},
			{name: 'numval'},
			{name: 'anoval'},
			{name: 'paso'},
			{name: 'idruta'},
			{name: 'numerocargo'},
			{name: 'numcargo'},
			{name: 'anocargo'},
			{name: 'fechanotifica'},
			{name: 'doc_memo'},
			{name: 'estadotmp'},
			{name: 'estadoimp'}
		
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Bandeja',
    	autoLoad: true,
		pageSize: 10,  
        proxy: {
            type: 'ajax',
            url : 'administrarvalor/retornadatos',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
	var toolbar = new Ext.Toolbar({
    	items : 
		[{
			text : 'Bandeja Valores Recibidos',
			xtype:'splitbutton',
			iconCls: 'Irecepcionado',
			listeners: {
            	click: function(){
					/*Ext.getCmp('xgridBandeja').columns[0].setVisible(false);	*/
					$('#hdnFlag').val('0');
					$("#btnEliminarSeleccionado").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");
					$("#btnGenerarExpediente").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");
					$("#btnEnviar").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");
					$("#btnDevolver").removeAttr("disabled").attr("aria-disabled","false").removeClass("ui-button-disabled ui-state-disabled");
					$("#btnRecepcionar").removeAttr("disabled").attr("aria-disabled","false").removeClass("ui-button-disabled ui-state-disabled");
					$("#xgridBandeja_header_hd-textEl").html("Bandeja de Valores Recibidos")
					buscarValor();
				}
            }
		},{
            xtype: 'tbseparator'
        },{
			text : 'Bandeja Valores Recepcionados',
			xtype:'splitbutton',
			iconCls: 'received',
   		    /*menu: [{
				 	text: 'Remover Valores Seleccionado',
					iconCls: 'TEliminar',
					handler: function(){
						Ext.example.msg('Click', 'You clicked on "Action 1".');
					}},
					{
				 	text: 'Seleccionar Todos Los Valores',
					iconCls: 'TSeleccionar',
					handler: function(){
						Ext.example.msg('Click', 'You clicked on "Action 1".');
					}}
					],*/
			listeners: {
            	click: function(){
					
					//action.setDisabled(!action.isDisabled());
           			//this.setText(action.isDisabled() ? 'Enable' : 'Disable');
				 /*	Ext.getCmp('xgridBandeja').columns[0].setVisible(true);	*/
					$('#hdnFlag').val('1');
					$("#btnEliminarSeleccionado").removeAttr("disabled").attr("aria-disabled","false").removeClass("ui-button-disabled ui-state-disabled");
					$("#btnGenerarExpediente").removeAttr("disabled").attr("aria-disabled","false").removeClass("ui-button-disabled ui-state-disabled");
					$("#btnEnviar").removeAttr("disabled").attr("aria-disabled","false").removeClass("ui-button-disabled ui-state-disabled");
					$("#btnDevolver").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");
					$("#btnRecepcionar").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");
					$("#xgridBandeja_header_hd-textEl").html("Bandeja de Valores Recepcionados")
					
					
					buscarValor();
				}
            }
		},{
            xtype: 'tbseparator'
        },{
			text : 'Remover Valores Seleccionado',
			iconCls: 'TEliminar',
			listeners: {
            	click: function(){	
					flagTMP=$('#hdnFlag').val();
					//alert(flagTMP);
					eliminarTMP(flagTMP);
					buscarValor();
				}
            }
		},{
            xtype: 'tbseparator'
        },{
			text : 'Seleccionar Todos Los Valores',
			iconCls: 'TSeleccionar',
			listeners: {
            	click: function(){
					flagTMP=$('#hdnFlag').val();
					//alert(flagTMP);
					agregartodosTMP(flagTMP);	
					buscarValor();
				}
            }
		}	
		
		]
	});

	
	function pendiente(val) {
		//alert(val)
        if (val > 0) {
            return '<span style="color:green;">' + val + '%</span>';
        } else if (val < 0) {
            return '<span style="color:red;">' + val + '%</span>';
        }
        return val;
    }
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBandeja',
    	store: store, 
        //width: '925px',
        //height: '350px',
        //columnLines: true,
		//frame: true,
		
		title: 'Bandeja de Valores Recibidos',
        viewConfig: {
        	loadMask: {msg: 'cargando...'},
			stripeRows: false,
            getRowClass: function(record) {
                var clsRow = '';
				/*
				if(record.get('cidpers') =='0000112942')
					clsRow = 'xrow-greenback';
				*/
                return clsRow;
				
				
				            },
    	},
		/*
        //enableColumnHide : false,
        //enableColumnMove : false,
        //enableDragDrop : true,
        //enableHdMenu : false,
    	selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode: 'MULTI',
			injectCheckbox: 0,
			checkOnly: false,
			enableKeyNav: false

        }),*/
		tbar: toolbar,
        columns: [
           

		{
            xtype:'actioncolumn',
			width: 22,
			hidden: false,
			tdCls: 'custom-column',
					items: [{
							icon:'img/drop-not.png',
							tooltip:'<b>Seleccionar Valor</b> Agrega un nuevo valor al grupo de Valores Seleccionados.',
							handler: function(grid, rowIndex, colIndex) {
									var rec = grid.getStore().getAt(rowIndex);
									
									parametros=rec.data;
									parametros['check']=1;	
									parametros['flag']=$("#hdnFlag").val();	
									parametersTMP(parametros)
									/*grid.columns[3].setVisible(false);*/
									//$(".custom-column").hide();
									grid.getSelectionModel().select(rowIndex);
									grid.getStore().getAt(rowIndex).set('estadotmp', 1);
									grid.removeRowCls(grid.getNode(rowIndex),'line-through');
									
								},
							style:'margin-left:5px',
							getClass: function(value,metadata,record){
								var closed = record.get('estadotmp');
								  if (closed == 1 ) {
									return 'x-hide-display';
									} else if(closed == 2 ) {
								  	return 'x-grid-center-icon';
									} else {
									return 'x-hide-display';	
									}
								
							}
           		 	},{
							icon:'img/drop-yes.png',
							tooltip:'<b>Quitar Valor Seleccionado.</b>. Elimina el Valor de Todo el Grupo Seleccionado.',
							handler: function(grid, rowIndex, colIndex) {
									var rec = grid.getStore().getAt(rowIndex);
									parametros=rec.data;
									parametros['check']=2;
									parametros['flag']=$("#hdnFlag").val();			
									parametersTMP(parametros)
									/*grid.columns[3].setVisible(true);*/
									grid.getStore().getAt(rowIndex).set('estadotmp', 2);
									grid.addRowCls(grid.getNode(rowIndex),'line-through');
									
									
								},
							style:'margin-left:5px',
							getClass: function(value,metadata,record){
								var closed = record.get('estadotmp');
								  if (closed == 2 ) {
									return 'x-hide-display';
									} else if(closed == 1 ) {
								  	return 'x-grid-center-icon';
									} else {
									return 'x-hide-display';	
									}
									
							}
           		 	}]   
			
			
			
			   
           }, Ext.create('Ext.grid.RowNumberer'), 

			
        {
			text: 'Datos del Contribuyente',
		columns:[{
            text: 'C&oacute;digo',
            width: 80, 
            dataIndex: 'cidpers'
        },{
            text: 'Nombres',
			//flex:1,
			width: 200, 
            dataIndex: 'nombre'            
        }]},
		{
			text: 'Datos del Valor',
		columns:[{            
            width: 40,            
            dataIndex: 'idvalor',
			hidden: true
        },{            
            width: 40,            
            dataIndex: 'numval',
			hidden: true
        },{            
            width: 40,            
            dataIndex: 'anoval',
			hidden: true
        },{
            text: 'Valor',
            width: 40,            
            dataIndex: 'desvalor'
        },{
            text: 'N&uacute;mero',
            width: 85,            
            dataIndex: 'numerval'
        },{
            text: 'Fecha Emisi&oacute;n',
            width: 120,
            dataIndex: 'fechaemi',
            align: 'right'
        
        },{            
            width: 40,            
            dataIndex: 'numexpe',
			hidden: true
        },{            
            width: 40,            
            dataIndex: 'anoexpe',
			hidden: true
        },{
            text: 'Numero Cargo',
            width: 100,
            dataIndex: 'numerocargo',
            align: 'right'
        
        },{
            text: 'Fecha Notificación',
            width: 120,
            dataIndex: 'fechanotifica',
            align: 'right'
        
        },{
            text: 'Memorandum',
            width: 120,
            dataIndex: 'doc_memo',
            align: 'right'
        
        }
		
		
		]},{
			text: 'Monto',
		columns:[{
            text: 'Total',
            width: 70,
            dataIndex: 'montotal',
            align: 'right'
        },{
            text: 'Cancelado',
            width: 70,
            dataIndex: 'cancelado',
            align: 'right',
			renderer:Ext.util.Format.numberRenderer('000.00')
        },{
            text: 'Pendiente',
            width: 70,
            dataIndex: 'pendiente',
			renderer : pendiente,
            align: 'right',
			renderer:Ext.util.Format.numberRenderer('000.00')
        },{
            text: 'idruta',
            width: 70,
            dataIndex: 'idruta',
            align: 'right',
			hidden: true
        },{
            text: 'estado TMP',
            width: 70,
            dataIndex: 'estadotmp',
            align: 'right',
			hidden: true
        },{
            text: 'estado IMP',
            width: 70,
            dataIndex: 'estadoimp',
            align: 'right',
			hidden: true
        }]},{
            text: 'Paso',
            width: 70,
            dataIndex: 'paso',
            align: 'right',
			tdCls: 'custom-column3'
		},{
            xtype:'actioncolumn',
            width:40,
			tdCls: 'custom-column2',
            items: [{
                icon: urljs + 'img/view.png',
                style: 'cursor: pointer',
                tooltip:'Ver Documento',
                handler: function(grid, rowIndex, colIndex) {
					 	var rec 		=grid.getStore().getAt(rowIndex);
						var valor		=rec.get('idvalor'); 
						var numval		=rec.get('numval');
						var anoval		=rec.get('anoval');
				
					showPopup('administrarvalor/verdocumento?valor='+valor+'&numval='+numval+'&anoval='+anoval,'#popupverdocumento','700','330','Valor Tributario');		
                },
				getClass: function(value,metadata,record){
					return 'x-grid-center-action';
				}
            },{
                icon: urljs +'img/reload.png',
                style: 'cursor: pointer',//style:'margin-left:5px', cursor: pointer;
                tooltip:'Ver Ruta',
                handler: function(grid, rowIndex, colIndex) {
					 var rec = grid.getStore().getAt(rowIndex);
//                        showPopup('tdenominacion/formu?codigo='+rec.get('id'),
					var valor = rec.get('idvalor'); //item.data.idvalor; 
					var num = rec.get('numval'); //item.data.numval; 
					var ano = rec.get('anoval'); //item.data.anoval; 
					
                   showPopup('administrarvalor/ruta?valor='+valor+'&num='+num+'&ano='+ano,'#popupruta','735','435','Ver Rutas');		
                },
				getClass: function(value,metadata,record){
					return 'x-grid-center-action';
				}
            }]
			
			
        }
		
		
		],
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
			
				if(record.get('flag')=='1')
				{
					
					var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
		
					if(selectedRecordsArray.length>0){
						
						var store = grid.getStore();
						var sm = grid.getSelectionModel().getSelection();
						var rec = store.getAt(store.indexOf(sm[0]));		
						//var codigo = rec.get('codigo');
						
						var codigo = "";			
						var contribuyente = "";
						var monto = "";
						var valor = "";
						var num = "";
						var ano = "";		
						
						var numexig = "";
						var anoexig = "";
						// var flag=0;
						
						var lsExigibi = [];
						
						Ext.each(selectedRecordsArray, function (item) {
							
							// if(codigo!=item.data.codigo)
								// flag = 1;
							
							lsExigibi.push({
								valor: item.data.valor,
								num: item.data.num,
								ano: item.data.ano,
								codigo: item.data.codigo,
								contribuyente: item.data.contribuyente,
								monto: item.data.monto,
								numexig: item.data.numexig,
								anoexig: item.data.anoexig
							});
							
						});
						
						showPopupData(lsExigibi,'bandedocumen/mostrarexigibilidad','#popupexx','700','460','Exigibilidad');
						
					}	
					
				}
				else				
				{
					//infoMessage('Alerta','No se ha genrado exigibilidad');
				}
					
            },
			selectionchange : function(dv, record, item, index, e) {
			//	alert("Texts")
			}
		},
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridBandeja',
        	pageSize: 10,
			store: store,
            displayInfo: true,
            displayMsg: 'Mostrando Documentos Recibidos {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Documentos" 
			 })         
    });
    
    grid.render('gridBandeja');
   

	//Al presionar enter en cualquier objeto
	$("#txtCodigo, #txtContribuyente, #txtRango1, #txtRango2, #txtRango3, #txtExpediente").keypress(function(event) {
  		if ( event.which == 13 ) {
     		buscarValor();
   		}
	});
    
	$("#btnBusBandeja").button({ icons: { primary: "ui-icon-document" } });
	$("#btnRecepcion").button({ icons: { primary: "ui-icon-document" } });
	
	
		
});

function agregartodosTMP(flag){
	Ext.Ajax.request({
						url: urljs + "administrarvalor/agregartodosvalores?flagtmp="+flag,			
						method: "POST",
						params: $("#frmValorestrubutarios").serializeObject(),
						success: function(data){							
						//alert(data.responseText);
						
						//infoMessage('Alerta',data.responseText);
						//textos=$(".desactivarinput textarea").val();
						//$(".desactivarinput textarea").val(textos+data.responseText+"\n")					
						}
	});
}

function eliminarTMP(flag){
	Ext.Ajax.request({
						url: urljs + "administrarvalor/eliminarvalores?flagtmp="+flag,			
						method: "POST",
						success: function(data){							
						//alert(data.responseText);					
						}
	});
}

function parametersIMP(parametros){

/*	
				parametros=record.data;
				record = Ext.isNumber(record) ? this.store.getAt(record) : record;
				parametros['check']=2;//this.selected.indexOf(record) !== -1
*/
		
				Ext.Ajax.request({
						url: urljs + "administrarvalor/impregistrovalores",			
						method: "POST",
						params: parametros,
						success: function(data){							
					//	alert(data.responseText);
						
						//infoMessage('Alerta',data.responseText);
						//textos=$(".desactivarinput textarea").val();
						//$(".desactivarinput textarea").val(textos+data.responseText+"\n")					
						}
				});
	}




function parametersTMP(parametros){

/*	
				parametros=record.data;
				record = Ext.isNumber(record) ? this.store.getAt(record) : record;
				parametros['check']=2;//this.selected.indexOf(record) !== -1
*/
		
				Ext.Ajax.request({
						url: urljs + "administrarvalor/tmpregistrovalores",			
						method: "POST",
						params: parametros,
						success: function(data){							
						/*alert(data.responseText);*/
						
						//infoMessage('Alerta',data.responseText);
						//textos=$(".desactivarinput textarea").val();
						//$(".desactivarinput textarea").val(textos+data.responseText+"\n")					
						}
				});
	}



$('#cmbBusqueda').change(function(){
    var busqueda=($(this).val());
	/*alert(busqueda);*/
	
    $('#txtBusqueda').val(busqueda);
	$("#tabelemtentos div").hide()
	$("#tabopciones0"+busqueda).show();	
});



function buscarValor(){
	var grid = Ext.getCmp('xgridBandeja');
	var store = grid.getStore();
	store.removeAll();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	/*
	var txtCodigo = $('#txtCodigo').val();
	var txtContribuyente = $('#txtContribuyente').val();
	var txtRango1 = $('#txtRango1').val();
	var txtRango2 = $('#txtRango2').val();
	var txtRango3 = $('#txtRango3').val();
	var txtExpediente = $('#txtExpediente').val();
	var txtExpediente = $('#txtExpediente').val();
	var txt_recepcion = $('#hdnFlag').val();
	*/

	proxy.extraParams = $("#frmValorestrubutarios").serializeObject();
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
	
}


function btnGenerarExpediente(){
	
	//alert($(this).val());
	//showPopupESC('administrarvalor/expediente','#popupexpediente','655','465','Generar Expedientes');	
	showPopup('administrarvalor/expediente','#popupexpediente','655','465','Generar Expedientes');	
	//$("#popup_popupexpediente").children().children().children().children().children().next().hide();
}

function AgregarValor(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	
		if(selectedRecordsArray.length>0){
			
				
//				alert(this.selected.indexOf(record) !== -1);
				
//				this.selected.indexOf(record)
	
//				record = Ext.isNumber(record) ? this.store.getAt(record) : record;

		
			Ext.each(selectedRecordsArray, function (item) {
				parametros=item.data;
				parametros['check']=1					
					Ext.Ajax.request({
							url: urljs + "administrarvalor/tmpregistrovalores",			
							method: "POST",
							params: parametros,
							success: function(data){							
							alert(data.responseText);
							
							//infoMessage('Alerta',data.responseText);
							//textos=$(".desactivarinput textarea").val();
							//$(".desactivarinput textarea").val(textos+data.responseText+"\n")					
							}
					});	
					
			});		
	
		}	
}

function confirmacionrecepcionar(count){
	
	if(count>0){
		var showResult = function(btn){
			
			if(btn=='yes'){
						Ext.Ajax.request({
							url: urljs + "administrarvalor/recepcionar",			
							method: "POST",
							success: function(data){														
								//textos=$(".desactivarinput textarea").val();
								//$(".desactivarinput textarea").val(textos+data.responseText+"\n")			
								
							}					
						});			
				buscarValor();	
			}
		};		
		if(count>1){
			docum="los "+count+" documentos";
		}else{
			docum="el documento";
		}
		
	confirmMessage('Confirmar','¿Seguro de Recepcionar '+docum+'?',showResult);
	}
	else{
		 infoMessage('Alerta','Debio Seleccionar un Documento...');
	}
}



function Recepcionarvalor(){

//alert("A");
	var count=0;
	Ext.Ajax.request({
		url: urljs + "administrarvalor/verificar",			
		method: "POST",
		success: function(cantidad){							
						//	alert("ddd"+cantidad.responseText);
			confirmacionrecepcionar(cantidad.responseText);				
			//count=cantidad.responseText;
							//infoMessage('Alerta',data.responseText);
							//textos=$(".desactivarinput textarea").val();
							//$(".desactivarinput textarea").val(textos+data.responseText+"\n")					
		}
	});	
		//	alert("AAA"+count);
					

}

function confirmaciondevolver(count){
	
	if(count>0){
		var showResult = function(btn){
			
			if(btn=='yes'){
						Ext.Ajax.request({
							url: urljs + "administrarvalor/devolver",			
							method: "POST",
							success: function(data){														
								//textos=$(".desactivarinput textarea").val();
								//$(".desactivarinput textarea").val(textos+data.responseText+"\n")			
								
							}					
						});			
				buscarValor();	
			}
		};		
		if(count>1){
			docum="los "+count+" documentos";
		}else{
			docum="el documento";
		}
		
	confirmMessage('Confirmar','¿Seguro de Devolver '+docum+'?',showResult);
	}
	else{
		 infoMessage('Alerta','Debio Seleccionar un Documento...');
	}
}


function Devolvervalor(){
//alert("A");

showPopup('administrarvalor/devolvervalor','#popupdevolvervalor','670','365','Devolver Valores');

//return false;

//ORIGINAL FUNCTION
/*
	var count=0;
	Ext.Ajax.request({
		url: urljs + "administrarvalor/verificar",			
		method: "POST",
		success: function(cantidad){							
						//--	alert("ddd"+cantidad.responseText);
			confirmaciondevolver(cantidad.responseText);				
			//--count=cantidad.responseText;
							//--infoMessage('Alerta',data.responseText);
							//--textos=$(".desactivarinput textarea").val();
							//--$(".desactivarinput textarea").val(textos+data.responseText+"\n")					
		}
	});	
	*/
		//	alert("AAA"+count);
					

}	

function enviarvalor(){
	//var grid = Ext.getCmp('xgridBandeja');
	//var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	//if(selectedRecordsArray.length>0){
		
		var valor = "";
		var num = "";
		var ano = "";
		/*
		Ext.each(selectedRecordsArray, function (item) {		  	
			valor = item.data.idvalor; 
			num = item.data.numval; 
			ano = item.data.anoval; 
		});
		*/
	showPopup('administrarvalor/enviarvalor?valor='+valor+'&num='+num+'&ano='+ano,'#popupenvios','670','365','Enviar Valores');
	// }else{
	//	 infoMessage('Alerta','Seleccione al menos un registro!!');
	// }
}

function ruta(){
	alert("");
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length==1){
			
		var valor = "";
		var num = "";
		var ano = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			valor = item.data.idvalor; 
			num = item.data.numval; 
			ano = item.data.anoval; 
		});
		showPopup('administrarvalor/ruta?valor='+valor+'&num='+num+'&ano='+ano,'#popupruta','735','400','Detalle de Rutas');		
		
	}
	else{
		
		if(selectedRecordsArray.length==0){
		infoMessage('Alerta','Seleccione al menos un registro!!');
		}else{
		infoMessage('Alerta','Seleccione solamente un registro!!');	
		}
		
		//infoMessage('Alerta','Seleccione solamente un registro!!');
		
	}
}



function verdocumento(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length==1){
			
		var valor = "";
		var num = "";
		var ano = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			valor = item.data.idvalor; 
			num = item.data.numval; 
			ano = item.data.anoval; 
		});
		showPopup('administrarvalor/verdocumento?valor='+valor+'&num='+num+'&ano='+ano,'#popupverdocumento','1000','400','Valor Tributario');		
		
	}
	else{
		
		if(selectedRecordsArray.length==0){
		infoMessage('Alerta','Seleccione al menos un registro!!');
		}else{
		infoMessage('Alerta','Seleccione solamente un registro!!');	
		}
		
		//infoMessage('Alerta','Seleccione solamente un registro!!');
		
	}
}


	$('#txtCodigo').blur(function(){
	if($('#txtCodigo').val().length > 0){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtCodigo').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtCodigo').val(valor2);  //obj.value = valor;   
	}
	});
	
	$('#txtRangoCargo1').blur(function(){
	if($('#txtRangoCargo1').val().length > 0){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtRangoCargo1').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtRangoCargo1').val(valor2);  //obj.value = valor;   
	}
	});
	
	$('#txtRangoCargo2').blur(function(){
	if($('#txtRangoCargo2').val().length > 0){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtRangoCargo2').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtRangoCargo2').val(valor2);  //obj.value = valor;   
	}
	});







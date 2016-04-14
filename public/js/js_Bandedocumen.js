Ext.onReady(function(){
    Ext.QuickTips.init();
    
    //enableButton('#btnTodos'); //si quiero que me aprezca uno seleccioando
   //disableButton('#btnRecep');
    
    Ext.define('Bandeja', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
            {name: 'contribuyente'},
            {name: 'documento'},
            {name: 'monto'},
            {name: 'fecha'},
            {name: 'valor'},
            {name: 'num'},
            {name: 'ano'},
            {name: 'numero'},
            {name: 'anio'},
			{name: 'orden'},
			{name: 'id'},
			{name: 'flag'},
			{name: 'flag1'},
			{name: 'numexig'},
			{name: 'anoexig'},
			{name: 'lote'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Bandeja',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'bandedocumen/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBandeja',
    	store: store, 
        // width: '862px',
		//height: '510px',
        //columnLines: true,
        //height: '100px',
        title: '',
        viewConfig: {
        	loadMask: {msg: 'Listando Documentos...'}
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
    	
    	selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode : 'MULTI'   
        		//mode : 'MULTI'                //seleccionar una sola fila mode : 'SINGLE'  -------------- varios mode:'MULTI'
        }),

    	
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),          
        {
            text: 'C&oacute;digo',
            width: 70,            
            dataIndex: 'codigo'
        },{
            text: 'Contribuyente',
            //width: 210, 
			flex:1,
            dataIndex: 'contribuyente'            
        },{
            text: 'Documento',
            width: 100,            
			//flex:1,
            dataIndex: 'documento'
        },{
            text: 'Monto Total',
            width: 90,
            dataIndex: 'monto',
            align: 'right'
        },{
            text: 'Fecha Emisi&oacute;n',
            width: 100,
            dataIndex: 'fecha',
            align: 'right'
         //   hidden:true
        },{
            text: 'Valor',
            width: 60, 
            dataIndex: 'valor',
            hidden:true
        },{
            text: 'Num',
            width: 60, 
            dataIndex: 'num',
            hidden:true
        },{
            text: 'Ano',
            width: 60, 
            dataIndex: 'ano',
            hidden:true
        },{
            text: 'Numero',
            width: 60, 
            dataIndex: 'numero',
            hidden:true
        },{
            text: 'Anio',
            width: 60, 
            dataIndex: 'anio',
            hidden:true
        },{
            text: 'Orden',
            width: 60, 
            dataIndex: 'orden',
            hidden:true
        },{
            text: 'Id',
            width: 60, 
            dataIndex: 'id',
            hidden:true
        },{
            text: 'Flag',
            width: 30, 
            dataIndex: 'flag',
            hidden:true
        },{
            text: 'Flag1',
            width: 30, 
            dataIndex: 'flag1',
            hidden:true
        },{
            text: 'Numexig',
            width: 60, 
            dataIndex: 'numexig',
            hidden:true
        },{
            text: 'Anoexig',
            width: 60, 
            dataIndex: 'anoexig',
            hidden:true
        },{
            text: 'Lote',
            width: 60, 
            dataIndex: 'lote',
            hidden:true
        },{
            xtype:'actioncolumn',
            width:50,
            items: [/*{
                icon: urljs + 'img/flagblue.png',
                style: 'cursor: pointer',
                tooltip: '',                
				getClass: function(v, meta, rec) {
				
					if(rec.get('flag')=='0')
						return 'xrow-hidden-button';
					//else
													
				}
				
				
            },{
                icon: urljs + 'img/flaggreen.png',
                style: 'cursor: pointer',
                tooltip: '',                
				getClass: function(v, meta, rec) {
				
					if(rec.get('flag')=='1')
						return 'xrow-hidden-button';
													
				}
            },*/{
                icon: urljs + 'img/ball_blue.png',
                style: 'cursor: pointer',
                tooltip: '',                
				getClass: function(v, meta, rec) {
				
					if(rec.get('flag1')=='0')
						return 'xrow-hidden-button';
													
				}
            },{
                icon: urljs + 'img/ball_green.png',
                style: 'cursor: pointer',
                tooltip: '',                
				getClass: function(v, meta, rec) {
				
					if(rec.get('flag1')=='1')
						return 'xrow-hidden-button';
													
				}
            }]
        }],
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
    
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarBandeja();
        }
    }); 
    
    $("input[type='button']").button();
});

$('#rdCriterio1').click(function(){
	
	if($('#rdCriterio1').attr('checked'))
	{
		$('#txtCriterio').val('');
	}
});

$('#rdCriterio2').click(function(){
	
	if($('#rdCriterio2').attr('checked'))
	{
		$('#txtCriterio').val('');
	}
});

$('#rdCriterio3').click(function(){
	
	if($('#rdCriterio3').attr('checked'))
	{
		$('#txtCriterio').val('');
	}
});

function buscarBandeja(){
	var grid = Ext.getCmp('xgridBandeja');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	var txtEstado = $('#txtEstado').val();
	
	//Ext.MessageBox.alert('Editar',rdCriterio+'/'+txtCriterio);
	
	proxy.extraParams = {page: '1', start: '0', limit: '10', rdcriterio: rdCriterio, criterio: txtCriterio, estado:txtEstado};
	store.loadPage(1); 
}

function buscarBandejaEst(est){
	var grid = Ext.getCmp('xgridBandeja');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	
	$('#txtEstado').val(est);
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	
	//Ext.MessageBox.alert('Editar',rdCriterio+'/'+txtCriterio);
	
	proxy.extraParams = {page: '1', start: '0', limit: '10', rdcriterio: rdCriterio, criterio: txtCriterio, estado:est};
	store.loadPage(1); 
}

$('#txtCriterio').blur(function(){
    var numCeros = '0000000'; // pon el nº de ceros que necesites
    var valor =  $('#txtCriterio').val().trim();
    var radio = $('#rdCriterio1').val();
   // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if($('#rdCriterio1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
    { 
    	//$('#txtCriterio').val(valor);
    	  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
  	    $('#txtCriterio').val(valor2);  //obj.value = valor;
    }
    else{
    	$('#txtCriterio').val(valor);
    }
    
});


function enviardocumento(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
			
		var valor = "";
		var num = "";
		var ano = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			valor = item.data.valor; 
			num = item.data.num; 
			ano = item.data.ano; 
		});
		showPopup('bandedocumen/formu?valor='+valor+'&num='+num+'&ano='+ano,'#popupenvios','750','460','Envios');
		//alert(valor);
	}
}

function enviarruta(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
			
		var valor = "";
		var num = "";
		var ano = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			valor = item.data.valor; 
			num = item.data.num; 
			ano = item.data.ano; 
		});
		showPopup('bandedocumen/ruta?valor='+valor+'&num='+num+'&ano='+ano,'#popupruta','1190','400','Detalle de Rutas');
		//alert(valor);
	}
}

function verdocumento(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
			
		var valor = "";
		var num = "";
		var ano = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			valor = item.data.valor; 
			num = item.data.num; 
			ano = item.data.ano; 
		});
		showPopup('bandedocumen/verdocumento?valor='+valor+'&num='+num+'&ano='+ano,'#popupdocumento','700','480','Ver Documento');
		//alert(valor);
	}
	else{
		infoMessage('Alerta','Seleccione al menos un registro!!');
	}
}


function expediente(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();

	
	 if(selectedRecordsArray.length>0){
		
		var store = grid.getStore();
		var sm = grid.getSelectionModel().getSelection();
		var rec = store.getAt(store.indexOf(sm[0]));		
		var codigo = rec.get('codigo');
		
		var valor = "";
		var num = "";
		var ano = "";
		var numero = "";
		var anio = "";
		var documento = "";
		var monto = "";
		var contribuyente = "";
		var flag=0;
		
		var lsExpe = [];
		
		Ext.each(selectedRecordsArray, function (item) {
			
			if(codigo!=item.data.codigo)
				flag = 1;
			
			lsExpe.push({
				codigo: item.data.codigo, 
				valor: item.data.valor, 
				num: item.data.num,
				ano: item.data.ano, 
				contribuyente: item.data.contribuyente,
				numero: item.data.numero, 
				anio: item.data.anio,
				documento: item.data.documento,
				monto: item.data.monto
			});
			
		});
		
		if(flag==0)
			showPopupData(lsExpe,'bandedocumen/expediente','#popupexpediente','700','460','Ver Expediente');
			//showPopupData(lsExpe,'bandedocumen/expediente?num='+num+'&ano='+ano,'#popupexpediente','700','460','Ver Expediente');
		else
			infoMessage('Alerta','Debe seleccionar el mismo contribuyente!!!');
	}
}


function recepcion(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
			
		var valor = "";
		var num = "";
		var ano = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			valor = item.data.valor; 
			num = item.data.num; 
			ano = item.data.ano; 
		});
		showPopup('bandedocumen/recepcion?valor='+valor+'&num='+num+'&ano='+ano,'#popuprecepcion','700','460','Notificaci&oacute;n');
		//alert(valor);
	}
}




function recepbandeja(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	
		var id = "";
		/*var valor = "";
		var num = "";
		var ano = "";
		var orden = "";*/
		
		if(selectedRecordsArray.length>0){
		
			
		 var showResult = function(btn){
			if(btn=='yes'){
				
				var idList = [];
				Ext.each(selectedRecordsArray, function (item) {
					idList.push(
						{
							id: item.data.id
							/*valor: item.data.valor,
							num: item.data.num,
							ano: item.data.ano,
							orden: item.data.orden*/
						});
				});
		
				Ext.Ajax.request({
		            url: urljs + "bandedocumen/recepbandeja",
		            method: "POST",
		            params: {json: JSON.stringify(idList)},
		            success: function(response){
						
						//data = Ext.JSON.decode(response.responseText);
							  	
					
		            	grid.getStore().loadPage(grid.getStore().currentPage);						
						//imprimeOpPdf();
												
						},
		            failure: function(response, opts){
		            	infoMessage('Alerta','Error');
						}
					});
				}
			};
 		
 		confirmMessage('Alerta','Seguro de Recepcionar el documento?',showResult);
		
		}
		
}



function exigibilidad(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	var action=0;
	
	if($("#chIndividual").is(':checked')){
		action=1;
	}
	if($("#chAgrupado").is(':checked')){
		action=2;
	}
	
		if(selectedRecordsArray.length>0){
		
			var store = grid.getStore();
			var sm = grid.getSelectionModel().getSelection();
			var rec = store.getAt(store.indexOf(sm[0]));
			var flag = rec.get('flag');
			
			
			var codigo = "";			
			var contribuyente = "";
			var monto = "";
			var valor = "";
			var num = "";
			var ano = "";		
			
			var numexig = "";
			var anoexig = "";
			
			
			var lsExigi = [];
			Ext.each(selectedRecordsArray, function (item) {
						lsExigi.push(
							{							
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
			
			//alert(flag);
			
			if(flag=='0')
			{
				// var lsExigi = [];
				
				var showResult = function(btn){
					if(btn=='yes'){
						// alert('BOTON si');
						
					// Ext.each(selectedRecordsArray, function (item) {
						// lsExigi.push(
							// {							
								// valor: item.data.valor,
								// num: item.data.num,
								// ano: item.data.ano,
								// codigo: item.data.codigo,
								// contribuyente: item.data.contribuyente,
								// monto: item.data.monto
							// });
					// });
				
					Ext.Ajax.request({
						url: urljs + "bandedocumen/exigibilidad",
						method: "POST",
						params: {json: JSON.stringify(lsExigi),action:action},
						success: function(response){
						
							infoMessage('Exigibilidad',response.responseText);					
							grid.getStore().loadPage(grid.getStore().currentPage);					
							
						},
						failure: function(response, opts){
								infoMessage('Alerta','Error');
								}
							});
						}
						else{
						// alert('BOTON NO');
						}
				};
				
				confirmMessage('Alerta','Seguro de  generar exigibilidad',showResult);
				
			}
		
			if(flag=='1')
			{
				infoMessage('Alerta','Ya se han generado el numero de exigibilidad!!!');
				//showPopupData(lsExigi,'bandedocumen/mostrarexigibilidad','#popupexx','700','460','Exigibilidad');
											
			}
				
			
		
		}
		
}


function exigibilidadgrupal(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
				
		if(selectedRecordsArray.length>0){
		
		var store = grid.getStore();
		var sm = grid.getSelectionModel().getSelection();
		var rec = store.getAt(store.indexOf(sm[0]));
		var codigo = rec.get('codigo');

		var codigo = "";			
		var contribuyente = "";
		var monto = "";
		var valor = "";
		var num = "";
		var ano = "";		
		// var flag=0;
		
		var lsExigigru = [];
		
		var showResult = function(btn){
			if(btn=='yes'){
				
	
					Ext.each(selectedRecordsArray, function (item) {
										
						// if(codigo!=item.data.codigo)
						// flag = 1;
					
					
						lsExigigru.push(
							{							
								valor: item.data.valor,
								num: item.data.num,
								ano: item.data.ano,
								codigo: item.data.codigo,
								contribuyente: item.data.contribuyente,
								monto: item.data.monto
							});
							
					});
		
					// if(flag==0){
						Ext.Ajax.request({
							url: urljs + "bandedocumen/exigibilidadgrupal",
							method: "POST",
							params: {json: JSON.stringify(lsExigigru)},
							success: function(response){
							
								grid.getStore().loadPage(grid.getStore().currentPage);						
														
							},
							failure: function(response, opts){
									infoMessage('Alerta','Error');
							}
						});
					// }
					// else{
						// infoMessage('Alerta','Debe seleccionar el mismo contribuyente');
					// }
			}
		};
 		
 		confirmMessage('Alerta','Seguro de generar exigibilidad',showResult);
		
		}
		
}


function mostrar(){

	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
		//showPopup('bandedocumen/mostrarexigibilidad','#popupexx','700','460','Derr');
		//alert('105');
	if(selectedRecordsArray.length>0){
		
		var store = grid.getStore();
		var sm = grid.getSelectionModel().getSelection();
		var rec = store.getAt(store.indexOf(sm[0]));		
		var flag = rec.get('flag');
		
		var valor = "";
		var num = "";
		var ano = "";
		var numero = "";
		var anio = "";
		var documento = "";
		var monto = "";
		var contribuyente = "";
		var numexig = "";
		var anoexig = "";
		//var flag=0;
		
		
		if(flag=='1')
		{
		var lsArray = [];
		
		Ext.each(selectedRecordsArray, function (item) {
			
			 
				// flag = 1;
			
			lsArray.push({
				codigo: item.data.codigo, 
				valor: item.data.valor, 
				num: item.data.num,
				ano: item.data.ano, 
				contribuyente: item.data.contribuyente,
				numero: item.data.numero, 
				anio: item.data.anio,
				documento: item.data.documento,
				monto: item.data.monto,
				numexig: item.data.numexig,
				anoexig: item.data.anoexig
			});
			
		});
		
		// if(flag==0)
		showPopupData(lsArray,'bandedocumen/mostrarexigibilidad','#popupexx','700','460','Derr');
			//showPopupData(lsArray,'bandedocumen/expediente','#popupexpediente','700','460','Ver Expediente');
			//showPopupData(lsExpe,'bandedocumen/expediente?num='+num+'&ano='+ano,'#popupexpediente','700','460','Ver Expediente');
			
		// else
			// infoMessage('Alerta','Debe seleccionar el mismo contribuyente!!!');
		}	
		else
		alert('215');
	}	
}



$('#btnRecibidos').click(function(){
	
	//$('#txtCriterio').val('');
	disableButton('#btnRecep');
});


$('#btnEspera').click(function(){
	
	//$('#txtCriterio').val('');
	enableButton('#btnRecep');
});

// function GenerarExigibilidad(){

	// if($("#chIndividual").is(':checked')){exigibilidad();}
	// if($("#chAgrupado").is(':checked')){exigibilidad();}
	// //if(check_pu==true){generarpupdf();}
// }

function selectexigibilidad(obj){
	$('.chcuponera').attr('checked',false);
	$(obj).attr('checked',true);
}


function der(){
	alert('En funcionamiento');
}
/*
$('#btnRecibidos').click(function() {
	
	alert($(this).attr("value"));
	//txtventani=$('#txtventani').val();
	
	$.ajax({     
		type: "POST",     
		url: "/turnventanilla/salir", 
		data: 'txtventani='+txtventani, 
		success: function(data) { 
			closePopup('#poptipoentrada');
		},     
		error: function() {
		} 
	});
	
});
*/

















Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
	$('#btnNuevoRepresentante').click(function (e) {
		showPopup('mantrepresentante/formu?codigo='+$("#divCodigo").html(), '#poprepreformu','800','630','Ingreso de Representante');
	});
//	
	
    $('#gridPredios').html('');
    			
    		    Ext.define('Repre', {
    		        extend: 'Ext.data.Model',
    		        fields: [
    		            {name: 'tipo_relacion'	, type: 'string'},
    		            {name: 'cod'	, type: 'string'},
    		            {name: 'nombres'		, type: 'string'},
    		            {name: 'tipo_documento'	, type: 'string'},
    		             {name: 'codigo'	, type: 'string'},
						 {name: 'direccion'	, type: 'string'}
    		        ]
    		    });
    		    
    		    storepredio = Ext.create('Ext.data.Store', {
    		        model: 'Repre',
    		        proxy: {
    		            type: 'ajax',
    		            url : urljs + 'mantrepresentante/consulta?codigo='+$('#divCodigo').html(),
    		            reader: {
    		                type: 'json',
    		                root: 'root'
    		            }
    		        }
    		    });
            	storepredio.load();

            	var grid = Ext.create('Ext.grid.Panel', {
					id: 'xgridRepre',
                    store: storepredio,
                    columnLines: true,
                    width: '1175px',
                    height: '300px',
                    //title: 'Predios',
                    //stateful: true,
                    //stateId: 'stateGrid',
                    selModel: Ext.create('Ext.selection.CheckboxModel',{
				   
					}),
                    //Ext.create('Ext.grid.RowNumberer'),
                    columns:[{
                    	text: 'Datos Representante',
                    columns:[{
                    	text: 'cod',
                        hidden: true,
                        dataIndex: 'cod'
                    },{
                    	text: 'codigo',
                        hidden: true,
                        dataIndex: 'codigo'
                    },{
						text: 'Tipo Repre',
					    width: 90,
					    align:'left',
					    dataIndex: 'tipo_relacion'
					},{
                    	text: 'Nombre',
                        width: 240,
                        align:'left',
                        dataIndex: 'nombres'
                    },{
                        text: 'Tipo Doc.',
                        width: 120,
                        align:'left',
                        dataIndex: 'tipo_documento'
                    },{
                        text: 'Direccion',
                        width: 659,
                        flex: 1,
                        dataIndex: 'direccion'            
                    }]},{
                        xtype:'actioncolumn',
                        width:40,
                        items: [{
                            icon: urljs + 'img/view.png',
                            tooltip: 'Editar',
                            handler: function(grid, rowIndex, colIndex) {
                           var rec = grid.getStore().getAt(rowIndex);
                                showPopup('mantrepresentante/formu?id='+rec.get('cod'),'#poprepreformu','800','630','Editar Representante');
                                //Ext.MessageBox.alert('Ver predios',rec.get('cod_pred'));
                                //showPopup('rentas/predios?accion=2&cod_pred='+rec.get('cod_pred'),'#popeditapu','860','600','Mantenimiento de Predios','frmpredios');
                            }
                        },{
                            icon: urljs + 'img/delete.png',
                            tooltip: 'Eliminar',
                            handler: function(grid, rowIndex, colIndex) {
                            var rec = grid.getStore().getAt(rowIndex);
						  	
                                //showPopup('mantrepresentante/formu?id='+rec.get('cod'),'#poprepreformu','1100','630','Mantenimiento de Predios');
                                //Ext.MessageBox.alert('Ver predios',rec.get('cod_pred'));
								
                                //showPopup('rentas/predios?accion=2&cod_pred='+rec.get('cod_pred'),'#popeditapu','1000','580','Mantenimiento de Predios','frmpredios');
								
								var showResult = function(btn){  // ELIMINAR POR MEDIO DEL BOTON
                    	if(btn=='yes')
                    		$.ajax({
                    			type: "GET", 
                    			url: 'mantrepresentante/eliminar',
                    			data: 'cod='+rec.get('cod')+'&codigo='+rec.get('codigo'),
								//data: 'cod='+rec.get('cod'),
                    			success: function(data){
                    				infoMessage('Eliminado ',data);                    				
                    				Ext.getCmp('xgridRepre').getStore().load();
                    			}
                    		});
                            };
							confirmMessage('Eliminar','Seguro de Eliminar ?',showResult);
							}
                        }]
                    }],
                    renderTo:'gridRepre'
                /*    viewConfig:{
	            	    listeners: {
		                    itemdblclick: function(grid, rowIndex, colIndex) {
            		 			var rec = grid.getStore().getAt(rowIndex);
            					alert(rec.get('cod_pred'));
		                    }}}*/
	                    
                });
				
				
    
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarPoblad();
        }
    }); 
});

function buscarPoblad(){
	var grid = Ext.getCmp('xgridRepre');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}


$('#txtCriterio').keypress(function (e) {
	
	if($('#rdCriterio1').attr('checked') )
		return validaTeclas(event,'number');
		
	if($('#rdCriterio2').attr('checked'))
		return validaTeclas(event,'text');
		
	if($('#rdCriterio3').attr('checked'))
		return validaTeclas(event,'text');
});

$('#rdCriterio1').click(function(){
	
	if($('#rdCriterio1').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});

$('#rdCriterio2').click(function(){
	
	if($('#rdCriterio2').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});

$('#rdCriterio3').click(function(){
	
	if($('#rdCriterio3').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});


$('#txtCriterio').blur(function(){
    var numCeros = '0000'; // pon el nº de ceros que necesites
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
////SELECC UNO Y ENVIA EL VALOR SELECCIONADO(atrapa el codigo)
function verdetalle(){
	var grid = Ext.getCmp('xgridRepre');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
			
		var codigo = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			codigo = item.data.codigo; 
		});
		///abre la ventana con el codigo seleccionado
		showPopup('mantvias/index?codigo='+codigo,'#popDetVias','550','450','Detalle de V&iacute;as');
	}
	else
		infoMessage('Centro Poblado','Seleccione un centro poblado!');	
}
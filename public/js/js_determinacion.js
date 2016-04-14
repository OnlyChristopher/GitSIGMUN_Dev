Ext.onReady(function(){
	Ext.QuickTips.init();
    
	
	$("input[type='button']").button();
	$('#gridDeterminacion').html('');
	
	var codigo=$('#divECodigo').html();
	var anno=$('#divAnno').html();
	
  $('#gridDeterminacion').html('');
    			
    		    Ext.define('Determinacion', {
    		        extend: 'Ext.data.Model',
    		        fields: [
    		            {name: 'tipo'	, type: 'string'},
    		            {name: 'cod_pred'	, type: 'string'},
    		            {name: 'anexo'		, type: 'string'},
    		            {name: 'direccion'	, type: 'string'},
    		            {name: 'area_terreno'	, type: 'string'},
    		            {name: 'porcen_propiedad'	, type: 'string'},
    		            {name: 'total_autoavaluo'	, type: 'string'},
						{name: 'predio_vendido', type: 'string'}
    		        ]
    		    });
    		    
    		    storepredio = Ext.create('Ext.data.Store', {
    		        model: 'Determinacion',
    		        proxy: {
    		            type: 'ajax',
    		            url : urljs + 'rentasdecjurada/predios?anno='+anno+'&codigo='+codigo,
    		            reader: {
    		                type: 'json',
    		                root: 'root'
    		            }
    		        }
    		    });
            	storepredio.load();

            	var grid = Ext.create('Ext.grid.Panel', {
					id: 'xgridDeterminacion',
                    store: storepredio,
                    columnLines: true,
                    flex: '1',
                    height: '200px',
                    //title: 'Predios',
                    //stateful: true,
                    //stateId: 'stateGrid',
					viewConfig: {
						loadMask: {msg: 'Listado de Predios...'},
						stripeRows: false,
						getRowClass: function(record, index){
							
							var clsRow = '';
							switch(record.get('predio_vendido').trim()){
								case '1': clsRow='xrow-cancelado'; break
							}
							return clsRow;
						}

					},
                  selModel: Ext.create('Ext.selection.CheckboxModel',{
						mode: 'MULTI',
						checkOnly: true
					}),
                    //Ext.create('Ext.grid.RowNumberer'),
                    columns:[{
                    	text: 'Predio',
                    columns:[{
						text: 'Tipo',
					    width: 35,
					    align:'left',
					   // renderer : change,
					    dataIndex: 'tipo'
					},{
                    	text: 'Codigo',
                        width: 79,
                        align:'left',
                        dataIndex: 'cod_pred'
                    },{
                        text: 'Anexo',
                        width: 70,
                        align:'left',
                        dataIndex: 'anexo'
                    },{
                        text: 'Ubicación',
                        width: 667,
                        dataIndex: 'direccion'            
                    }]},{
                    	text: 'Valores',
                    columns:[{
                        text: '%',
                        width: 50,
                        align:'right',
                        dataIndex: 'porcen_propiedad'
                    },{
                        text: 'Autoavalúo',
                        width: 80,
                        align:'right',
                        dataIndex: 'total_autoavaluo'
                    }]}],
                    renderTo:'gridDeterminacion'
                /*    viewConfig:{
	            	    listeners: {
		                    itemdblclick: function(grid, rowIndex, colIndex) {
            		 			var rec = grid.getStore().getAt(rowIndex);
            					alert(rec.get('cod_pred'));
		                    }}}*/
	                    
                });    		
});
function calcularip(){
	var showResult = function(btn){
			if(btn=='yes'){
					var codigo=$('#divECodigo').html();
					var anno=$('#divAnno').html();
					var tipodeterminacion=$('#cb_tipodeterminacion').val();
					if(codigo.length>0){
						Ext.Ajax.request({
							  url: urljs + "Rentasdecjurada/determinacionimpuesto",
							  method: "POST",
							  params: {codigo: codigo,anno: anno,tipodeterminacion:tipodeterminacion},
							  success: function(response){			
									infoMessage('Alerta',response.responseText,'');
							  }
						});		
					}
			}
		}
		confirmMessage('Calculo de IP','Seguro desea recalcular el IP?',showResult);
}
function calculararb(){
	var showResult = function(btn){
			if(btn=='yes'){
					var codigo=$('#divECodigo').html();
					var anno=$('#divAnno').html();
					var tipodeterminacion=$('#cb_tipodeterminacion').val();
					data = $("#frmDeterminacion").serializeObject();
					var dataPredio = new Array();	
					var dataGeneral= new Array();
					var grid = Ext.getCmp('xgridDeterminacion');
					var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
					
					if (selectedRecordsArray.length > 0){
						var i=0;
						
						var lsRecibo = [];
						
						Ext.each(selectedRecordsArray, function (item) {	
						
						lsRecibo.push({
							codigo: codigo,
							anno: anno,
							cod_pred: item.data.cod_pred,
							anexo: item.data.anexo.substring(0,4),
							sub_anexo: item.data.anexo.substring(5),
							tipodeterminacion: tipodeterminacion
							});
						});
						 			
						
						
						Ext.Ajax.request({
							  url: urljs + "Rentasdecjurada/determinacionarbitrio",
							  method: "POST",
							  params: {json: JSON.stringify(lsRecibo)},
							  success: function(response){			
									infoMessage('Alerta',response.responseText,'');
							  }
						});		
					}
					else {
						//infoMessage('Error','Debe Seleccionar como minimo un predio','');
					}
			}
		}
		confirmMessage('Calculo de IP','Seguro desea recalcular arbitrios a los predios seleccionados?',showResult);
}
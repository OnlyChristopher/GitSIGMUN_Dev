Ext.onReady(function(){
    Ext.QuickTips.init();
	
	$("input[type='button']").button();
	$("#btnCerrarxInstalacion").button({icons:{primary:"ui-icon-print"}});

   Ext.define('xInstalacionModel', {
        extend: 'Ext.data.Model',
        fields: [
				{name: 'idxxx', type: 'string'},
				{name: 'idpu', type: 'string'},
				{name: 'anio', type: 'string'},
				{name: 'mes_cons', type: 'string'},
				{name: 'anio_cons', type: 'string'},
				{name: 'anio_antig', type: 'string'},
				{name: 'id_clafica', type: 'string'},
				{name: 'id_materia', type: 'string'},
				{name: 'id_estados', type: 'string'},
				{name: 'val_estima', type: 'string'},
				{name: 'val_unitar', type: 'string'},
				{name: 'por_deprec', type: 'string'},
				{name: 'val_deprec', type: 'string'},
				{name: 'val_un_dep', type: 'string'},
				{name: 'cantidad', type: 'string'},
				{name: 'val_instal', type: 'string'},
				{name: 'instalacion', type: 'string'},
				{name: 'uni_med', type: 'string'},
        ]
    });

    var xInstalacionStore = Ext.create('Ext.data.Store', {
    	model: 'xInstalacionModel',
    	autoLoad: true,
        proxy: {
            type: 'ajax',
            url : 'fiscalizacionpredio/gridinstalacion',
			extraParams: {
				txtidpu  : $('#txtidpu').val()
			},
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });

    var xgridInstalacion = Ext.create('Ext.grid.Panel', {
    	id: 'xgridInstalacion',
        store: xInstalacionStore,
		height: '198px',
		//width: '100px',
        //title: 'Requerimiento',
		columnLines: true,
        viewConfig: {
        	//loadMask: {msg: 'Cargando...'},
			loadMask: false
		},
        enableColumnHide : false,
        enableColumnMove : false,
        enableDragDrop : false,
        enableHdMenu : false,
		//disableSelection: true,
        columns: [{
            text: 'idxxx',
            width: 70, 
			hidden: true,
            dataIndex: 'idxxx',
        },{
            text: 'anio',
            width: 70, 
			hidden: true,
            dataIndex: 'anio',
        },{
            text: 'Instalacion',
            width: 383,
            dataIndex: 'instalacion',
			fixed: true,
			align:'LEFTH',
			sortable: false,
			renderer: function(value, metaData, record, rowIdx, colIdx, store) {
				metaData.tdAttr = 'data-qtip="' + value + '"';
				return value;
			},
        },{
            text: 'Med.',
            width: 40,
            dataIndex: 'uni_med',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
        	text: 'C',
        	width: 18,
            dataIndex: 'id_clafica',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'M',
        	width: 18,
            dataIndex: 'id_materia',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'E',
            width: 18,
            dataIndex: 'id_estados',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'A&ntilde;o',
            width: 40,
            dataIndex: 'anio_cons',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'Valor Estimado',
            width: 70,
            dataIndex: 'val_estima',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('val_estima'),'0,000.00');
            }
        },{
            text: 'Cantidad',
            width: 78,
            dataIndex: 'cantidad',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('cantidad'),'0,000.00');
            }
        },{
            text: 'Valor Total',
            width: 88,
            dataIndex: 'val_instal',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('val_instal'),'0,000.00');
            }
        }]
    });
    xgridInstalacion.render('xgridInstalacion');
	
	var toolPisos = Ext.create('Ext.toolbar.Toolbar', {
		layout: 'vbox',
		width: '27px',
		height: '198px',
		items: [{
			xtype: 'button',
			text: '',
			icon: 'img/add.png',
			handler: function(){
				
				var msgpago='Se ingresara una nueva instalacion, Seguro de Continuar?';
				var showResult = function(btn){
					if(btn=='yes'){

							$.ajax({
								type: "POST",  
								url: urljs + "fiscalizacionpredio/crearinstalacion",
								data: $('#formModificarPredio').serializeObject(),
								//dataType : 'json',
								success: function(data) { 
									MostrarInstalacion();
								},     
								error: function(data) {
									infoMessage('Error',data);
								} 
							});
					}
				};
				confirmMessage('SIGMUN',msgpago,showResult);
				
			}
			},{
			xtype: 'button',
			text: '', 
			icon: 'img/edit.png',
			handler: function(){
					ModificarInstalacion();
				}
			},{
			xtype: 'button', 
			text: '', 
			icon: 'img/delete.png',
			handler: function(){
					EliminarInstalacion();
				}
			}],
		renderTo: 'btnsInstalacion'
	});
});

function MostrarInstalacion(){
	
	var grid = Ext.getCmp('xgridInstalacion');
	var store = grid.getStore();
	store.removeAll();
	var proxy = store.getProxy();
	var params = proxy.extraParams;
	delete params;
	
	proxy.extraParams = {txtidpu  : $('#txtidpu').val()};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){
			var total_sum=0;
        	store.each(function (rec) { 
				total_sum += Number(rec.get('val_instal'));				
			});
			$('#txttinstalacion').val(Ext.util.Format.number(Math.round(total_sum*100)/100,'0,000.00')).autoNumeric();
		}
	});	
}

function ModificarInstalacion(){
	var grid = Ext.getCmp('xgridInstalacion');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idxxx');
		var getanio  = selectedRecords.get('anio');
		showPopup('fiscalizacionpredio/modificarinstalacion?idpin='+getvalue+'&anio='+getanio,'#popmodificarinstalacion','550','320','Modificar Instalacion...');
	}else{
		infoMessage('Requerimiento','Seleccione el Predio  ');
	}
}

function EliminarInstalacion(){

	var grid = Ext.getCmp('xgridInstalacion');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idxxx');
		var getanio  = selectedRecords.get('anio');

		var msgpago='Se eliminara la instalacion, Seguro de Continuar?';
		var showResult = function(btn){
			if(btn=='yes'){
				$.ajax({
					type: "POST",  
					url: urljs + "fiscalizacionpredio/eliminarinstalacion",
					data: { getvalue: getvalue},
					//dataType : 'json',
					success: function(data){
						//console.log(data);
						MostrarInstalacion();
					},     
					error: function(data) {
						infoMessage('Error',data);
					}
				});
			}
		};
		confirmMessage('SIGMUN',msgpago,showResult);
	}else{
		infoMessage('Requerimiento','Seleccione la instalacion  ');
	}

}
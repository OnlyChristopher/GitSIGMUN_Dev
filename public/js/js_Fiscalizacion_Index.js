Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("#btnNuevoRequerimiento").button({icons:{primary:"ui-icon-print"}});
	$("#btnEditaRequerimiento").button({icons:{primary:"ui-icon-print"}});
	$("#btnBuscar").button({icons:{primary:"ui-icon-print"}});
	$("#btnVerActas").button({icons:{primary:"ui-icon-print"}});
	$("input[type='button']").button();
	
    Ext.define('RequerimientoModel', {
        extend: 'Ext.data.Model',
        fields: [
				{name: 'idPk', type: 'string'},
				{name: 'documento', type: 'string'},
				{name: 'anio', type: 'string'},
				{name: 'codigo', type: 'string'},
				{name: 'nombre', type: 'string'},
				{name: 'fecha', type: 'string'},
				{name: 'motivo', type: 'string'}				
        ]
    });

    var RequerimientoStore = Ext.create('Ext.data.Store', {
    	model: 'RequerimientoModel',
    	autoLoad: false,
        proxy: {
            type: 'ajax',
            url : 'fiscalizacion/buscarrequerimiento',
			extraParams: {
				cmbtipos  : $('#cmbTipos').val(),
				txtdatos  : $('#txtDatos').val()
			},
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var gridRequerimiento = Ext.create('Ext.grid.Panel', {
    	id: 'xgridRequerimiento',
        store: RequerimientoStore,
		height: '385px',
        title: 'Requerimiento',
		columnLines: true,
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
		},
        enableColumnHide : false,
        enableColumnMove : false,
        enableDragDrop : false,
        enableHdMenu : false,
        columns: [{
			xtype: 'rownumberer',width: 45
		},{
            text: 'idx',
            width: 70, 
			hidden: true,
            dataIndex: 'idPk',
			fixed: true,
			align:'LEFT',
			sortable: false
        },{
            text: 'Documento',
            width: 190,
            dataIndex: 'documento',
			fixed: true,
			align:'LEFT',
			sortable: false
        },{        
        	text: 'C&oacute;digo',
        	width: 55,
            dataIndex: 'codigo',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'Nombre o Raz&oacute;n Social',
            flex: 1,
            dataIndex: 'nombre',
			fixed: true,
			align:'LEFT',
			renderer: function(value, metaData, record, rowIdx, colIdx, store) {
				metaData.tdAttr = 'data-qtip="' + value + '"';
				return value;
			},
			sortable: false
        },{
            text: 'Fecha',
            width: 70,
            dataIndex: 'fecha',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'Motivo',
            width: 90,
            dataIndex: 'motivo',
			fixed: true,
			align:'LEFT',
			sortable: false
        },{
            xtype:'actioncolumn',
			sortable: false,
			fixed: true,
            width:30,
            items: [{
                icon: urljs +'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Empresa',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('transporte/mantenimientoempresas?idtransporte='+rec.get('idx'),'#popempresa','800','620','Editar Empresa');
                }
            }]
        }],
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
				editarequerimiento();
            }
		}
    });
    
    gridRequerimiento.render('divGridRequerimiento');

});

function buscarRequerimiento(){
	var grid = Ext.getCmp('xgridRequerimiento');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {
		cmbtipos: $('#cmbTipos').val(), txtdatos: $('#txtDatos').val()
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}

function muestrarequerimiento(){
	showPopup('fiscalizacion/muestrarequerimiento?txtaction=1','#popmuestrarequerimiento','550','328','Requerimiento');		
}

function editarequerimiento(){
	var grid = Ext.getCmp('xgridRequerimiento');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idPk');
		//alert(getvalue);
		showPopup('fiscalizacion/muestrarequerimiento?txtaction=2&txtidx='+getvalue,'#popmuestrarequerimiento','550','328','Requerimiento');
	}else{
		infoMessage('Requerimiento','Seleccione el requerimiento  ');
	}
			
}

function detalleRequerimiento(){
	var grid = Ext.getCmp('xgridRequerimiento');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idPk');
		//alert(getvalue);
		showPopup('fiscalizaciondetalle/index?txtidx='+getvalue,'#popdetallerequerimiento','960','600','Requerimiento');
	}else{
		infoMessage('Requerimiento','Seleccione el requerimiento  ');
	}
			
}
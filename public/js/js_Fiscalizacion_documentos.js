Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("#btnResolucion").button({icons:{primary:"ui-icon-print"}});
	$("#btnImprimir").button({icons:{primary:"ui-icon-print"}});
	$("#btnBuscarRes").button({icons:{primary:"ui-icon-print"}});
	$("#btnInspeccion").button({icons:{primary:"ui-icon-print"}});
    $("#btnMulta").button({icons:{primary:"ui-icon-document"}});
	
	$("input[type='button']").button();
	
    Ext.define('ResolucionesModel', {
        extend: 'Ext.data.Model',
        fields: [
				{name: 'idPk', type: 'string'},
				{name: 'valor', type: 'string'},
				{name: 'codigo', type: 'string'},
				{name: 'nombre', type: 'string'},
				{name: 'fecha', type: 'string'},
				{name: 'montotal', type: 'string'}				
        ]
    });

    var ResolucionesStore = Ext.create('Ext.data.Store', {
    	model: 'ResolucionesModel',
    	autoLoad: false,
        proxy: {
            type: 'ajax',
            url : 'fiscalizaciondocumentos/gridresoluciones',
			extraParams: {
				cmbtipos  : $('#cmbtipos').val(),
				txtdatos  : $('#txtdatos').val()
			},
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var gridResoluciones = Ext.create('Ext.grid.Panel', {
    	id: 'xgridResoluciones',
        store: ResolucionesStore,
		height: '385px',
        title: 'Resoluciones',
		columnLines: false,
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
            text: 'Resoluciones',
            width: 240,
            dataIndex: 'valor',
			fixed: true,
			align:'LEFT'
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
            text: 'Monto Total',
            width: 90,
            dataIndex: 'montotal',
			fixed: true,
			align:'RIGHT',
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('montotal'),'0,000.00');
            },
			sortable: false
        }],
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
				//editarequerimiento();
            }
		}
    });
    
    gridResoluciones.render('divGridResoluciones');

});

function buscarResoluciones(){
	var grid = Ext.getCmp('xgridResoluciones');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {
		cmbtipos: $('#cmbTipos').val(), 
		txtdatos: $('#txtDatos').val()
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}

function VerResolucion(){
	var grid = Ext.getCmp('xgridResoluciones');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idPk');
		showPopup('fiscalizaciondocumentos/verdetalle?idrs='+getvalue,'#popVerResolucion','700','620','Ver...');
	}else{
		infoMessage('Requerimiento','Seleccione el Predio  ');
	}
}

function ImprimirResolucion(){
	var grid = Ext.getCmp('xgridResoluciones');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idPk');
		showPopupReport('schema=&tipo=pdf&nombrereporte=rdresolucion&param=idres^'+getvalue,'poupresolucion',700,600,'Resolucion de Determinacion');
	}else{
		infoMessage('Requerimiento','Seleccione el Predio  ');
	}
}

function InspeccionResolucion(){
	var grid = Ext.getCmp('xgridResoluciones');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idPk');
		showPopupReport('schema=&tipo=pdf&nombrereporte=rdinspeccion&param=idres^'+getvalue,'poupresolucion',700,600,'Comparacion de Informacion');
	}else{
		infoMessage('Requerimiento','Seleccione el Predio  ');
	}
}

function MultaResolucion(){
	var grid = Ext.getCmp('xgridResoluciones');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idPk');
		showPopupReport('schema=&tipo=pdf&nombrereporte=mtresolucion&param=idres^'+getvalue,'poupresolucion',700,600,'Resolucion de Multa');
	}else{
		infoMessage('Requerimiento','Seleccione el Predio  ');
	}
}

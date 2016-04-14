Ext.onReady(function(){
    Ext.QuickTips.init();
	$("#btnvercerrar").button({icons:{primary:"ui-icon-print"}});
	$("input[type='button']").button();
	
    Ext.define('verResolucionesModel', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'idPk',type: 'string'},
			{name: 'anio',type: 'string'},
			{name: 'periodo',type: 'string'},
			{name: 'insoluto',type: 'float'},
			{name: 'reajuste',type: 'float'},
			{name: 'mora',type: 'float'},
			{name: 'emision',type: 'float'},
			{name: 'total',type: 'float'},
			{name: 'valor',type: 'string'},
			{name: 'estado',type: 'string'},
			{name: 'dfecven',type: 'string'},
        ]
    });

    var verResolucionesStore = Ext.create('Ext.data.Store', {
    	model: 'verResolucionesModel',
    	autoLoad: true,
        proxy: {
            type: 'ajax',
            url : 'fiscalizaciondocumentos/griddeuda',
			extraParams: {
				txtres : $('#txtres').val()
			},
            reader: {
               type: 'json',
               root: 'rows'
            }
        },
		groupField: 'valor'
    });
    
    var gridverResoluciones = Ext.create('Ext.grid.Panel', {
    	id: 'xgridverResoluciones',
        store: verResolucionesStore,
		height: '570px',
        //title: 'Resoluciones',
		columnLines: true,
		viewConfig: {
			loadMask: {msg: 'cargando...'},
            stripeRows: false, // quita las sombra de las filas de la grid (cebra)
			getRowClass: function(record, index){
				var clsRow = '';
				switch(record.get('estado').trim()){
					case '4': clsRow='xrow-valortrib'; break
					case '2': clsRow='xrow-coactivo'; break
					case '3': clsRow='xrow-fraccionamiento'; break
					case '1': clsRow='xrow-fiscalizacion'; break
				}
				return clsRow;
			}
        },
        enableColumnHide : false,
        enableColumnMove : false,
        enableDragDrop : false,
        enableHdMenu : false,
		features: [{
            id: 'group',
            ftype: 'groupingsummary',
            groupHeaderTpl: '{name}',
            hideGroupedHeader: true,
            enableGroupingMenu: false
        }],
        columns: [{
            text: 'Valor',
            align:'LEFT',
            dataIndex: 'valor',
			sortable: false
        },{
            text: 'A&ntilde;o',
            align:'CENTER',
			width: 70,
            dataIndex: 'anio',
			sortable: false
        },{
            text: 'Periodo',
            align:'CENTER',
			width: 58,
            dataIndex: 'periodo',
            summaryRenderer: function(value, summaryData, dataIndex) {
				return 'Total:';
            },
			sortable: false
        },{
            text: 'Insoluto',
			align:'RIGHT',
			width: 91,
            dataIndex: 'insoluto',
			summaryType: 'sum',
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(value,'0,000.00');
            },
			summaryRenderer: function(value, summaryData, dataIndex) {
				return Ext.util.Format.number(value, '0,000.00');
            },
            field: {
                xtype: 'numberfield'
            },
			sortable: false
        },{
            text: 'Reajuste',
			align:'RIGHT',
			width: 91,
            dataIndex: 'reajuste',
			summaryType: 'sum',
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(value,'0,000.00');
            },
			summaryRenderer: function(value, summaryData, dataIndex) {
				return Ext.util.Format.number(value, '0,000.00');
            },
            field: {
                xtype: 'numberfield'
            },
			sortable: false
        },{
            text: 'Mora',
			align:'RIGHT',
			width: 91,
            dataIndex: 'mora',
			summaryType: 'sum',
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(value,'0,000.00');
            },
			summaryRenderer: function(value, summaryData, dataIndex) {
				return Ext.util.Format.number(value, '0,000.00');
            },
            field: {
                xtype: 'numberfield'
            },
			sortable: false
        },{
            text: 'Costo Emisi&oacute;n',
			align:'RIGHT',
			width: 91,
            dataIndex: 'emision',
			summaryType: 'sum',
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(value,'0,000.00');
            },
			summaryRenderer: function(value, summaryData, dataIndex) {
				return Ext.util.Format.number(value, '0,000.00');
            },
            field: {
                xtype: 'numberfield'
            },
			sortable: false
        },{
			id: 'rowTotal',
			tdCls: 'xrow-total',
            text: 'Total',
			align:'RIGHT',
			width: 91,
            dataIndex: 'total',
			summaryType: 'sum',
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return '<b>'+Ext.util.Format.number(value,'0,000.00')+'</b>';
            },
			summaryRenderer: function(value, summaryData, dataIndex) {
				return Ext.util.Format.number(value, '0,000.00');
            },
            field: {
                xtype: 'numberfield'
            },
			sortable: false
        },{
            text: 'estado',
            align:'LEFT',
            dataIndex: 'estado',
			hidden:	true,
			sortable: false
        },{
            text: 'Vencimiento',
            align:'CENTER',
			width: 97,
            dataIndex: 'dfecven',
			sortable: false
        }]
    });
    
    gridverResoluciones.render('gridDetalledeuda');

});



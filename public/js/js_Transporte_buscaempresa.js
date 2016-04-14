Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('EmpresasModel', {
        extend: 'Ext.data.Model',
        fields: [
				{name: 'idx', type: 'string'},
				{name: 'id_solicitud', type: 'string'},
				{name: 'anio', type: 'string'},
				{name: 'codigo', type: 'string'},
				{name: 'nombre', type: 'string'},
				{name: 'num_doc', type: 'string'}
        ]
    });

    var empresasStore = Ext.create('Ext.data.Store', {
    	model: 'EmpresasModel',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'transporte/buscarempresas',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridEmpresas',
        store: empresasStore,
		height: '285px',
        title: 'Empresas',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
		},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'idx',
            width: 70,            
            dataIndex: 'idx'
        },{
            text: 'Registro',
            width: 70,            
            dataIndex: 'id_solicitud'
        },{
            text: 'A&ntildeo',
            width: 70,            
            dataIndex: 'anio'
        },{        
        	text: 'C&oacute;digo',
        	width: 70,
            dataIndex: 'codigo'
        },{
            text: 'Nombre o Raz&oacute;n Social',
            flex: 1,
            dataIndex: 'nombre'
        },{
            text: 'RUC',
            width: 100,
            dataIndex: 'num_doc'
        },{
            xtype:'actioncolumn',
            width:60,
            items: [{
                icon: urljs +'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Empresa',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('transporte/mantenimientoempresas?idtransporte='+rec.get('idx'),'#popempresa','800','620','Editar Empresa');
                }
            }]
        }]
    });
    
    grid.render('divgridEmpresas');

});

function buscarVehiculos(){
	var grid = Ext.getCmp('xgridEmpresas');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {
		cmbTipos: $('#cmbTipos').val(),txtDatos: $('#txtDatos').val()
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}
/*
function conductorpdf(){

	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	var cmbcajas= $('#cmbcajas').val();
	showPopupReport('tipo=pdf&nombrereporte=rpt_emitidos&param=FECDESDE^'+tesodesde+'|FECHASTA^'+tesohasta+'|CAJERO^'+cmbcajas+'|USUARIO^'+userjs,'pouprptemitido',700,600,'Recibos emitidos');

}
*/
Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Buspredio', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
			{name: 'nombre'},
            {name: 'cod_pred', type: 'string'},
            {name: 'anexo', type: 'string'},
            {name: 'sub_anexo', type: 'string'},
			{name: 'direccionpredio', type: 'string'},
			{name: 'zona', type: 'string'}
        ]
    });
	
	var store = Ext.create('Ext.data.Store', {
    	model: 'Buspredio',
    	autoLoad: false,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantbusquedapredio/consultapredio?codigocontri='+$('#txtCodcontrib').val(),
			//url : 'mantbajapre/consulta?codigobaja='+$('#divCodigo').html(),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
	
	var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBuspredio',
        store: store,        
        title: 'Predios',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 60,            
            dataIndex: 'codigo'
        },{
            text: 'Nombre',
            flex: 1,
            dataIndex: 'nombre'            
        },{
            text: 'Codigo Pred.',
            width: 70,
            dataIndex: 'cod_pred'            
        },{
            text: 'Anexo',
            width: 58,
            dataIndex: 'anexo'
        },{
            text: 'Sub. Anex.',
            width: 58,
            dataIndex: 'sub_anexo'
        },{
            text: 'Direccion Predio',
            flex: 1,//width: 560,
            dataIndex: 'direccionpredio'
        },{
            text: 'Zona',
            width: 100,
            dataIndex: 'zona'
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridBuspredio',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        }),
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
                //alert(record);
                closePopup('#popbuscapredio');
				disableButton('#btnBusPredio');
                window.parent.muestraDatosContripredio(record);//Captura Valores
            }
        },
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridBuspredio',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        })	
    });
	
	 grid.render('gridBuspredio');
	 
	 buscarPredio();
	 
	 // $('#txtCriteriobuscoac').keypress(function(e) {
        // if(e.which == 13) {
        	// buscarPredio();
        // }
    // });
	
});


function buscarPredio(){
	var grid = Ext.getCmp('xgridBuspredio');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var txt_viabus = $('#txt_viabus').val();
	var cb_annobus = $('#cb_annobus').val();
	var txt_busnro = $('#txt_busnro').val();
	var txt_busdpto = $('#txt_busdpto').val();
	var txt_busmaza = $('#txt_busmaza').val();
	var txt_buslte = $('#txt_buslte').val();
	var txt_sublte = $('#txt_sublte').val();
	
	proxy.extraParams = {anno: cb_annobus, cod_via: txt_viabus, nro:txt_busnro,dpto: txt_busdpto,mza:txt_busmaza,lte:txt_buslte,sublote:txt_sublte};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});

}

$('#rdCriterio5').click(function(){
	if($('#rdCriterio5').attr('checked'))
	{
	
		//buscarPredio();
		//$('#BusquedaContri').hide();
		$('#Busquedapredio').show();
	
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		
		// $('#C input').val('');
		// $('#N input').val('');
		// $('#R input').val('');
		// $('#D input').val('');
		
		
	}
});

function mostrarVias(){
	var anno_bvia=$('#cb_annobus').val();
	showPopup('mantbusquedapredio/contribusvias?anno='+anno_bvia,'#popbuscar','700','280','Mantenimiento de Predios');
}

window.muestraDatos = function(obj) { 
	//$('#txtVia').val(obj.get('nomvia'));
	$('#txt_viabus').val(obj.get('codigo'));
	//$('#txtCp').val(obj.get('nomurba'));
	//$('#txtSector').val(obj.get('nomzona'));
	//$('#txtArancel').val(obj.get('arancel'));
	
};








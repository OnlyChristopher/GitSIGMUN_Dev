Ext.onReady(function(){
    Ext.QuickTips.init();
    var txtAnno=$("#anno_busqueda").html();
	$("input[type='button']").button();
	
    Ext.define('Buscar', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo', type: 'string'},
            {name: 'codzona', type: 'string'},
            {name: 'nomzona', type: 'string'},
            {name: 'codurba', type: 'string'},
            {name: 'nomurba', type: 'string'},
            {name: 'nomvia', type: 'string'},
            {name: 'arancel', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Buscar',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'rentas/consulta?anno='+txtAnno,
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBuscar',
        store: store,        
        title: '',
        viewConfig: {
        	loadMask: {msg: 'Listando ...'}
    	},
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 100,            
            dataIndex: 'codigo'
        },{        
        	text: 'CodZona',
        	width: 60,
            dataIndex: 'codzona',
			hidden: true
        },{
            text: 'Zona',
            width: 100,
            dataIndex: 'nomzona'            
        },{
            text: 'Codurba',
            width: 60,
            dataIndex: 'codurba',
			hidden: true
        },{
            text: 'Urbanizaci&oacute;n',
            width: 150,
            dataIndex: 'nomurba'            
        },{
            text: 'Via',
            width: 220,
            dataIndex: 'nomvia'            
        },{
            text: 'Arancel',
            width: 80,
			hidden:true,
            dataIndex: 'arancel'            
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridBuscar',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Datos"
        }),
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
                //alert(record);
				var pio= $('#txtflag').val();
                closePopup('#popbuscar');
				if(pio==1)
				{
                window.parent.muestraDatos(record);//Captura Valores
				}
				else{
				window.parent.muestraDatos2(record)
				}
            }
        } 
        
    });
    
    grid.render('gridBuscar');
    
    $('#txt_busvia').keypress(function(e) {
        if(e.which == 13) {
        	buscarDatos();
        }
    }); 
    
    $('#txt_busvia').focus();
    
});

function buscarDatos(){
	var grid = Ext.getCmp('xgridBuscar');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	//var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txt_busvia').val();
	var txtAnno=$("#anno_busqueda").html();
	
	//proxy.extraParams = {page: '1', start: '0', limit: '10', rdcriterio: rdCriterio, criterio: txtCriterio};
	//store.loadPage(1); 
	proxy.extraParams = {criterio: txtCriterio, anno: txtAnno};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}


/*

$("#btnBusquedacri").click(function(){
	var valor=$("#txtBusqueda").val();
	if(valor==""){
		alert('Debe Ingresar');
	}
	else{
	 //rdbCriterios=$("#rdbCriterios").val();
	 txtBusqueda=$("#txtBusqueda").val();
		$.ajax({     
			type: "POST",     
			url: "/mantbusqueda/busqueda",
			data: 'txtCriterio='+txtBusqueda,     
			success: function(data) { 
				$("#detallesBusqueda").html(data);
			},     
			error: function() {
			} 
		}); 
	}
});

function mostrarZona(nombrevia){
	$("#txtViacontri").val(nombrevia);
}

*/
Ext.onReady(function(){
    Ext.QuickTips.init();
    
    Ext.define('Acceso', { //contable= conta
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id_acceso'},
            {name: 'orden'},
            {name: 'nombre'},
			{name: 'id_objeto'},
			{name: 'icono'},
			{name: 'doform'},
			{name: 'nestado'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Acceso', // contable=conta
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantacceso/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridAcceso', // contabilidad=conta
        store: store,        
        title: 'Tabla de Acceso',
        viewConfig: {
        	loadMask: {msg: 'Cargando ...'}
    	},
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'ID Acceso',
            width: 150,            
            dataIndex: 'id_acceso'
        },{        
        	text: 'Tipo',
        	width: 100,
            dataIndex: 'orden'
        },{
            text: 'Nombre',
            flex: 1,
            dataIndex: 'nombre'            
        },{
            text: 'ID Objeto',
            width: 150,
            dataIndex: 'id_objeto'            
        },{
            text: '&Iacute;cono',
            width: 100,
            dataIndex: 'icono'            
        },{
            text: 'Formulario',
            flex: 1,
            dataIndex: 'doform'            
        },{
            text: 'Estado',
            width: 100,
            dataIndex: 'nestado'            
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Acesso',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('mantacceso/formu?id_acceso='+rec.get('id_acceso'),'#popNuevoAcceso','420','280','Editar Acceso');
                }
            },{
                icon: urljs + 'img/delete.png',
                tooltip: 'Eliminar Acceso',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    
                    var showResult = function(btn){  // ELIMINAR POR MEDIO DEL BOTON
                    	if(btn=='yes')
                    		$.ajax({
                    			type: "GET", 
                    			url: 'mantacceso/eliminar',
                    			data: 'id_acceso='+rec.get('id_acceso'),
                    			success: function(data){
                    				infoMessage('Eliminado ',data);
                    				Ext.getCmp('xgridAcceso').getStore().load();
                    			}
                    		});
                    };
					
                    confirmMessage('Eliminar','Seguro de Eliminar ?',showResult);
                }                
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridAcceso',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Displaying topics {0} - {1} of {2}',
            emptyMsg: "No se encuentran contables"
        })
    });
      
  	grid.render('gridAcceso');
	
	$('#txtCriterio, #cmbTipo').keypress(function(e) {
        if(e.which == 13) {
        	buscarAcceso();
        }
    });
	
	$('#txtCriterio').focus();
 
});

///////////////////////////////////////////////////////////
$('#rdC1').click(function(){	
	if($('#rdC1').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});

$('#rdC3').click(function(){
	if($('#rdC3').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});
///////////////////////////////////////////////////////////
$('#txtCriterio').keypress(function (e) {
	if($('#rdC1').attr('checked') )
		return validaTeclas(e,'numeric');
	
	if($('#rdC3').attr('checked'))
		return validaTeclas(e,'text');
});
///////////////////////////////////////////////////////////

function changeMenu(valor){
	$.ajax({     
		type: "POST",     
		url: urljs + "mantacceso/combos",
		data: "opt=3&valor="+valor,
		success: function(response) { 
			$('#cmbPantalla').html(response);
		} 
	}); 
}

function buscarAcceso(){
	
	var grid = Ext.getCmp('xgridAcceso');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	var cmbTipo = $('#cmbTipo').val();
	var cmbMenu = $('#cmbMenu').val();
	var cmbPantalla = $('#cmbPantalla').val();
	
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio, tipo: cmbTipo, menu: cmbMenu, pantalla: cmbPantalla};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
	
}
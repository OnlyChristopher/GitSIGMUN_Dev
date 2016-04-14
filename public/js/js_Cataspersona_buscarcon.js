Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('CPersony', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'idsigma'},
            {name: 'nombres', type: 'string'},
			{name: 'nombre', type: 'string'},
			{name: 'apatern', type: 'string'},
			{name: 'amatern', type: 'string'},
            {name: 'tipodoc', type: 'string'},
			{name: 'numedoc', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'CPersony',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'cataspersona/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridCPersony',
        store: store,        
        title: 'Personas',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
		listeners: {
			afterrender: function(grid) {
				Ext.get('txtCriterioy').focus(500);
			},
			itemdblclick: {
				fn : function(grid, record) {
					closePopup('#popcatascon');
					selectConyu(record);
				}
			}
		},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 60,            
            dataIndex: 'idsigma'
        },{        
        	text: 'Nombres',
        	flex: 1,
            dataIndex: 'nombres'
        },{        
			hidden: true,
            dataIndex: 'nombre'
        },{       
        	hidden: true,
            dataIndex: 'apatern'
        },{        
        	hidden: true,
            dataIndex: 'amatern'
        },{
            text: 'Tipo Doc.',
            width: 100,
            dataIndex: 'tipodoc'            
        },{
            text: 'Nro. Doc.',
            width: 100,
            dataIndex: 'numedoc'            
        },{
            xtype:'actioncolumn',
            width:55,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('cataspersona/personx?idsigmaxx='+rec.get('idsigma'),'#popcpersonx','340','150','Editar Persona');					
                }
            },{
                icon: urljs +'img/delete.png',
                style: 'cursor: pointer',
                tooltip: 'Eliminar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    var showResult = function(btn){
                    	if(btn=='yes')
                    		$.ajax({
                    			type: "GET", 
                    			url: 'cataspersona/eliminar',
                    			data: 'idsigma='+rec.get('idsigma'),
                    			success: function(response){
                    				infoMessage('Eliminando',response);                    				
                    				grid.getStore().load(grid.getStore().currentPage);                 				
                    			} 
                    		});                    	
                    };
                    confirmMessage('Eliminar','Seguro de eliminar el c&oacute;digo '+rec.get('idsigma')+'?',showResult);               
                }
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        })
    });
    
    grid.render('gridCPersony');
	
	$('#txtCriterioy').keypress(function(e) {
        if(e.which == 13) {
        	buscarCPersony();
			return false;
        }		
		if($('#rdCriterioy1').attr('checked') )
			return validaTeclas(event,'number');			
		if($('#rdCriterioy2').attr('checked'))
			return validaTeclas(event,'text');			
		if($('#rdCriterioy3').attr('checked'))
			return validaTeclas(event,'alpha');
    });
	
	$('input[name*="rdCriterioy"]').click(function() {
        $('#txtCriterioy').val('');
		$('#txtCriterioy').focus();
    });
	
	$('.caja').live('blur', function(){
		this.value = this.value.toUpperCase();
		
		var numCeros = '0000000';
		var valor =  $('#txtCriterioy').val().trim();
		var vradio = $('input[name*="rdCriterioy"]:checked').val();
		if((vradio=='C') && valor.length>0)
		{ 
			var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
			$('#txtCriterioy').val(valor2);
		}
		else{
			$('#txtCriterioy').val(valor);
    	}
	});
	
});

function buscarCPersony(){
	var grid = Ext.getCmp('xgridCPersony');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterioy"]:checked').val();
	var txtCriterio = $('#txtCriterioy').val();
	
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){
			Ext.get('txtCriterioy').focus(500);
		}
	});	
}
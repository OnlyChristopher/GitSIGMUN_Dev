Ext.onReady(function(){
    Ext.QuickTips.init();
    
    Ext.define('Ingresos', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
            {name: 'descripcion', type: 'string'},
            {name: 'partida', type: 'string'},
            {name: 'area', type: 'string'},
            {name: 'fuente', type: 'string'},
            {name: 'monto', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Ingresos',
        autoLoad: true,
    	pageSize: 10,
    	proxy: {
            type: 'ajax',
            url : 'mantingresos/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridIngresos',
        store: store,        
        title: '',
        viewConfig: {
        	loadMask: {msg: 'Listando Ingresos...'}
    	},
        selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [
        {
            xtype: 'rownumberer',
            width: 40,
            sortable: false
        }, 
          //  Ext.create('Ext.grid.RowNumberer'),   
        {
            text: 'C&oacute;digo',
            width: 60,            
            dataIndex: 'codigo'
        },{        
        	text: 'Descripci&oacute;n',
        	width: 340,
            dataIndex: 'descripcion'
        },{
            text: 'Partida',
            width: 100,
            dataIndex: 'partida'            
        },{
            text: '&Aacute;rea',
            width: 325,
            dataIndex: 'area'
        },{
            text: 'Fuente',
            width: 70,
            dataIndex: 'fuente',
            align:'center'
        },{
            text: 'Monto',
            width: 70,
            dataIndex: 'monto',
            align:'right'
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Ingreso',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    showPopup('mantingresos/formu?actionIngreso=E&codigo='+rec.get('codigo'),'#popupIngreso','1000','320','Editar Ingreso');
                }
            },{
                icon: urljs + 'img/view.png',
                tooltip: 'Eliminar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Ver predios',rec.get('codigo'));
                    //showPopup('rentasdecjurada/index?codigo='+rec.get('codigo'),'#popupIngreso','900','580','Declaraciones Juradas de Autovaluo');
                    
                    var showResult = function(btn){
            			if(btn=='yes')  //si es 'yes' llamo al ajax
            				
            				$.ajax({
                        		type: "GET",
                        		url: 'mantingresos/eliminar',
                        		data: 'codigo='+rec.get('codigo'),
                        		success: function(data){
                        			//alert(data);
                        			infoMessage('Eliminado',data);
                        			
                					Ext.getCmp('xgridIngresos').getStore().load();
                        		}
                        	});	
            		};
            		confirmMessage('Eliminar','Seguro de eliminar el c&oacute;digo '+rec.get('codigo')+' ?',showResult);
                }                
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridIngresos',
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} of {2}',
            emptyMsg: "No se encontr&oacute; ingresos",
            pageSize: 10            
        })
    });
    
    grid.render('gridIngresos');
    
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarIngresos();
        }
    });
});

function buscarIngresos(){
	var grid = Ext.getCmp('xgridIngresos');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	
	//proxy.extraParams = {page: '1', start: '0', limit: '10', rdcriterio: rdCriterio, criterio: txtCriterio};
	//store.loadPage(1); 
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}

$('#txtCriterio').keypress(function (e) {
	
	if($('#rdCriterio1').attr('checked') )
		return validaTeclas(event,'numeric');
		
	if($('#rdCriterio2').attr('checked'))
		return validaTeclas(event,'text');
		
	if($('#rdCriterio3').attr('checked'))
		return validaTeclas(event,'text');
});

$('#rdCriterio1').click(function(){
	
	if($('#rdCriterio1').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});

$('#rdCriterio2').click(function(){
	
	if($('#rdCriterio2').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});

$('#rdCriterio3').click(function(){
	
	if($('#rdCriterio3').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});


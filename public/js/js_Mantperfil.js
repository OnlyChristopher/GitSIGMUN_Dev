Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
	
    Ext.define('Perfil', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id_perfil'},
            {name: 'nombre'},
			{name: 'nestado'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Perfil',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantperfil/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridPerfil',
        store: store,        
        title: 'Perfiles',
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 100,            
            dataIndex: 'id_perfil'
        },{        
        	text: 'Descripci&oacute;n',
        	flex: 1,
            dataIndex: 'nombre'
        },{
            text: 'Estado',
            width: 150,
            dataIndex: 'nestado' 
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
				altText: 'btnEditPerfil',
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('mantperfil/formu?id_perfil='+rec.get('id_perfil'),'#popNewPerfil','890','400','Editar Perfil');
                }
            },{
				altText: 'btnDeletePerfil',
                icon: urljs + 'img/delete.png',
                tooltip: 'Eliminar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);

					var showResult = function(btn){
                    	if(btn=='yes')
                    		$.ajax({
                    			type: "GET", 
                    			url: 'mantperfil/eliminar',
                    			data: 'id_perfil='+rec.get('id_perfil'),
                    			success: function(data){
                    				infoMessage('Eliminar',data);
                    				Ext.getCmp('xgridPerfil').getStore().load();
                    			}
                    		});
                    	
                    };
                    
					confirmMessage('Eliminar','Seguro de Eliminar ?',showResult);
                }                
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridUsuario',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontraron registros"
        })
    });
    
	grid.render('gridPerfil');
	
    //------------------------------------------------------------------------------------------------------
    
    $('#txtCriterio').keypress(function (e) {
    	if($('#rdC1').attr('checked') )
    		return validaTeclas(e,'number');
    	
    	if($('#rdC2').attr('checked'))
    		return validaTeclas(e,'text');    		
    });
    
        
    ///-----------------------------------------------------
    $('#rdC1').click(function(){
    	
    	if($('#rdC1').attr('checked'))
    	{
    		$('#txtCriterio').val('');
			$('#txtCriterio').focus();
    	}
    });

    $('#rdC2').click(function(){
    	
    	if($('#rdC2').attr('checked'))
    	{
    		$('#txtCriterio').val('');
			$('#txtCriterio').focus();
    	}
    });

    $('#txtCriterio').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtCriterio').val().trim();
        var radio = $('#rdC1').val();
       //alert(radio);
        //if(valor.length==0 &&  radio!="C")
        if($('#rdC1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
        { 
        	//$('#txtCriterio').val(valor);
        	  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
      	    $('#txtCriterio').val(valor2);  //obj.value = valor;
        }
        else{
        	$('#txtCriterio').val(valor);
        }
        
    });
    
   //------------------------------------------------------------------     
    
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarPerfil();
        }
    });
    
});

function buscarPerfil(){
	var grid = Ext.getCmp('xgridPerfil');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	var cmbEstado = $('#cmbEst').val();
	
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio, estado: cmbEstado};
	
	store.currentPage = 1;
	
	store.load({
		callback: function(documents, options, success){}
	});
}
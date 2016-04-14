Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('CPerson', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'idsigma'},
            {name: 'nombres', type: 'string'},
            {name: 'tipodoc', type: 'string'},
			{name: 'numedoc', type: 'string'},
            {name: 'direccion', type: 'string'},
			{name: 'cidcont', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'CPerson',
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
    	id: 'xgridCPerson',
        store: store,        
        title: 'Contribuyentes',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 60,            
            dataIndex: 'idsigma'
        },{        
        	text: 'Nombres / Raz&oacute;n Social',
        	width: 300,
            dataIndex: 'nombres'
        },{
            text: 'Tipo Doc.',
            width: 80,
            dataIndex: 'tipodoc'            
        },{
            text: 'Nro. Doc.',
            width: 100,
            dataIndex: 'numedoc'            
        },{
            text: 'Direcci&oacute;n',
            flex: 1,
            dataIndex: 'direccion'
        },{
            text: 'Cod. Rentas',
            width: 100,
            dataIndex: 'cidcont'            
        },{
            xtype:'actioncolumn',
            width:55,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Contribuyente',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);					
                    showPopup('cataspersona/formu?idsigma='+rec.get('idsigma')+'&dest=1','#popcperson','750','570','Editar Contribuyente');			
                }
            },{
                icon: urljs +'img/delete.png',
                style: 'cursor: pointer',
                tooltip: 'Eliminar Contribuyente',
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
            },{
                icon: urljs + 'img/view.png',
                tooltip: 'Ver Fichas',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('catasfichas/index?idsigma='+rec.get('idsigma'),'#popfcatas','900','400','Fichas Catastrales');
                }
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridContri',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        })
    });
    
    grid.render('gridCPerson');
    
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarCPerson();
			return false;
        }
		if($('#rdCriterio1').attr('checked') )
			return validaTeclas(event,'number');			
		if($('#rdCriterio2').attr('checked'))
			return validaTeclas(event,'text');			
		if($('#rdCriterio3').attr('checked'))
			return validaTeclas(event,'alpha');
		if($('#rdCriterio4').attr('checked') )
			return validaTeclas(event,'number');
    });
	
	$('input[name*="rdCriterio"]').click(function() {
        $('#txtCriterio').val('');
		$('#txtCriterio').focus();
    });
	
	$('.caja').live('blur', function(){
		this.value = this.value.toUpperCase();
		
		var numCeros = '0000000';
		var valor =  $('#txtCriterio').val().trim();
		var vradio = $('input[name*="rdCriterio"]:checked').val();
		if((vradio=='C' || vradio=='O') && valor.length>0)
		{ 
			var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
			$('#txtCriterio').val(valor2);  //obj.value = valor;
		}
		else{
			$('#txtCriterio').val(valor);
    	}
	});
	
});

function buscarCPerson(){
	var grid = Ext.getCmp('xgridCPerson');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});

}

function actualizarCPerson(){
	var grid = Ext.getCmp('xgridCPerson');
	var store = grid.getStore();
	store.reload();
}

function changeTtitu(tipo){
	switch(tipo){
		case '1':
			$('#cidcivi').attr('disabled',false);
			$('#ciddocu').attr('disabled',false);
			$('#numedoc').attr('disabled',false);
			$('#numedoc').css({'background-color': '#fff'});
			$('#nombres').attr('disabled',false);
			$('#nombres').css({'background-color': '#fff'});
			$('#apatern').attr('disabled',false);
			$('#apatern').css({'background-color': '#fff'});
			$('#amatern').attr('disabled',false);
			$('#amatern').css({'background-color': '#fff'});
			
			$('#numruc').attr('disabled',false);
			//$('#numruc').css({'background-color': '#dfe9f6'});
			//$('#numruc').val('');
			$('#razsoc').attr('disabled',true);
			$('#razsoc').css({'background-color': '#dfe9f6'});
			$('#razsoc').val('');
			$('#cidjuri').attr('disabled',true);
			$("#cidjuri").val($("#cidjuri option:first").val());
			$('#cidespe').attr('disabled',true);
			$("#cidespe").val($("#cidespe option:first").val());
						
			enableButton('#btnSelectCon');
			
			$('#cidcivi').focus();
		break;
		case '2':
			$('#cidcivi').attr('disabled',true);
			$("#cidcivi").val($("#cidcivi option:first").val());
			$('#ciddocu').attr('disabled',true);
			$("#ciddocu").val($("#ciddocu option:first").val());
			$('#numedoc').attr('disabled',true);
			$('#numedoc').css({'background-color': '#dfe9f6'});
			$('#numedoc').val('');
			$('#nombres').attr('disabled',true);
			$('#nombres').css({'background-color': '#dfe9f6'});
			$('#nombres').val('');
			$('#apatern').attr('disabled',true);
			$('#apatern').css({'background-color': '#dfe9f6'});
			$('#apatern').val('');
			$('#amatern').attr('disabled',true);
			$('#amatern').css({'background-color': '#dfe9f6'});
			$('#amatern').val('');
			
			$('#numruc').attr('disabled',false);
			//$('#numruc').css({'background-color': '#fff'});
			$('#razsoc').attr('disabled',false);
			$('#razsoc').css({'background-color': '#fff'});
			$('#cidjuri').attr('disabled',false);
			$('#cidespe').attr('disabled',false);
			
			disableButton('#btnSelectCon');
			
			$('#numruc').focus();
		break;
		default:
			$('#cidcivi').attr('disabled',false);
			$('#ciddocu').attr('disabled',false);
			$('#numedoc').attr('disabled',false);
			$('#numedoc').css({'background-color': '#fff'});
			$('#nombres').attr('disabled',false);
			$('#nombres').css({'background-color': '#fff'});
			$('#apatern').attr('disabled',false);
			$('#apatern').css({'background-color': '#fff'});
			$('#amatern').attr('disabled',false);
			$('#amatern').css({'background-color': '#fff'});
			$('#numruc').attr('disabled',false);
			//$('#numruc').css({'background-color': '#fff'});
			$('#razsoc').attr('disabled',false);
			$('#razsoc').css({'background-color': '#fff'});
			$('#cidjuri').attr('disabled',false);
			$('#cidespe').attr('disabled',false);
			
			disableButton('#btnSelectCon');
		break;
	}
}
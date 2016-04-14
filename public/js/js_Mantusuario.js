Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
	
	$("#txtNomUsuario").css('text-transform','uppercase');
	$("#txtApeUsuario").css('text-transform','uppercase');
    
    Ext.define('Usuario', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id_usuario'},
            {name: 'nombre'},
            {name: 'area'},
			{name: 'perfil'},
            {name: 'vlogin'},
			{name: 'nestado'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Usuario',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantusuario/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridUsuario',
        store: store,        
        title: 'Usuarios',
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 100,            
            dataIndex: 'id_usuario'
        },{        
        	text: 'Nombres y Apellidos',
        	flex: 1,
            dataIndex: 'nombre'
        },{
            text: 'Area',
            width: 350,
            dataIndex: 'area' 
        },{
            text: 'Perfil',
            width: 150,
            dataIndex: 'perfil' 
        },{
            text: 'Usuario',
            width: 150,
            dataIndex: 'vlogin' 
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
                tooltip: 'Editar Usuario',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    showPopup('mantusuario/formu?actionUsuario=E&id_usuario='+rec.get('id_usuario'),'#popNuevoUsu','470','420','Editar Usuario');
                }
            },{
                icon: urljs + 'img/delete.png',
                tooltip: 'Eliminar Usuario',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);

					var showResult = function(btn){  // ELIMINAR POR MEDIO DEL BOTON
                    	if(btn=='yes')
                    		$.ajax({
                    			type: "GET", 
                    			url: 'mantusuario/eliminar',
                    			data: 'id_usuario='+rec.get('id_usuario'),
                    			success: function(data){
                    				infoMessage('Eliminado ',data);                    				
                    				Ext.getCmp('xgridUsuario').getStore().load();
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
    
    //------------------------------------------------------------------------------------------------------
    
    $('#txtCriterio').keypress(function (e) {
    	if($('#rdC1').attr('checked') )
    		return validaTeclas(e,'number');
    	
    	if($('#rdC2').attr('checked'))
    		return validaTeclas(e,'text');
    		
    	if($('#rdC3').attr('checked'))
    		return validaTeclas(e,'text');    	
    });
    
    
    
    ///-----------------------------------------------------
    $('#rdC1').click(function(){
    	
    	if($('#rdC1').attr('checked'))
    	{
    		$('#txtCriterio').val('');
    	}
    });

    $('#rdC2').click(function(){
    	
    	if($('#rdC2').attr('checked'))
    	{
    		$('#txtCriterio').val('');
    	}
    });

    $('#rdC3').click(function(){
    	
    	if($('#rdC3').attr('checked'))
    	{
    		$('#txtCriterio').val('');
    	}
    }); 
	
	$('#rdC4').click(function(){
    	
    	if($('#rdC4').attr('checked'))
    	{
    		$('#txtCriterio').val('');
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
    grid.render('gridUsuario');
    
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarUsuario();
        }
    });
    
});

function buscarUsuario(){
	var grid = Ext.getCmp('xgridUsuario');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	var cmbArea = $('#cmbAre').val();
	var cmbPerfil = $('#cmbPerf').val();
	var cmbEstado = $('#cmbEst').val();
	
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio, area: cmbArea, perfil: cmbPerfil, estado: cmbEstado};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ejecutarclave(){
	var codigo=$('#id_usuario').val();
	showPopup('mantusuario/password?codigo='+codigo,'#popNuevaUsupass','300','200','Generar Clave');
	
}
//-----------------------------------------------------------------------

function habilitaCajero(val){
	if(!val)
		$('#cmbCajero').attr('disabled',true);
	else
		$('#cmbCajero').attr('disabled',false);	
}

//-------------------------------------------------------------------
function validaText(val){
	var op = val.substr(0,2);
	var ncar = val.substr(3,4);
	
	//alert(ncar);
	if(ncar>0)
	{
		$('#txtdocUsuario').attr('disabled',false);
		$('#txtdocUsuario').autotab({ maxlength: ncar });
	}
	else
	{
		$('#txtdocUsuario').val('');
		$('#txtdocUsuario').attr('disabled',true);
	}
}
//--------------------------------------------------------
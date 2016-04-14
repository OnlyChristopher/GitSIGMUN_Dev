Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('CMficha', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'idficha'},
            {name: 'periodo'},
			{name: 'cidtipo'},
            {name: 'fichtipo'},
			{name: 'nrficha'},
            {name: 'nflote'},
			{name: 'rsector'},
			{name: 'rmanzan'},
			{name: 'reflote'},
			{name: 'redific'},
			{name: 'rentrad'},
			{name: 'refpiso'},
			{name: 'refunid'},
			{name: 'dc_cata'},
			{name: 'refcat'},
			{name: 'cidpers'},
			{name: 'titular'},
			{name: 'zona'},
			{name: 'hurba'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'CMficha',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'catasfichas/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridCMficha',
        store: store,        
        title: 'Fichas Catastrales',
        viewConfig: {
        	loadMask: {msg: 'cargando...'},
			stripeRows: false, 
			getRowClass: function(record) { 
				var clsRow = '';
				switch(record.get('cidtipo')){
					case '02': clsRow='xrow-red'; break
					case '03': clsRow='xrow-blue'; break
					case '04': clsRow='xrow-green'; break
				}
				return clsRow;
			} 
    	},
		listeners: {
			itemdblclick: {
				fn : function(grid, record) {
					//alert(record.get('idficha'));
					showPopup('catasfichas/mapa?idficha='+record.get('idficha'),'#popcatasmapa','600','400','Fichas Dependientes');
				}
			}
		},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            hidden: true,            
            dataIndex: 'idficha'
        },{
            hidden: true,            
            dataIndex: 'cidtipo'
        },{
            hidden: true,            
            dataIndex: 'cidpers'
        },{
            text: 'Per.',
            width: 40,            
            dataIndex: 'periodo'
        },{
            text: 'Tipo de Ficha',
            width: 100,            
            dataIndex: 'fichtipo'
        },{        
        	text: 'N&deg; de Ficha',
        	width: 100,
            dataIndex: 'nrficha'
        },{
            text: 'N&deg; F/Lote',
            width: 70,
            dataIndex: 'nflote'            
        },{
            text: 'Sec.',
            width: 40,
            dataIndex: 'rsector'            
        },{
            text: 'Mz.',
            width: 40,
            dataIndex: 'rmanzan'            
        },{
            text: 'Lote',
            width: 40,
            dataIndex: 'reflote'            
        },{
            text: 'Edif',
            width: 40,
            dataIndex: 'redific'            
        },{
            text: 'Ent.',
            width: 40,
            dataIndex: 'rentrad'            
        },{
            text: 'Piso',
            width: 40,
            dataIndex: 'refpiso'            
        },{
            text: 'Unid',
            width: 50,
            dataIndex: 'refunid'            
        },{
            text: 'DC',
            width: 50,
            dataIndex: 'dc_cata'            
        },{
            text: 'Ref. Catastral',
            width: 150,
            dataIndex: 'refcat'            
        },{
            text: 'Titular',
            flex: 1,
            dataIndex: 'titular'            
        },{
            text: 'Habilitaci&oacute;n Urbana',
            flex: 1,
            dataIndex: 'hurba'            
        },{
            text: 'Zona / Sector / Etapa',
            flex: 1,
            dataIndex: 'zona'
        },{
            xtype:'actioncolumn',
            width:60,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);					
                    editFicha(rec.get('cidtipo'),rec.get('idficha'));
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
                    			url: 'catasfichas/eliminar',
                    			data: 'idficha='+rec.get('idficha')+"&cidtipo="+rec.get('cidtipo'),
                    			success: function(response){
                    				infoMessage('Eliminando',response);                    				
                    				grid.getStore().load(grid.getStore().currentPage);                 				
                    			} 
                    		});                    	
                    };
                    confirmMessage('Eliminar','Seguro de eliminar la Ficha N&deg; '+rec.get('nrficha')+'?',showResult);               
                }
            },{
                icon: urljs + 'img/view.png',
                tooltip: 'Enlazar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('catasfichas/enlace?idficha='+rec.get('idficha'),'#popcatasenlace','300','140','Enlazar Fichas');
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
    
    grid.render('gridCMficha');
	
	//Todo a mayúscula
	$('.caja').blur(function(event){
		this.value = this.value.toUpperCase();
	});
	
	$('#cmbPeriodo,#txtSector,#txtManzana,#txtLote,#txtNficha,#txtTitular,#txtZse,#txtHub').keypress(function(e) {
        if(e.which == 13) {
        	buscarCMficha();
			return false;
        }		
    });
});

function buscarCMficha(){
	var grid = Ext.getCmp('xgridCMficha');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var cmbPeriodo = $('#cmbPeriodo').val();
	var txtSector = $('#txtSector').val();
	var txtManzana = $('#txtManzana').val();
	var txtLote = $('#txtLote').val();
	var cmbTipo = $('#cmbTipo').val();
	var txtNficha = $('#txtNficha').val();
	var txtTitular = $('#txtTitular').val();
	var txtZse = $('#txtZse').val();
	var txtHub = $('#txtHub').val();
	
	proxy.extraParams = {
		periodo: cmbPeriodo, 
		rsector: txtSector, 
		rmanzan: txtManzana, 
		reflote: txtLote,
		cidtipo: cmbTipo,
		nrficha: txtNficha,
		nombr01: txtTitular,
		nombr02: txtZse,
		nombr03: txtHub
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});

}

function selectSubFicha(obj){
	$('.chkSubFich').attr('checked',false);	
	$(obj).attr('checked',true);	
}

function newFicha(){
	var cidTipo = "";
	var params = "";
	
	cidTipo = parseInt($('.chkSubFich:checked').val());
	if(!isNaN(cidTipo)){
		switch(cidTipo){
			case 1:
				//Ficha Individual
				var idficha = '';
				var cidtipo = '';
				var nrficha = '';
				var grid = Ext.getCmp('xgridCMficha');
				var store = grid.getStore();
				if (grid.getSelectionModel().selected.length == 1)
				{		
					var sm = grid.getSelectionModel().getSelection();
					var rec = store.getAt(store.indexOf(sm[0]));					
					idficha = rec.get('idficha');
					cidtipo = rec.get('cidtipo');
					nrficha = rec.get('nrficha');
				}
				
				if(cidtipo=='04'){
					var showResult = function(btn){
						if(btn=='yes'){
							params = "?periodo="+$('#cmbPeriodo').val()+"&idficbco="+idficha;
							showPopup('catasficind/index'+params,'#popcatasficind','1020','590','Nueva Ficha');	
						}
					};
					confirmMessage('Nueva Ficha Individual','Agregar una Ficha Individual a la Ficha de Bien Com&uacute;n N&deg; '+nrficha+'?',showResult);
				}
				else{
					params = "?periodo="+$('#cmbPeriodo').val();
					showPopup('catasficind/index'+params,'#popcatasficind','1020','590','Nueva Ficha');	
				}				
			break;
			case 2:
				//Ficha Cotitular
				var grid = Ext.getCmp('xgridCMficha');
				var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
				if(selectedRecordsArray.length>0){
					var idficha = "";
					var cidtipo = "";
					var nrficha = "";
					Ext.each(selectedRecordsArray, function (item) { 
						idficha = item.data.idficha;
						cidtipo = item.data.cidtipo;
						nrficha = item.data.nrficha;
					});
					if(cidtipo=='01'){						
						var showResult = function(btn){
                    		if(btn=='yes'){
								params = "?periodo="+$('#cmbPeriodo').val()+"&idficind="+idficha;
								showPopup('catasficcot/index'+params,'#popcatasficcot','985','580','Nueva Ficha');
							}
						};
						confirmMessage('Nueva Ficha Cotitular','Agregar una Ficha Cotitular a la Ficha Individual N&deg; '+nrficha+'?',showResult);
					}
					else
						infoMessage('Nueva Ficha Cotitular','Debe seleccionar una ficha individual!');
				}
				else{
					infoMessage('Nueva Ficha Cotitular','Debe seleccionar una ficha individual!');
				}
			break;
			case 3: 
				//Ficha Económica								
				var grid = Ext.getCmp('xgridCMficha');
				var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
				if(selectedRecordsArray.length>0){
					var idficha = "";
					var cidtipo = "";
					var nrficha = "";
					Ext.each(selectedRecordsArray, function (item) { 
						idficha = item.data.idficha;
						cidtipo = item.data.cidtipo;
						nrficha = item.data.nrficha;
					});
					if(cidtipo=='01'){						
						var showResult = function(btn){
                    		if(btn=='yes'){
								params = "?periodo="+$('#cmbPeriodo').val()+"&idficind="+idficha;
								showPopup('catasficeco/index'+params,'#popcatasficeco','1000','580','Nueva Ficha');
							}
						};
						confirmMessage('Nueva Ficha Econ&oacute;mica','Agregar una Ficha Econ&oacute;mica a la Ficha Individual N&deg; '+nrficha+'?',showResult);
					}
					else
						infoMessage('Nueva Ficha Econ&oacute;mica','Debe seleccionar una ficha individual!');
				}
				else{
					infoMessage('Nueva Ficha Econ&oacute;mica','Debe seleccionar una ficha individual!');
				}
			break;			
			case 4:
				//Ficha Bienes Comunes
				var idficha = '';
				var cidtipo = '';
				var nrficha = '';
				var grid = Ext.getCmp('xgridCMficha');
				var store = grid.getStore();
				if (grid.getSelectionModel().selected.length == 1)
				{		
					var sm = grid.getSelectionModel().getSelection();
					var rec = store.getAt(store.indexOf(sm[0]));					
					idficha = rec.get('idficha');
					cidtipo = rec.get('cidtipo');
					nrficha = rec.get('nrficha');
				}
				
				if(cidtipo=='04'){
					var showResult = function(btn){
						if(btn=='yes'){
							params = "?periodo="+$('#cmbPeriodo').val()+"&idficbco="+idficha;
							showPopup('catasficbco/index'+params,'#popcatasficbco','1020','590','Nueva Ficha');
						}
					};
					confirmMessage('Nueva Ficha de Bien Com&uacute;n','Agregar una Ficha de Bien Com&uacute;n a la Ficha de Bien Com&uacute;n N&deg; '+nrficha+'?',showResult);
				}
				else{
					params = "?periodo="+$('#cmbPeriodo').val()+"&idficbco="+idficha;
					showPopup('catasficbco/index'+params,'#popcatasficbco','1020','590','Nueva Ficha');
				}
			break;
		}
	}
	else{
		infoMessage('Nueva Ficha','Seleccione el tipo de ficha!');
	}
}

function editFicha(cidtipo,idficha){
	switch(cidtipo){
		case '01':
			showPopup('catasficind/index?idficha='+idficha,'#popcatasficind','1020','590','Editar Ficha');
		break;
		case '02':
			showPopup('catasficcot/index?idficha='+idficha,'#popcatasficcot','985','580','Editar Ficha');
		break;
		case '03':
			showPopup('catasficeco/index?idficha='+idficha,'#popcatasficeco','1000','580','Editar Ficha');
		break;
		case '04':
			showPopup('catasficbco/index?idficha='+idficha,'#popcatasficbco','1020','590','Editar Ficha');
		break;
	}
}

function printFicha(){
	var grid = Ext.getCmp('xgridCMficha');
	var store = grid.getStore();
	if (grid.getSelectionModel().selected.length == 1)
	{		
		var nreporte = "";
		var sm = grid.getSelectionModel().getSelection();
		var rec = store.getAt(store.indexOf(sm[0]));					
		idficha = rec.get('idficha');
		cidtipo = rec.get('cidtipo');
		//alert(cidtipo);
		
		switch(cidtipo){
			case '01': nreporte = "rpt_catasficind"; break;
		}
		
		showPopupReport('tipo=pdf&nombrereporte='+nreporte+'&param=idficha^'+idficha+'|','#poprptficha',700,600,'Ficha Catastral','catas');
	}
	else
		infoMessage('Imprimir Ficha','Debe seleccionar una ficha!');
}

//Eventos Ficha Individual
function actDec(est){
	if(est){
		$('input[dir=dfirmas]').removeClass('cajacoff');
		$('input[dir=dfirmas]').addClass('cajac');
		$('input[dir=dfirmas]').attr('readonly',false);
	}
	else{
		$('input[dir=dfirmas]').removeClass('cajac');
		$('input[dir=dfirmas]').addClass('cajacoff');
		$('input[dir=dfirmas]').attr('readonly',true);
	}
}

function actSup(est){
	if(est){
		$('input[dir=sfirmas]').removeClass('cajacoff');
		$('input[dir=sfirmas]').addClass('cajac');
		$('input[dir=sfirmas]').attr('readonly',false);
	}
	else{
		$('input[dir=sfirmas]').removeClass('cajac');
		$('input[dir=sfirmas]').addClass('cajacoff');
		$('input[dir=sfirmas]').attr('readonly',true);		
	}
}

function actTec(est){
	if(est){
		$('input[dir=tfirmas]').removeClass('cajacoff');
		$('input[dir=tfirmas]').addClass('cajac');
		$('input[dir=tfirmas]').attr('readonly',false);
	}
	else{
		$('input[dir=tfirmas]').removeClass('cajac');
		$('input[dir=tfirmas]').addClass('cajacoff');
		$('input[dir=tfirmas]').attr('readonly',true);		
	}
}

function actVer(est){
	if(est){
		$('input[dir=vfirmas]').removeClass('cajacoff');
		$('input[dir=vfirmas]').addClass('cajac');
		$('input[dir=vfirmas]').attr('readonly',false);		
	}
	else{
		$('input[dir=vfirmas]').removeClass('cajac');
		$('input[dir=vfirmas]').addClass('cajacoff');
		$('input[dir=vfirmas]').attr('readonly',true);
	}
}

function getDC(){
	var dc01 = isNaN(parseInt($('#idubigeo').val().trim().substring(0,1))) ? 0 : parseInt($('#idubigeo').val().trim().substring(0,1));
	var dc02 = isNaN(parseInt($('#idubigeo').val().trim().substring(1,2))) ? 0 : parseInt($('#idubigeo').val().trim().substring(1,2));
	var dc03 = isNaN(parseInt($('#idubigeo').val().trim().substring(2,3))) ? 0 : parseInt($('#idubigeo').val().trim().substring(2,3));
	var dc04 = isNaN(parseInt($('#idubigeo').val().trim().substring(3,4))) ? 0 : parseInt($('#idubigeo').val().trim().substring(3,4));
	var dc05 = isNaN(parseInt($('#idubigeo').val().trim().substring(4,5))) ? 0 : parseInt($('#idubigeo').val().trim().substring(4,5));
	var dc06 = isNaN(parseInt($('#idubigeo').val().trim().substring(5,6))) ? 0 : parseInt($('#idubigeo').val().trim().substring(5,6));
	
	var dc07 = isNaN(parseInt($('#rsector').val().trim().substring(0,1))) ? 0 : parseInt($('#rsector').val().trim().substring(0,1));
	var dc08 = isNaN(parseInt($('#rsector').val().trim().substring(1,2))) ? 0 : parseInt($('#rsector').val().trim().substring(1,2));
	
	var dc09 = isNaN(parseInt($('#rmanzan').val().trim().substring(0,1))) ? 0 : parseInt($('#rmanzan').val().trim().substring(0,1));
	var dc10 = isNaN(parseInt($('#rmanzan').val().trim().substring(1,2))) ? 0 : parseInt($('#rmanzan').val().trim().substring(1,2));
	var dc11 = isNaN(parseInt($('#rmanzan').val().trim().substring(2,3))) ? 0 : parseInt($('#rmanzan').val().trim().substring(2,3));
	
	var dc12 = isNaN(parseInt($('#reflote').val().trim().substring(0,1))) ? 0 : parseInt($('#reflote').val().trim().substring(0,1));
	var dc13 = isNaN(parseInt($('#reflote').val().trim().substring(1,2))) ? 0 : parseInt($('#reflote').val().trim().substring(1,2));
	var dc14 = isNaN(parseInt($('#reflote').val().trim().substring(2,3))) ? 0 : parseInt($('#reflote').val().trim().substring(2,3));
	
	var dc15 = isNaN(parseInt($('#redific').val().trim().substring(0,1))) ? 0 : parseInt($('#redific').val().trim().substring(0,1));
	var dc16 = isNaN(parseInt($('#redific').val().trim().substring(1,2))) ? 0 : parseInt($('#redific').val().trim().substring(1,2));
	
	var dc17 = isNaN(parseInt($('#rentrad').val().trim().substring(0,1))) ? 0 : parseInt($('#rentrad').val().trim().substring(0,1));
	var dc18 = isNaN(parseInt($('#rentrad').val().trim().substring(1,2))) ? 0 : parseInt($('#rentrad').val().trim().substring(1,2));
	
	var dc19 = isNaN(parseInt($('#refpiso').val().trim().substring(0,1))) ? 0 : parseInt($('#refpiso').val().trim().substring(0,1));
	var dc20 = isNaN(parseInt($('#refpiso').val().trim().substring(1,2))) ? 0 : parseInt($('#refpiso').val().trim().substring(1,2));
	
	var dc21 = isNaN(parseInt($('#refunid').val().trim().substring(0,1))) ? 0 : parseInt($('#refunid').val().trim().substring(0,1));
	var dc22 = isNaN(parseInt($('#refunid').val().trim().substring(1,2))) ? 0 : parseInt($('#refunid').val().trim().substring(1,2));
	var dc23 = isNaN(parseInt($('#refunid').val().trim().substring(2,3))) ? 0 : parseInt($('#refunid').val().trim().substring(2,3));
	
	var dc00 = dc01+dc02+dc03+dc04+dc05+dc06+dc07+dc08+dc09+dc10+dc11+dc12+dc13+dc14+dc15+dc16+dc17+dc18+dc19+dc20+dc21+dc22+dc23;
	
	$('#dc_cata').val(dc00%9);
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

function veryFicBC(){
	if(parseInt($('#idficbco').val())>0){
		$('.veryFBC').attr('disabled',true);
		$('.veryFBC').css({'background-color': '#dfe9f6'});
	}	
}
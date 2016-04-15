Ext.require( [ '*' ]);

Ext.onReady(function() {

	Ext.QuickTips.init();	
	
	Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));

	// Creamos la pantalla principal
	var viewport = Ext.create('Ext.Viewport', {
		id : 'view',
		layout : 'border',
		items : [

		Ext.create('Ext.Component', {
			region : 'north',
			height : 65,
			contentEl : 'headlayout'
		}), {
			region : 'south',
			contentEl : 'footlayout',
			split : true,
			height : 60,
			minSize : 100,
			maxSize : 200,
			collapsible : true,
			collapsed : true,
			title : 'Copyright &copy; 2013',
			margins : '0 0 0 0'
		}, {
			region : 'west',
			stateId : 'navigation-panel',
			id : 'west-panel',
			title : 'Men&uacute; Principal',
			split : true,
			width : 200,
			minWidth : 175,
			maxWidth : 400,
			collapsible : true,
			animCollapse : true,
			margins : '0 0 0 5',
			layout : 'accordion'
			
		},

		Ext.create('Ext.tab.Panel', {
			id : 'contentTab',
			region : 'center',
			deferredRender : false,
			activeTab : 0,
			margins : '0 5 0 0',
			items : [ {
				id : 'content',
				contentEl : 'contentlayout',
				title : 'Bienvenidos',
				closable : false,
				autoScroll : true,
				style : 'margin: 10px;',
				plain : true,
				loader : {
					autoLoad : true,
					url : urljs + 'main/index'
				}
			} ]
		}) ]
	});
	
	var myMask = new Ext.LoadMask(Ext.getCmp('west-panel'), {msg:"Cargando..."});
	myMask.show();	
	
	Ext.Ajax.request({
        url : urljs + 'main/menu',
        success :function(response){
			//alert(response.responseText);			
			
			var clickFun = function(view, record, item, index, event) {
				getNode(record.data);
				//alert(record.data.cls);
			};
			
			Ext.getCmp('west-panel').add(eval(response.responseText));
			myMask.hide();
			
			//Apertura caja
			verificaApertura();
        }
    });
    
	// Creamos toolbar usuario
	var tb = Ext.create('Ext.toolbar.Toolbar', {
		style : 'width:150px'
	});
	tb.render('toolbarMain');

	tb.add( {
		text : userjs,
		iconCls : 'user',
		menu : {
			xtype : 'menu',
			plain : true,
			items : {
				xtype : 'buttongroup',
				title : 'Opciones de Usuario',
				columns : 1,
				defaults : {
					xtype : 'button',
					scale : 'large',
					iconAlign : 'left'
				},
				items : [
						{
							text : 'Contrase&ntilde;a',
							scale : 'small',
							width : 130,
							handler : function() {
								var data = {id:'', hrefTarget:urljs + 'mantusuario/changepass', cls:'Cambiar Contrase&ntilde;a'};
								getNode(data);
							}
						},
						{
							text : 'Cerrar Sesi&oacute;n',
							scale : 'small',
							width : 130,
							handler : function() {
								var showResult = function(btn) {
									if (btn == 'yes')
										location.href = 'index/logout';
								};
								confirmMessage('Atenci&oacute;n',
										'Seguro de salir del sistema?',
										showResult);
							}
						} ]
			}
		}
	});

	// Establecemos el fondo
	$('#content-body').css( {
		'background-image' : 'url(' + urljs + 'img/bg.jpg)'
	});	

});

function verificaApertura(){

	Ext.Ajax.request({
        url : urljs + 'tesocierre/consultaapertura',
        success :function(response){
			//alert(response.responseText);
			if(response.responseText=='1'){
				$("div").find("[data-qtip='02.01.00']").show();
				$("div").find("[data-qtip='02.02.00']").show();
				$("div").find("[data-qtip='02.03.00']").show();
				$("div").find("[data-qtip='02.08.00']").show();
				$("div").find("[data-qtip='02.09.00']").show();
				return;
			}
			
			if(response.responseText=='0'){
				$("div").find("[data-qtip='02.01.00']").hide();
				$("div").find("[data-qtip='02.02.00']").hide();
				$("div").find("[data-qtip='02.03.00']").hide();
				$("div").find("[data-qtip='02.08.00']").hide();
				$("div").find("[data-qtip='02.09.00']").hide();
				return;
			}
        }
    });
	
}

function getNode(obj) {
	var id = obj.id;
	var ruta = obj.hrefTarget;
	var title = obj.cls;
	
	// Limpiamos el fondo
	$('#content-body').css( {
		'background-image' : ''
	});

	Ext.getCmp('content').update('');
	Ext.getCmp('content').setTitle(title);

	var myMask = new Ext.LoadMask(Ext.getCmp('content'), {
		msg : "Cargando..."
	});
	myMask.show();

	$('#content-body').load(ruta, function() {
		myMask.hide();
		myMask.destroy();
		//Para los accesos
		$('#hdnAcceso').val(obj.qtip);
		verificaAccesos(userjs,$('#hdnAcceso').val());
	});
}
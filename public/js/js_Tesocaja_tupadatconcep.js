$('#txtcantidad').blur(function(){
	mostrarTotal();
});
$('#txtprecio').blur(function(){
	mostrarTotal();
});
$('#txtprecio').keyup(function(){
	mostrarTotal();
});
$('#txtcantidad').keyup(function(){
	mostrarTotal();
});
function mostrarTotal(){
    var precio=Number($('#txtprecio').val());
	var cantidad=Number($('#txtcantidad').val());
	var totalpago=precio*cantidad;
	
	$('#txttotal').val(Math.round((totalpago)*100)/100).autoNumeric();
}

Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
	Ext.define('TesoDetaTupa', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'tipo'},
            {name: 'descorta'},
            {name: 'descripcion'},
			{name: 'monto'},
			{name: 'uit'},
			{name: 'tiporec'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'TesoDetaTupa',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'tesocaja/detalletupa?tiporec='+tiporec,
            reader: {
               type: 'json',
               root: 'rows'
            },
			actionMethods: {
                create : 'POST',
                read   : 'POST',
                update : 'POST',
                destroy: 'POST'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridTesoDetaTupa',
        store: store,
		height: '200px',
        title: 'CRITERIOS',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
    	selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode : 'SINGLE',
			//checkOnly: true,
			//showHeaderCheckbox :false
        	//mode : 'MULTI'//seleccionar una sola fila mode : 'SINGLE'  -------------- varios mode:'MULTI'
        }),
        columns: [ 

        {
            text: 'Desc.Corta',
            width: 150,            
            dataIndex: 'descorta'
        },{        
        	text: 'Descripci&oacute;n',
        	flex: 1,
            dataIndex: 'descripcion'
        },{        
        	text: 'Monto Fijo',
        	hidden:	false,
            dataIndex: 'monto'
        },{        
        	text: '% UIT',
        	hidden:	false,
            dataIndex: 'uit'
        },{        
        	text: 'tipo',
        	hidden:	true,
            dataIndex: 'tipo'
        },{        
        	text: 'tiporec',
        	hidden:	true,
            dataIndex: 'tiporec'
        }],
        listeners : {
            itemclick: function(dv, record, item, index, e) {
                //alert(record.get('tipo'));
				$('#tiporecoculto').val(record.get('tiporec'));
				$('#tipooculto').val(record.get('tipo'));
				$('#txtprecio').val(record.get('monto'));
				mostrarTotal();
            }
		}		
    });
    
    grid.render('gridTesoDetaTupa');
	mostrarDetalleTupa();	
});

function mostrarDetalleTupa(){
	var oculto=$('#oculto').val();
	
	var grid = Ext.getCmp('xgridTesoDetaTupa');
	var store = grid.getStore();
	store.removeAll();
	var proxy = store.getProxy();
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {oculto: oculto};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){},
	});
}

function getcancelarTupaNuevo(){

	var rdformaPago = $('input[name*="rdformaPago"]:checked').val();
	var nrodocu=$("#nrodocu").val();
	var contribuyente=$("#contribuyente").val();
	var referencia=$('#referencia').val();
	var observacion=$('#observacion').val();
	
	var	cmbtarjeta	= $('#cmbtarjeta').val();
	var	tipooculto	= $('#tipooculto').val();
	var	tiporecoculto	= $('#tiporecoculto').val();
	var	txtprecio	= $('#txtprecio').val().replace(',','');
	var	txtcantidad	= $('#txtcantidad').val().replace(',','');
	var	txttotal	= $('#txttotal').val().replace(',','');	
	var gridDetalle = Ext.getCmp('xgridTesoDetaTupa');
	
	if(contribuyente==''){
		infoMessagex('SIGMUN-Tesoreria','Ingrese en Nombre del contribuyente','#contribuyente');
		return;
	}
	else		
	if(gridDetalle.getSelectionModel().getSelection().length<=0){
		infoMessage('SIGMUN-Tesoreria','Debe seleccionar al menos un item');
		return;
	}
	
	if(txttotal > 0){
			var showResult = function(btn){
			if(btn=='yes'){
			
				$("#btnPagar").attr('disabled',true);
				$("#rdformaPago1").attr('disabled',true);
				$("#rdformaPago2").attr('disabled',true);
				$("#rdformaPago3").attr('disabled',true);

				//console.log(idList);
				
				Ext.Ajax.request({
		            url: urljs + "tesopagosdiv/registrapagotupa02",
		            method: "POST",
		            params: {cmbtarjeta:cmbtarjeta,tipooculto:tipooculto,txtprecio:txtprecio,txtcantidad:txtcantidad,txttotal:txttotal,rdformaPago:rdformaPago,nrodocu:nrodocu,contribuyente:contribuyente,referencia:referencia,idx:1,tiporecoculto:tiporecoculto,observacion:observacion},
		            success: function(response){
						$("#div_pintaboton").html(response.responseText);
		            },
		            failure: function(response, opts){
		            	infoMessage('Contribuyentes','Error al intentar cobrar... ');
		            }
		        });
			}
 		};
 		confirmMessage('Contribuyentes','Seguro de Cancelar?',showResult);
	}else{
		infoMessage('Contribuyentes','Ingresar Monto a cobrar!');
	}
}
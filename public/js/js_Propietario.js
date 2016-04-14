$(function(){
	$("input[type='button']").button();
	$("#btnClose").click(function() {
		closePopup('#popupprop');
	});
	$('#frmpropietario').validate({
		rules: {
	
		//'txtDpto': { required: false, digits: false },
		//'txtMza': { required: false, digits: false }
	},
		messages: {
		
//		'txtDpto': { required: '', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
//		'txtMza': { required: '', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
	},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
		
		}
	});
	
});

	
	Ext.define('Propietario', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'codigo'},
			{name: 'Nombre'},
			{name: 'Porcen'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Propietario',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridPropietario',
		columnLines: true,
		store: store,
		width: '700px',
		height: '170px',        
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "rentas/gridpropietarios",
						  method: "POST",
						  params: {cod_pred: $('#divCodpred').html()},
						  success: function(response){			
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idinsta + '|';
									addRowsPropietario(data[i]);
								}
						  }
					});					
            	}
            }
    	},
        columns: [ 
        {
            text: 'Codigo',
			width: 60,
			dataIndex: 'codigo'
        },{
            text: 'Nombre',
			width: 541,
			dataIndex: 'Nombre'
        },{
            text: '% Propiedad',
            width: 84,		
            dataIndex: 'Porcen'		
        }]
    });
    
    grid.render('gridPropietario');
	
function addRowsPropietario(data){
	var grid = Ext.getCmp('xgridPropietario');
	var store = grid.getStore();
	var r = Ext.create('Propietario', {
		codigo: data.codigo,
		Nombre: data.Nombre,
		Porcen: data.Porcen
	});
	store.insert(store.data.length, r);
}

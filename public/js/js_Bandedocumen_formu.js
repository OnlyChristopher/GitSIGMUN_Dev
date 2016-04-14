/*Ext.onReady(function(){
    Ext.QuickTips.init();
    
    Ext.define('Envios', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'login'},
            {name: 'usuario', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Envios',
        data: [
            ['JUFLO','Juan Flores'],
            ['JORRAM','Jorge Ramos'],
            ['JUMAR','Julio Márquez'],
            ['ROSHUA','Rosa Huamaní'],
            ['KARTO','Karla Torres'],
        ]
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id:'xgridEnvios',
        store: store,
        width: '300px',
        //height: '200px',
        selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),          
        {
            text: 'Login',
            width: 100,            
            dataIndex: 'login'
        },{        
        	text: 'Usuario',
        	width: 151,
            dataIndex: 'usuario'
        }]
    });
    
    grid.render('gridEnvios');
    
    $("input[type='button']").button();
    
    Ext.getCmp('xgridEnvios').getEl().hide();
    $('#fieldobservacion').hide();
    //$('#divoculto').hide();
});
*/


	
	
$(function(){
	//Valida y envía form contribuyente
	$('#frmbandeja').validate({
		rules: {
		//'txtNomPoblado': 'required',
		'cmbAreades':'required',
		'cmbSituacion':'required',
		'txtareas':'required'
		//'txtNumDoc': { required: true, digits: true }
		},
		messages: {
		//'txtNomPoblado': 'Debe ingresar el nombre',
		'cmbAreades':'Debe seleccionar un area',
		'cmbSituacion':'Debe seleccionar una Situación',
		'txtareas': 'Debe ingresar uns observación'
		//'cmbZona':'Debe seleccionar una zona'
		//'txtNumDoc': { required: 'Ingrese el documento', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){
			var myMask = new Ext.LoadMask(Ext.get('popup_popupenvios'), {msg:"Guardando..."});			
			$.ajax({     
				type: "POST",     
				url: "bandedocumen/grabar",
				data: $('#frmbandeja').serializeObject(),     
				success: function(data) { 
					//alert(data);
					infoMessage('Guardando Documentos',data);
					closePopup('#popupenvios');
					Ext.getCmp('xgridBandeja').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}		 
	});
	
});


$('#cmbAreades').change(function(){
    var areadestino=($(this).val());
   
    $('#txtAreades').val(areadestino);
    
    $.ajax({     
		type: "POST",     
		url: "bandedocumen/consusario",
		data: 'area='+areadestino,
		success: function(data) { 
			$('#cmbUsuario').html(data);		
			$('#txtUsuario').val('');
			//alert(data);
		}
	});
});


$('#cmbUsuario').change(function(){
    var usuario=($(this).val());
    $('#txtUsuario').val(usuario);
});


$(function(){

	$.ajax({     
		type: "POST",     
		url: "bandedocumen/fecha",
		//data: '',
		success: function(data) { 
			$('#txtSalida').val(data);			
			//alert(data);
		}
	});
});




/*
$('#cmbAreades').change(function(){
	alert($(this).val());   
});
*/
/*
var partida= combo;
$('#txtPartida').val(partida);
*/


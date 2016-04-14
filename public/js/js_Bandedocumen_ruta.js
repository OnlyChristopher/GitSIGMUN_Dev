Ext.onReady(function(){
    Ext.QuickTips.init();
    
    Ext.define('Ruta', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'orden', type: 'string'},
            {name: 'areaorigen', type: 'string'},
            {name: 'usuarioorigen', type: 'string'},
            {name: 'fechaorigen', type: 'string'},
            {name: 'areadestino', type: 'string'},
            {name: 'usuariodestino', type: 'string'},
            {name: 'fechadestino', type: 'string'},
            {name: 'tiempo', type: 'string'},
            {name: 'observacion', type: 'string'},
            {name: 'situacion', type: 'string'},
			{name: 'frecepcion', type: 'string'}
        ]
    });
    	    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Ruta',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'bandedocumen/detalleruta?valor='+valor+'&num='+num+'&ano='+ano,  //nuevooo
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id:'xgridRuta',
        store: store,
        //width: '300px',
        height: '200px',
        //selModel: Ext.create('Ext.selection.CheckboxModel'), quitarle check
        columns: [ 
           // Ext.create('Ext.grid.RowNumberer'),  quitarle numer          
        {        
        	text: 'NºPaso',
        	width: 30,
            dataIndex: 'orden'
        },{
            text: 'Area Origen',
            width: 270,            
            dataIndex: 'areaorigen'
        },{        
        	text: 'Usuario Origen',
        	width: 100,
            dataIndex: 'usuarioorigen'
        },{        
        	text: 'Fecha Envio',
        	width: 130,
            dataIndex: 'fechaorigen'
        },{        
        	text: 'Area Destino',
        	width: 250,
            dataIndex: 'areadestino'
        },{        
        	text: 'Usuario Destino',
        	width: 100,
            dataIndex: 'usuariodestino'
        },{        
        	text: 'Fecha Destino',
        	width: 130,
            dataIndex: 'fechadestino'
        },{        
        	text: 'Tiempo',
        	width: 80,
            dataIndex: 'tiempo',
			hidden:true
        },{        
        	text: 'Fecha Recepcion',
        	width: 130,
            dataIndex: 'frecepcion'
        },{        
        	text: 'Observacion',
        	width: 80,
            dataIndex: 'observacion',
            hidden:true
        },{        
        	text: 'Situacion',
        	width: 80,
            dataIndex: 'situacion',
            hidden:true
        }],
        listeners : {
        	itemclick: function(dv, record, item, index, e) {
        		$('#txtobsruta').val(record.get('observacion'));
        		$('#txtSituacionruta').val(record.get('situacion'));
        		
        		record.get('observacion');
        		record.get('situacion');
        	}
        }
    });
    grid.render('gridRuta');
});


/*
	
$(function(){
	//Valida y envía form contribuyente
	$('#frmruta').validate({
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
				url: "bandedocumen/grabarii",
				data: $('#frmruta').serializeObject(),     
				success: function(data) { 
					//alert(data);
					infoMessage('Guardando Documentos',data);
					closePopup('#popupruta');
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

*/


/*
$('#cmbAreades').change(function(){
	alert($(this).val());   
});
*/
/*
var partida= combo;
$('#txtPartida').val(partida);
*/


var contadorGridFisca=0;

Ext.onReady(function(){
	$("input[type='button']").button();
	
	$("#btnCancelCartareq").click(function(){
		closePopup('#popupcartareq');
	});

	$("#btnSaveCartareq").click(function(){
       
       goToFormulario("formCartareq");
    
    });

    var estado = $('#hcartaestado').val();
    var idCarta = $('#hcodCarta').val();

    if (idCarta=='') {
       $("#btnActaVisita").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");  
       $("#btnNotificacionCedulon").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");  
       $("#btnCargoNotificacion").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");  
       $("#btnImprimirCartaReq").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");  
    };

    if (estado>1) {
        $("#btnSaveCartareq").attr({"disabled":"disabled","aria-disabled":"true"}).addClass("ui-button-disabled ui-state-disabled");  
    }
	
    //Valida y env�a form contribuyente


	$('#formCartareq').validate({
		ignore: '',
        rules: {
			'txtFecha': 'required',
			'txtHora': 'required',
            'cbAnioDesde' : 'required',
            'cbMotivo' : 'required'
		},

		messages: {
			'txtFecha': 'Debe ingresar fecha',
			'txtHora': 'Debe ingresar hora ',
            'cbAnioDesde' : 'Debe seleccionar a&ntilde;o de inicio',
            'cbMotivo' : 'Debe seleccionar motivo'
		},		
				
		debug: true,
		errorElement: 'div',
		errorContainer: $('#errores'),
		submitHandler: function(form){
		  if (contadorGridFisca==0) {
            infoMessage('Alerta','Debe seleccionar al menos un fiscalizador');
          }else{
                var gridPrediosx = Ext.getCmp('xgridPredios');
                var gridFiscalizadoresx = Ext.getCmp('xgridFiscalizadores');
                
                var selectedRecordsArrayPR = gridPrediosx.getView().getSelectionModel().getSelection();
                var selectedRecordsArrayFI = gridFiscalizadoresx.getView().getSelectionModel().getSelection();
             
                if(selectedRecordsArrayPR.length<1)  {
                    infoMessage('Alerta','Debe seleccionar al menos la direccion de un predio')
                }else if(selectedRecordsArrayFI.length<1){
                    infoMessage('Alerta','Debe seleccionar al menos un fiscalizador');
                }else{

               
                var selection = gridPrediosx.getSelectionModel();
                var lsReciboPR= [];

                for (var i = 0; i<gridPrediosx.store.getCount(); i++) {
                    data =gridPrediosx.store.getAt(i).data;       
                    var confirmado= (selection.isSelected(i))? 1 : 0 ;
                    lsReciboPR.push({
                        'cod_pred' : data.cod_pred,
                        'anexo' : data.anexo,
                        'sub_anexo' : data.sub_anexo,
                        'id_urba' : data.id_urba,
                        'id_via' : data.id_via,
                        'num_manz' : data.num_manz,
                        'num_lote' : data.num_lote,
                        'sub_lote' : data.sub_lote,
                        'num_call' : data.num_call,
                        'num_depa' : data.num_depa,
                        'referenc' : data.referenc,
                        'confirmado' : confirmado,
                        'nueva_dir' : data.nueva_dir
                    });
                };

                var lsReciboFI = [];
                    Ext.each(selectedRecordsArrayFI, function (item) {
                        lsReciboFI.push({
                            "idFiscalizador": item.data.id                            
                        });
                    });
            
                    $.ajax({
                            type: "POST",
                            url : urljs + "cartareq/grabar",
                            data: {
                                    idCarta : $('#hcodCarta').val(),
                                    idContrib : $('#txtCodigoContribuyente').val(),
                                    fechaFiscalizacion : $('#txtFecha').val(),
                                    hora : $('#txtHora').val(),
                                    anioDesde: $('#cbAnioDesde option:selected').html(),
                                    anio: $('#dvAnio').html(),
                                    idMotivo : $('#cbMotivo').val(),
                                    jsonPredios: JSON.stringify(lsReciboPR),
                                    jsonFiscalizadores : JSON.stringify(lsReciboFI)
                                },
                            beforeSend: function() {    
                                saveMessage();
                            },
                            success: function(data){

                                var msj = data.split("|");
                                var mensaje = msj[1];
                    
                                if (msj[0]=="TRUE") {

                                    //registro correctamente
                                    infoMessage('Resultado',mensaje);
                                    closePopup('#popupcartareq');
                                    Ext.getCmp('xgridFisca').getStore().load();
                                }else{
                                    errorMessage('Mensaje',mensaje);
                                }

                            },
                            error: function(data){
                                errorMessage("Error",data);
                            }
                    });

                    
                }
    	   }
		}
		
	});

    
    predios();
            
});

function predios(){

    Ext.define('Tpredios', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'cod_pred'},
            {name: 'anexo'},
            {name: 'sub_anexo'},
            {name: 'id_urba'},
            {name: 'id_via'},
            {name: 'num_manz'},
            {name: 'num_lote'},
            {name: 'sub_lote'},
            {name: 'num_call'},
            {name: 'num_depa'},
            {name: 'referenc'},
            {name: 'dirPredio'},
            {name: 'confirmado'},
            {name: 'nueva_dir'}
        ]});
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Tpredios',
        autoLoad: true,
        pageSize: 10,       
        proxy: {
            type: 'ajax',
            url : "cartareq/listarpredios?codContrib="+$('#txtCodigoContribuyente').val()+"&codCarta="+$('#hcodCarta').val()+"&anio="+$('#dvAnio').html(),
            reader: {
                type: 'json',
                root: 'rows'
            }                
        }
    });
    
    var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });

    var grid = Ext.create('Ext.grid.Panel', {
        id: 'xgridPredios',
        store: store,  
        title: 'Predios - '+$('#dvAnio').html(),                  
        height:300,        
        loadMask: {msg: 'Listado de Predios...'},
        viewConfig: {
            listeners: {                
                beforerefresh: function() {
                    var ind=0;
                    this.store.each(function(record) {
                        if(record.get('confirmado') == 1)
                            Ext.getCmp('xgridPredios').getSelectionModel().select(ind,true,false);
                        ind++;
                    });

                    if (ind==0) {
                        $("#cbMotivo").val("2");
                    }else{
                        $("#cbMotivo").val("1");
                    }
                }
            }
        },
        selModel: Ext.create('Ext.selection.CheckboxModel',{
        mode : 'MULTI',   
        checkOnly: true,
        allowDeselect: false,
        ignoreRightMouseSelection: true
            //mode : 'MULTI'                //seleccionar una sola fila mode : 'SINGLE'  -------------- varios mode:'MULTI'
        }),     
       
        columns: [
       
        {
            text: ' Cod. Predio',
            width:75,
            dataIndex:'cod_pred',
            align: 'center'
        },
        {
            text: ' Anexo',
            width:75,
            dataIndex:'anexo',
            align: 'center'
        },
        {
            text: 'Sub Anexo',
            width:75,
            dataIndex:'sub_anexo',
            align: 'center'
        },
        {
            text: 'Direccion',
            flex:1,            
            dataIndex: 'dirPredio'
        }],
        tbar: [{
            minHeight: 20,
            text: 'Agregar direccion de Predio',
            handler : function(){
                showPopup('cartareq/formupredio','#popupregpredio','600','290','Registrar direccion de nuevo predio');              
            }
        }],
        plugins: [cellEditing]
    });
    
    grid.render('gridPredios');

}

function fiscalizadores(){
    Ext.define('Tfiscalizadores', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id'},
            {name: 'fiscalizador',type: 'string'},
            {name: 'seleccionado',type: 'string'}
        ]});
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Tfiscalizadores',
        autoLoad: true,
        pageSize: 10,       
        proxy: {
            type: 'ajax',
            url : "cartareq/listarfiscalizadores?codCarta="+$('#hcodCarta').val(),
            reader: {
                type: 'json',
                root: 'rows'
            }                
        }

    });
    
     var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });

    var grid = Ext.create('Ext.grid.Panel', {
        id: 'xgridFiscalizadores',
        title: 'Fiscalizadores',
        columnLines: true,
        store: store,
        flex:1,
        height: 300,        
        viewConfig: {
            listeners: {                
                beforerefresh: function() {
                    var ind=0;
                    this.store.each(function(record) {
                        if(record.get('seleccionado') == 1)
                            Ext.getCmp('xgridFiscalizadores').getSelectionModel().select(ind,true,false);
                        
                        ind++;
                    });
                }
            }
        },
        selModel: Ext.create('Ext.selection.CheckboxModel',{
          //mode : 'MULTI'   
            checkOnly: true,
            allowDeselect: false,
            ignoreRightMouseSelection: true
                            //mode : 'MULTI'                //seleccionar una sola fila mode : 'SINGLE'  -------------- varios mode:'MULTI'
        }),     
        columns: [ 
        {
            text: 'Codigo',
            width:100,
            dataIndex: 'id'
        },{
            text: 'Fiscalizador',
            flex:1,
            dataIndex: 'fiscalizador'
        }],
        plugins: [cellEditing]
    });
    
    grid.render('gridFiscalizadores');
}


$("#tab2").click(function(){
    contadorGridFisca++;
    if (contadorGridFisca==1) {
        fiscalizadores();
    };
});
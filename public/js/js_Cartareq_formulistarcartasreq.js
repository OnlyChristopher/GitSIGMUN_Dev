Ext.onReady(function(){
    Ext.QuickTips.init();

    $("#btnNewFisca").button({icons:{primary:"ui-icon-print"}});
    $("#btnCancelFisca").button({icons:{primary:"ui-icon-print"}});
    $("#btnVerActas").button({icons:{primary:"ui-icon-print"}});

    $("#btnCancelFisca").click(function(){
		closePopup('#popuplistarcartasreq');
	});
    $("input[type='button']").button(); 
    busFisca();    

    
});

function busFisca(){
        Ext.define('fisca', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'idCarta'},
                {name: 'nroCarta', type: 'string'},
                {name: 'anio', type: 'string'},
                {name: 'fecInspec', type: 'string'},
                {name: 'motivo', type: 'string'},
                {name: 'estado',type: 'string'}
            ]
        });
        
        var store = Ext.create('Ext.data.Store', {
            model: 'fisca',
            autoLoad: true,
            pageSize: 10,       
            proxy: {
                type: 'ajax',
                url : 'cartareq/consultacartasreq?codigo='+$('#codContrib').val(),
                reader: {
                   type: 'json',
                   root: 'rows'
                }
            }
        });

        var grid = Ext.create('Ext.grid.Panel', {
            id: 'xgridFisca',
            store: store,        
            title: 'Lista de cartas de presentaci&oacute;n y req. por contribuyente',
            height: '260px',            
            columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
            {              
                hidden: true,
                dataIndex: 'idCarta'

            },{
                text: 'NroCarta',
                dataIndex: 'nroCarta',
                width:80                
            },{
                text: 'A&ntilde;o',                
                width:80 ,
                dataIndex: 'anio'
            },{
                text: 'Fecha de Inspeccion',
                width:150,
                dataIndex: 'fecInspec'
            },{
                text: 'Motivo',
                width:150,
                dataIndex: 'motivo'
            },{
                hidden:true,
                dataIndex: 'estado'
            },{
                xtype:'actioncolumn',
                width:82,                
                items: [{
                    icon: urljs + 'img/edit.png',
                    tooltip: 'Editar',
                    handler: function(grid, rowIndex, colIndex) {
                       var rec = grid.getStore().getAt(rowIndex);
                       //var estado = rec.get('estado'); 
                       //var msj='La carta de requerimiento seleccionada , no se puede modificar debido a que : ';
                      /* 
                       if(estado==1){
                            showPopup('cartareq/formu?codCarta='+rec.get('idCarta'),'#popupcartareq','840','570','Editar - CARTA DE PRESENTACION Y REQUERIMIENTO DE DOCUMENTACION');                            
                       }else{
                            if (estado==2) {
                            msj=msj+' <br> Se inici&oacute el proceso de fiscalizacion.';
                           }else if(estado==3){
                                msj=msj+' <br> La carta ya fue fiscalizada.';
                           }

                           infoMessage('Atenci&oacute;n',msj);
                       }  */                    
                     showPopup('cartareq/formu?codCarta='+rec.get('idCarta'),'#popupcartareq','840','570','Editar - CARTA DE PRESENTACION Y REQUERIMIENTO DE DOCUMENTACION');  
                    }
                },/*{
                    icon: urljs +'img/ficha.png',
                    tooltip: 'Ficha de fiscalizaci&oacute;n',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                        var estado = rec.get('estado');
                        if (estado==1) {
                            var showResult = function(btn){
                            if(btn=='yes')
                                $.ajax({
                                            type: "POST", 
                                            url: 'fiscalizacion/iniciarinspeccion',
                                            data: 'idCarta='+rec.get('idCarta'),
                                            success: function(data){  
                                                if (data!='') {
                                                infoMessage('Mensaje',data);            
                                                }else{
                                                  showPopup('fiscalizacion/formu?idCarta='+rec.get('idCarta')+'&idContrib='+$('#codContrib').val(),'#popupaniopredio','900','550','Predios por a&ntilde;o');                                                                                    
                                                }                                                
                                                grid.getStore().load(grid.getStore().currentPage);                                                
                                            } 
                                        });                     
                            };
                            confirmMessage('Alerta','&#191;Est&aacute; seguro de empezar el proceso de fiscalizaci&oacute;n? <br> NOTA: Al empezar el proceso se bloquear&aacute; la edici&oacute;n de la carta de requerimiento.',showResult);    
                        }else{
                            showPopup('fiscalizacion/formu?idCarta='+rec.get('idCarta')+'&idContrib='+$('#codContrib').val(),'#popupaniopredio','900','560','Fiscalizaci&oacute;n');                                 
                        }
                        
                    }
                },*/{
                    icon: urljs +'img/delete.png',
                    tooltip: 'Eliminar carta de requerimiento',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                        var showResult = function(btn){
                            if(btn=='yes')
                                $.ajax({
                                    type: "POST", 
                                    url: 'cartareq/eliminar',
                                    data: 'codCarta='+rec.get('idCarta'),
                                    success: function(data){
                                        infoMessage('Eliminando',data);
                                        
                                        grid.getStore().load(grid.getStore().currentPage);                                  
                                    } 
                                });                     
                        };
                        confirmMessage('Eliminar','Seguro de eliminar la carta de requerimiento numero: '+rec.get('nroCarta')+'-'+rec.get('anio')+' ?',showResult);
                    }
                }/*,{
                    icon: urljs + 'img/printer_rec.png',
                    tooltip: 'Imprimir documentos.',
                    handler: function(grid, rowIndex, colIndex) {
                       var rec = grid.getStore().getAt(rowIndex);
                       //showPopup('cartareq/formu?codCarta='+rec.get('idCarta'),'#popupcartareq','1030','540','Editar - CARTA DE PRESENTACION Y REQUERIMIENTO DE DOCUMENTACION');
                      // showPopup('cartareq/listafiscalizaciones?codigo='+rec.get('id'),'#popupcartareq','985','540','Lista de fiscalizaciones actuales');
                      showPopupReport('schema=&tipo=pdf&nombrereporte=CartaReq_todo&param=idCartaReq^'+rec.get('idCarta'),'reporteTodo',700,600,'Reportes');

                    }
                }*/]
            }],
		        bbar: Ext.create('Ext.PagingToolbar', {
		                id: 'toolbarGridContriFisca',
		                pageSize: 10,
		                store: store,
		                displayInfo: true,
		                displayMsg: 'Mostrando {0} - {1} de {2}',
		                emptyMsg: "No se encontr&oacute; cartas de requerimiento."
		            })
		    });
        
        grid.render('gridFisca');
    }

function detalleRequerimiento(){
	var grid = Ext.getCmp('xgridFisca');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idCarta');
		//alert(getvalue);
		showPopup('fiscalizaciondetalle/index?txtidx='+getvalue,'#popdetallerequerimiento','960','600','Requerimiento');
	}else{
		infoMessage('Requerimiento','Seleccione el requerimiento  ');
	}
			
}
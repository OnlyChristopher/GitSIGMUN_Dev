$(function(){
	$("input[type='button']").button();
	if($('#hdEstadoConvenio').val().trim()=='1' && $('#hdNroRecibo').val().trim()==''){disableButton('#btnGenResol');disableButton('#btnImproResol');}
	if($('#hdEstadoConvenio').val().trim()=='2'){disableButton('#btnGenResol');}
	if($('#hdEstadoConvenio').val().trim()=='3'){disableButton('#btnGenResol');disableButton('#BtnAnularConvenio');disableButton('#btnImproResol');disableButton('#BtnAnularConveniosc');}
	
    Ext.define('Cuotasfr', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'periodo', type: 'string'},			  
			{name: 'imp_insol', type: 'string'},
			{name: 'reaj', type: 'string'},
			{name: 'total', type: 'string'},
			{name: 'fecha_v', type: 'string'},
			{name: 'nro_recibo', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Cuotasfr',
    	autoLoad: true,
        proxy: {
            type: 'ajax',
            url : 'fraccionar/resolcuotas?codigo='+$('#hdCodigofrac').val()+'&convenio='+$('#hdCodConvenio').val(),
            reader: {
               type: 'json',
               root: 'rows'
            }, 
            actionMethods: {
                create : 'POST',
                read   : 'POST',
                update : 'POST',
                destroy: 'POST'
            },
            extraParams: {json: JSON.stringify(data1)}
        }
    });
	
    var gridCuotas = Ext.create('Ext.grid.Panel', {
    	id: 'xgridCuotasfr',
        store: store,        
        title: 'Detalle de Cuotas',
		width: 510,
        height: 310,

        columns: [ 
        {
            text: 'Cuotas',
            flex: 1,      
            dataIndex: 'periodo',
            hidden:	false
        },{        
        	text: 'Importe',
            flex: 1,
        	dataIndex: 'imp_insol',
        	hidden:	false
        },{
            text: 'Reajuste',
            flex: 1,
            dataIndex: 'reaj',
            hidden:	false
        },{
            text: 'Total',
            flex: 1,
            dataIndex: 'total',
        	hidden:	false
        },{
            text: 'Fecha Venc.',
            flex: 1,
            dataIndex: 'fecha_v',
            hidden:	false
        },{
            text: 'Nro. Recibo',
            flex: 1,
            dataIndex: 'nro_recibo',
            hidden:	false
        }]
    });
    
    gridCuotas.render('gridCuotasfr');
  
});

function generaResolucion(cod,frac,url){
	$.ajax({     
				type: "POST",     
				url: urljs + url,
				data: 'codigo='+cod+'&convenio='+frac,     
				success: function(response) { 
					$('#lblEstadoConvenio').html('');
					$('#lblEstadoConvenio').html('Con Resolucion');
					Ext.getCmp('xgridListado').getStore().load();
					infoMessage('Resolucion de Convenio',response);
				},     
				error: function() {
				} 
			});
}
function ReoporteResolucion(codigo,convenio){
	showPopupReport('tipo=pdf&nombrereporte=rpt_conv_resolucion&param=codigo^'+codigo+'|convenio^'+convenio,'pouprptconvenio',700,600,'Resolucion de Convenio');
}
function anularFrac(url,codigo,convenio){
			var showResult = function(btn){
				if(btn=='yes'){
					Ext.Ajax.request({
			            url: urljs + url,
			            method: "POST",
			            params: {codigo: codigo,convenio: convenio},
			            success: function(response){
							infoMessage('Resolucion de Convenio','Se anulo correctamente');
							Ext.getCmp('xgridListado').getStore().load();
							closePopup('#popresol');
							
			            },
			            failure: function(response, opts){
			            	infoMessage('Contribuyentes','Error al intentar cobrar... ');
			            }
			        });
				}
	 		};
	 		
	 		confirmMessage('Contribuyentes','Seguro desea cancelar el fraccionamiento?',showResult);

}
function anularFracsc(url,codigo,convenio){
	var showResult = function(btn){
		if(btn=='yes'){
			Ext.Ajax.request({
	            url: urljs + url,
	            method: "POST",
	            params: {codigo: codigo,convenio: convenio},
	            success: function(response){
					infoMessage('Resolucion de Convenio','Se anulo sin Compensar correctamente');
					Ext.getCmp('xgridListado').getStore().load();
					closePopup('#popresol');
					
	            },
	            failure: function(response, opts){
	            	infoMessage('Contribuyentes','Error al intentar cobrar... ');
	            }
	        });
		}
		};
		
		confirmMessage('Contribuyentes','Seguro desea cancelar el fraccionamiento sin Compensar?',showResult);

}




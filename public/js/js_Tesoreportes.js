Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
    
    Ext.define('recibosModel', {
        extend: 'Ext.data.Model',
        fields: [
                 {name: 'recibo', type: 'string'},
                 {name: 'hora_pago', type: 'string'},
                 {name: 'codigo', type: 'string'},
                 {name: 'nombre', type: 'string'},
                 {name: 'monto', type: 'string'},
                 {name: 'tipo_pago', type: 'string'},
                 {name: 'estado', type: 'string'},
				 {name: 'tipo_operacion', type: 'string'},
				 {name: 'deta_operacion', type: 'string'},
                 {name: 'tipo_rec', type: 'string'}
             ]
    });
    
    var recibosStore = Ext.create('Ext.data.Store', {
    	model: 'recibosModel',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'tesoreportes/recibos',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridRecibos',
        store: recibosStore,
		height: '480px',
        title: 'Parte Diario',
        viewConfig: {
            loadMask: {msg: 'cargando...'},
            stripeRows: false,
            getRowClass: function(record) {
                var clsRow = '';
                switch(record.get('tipo_operacion')){
                    case '2': clsRow='xrow-liceE'; break
                    //case '02': clsRow='xrow-liceE'; break
                    //case '03': clsRow='xrow-liceP'; break
                    //case '01': clsRow='xrow-liceP'; break
                    //case '3.0': clsRow='xrow-obs'; break
                    //case '4.0': clsRow='xrow-obs'; break
                }

                switch(record.get('tipo_rec')){
                    case '12.23': clsRow='xrow-liceR'; break
                    //case '02': clsRow='xrow-liceE'; break
                    //case '03': clsRow='xrow-liceP'; break
                    //case '01': clsRow='xrow-liceP'; break
                    //case '3.0': clsRow='xrow-obs'; break
                    //case '4.0': clsRow='xrow-obs'; break
                }

                return clsRow;

            }
    	},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer',{width: 50}),
        {
            text: 'Operacion',
            width: 80,
			hidden:true,
            dataIndex: 'tipo_operacion'
        },
        {
            text: 'Tipo Rec',
            width: 80,
            hidden:true,
            dataIndex: 'tipo_rec'
        },{
            text: 'Recibo',
            width: 80,
            //renderer : change(),
            dataIndex: 'recibo'
        },{        
        	text: 'Fecha Pago',
            width: 100,
            dataIndex: 'hora_pago'
        },{
            text: 'Operacion',
            width: 70,
            dataIndex: 'deta_operacion'
        },{
            text: 'Codigo',
            width: 70,
            dataIndex: 'codigo'            
        },{
            text: 'Nombre',
            flex: 1,
            dataIndex: 'nombre'
        },{
            text: 'Monto',
            width: 100,
            align:'right',
            dataIndex: 'monto'
        },{
            text: 'Tipo Pago',
            width: 70,
            dataIndex: 'tipo_pago'
        },{
            text: 'Extornado',
            width: 70,
            dataIndex: 'estado'
        },{
            xtype:'actioncolumn',
            width:60,
            items: [/*{
                icon: urljs +'img/delete.png',
                style: 'cursor: pointer',
                tooltip: 'Anular Recibo',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    var showResult = function(btn){
                    	if(btn=='yes')
                    		$.ajax({
                    			type: "POST", 
                    			url: urljs + 'tesocaja/aceptarrecibo',
                    			data: 'txtRecibo='+rec.get('recibo'),
                    			success: function(data){
                    				infoMessage('Eliminando',data);
                    				grid.getStore().load(grid.getStore().currentPage);                    				
                    			} 
                    		});                    	
                    };
                    confirmMessage('Eliminar','Seguro de Anular Recibo '+rec.get('recibo')+'?',showResult);
                }
            },*/{
                icon: urljs +'img/printer_rec.png',
                style: 'cursor:hand',
                tooltip: 'Reimpresión de Recibo',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
					
				if(rec.get('tipo_operacion').trim()=='1'){
					window.open(urljs +"tesoprepago/imprimirrecibopagos?nrorecibo="+rec.get('recibo'),"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no");
					return;
				}
				if(rec.get('tipo_operacion').trim()=='2'){
					window.open(urljs +"tesopagosdiv/imprimirrecibopagostupa?idx=1&nrorecibo="+rec.get('recibo'),"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no"); 
					return;
				}
				if(rec.get('tipo_operacion').trim()=='4'){
					window.open(urljs +"tesopagosdiv/imprimirrecibopagostupa?idx=2&nrorecibo="+rec.get('recibo'),"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no"); 
					return;
				}				

                }
            }]
        }]
    });
    
    grid.render('gridRecibos');
 
});

function change(val) {
	var rec = grid.getStore().getAt(rowIndex);
    if (rec.get('estado')=='NO') {
        return '<span style="color:green;">' + val + '</span>';
    } else if (rec.get('estado')=='SI') {
        return '<span style="color:red;">' + val + '</span>';
    }
    return val;
}

function buscarRecibos(){
	
	var grid = Ext.getCmp('xgridRecibos');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	/*
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	*/
	var tesodesde= $('#tesodesde').val();
	var tesohasta= $('#tesohasta').val();
	var cmbcajas= $('#cmbcajas').val();
	
	proxy.extraParams = {tesodesde: tesodesde, tesohasta: tesohasta,cmbcajas:cmbcajas};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){
			var total_sum=0;
        	store.each(function (rec) { 
				//alert(rec.get('monto')); 
				total_sum += Number(rec.get('monto'));				
			});
			$('#txttotal').val(Math.round(total_sum*100)/100).autoNumeric();
			//$('#txttotal').autoNumeric();
		}
	});	
}
/*
function actualizarContri(){
	var grid = Ext.getCmp('xgridContri');
	var store = grid.getStore();
	store.reload();
}
*/

function generarpdf(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	var cmbcajas= $('#cmbcajas').val();
	//showPopupReport('tipo=pdf&nombrereporte=rpt_emitidos&param=FECDESDE^'+tesodesde+'|FECHASTA^'+tesohasta+'|CAJERO^'+cmbcajas+'|USUARIO^'+userjs,'pouprptemitido',700,600,'Recibos emitidos');
	showPopupReportHtml('tesoreportes/reporterecibosemitidos?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas,'pouprptemitidos','Reporte de Recibos Emitidos');
}

function generarpdfParidas(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	var cmbcajas= $('#cmbcajas').val();
	//alert(userjs);
	
	//showPopupReport('tipo=pdf&nombrereporte=ReportePartidas&param=FECDESDE^'+tesodesde+'|FECHASTA^'+tesohasta+'|CAJERO^'+cmbcajas+'|USUARIO^'+userjs,'pouprptemitido',700,600,'Recibos emitidos');
	showPopupReportHtml('tesoreportes/reportexpartida?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas,'pouprptpartidas','Reporte de Partidas');
}
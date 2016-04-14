Ext.onReady(function(){
    
	$("input[type='button']").button();

    Ext.QuickTips.init();
    
    Ext.define('DetalleValor', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'tipo', type: 'string'},
            {name: 'anio', type: 'string'},
            {name: 'periodo', type: 'string'},
            {name: 'insoluto', type: 'string'},
            {name: 'factorreaj', type: 'string'},
            {name: 'reajustado', type: 'string'},
            {name: 'fatormora', type: 'string'},
            {name: 'mora', type: 'string'},
            {name: 'emision', type: 'string'},
            {name: 'total', type: 'string'}
        ]
    });
    	    
    var store = Ext.create('Ext.data.Store', {
    	model: 'DetalleValor',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
			url : 'administrarvalor/detallevalor?valor='+$('#txtValorVerDocumen').val()+'&num='+$('#txtNumruta').val()+'&ano='+$('#txtAnoruta').val()+'&num_val='+$("#txtValorNumVal").val()+'&ano_val='+$("#txtValorAnnoVal").val(),  //nuevooo
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id:'xgridDetalleValor',
        store: store,
        //width: '300px',
        height: '200px',
		/*columnLines: true,*/
        columns: [ 
		{
        	text: 'Tipo',
        	width: 52,
            dataIndex: 'tipo'
        },{
            text: 'A&ntilde;o',
            width: 40,            
            dataIndex: 'anio'
        },{        
        	text: 'Periodo',
        	width: 55,
            dataIndex: 'periodo'
        },{        
        	text: 'Insoluto',
        	width: 70,
            dataIndex: 'insoluto'
        },{
        	text: 'Fac. Reajuste.',
        	width: 100,
            dataIndex: 'factorreaj'
        },{        
        	text: 'Reajustado',
        	width: 80,
            dataIndex: 'reajustado'
        },{        
        	text: 'Fact. Mora',
        	width: 70,
            dataIndex: 'fatormora'
        },{        
        	text: 'Mora',
        	width: 60,
            dataIndex: 'mora'
        },{       
        	text: 'Emisi&oacute;n',
        	width: 70,
            dataIndex: 'emision'
        },{        
        	text: 'Sub Total',
        	width: 70,
            dataIndex: 'total'
        }]
    });
    grid.render('gridDetalleValor');
});



$("#btnDocumentoImprimir").click(function(){
	var idvalor=$("#txtValorVerDocumen").val();
	var desvalor=$("#txtDesValorVer").val();
//	alert(idvalor+' => '+desvalor);

	if(desvalor=="RD"){
		showPopupReport('tipo=pdf&nombrereporte=rptRD&param=_IDVALOR^'+idvalor,'pouprptvistard',700,600,'Vista Previa Resolución Determinación');
	}else if(desvalor=="OP"){
		showPopupReport('tipo=pdf&nombrereporte=rpt_orpa&param=_IDVALOR^'+idvalor,'pouprptvistaop',700,600,'Vista Previa Orden de Pago');
	}
	
	
	return false;
})




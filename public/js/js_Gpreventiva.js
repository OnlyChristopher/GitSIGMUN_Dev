Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Gpreventiva', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id_notif'},
			{name: 'anno_notif'},
			{name: 'codigo'},
            {name: 'n_notif', type: 'string'},
			{name: 't_notif'},
            {name: 'fec_notif', type: 'string'},
			{name: 'est_subsa'},
			{name: 'anno_area'},
			{name: 'codigo_area'},
			{name: 'tipo_infraccion'},
			{name: 'codigo_infraccion'},
			{name: 'codigo_adicional'},
			{name: 'd_infraccion'},
			{name: 'base_calc'},
			{name: 'factor'},
			{name: 'monto'},
			{name: 'codtipocontri'},
			{name: 'resolucion'}
			
			
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Gpreventiva',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'Gpreventiva/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridGpreventiva',
        store: store,
		//width: '800px',
        title: 'Listado de Preventivas',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'N. Preventiva',
            width: 90, 
			align:'center',
            dataIndex: 'id_notif'
        },{
        	text: 'Anno',
        	width: 60,
			align:'center',
			dataIndex: 'anno_notif'
        },{
        	text: 'Codigo',
        	width: 90,
			align:'center',
            dataIndex: 'codigo'
        },{
        	text: 'Notificacion',
        	width: 100,
			align:'center',
            dataIndex: 'n_notif'
        },{
        	text: 'T. Preventiva',
        	width: 110,
			align:'center',
            dataIndex: 't_notif'
        },{
            text: 'F. Preventiva',
            width: 130,
			align:'center',            
            dataIndex: 'fec_notif'
        },{
        	text: 'est_subsa',
        	width: 150,
			hidden:true,
			dataIndex: 'est_subsa'
        },{
        	text: 'anno_area',
        	width: 110,
			hidden:true,
            dataIndex: 'anno_area'
        },{
        	text: 'codigo_area',
        	width: 110,
			hidden:true,
            dataIndex: 'codigo_area'
        },{
        	text: 'tipo_infraccion',
        	width: 180,
			hidden:true,
            dataIndex: 'tipo_infraccion'
        },{
        	text: 'codigo_infraccion',
        	width: 380,
			hidden:true,
            dataIndex: 'codigo_infraccion'
        },{
            text: 'codigo_adicional',
            width: 60, 
			hidden:true,			
            dataIndex: 'codigo_adicional'
        },{
        	text: 'Infraccion',
        	width: 550,
			dataIndex: 'd_infraccion'
        },{
        	text: 'Base',
        	width: 70,
            dataIndex: 'base_calc'
        },{
        	text: 'Factor',
        	width: 70,
            dataIndex: 'factor'
        },{
        	text: 'Monto',
        	width: 70,
            dataIndex: 'monto'
        },{
        	text: 'T.Contri',
        	width: 70,
			hidden:true,
            dataIndex: 'codtipocontri'
        },{
        	text: 'Resolucion',
        	width: 70,
			hidden:true,
            dataIndex: 'resolucion'
        },
		{
            xtype:'actioncolumn',
            width:82,
			align:'right',
            items: [{
                icon: urljs + 'img/edit.png',
                tooltip: 'Editar Infractor',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
					//showPopup('mantinfractor/formu?codigo='+rec.get('codigo'),'#popinfractor','820','680','Editar Infractor');
					showPopup('preventiva/notificacion?codigo='+rec.get('codigo')+'&anno_notif='+rec.get('anno_notif')+'&id_notif='+rec.get('id_notif')+'&codtipocontri='+rec.get('codtipocontri'),'#popnewmulta','700','450','Editar Preventiva');                     
                },
                getClass: function(value,metadata,record){
                	if (mod=='14')
                		return gridColumnAction(false);
					if(record.get('resolucion')=='1')
						return 'xrow-hidden-button';
                	   
                }
			},{
                icon: urljs + 'img/view.png',
                tooltip: 'Gestion de Resoluciones',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                   	//showPopup('Preventiva/local?codigo='+rec.get('codigo'),'#popcontri','820','450','Locales Notificados');
					var codigo			=rec.get('codigo')
					var n_preventiva	=rec.get('id_notif')
					var anno_preventiva	=rec.get('anno_notif')
					var t_preventiva	=rec.get('t_notif')
					showPopup('gpreventiva/frmgestion?codigo='+codigo+'&npreventiva='+n_preventiva+'&anno='+anno_preventiva+'&tipo='+t_preventiva,'#popgestion','600','380','Gestion de Preventivas');
                },
                getClass: function(value,metadata,record){
                	if (mod=='14')
                		return gridColumnAction(false);
					if(record.get('resolucion')=='1')
						return 'xrow-hidden-button';
                	   
                }
            },{
                icon: urljs + 'img/printer_rec.png',
                style: 'cursor: pointer',
                tooltip: 'Imprimir Preventiva',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
					 var codigo=rec.get('codigo');
					 var anno_notif=rec.get('anno_notif');
					 var id_notif=rec.get('id_notif');
					 var t_notif=rec.get('t_notif');
					 
                    showPopupReport('schema=&tipo=pdf&nombrereporte=rpt_notificamulta&param=CODIGO^'+codigo+'|ANNO^'+anno_notif+'|ID_NOTIF^'+id_notif+'|T_NOTIF^'+t_notif,'pouprptvias',700,600,'Resolucion de Multas Administrativas');
                },
				getClass: function(value,metadata,record){
					if(record.get('resolucion')!='1')
						return 'xrow-hidden-button';
                	   
                }
            }/*,{
                icon: urljs + 'img/diner.png',
                tooltip: 'Pagos Diversos',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Ver predios',rec.get('codigo'));
                    showPopup('tesopagosdiv/index?codigo='+rec.get('codigo')+'&nombre='+rec.get('nombres'),'#popPagosDiv','1200','600','Pagos Diversos');
                },
                getClass: function(value,metadata,record){
                	if (mod=='2')
                		return gridColumnAction(false);
                }
            }*/]
        }],
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
				if (mod=='14')
					showPopup('tesocaja/index?codigo='+record.get('codigo'),'#poptesore','1020','540','Caja');
            }
		},
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridGpreventiva',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Infractores"
        })
    });
    
    grid.render('gridGpreventiva');
	
    changeDisContri($('#cmbDisContri').val());
	
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarContri();
        }
    });
});




function ImpremeCargoRecepcion(){
var FLAG='1';
showPopupReport('schema=&tipo=pdf&nombrereporte=cargo_notif&param=FLAG^'+FLAG,'pouprptCargo',700,600,'Cargo de Recepcion');
//showPopupReport('schema=&tipo=pdf&nombrereporte=Vias&param=codigo^'+codigo,'pouprptvias',700,600,'Reporte');
}


function buscarPreventiva(){

	var grid = Ext.getCmp('xgridGpreventiva');
	var store = grid.getStore(); //carga la grilla
	var proxy = store.getProxy(); //actualiza la grilla
	
	var params = proxy.extraParams; //busca las paginas de la grilla
	delete params;
	
	var combo1=$('#cmbInfraccion').val();
	var combo2=$('#cmbConcepto').val();
	var combo3=$('#cmbSubConcepto').val();

	if(combo1.length>0)
	{
		var array1=combo1.split('*');
		var res1=array1[0];
		var anno_area=array1[1];
	}
	else{	var res1=''; 
			var anno_area='';
		}
	
	if(combo2.length>0)
	{
		var array2=combo2.split(':');
		var res2=array2[2];
	}
	else{var res2='';}
	
	if(combo3.length>0)
	{
		var array3=combo3.split(':');
		var res3=array3[3];
	}
	else{var res3='';}
	
	var npreventiva= $('#txtNro').val();
	var anno=$('#cmbAnio').val();
	var tipo=$('#cmbTipo').val();
	var anno_ini=$('#txtFini').val();
	var anno_fin=$('#txtFfin').val();
	
	//alert (npreventiva+'/'+anno+'/'+tipo+'/'+anno_area+'/'+res1+'/'+res2+'/'+res3);

	 proxy.extraParams = {
	 npreventiva: 	npreventiva
	,anno		: 	anno
	,tipo		:	tipo
	,anno_ini	:	anno_ini
	,anno_fin	:	anno_fin
	,anno_area	: 	anno_area
	,res1		:	res1
	,res2		:	res2
	,res3		:	res3
	
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});

}

function actualizarContri(){
	var grid = Ext.getCmp('xgridGPreventiva');
	var store = grid.getStore();
	store.reload();
}

$('#txtCriterio').keypress(function (e) {
	
	if($('#rdCriterio1').attr('checked') ){
		return validaTeclas(event,'number');}
});

$('#txtCriterioNombre').keypress(function (e) {
	
	if($('#rdCriterio2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioAPaterno').keypress(function (e) {
	
	if($('#rdCriterio2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioAMaterno').keypress(function (e) {
	
	if($('#rdCriterio2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioRazon').keypress(function (e) {
	
	if($('#rdCriterio4').attr('checked') ){
		return validaTeclas(event,'text');}
});

$('#txtCriterio').keypress(function (e) {
if(e.keyCode==13){
		 var numCeros = '0000000'; // pon el n� de ceros que necesites
		var valor =  $('#txtCriterio').val().trim();
		var radio = $('#rdCriterio1').val();
	   // alert(radio);
		//if(valor.length==0 &&  radio!="C")
		if($('#rdCriterio1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
		{ 
			//$('#txtCriterio').val(valor);
			  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
			$('#txtCriterio').val(valor2);  //obj.value = valor;
		}
		else{
			$('#txtCriterio').val(valor);
		}
		buscarContri();	
	}
});
$('#txtCriterioNombre').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});
$('#txtCriterioAPaterno').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});
$('#txtCriterioAMaterno').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});
$('#txtCriterioRazon').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});
$('#txtDocumento').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});



$('#rdCriterio1').click(function(){
	
	if($('#rdCriterio1').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		//removeClass('myClass yourClass');
		//.addClass( "myClass yourClass" );
		$('#C').removeClass( "invisible" );
		$('#N').addClass( "invisible" );
		$('#R').addClass( "invisible" );
		$('#D').addClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#R input').val('');
		$('#D input').val('');
	}
});

$('#rdCriterio2').click(function(){
	
	if($('#rdCriterio2').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		$('#C').addClass( "invisible" );
		$('#N').removeClass( "invisible" );
		$('#R').addClass( "invisible" );
		$('#D').addClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#R input').val('');
		$('#D input').val('');
	}
});

$('#rdCriterio3').click(function(){
	
	if($('#rdCriterio3').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		$("#txtCriterio").attr('maxlength','11');
		
		$('#C').addClass( "invisible" );
		$('#N').addClass( "invisible" );
		$('#R').addClass( "invisible" );
		$('#D').removeClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#R input').val('');
		$('#D input').val('');
	}
});
$('#rdCriterio4').click(function(){
	
	if($('#rdCriterio4').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		$("#txtCriterio").attr('maxlength','11');
		
		$('#C').addClass( "invisible" );
		$('#N').addClass( "invisible" );
		$('#R').removeClass( "invisible" );
		$('#D').addClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#R input').val('');
		$('#D input').val('');
	}
});

$('#txtCriterio').blur(function(){
    var numCeros = '0000000'; // pon el n� de ceros que necesites
    var valor =  $('#txtCriterio').val().trim();
    var radio = $('#rdCriterio1').val();
   // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if($('#rdCriterio1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
    { 
    	//$('#txtCriterio').val(valor);
    	  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
  	    $('#txtCriterio').val(valor2);  //obj.value = valor;
    }
    else{
    	$('#txtCriterio').val(valor);
    }
    
});

function changeDisContri(idDis){
	if(idDis=='003'){
		$('#rowUrbOtherDis').hide();
		$('#rowViaOtherDis').hide();
		
		$('#rowZonaDis').show();
		$('#rowUrbDis').show();
		$('#rowViaDis').show();
	}
	else{
		$('#rowZonaDis').hide();
		$('#rowUrbDis').hide();
		$('#rowViaDis').hide();
		
		$('#rowUrbOtherDis').show();
		$('#rowViaOtherDis').show();
	}
}

function pagotupaNuevo(){
	showPopup('tesopagosdiv/index?codigo=9999999&nombre=','#popPagosDiv','1200','600','Pagos Diversos');
}


function FiltraCodArea(val){
	//alert(val);
	var array=val.split('*');
	//alert(array[0]+' + '+array[1]);
	var contentAjax = $('#cmbConcepto').html('...');
	
	$.ajax({
		type: "POST",
		//url: urljs + "multasadmin/selectarea",
		url: urljs + "Gpreventiva/selectarea",
		data: "codigo_area="+array[0]+"&anno_area="+array[1],
		success: function(response){
			contentAjax.html(response);			
		}
	});
	
	//alert(array[0]+'/'+array[1]);
} 


function Muestraselect(){

	var contentAjax = $('#cmbSubConcepto').html('...');
	$.ajax({
		type: "POST",
		//url: urljs + "multasadmin/selectarea",
		url: urljs + "Gpreventiva/select",
		success: function(response){
			contentAjax.html(response);			
		}
	});
}


function FiltraTipInfrac(val){
	var contentAjax = $('#cmbSubConcepto').html('...');
	//alert(val);
	$.ajax({
		type: "POST",
		url: urljs + "Gpreventiva/selecttipo",
		//data: "tipo_infraccion="+val,
		data: "tipo_infraccion="+val+"&tipocontri_multa="+$('#tipocontri_multa').val(),
		success: function(response){
			contentAjax.html(response);			
		}
	});
}







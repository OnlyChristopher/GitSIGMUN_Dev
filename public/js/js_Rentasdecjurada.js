Ext.onReady(function(){
    Ext.QuickTips.init();
	var anioIni='';
	$("input[type='button']").button();
	
    var storepredio=null;
    var storeTree = Ext.create('Ext.data.TreeStore', {
        root: {
    		text: 'Periodos',
            expanded: true
        },
        proxy: {
            type: 'ajax',
            url: urljs + 'rentasdecjurada/annos',
            reader: {
                type: 'json',
                root: 'root'
            }
        }
		,listeners: {
            append: function(thisNode, newChildNode, index, eOpts) {
				//Capturamos el último nodo
				anioIni = newChildNode.get('text');
            }
        }
    }); 
	
    var tree = new Ext.tree.TreePanel({
    	id: 'xtreePanel',
    	title: 'Declaraciones',
        width: '200px',
        height: '300px',
        useArrows: true,
        lines: false,
        store: storeTree,
        listeners: {
            itemclick: 
            	function(s,r) {
    			anno=r.data.text;
    			cargar_grilla(anno);
    			
            }
			,load:function(loader,node,response){
				//Cargamos la grilla	
				cargar_grilla(anioIni);
		    }
        }
    });
    
    tree.render('treeAnios');

    //Grilla periodos
    var grid = Ext.create('Ext.grid.Panel', {
        store: storepredio,
        width: '650px',
        height: '200px',
        //title: 'Predios',
        //stateful: true,
        //stateId: 'stateGrid',
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
		viewConfig: {
        	loadMask: {msg: 'Listado de Predios...'},
			stripeRows: false,
        	getRowClass: function(record, index){
				
				var clsRow = '';
				switch(record.get('predio_vendido').trim()){
					case '1': clsRow='xrow-cancelado'; break
				}
				return clsRow;
			}

    	},
        columns:[
           // Ext.create('Ext.grid.RowNumberer'),          
        {
            text: 'Codigo Predio',
            width: 90,
            align:'LEFT',
            dataIndex: 'cod_pred'
        },
		{
            text: 'Direccion',
            flex: 1,
            dataIndex: 'direccion'            
        },{
            text: 'A. Terreno',
            width: 100,
            align:'RIGHT',
            sortable : true,
            dataIndex: 'are_terre'
        },{
            text: '% Propiedad',
            width: 60,
            align:'RIGHT',
            dataIndex: 'porce_pro'
        },{
            text: 'Autoavaluo',
            width: 80,
            align:'RIGHT',
            dataIndex: 'tot_autova'
        },{
            xtype:'actioncolumn',
            width:35,
            items: [
                {
                icon: urljs + 'img/view.png',
                tooltip: 'Caracteristicas',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Ver predios',rec.get('cod_pred'));
                    showPopup('rentas/predios','#popuppred','1100','630','Mantenimiento de Predios','frmpredios');
                }                
            }]
        }],
        renderTo:'gridPredios'
    });
});

function EliminarPredio(){
	var grid = Ext.getCmp('xgridtblpredio');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
		if(selectedRecordsArray.length==1)
		{
			var codigo=$('#divCodigo').html();
			var anno=$('#divanno').html();
			var cod_pred = "";
			var anexo="";
			var direccion="";
			
			Ext.each(selectedRecordsArray, function (item) {		  	
				cod_pred = item.data.cod_pred; 
				anexo = item.data.anexo;
				direccion = item.data.direccion;
			});
			showPopup('Rentasdecjurada/bajapredio?codigo='+codigo+'&anno='+anno+'&cod_pred='+cod_pred+'&anexo='+anexo+'&direccion='+direccion,'#bajapredio','740','510','Baja de Predio','frmbajapredio');

		}
		else{
			infoMessage('Alerta','Debe Seleccionar solo un Predio para dar de Baja!');
		}
	}
	else
		infoMessage('Alerta','Debe Seleccionar un Predio!');	
}
function VenderPredio(){
	var grid = Ext.getCmp('xgridtblpredio');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
		if(selectedRecordsArray.length==1)
		{
			var showResult = function(btn){
				if(btn=='yes'){
					var codigo=$('#divCodigo').html();
					var anno=$('#divanno').html();
					var cod_pred = "";
					var anexo="";
					
					Ext.each(selectedRecordsArray, function (item) {		  	
						cod_pred = item.data.cod_pred; 
						anexo = item.data.anexo;
					});

					Ext.Ajax.request({
			            url: urljs + "rentasdecjurada/ventapredio",
			            method: "POST",
			            params: {codigo: codigo,cod_pred: cod_pred, anexo: anexo,anno: anno},
			            success: function(response){
							Ext.getCmp('xgridtblpredio').getStore().load();
			            	infoMessage('Contribuyentes',response.responseText);		            	
			            },
			            failure: function(response, opts){
			            	 ('Contribuyentes','Error al intentar cobrar... ');
			            }
			        });
					
					
				}
	 		};
	 		
	 		confirmMessage('Contribuyentes','Seguro de marcar al predio como vendido?',showResult);
			
		}
		else{
			infoMessage('Alerta','Debe Seleccionar solo un Predio para marcar como Vendio y no migrar al proximo Año!');
		}
	}
	else
		infoMessage('Alerta','Debe Seleccionar un Predio!');	
}
function cargar_grilla(anno){
				if(	anno=='Periodos'){
		    		return;
		    	};
    			
            	
    			$('#divanno').html(anno);

    	        var datos={
    	                txtanno:anno,
    	                txtcodigo:$('#divCodigo').html()
    	            };
    	        
    			$.ajax({
    				type: "POST",     
    				url: urljs + "rentasdecjurada/detalleanno",
    				data: datos,
    				dataType : 'json',
    				success: function(response) { 
    					
    					len = response.datoshr.length;
    					
    					$('#tblpredios').html(len > 0 ? response.datoshr[0].nro_predi : 0);
    					$('#tbltotaautova').html(len > 0 ? response.datoshr[0].tot_autoavaluo : 0.00);
    					$('#tblbaseimp').html(len > 0 ? response.datoshr[0].base_imponible : 0.00);
    					$('#tblanual').html(len > 0 ? response.datoshr[0].imp_anual : 0.00);
    					$('#tbltrimestre').html(len > 0 ? response.datoshr[0].imp_trime : 0.00);
    					$('#tblemision').html(len > 0 ? response.datoshr[0].costo_emi : 0.00);
						$('#divRegimen').html(len > 0 ? response.datoshr[0].por_inafec : '');
						
    				},     
    				error: function() {
    				} 
    			}); 
    			
    		    function change(val) {
    		        if (val.length > 0) {
    		            return '<span style="color:green;">' + val + '</span>';
    		        } else if (val.length < 0) {
    		            return '<span style="color:red;">' + val + '</span>';
    		        }
    		        return val;
    		    }
    		    
    			$('#gridPredios').html('');
    			
    		    Ext.define('tblpredio', {
    		        extend: 'Ext.data.Model',
    		        fields: [
    		            {name: 'tipo'	, type: 'string'},
    		            {name: 'cod_pred'	, type: 'string'},
    		            {name: 'anexo'		, type: 'string'},
    		            {name: 'direccion'	, type: 'string'},
    		            {name: 'area_terreno'	, type: 'string'},
    		            {name: 'porcen_propiedad'	, type: 'string'},
    		            {name: 'total_autoavaluo'	, type: 'string'},
						{name: 'predio_vendido', type: 'string'}
    		        ]
    		    });
    		    
    		    storepredio = Ext.create('Ext.data.Store', {
    		        model: 'tblpredio',
    		        proxy: {
    		            type: 'ajax',
    		            url : urljs + 'rentasdecjurada/predios?anno='+anno+'&codigo='+$('#divCodigo').html(),
    		            reader: {
    		                type: 'json',
    		                root: 'root'
    		            },
						timeout : 60000
    		        }
    		    });
            	storepredio.load();

            	var grid = Ext.create('Ext.grid.Panel', {
					id: 'xgridtblpredio',
                    store: storepredio,
                    columnLines: true,
                    width: '660px',
                    height: '200px',
                    //title: 'Predios',
                    //stateful: true,
                    //stateId: 'stateGrid',
					viewConfig: {
						loadMask: {msg: 'Listado de Predios...'},
						stripeRows: false,
						getRowClass: function(record, index){
							
							var clsRow = '';
							switch(record.get('predio_vendido').trim()){
								case '1': clsRow='xrow-cancelado'; break
							}
							return clsRow;
						}

					},
                    selModel: Ext.create('Ext.selection.CheckboxModel',{
				   
					}),
                    //Ext.create('Ext.grid.RowNumberer'),
                    columns:[{
                    	text: 'Predio',
                    columns:[{
						text: 'Tipo',
					    width: 35,
					    align:'left',
					    renderer : change,
					    dataIndex: 'tipo'
					},{
                    	text: 'Codigo',
                        width: 79,
                        align:'left',
                        dataIndex: 'cod_pred'
                    },{
                        text: 'Anexo',
                        width: 70,
                        align:'left',
                        dataIndex: 'anexo'
                    },{
                        text: 'Ubicación',
                        width: 430,
                        dataIndex: 'direccion'            
                    }]},{
                    	text: 'Valores',
                    columns:[{
                        text: '%',
                        width: 50,
                        align:'right',
                        dataIndex: 'porcen_propiedad'
                    },{
                        text: 'Autoavalúo',
                        width: 80,
                        align:'right',
                        dataIndex: 'total_autoavaluo'
                    }]},{
                        xtype:'actioncolumn',
                        width:80,
                        items: [{
                            icon: urljs + 'img/view.png',
                            tooltip: 'Caracter&iacute;sticas',
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.getStore().getAt(rowIndex);
                                
                                var codigo=$('#divCodigo').html();
                                var anno=$('#divanno').html();
                                var cod_pred=rec.get('cod_pred');
                                var anexo=rec.get('anexo').substring(0,4);
                                var sub_anexo=rec.get('anexo').substring(5);

                                showPopup('rentas/predios?tipo=E&codigo='+codigo+'&anno='+anno+'&cod_pred='+cod_pred+'&anexo='+anexo+'&sub_anexo='+sub_anexo,'#popuppred','1100','630','Mantenimiento de Predios','frmpredios');
                                //Ext.MessageBox.alert('Ver predios',rec.get('cod_pred'));
                                //showPopup('rentas/predios?accion=2&cod_pred='+rec.get('cod_pred'),'#popeditapu','1000','580','Mantenimiento de Predios','frmpredios');
                            }
                        },{
                            icon: urljs + 'img/user_go.png',
                            tooltip: 'Propietarios',
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.getStore().getAt(rowIndex);
                                
                                var codigo=$('#divCodigo').html();
                                var anno=$('#divanno').html();
                                var cod_pred=rec.get('cod_pred');
                                var anexo=rec.get('anexo').substring(0,4);
                                var sub_anexo=rec.get('anexo').substring(5);

                                showPopup('rentas/propietarios?cod_pred='+cod_pred+'&anexo='+anexo+'&sub_anexo='+sub_anexo,'#popupprop','800','310','Lista de Propietarios del Predio','frmpropietario');
                                //Ext.MessageBox.alert('Ver predios',rec.get('cod_pred'));
                                //showPopup('rentas/predios?accion=2&cod_pred='+rec.get('cod_pred'),'#popeditapu','1000','580','Mantenimiento de Predios','frmpredios');
                            }
                        },{
                            icon: urljs + 'img/export.gif',
                            tooltip: 'Reporte HR',
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.getStore().getAt(rowIndex);
                                
                                var codigo=$('#divCodigo').html();
                                var anno=$('#divanno').html();
                                var cod_pred=rec.get('cod_pred');
                                var anexo=rec.get('anexo').substring(0,4);
                                var sub_anexo=rec.get('anexo').substring(5);
							showPopupReport('schema=&tipo=pdf&nombrereporte=rpto_declaraciondoc&param=CODIGO^'+codigo+'|ANNO^'+anno+'|COD_PRED^'+cod_pred+'|ANEXO^'+anexo+'|SUB_ANEXO^'+sub_anexo,'reportehrc',700,600,'Hoja de Resumen');
                            //generarHrCompleto(codigo,anno,cod_pred,anexo,sub_anexo);
                            }
                        },{
                            icon: urljs + 'img/user_go.png',
                            tooltip: 'Historial de Predio',
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.getStore().getAt(rowIndex);
                                
                                var codigo=$('#divCodigo').html();
                                var anno=$('#divanno').html();
                                var cod_pred=rec.get('cod_pred');
                                var anexo=rec.get('anexo').substring(0,4);
                                var sub_anexo=rec.get('anexo').substring(5);
							showPopup('rentas/historicopredio?codigo='+codigo+'&anno='+anno+'&cod_pred='+cod_pred+'&anexo='+anexo+'&sub_anexo='+sub_anexo,'#popuphistorial','1200','600','Historial de Predios','frmhistorial');
                            //generarHrCompleto(codigo,anno,cod_pred,anexo,sub_anexo);
                            }
                        }]
                    }],
                    renderTo:'gridPredios'
                /*    viewConfig:{
	            	    listeners: {
		                    itemdblclick: function(grid, rowIndex, colIndex) {
            		 			var rec = grid.getStore().getAt(rowIndex);
            					alert(rec.get('cod_pred'));
		                    }}}*/
	                    
                });
				}

function generarhrpdf(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
	var codigo=$('#divCodigo').html();
	var anno=$('#divanno').html();
	showPopupReport('schema=&tipo=pdf&nombrereporte=hr_anno&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido',700,600,'Hora Resumen');
}
function generarpupdf(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
	var codigo=$('#divCodigo').html();
	var anno=$('#divanno').html();
    if (anno==2016)
    {
        showPopupReport('schema=&tipo=pdf&nombrereporte=pu_anno_2016&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido2',700,600,'Hora Resumen');
    }
    else
    {
        showPopupReport('schema=&tipo=pdf&nombrereporte=pu_anno&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido2',700,600,'Hora Resumen');
    }
	
}
function generarhlppdf(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
	var codigo=$('#divCodigo').html();
	var anno=$('#divanno').html();
    if (anno==2016)
    {
        showPopupReport('schema=&tipo=pdf&nombrereporte=hli_anno_2016&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido2',700,600,'Hora Resumen');
    }
    else
    {
        showPopupReport('schema=&tipo=pdf&nombrereporte=hli_anno&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido2',700,600,'Hora Resumen');
    }
	
}
function generarhlapdf(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
	var codigo=$('#divCodigo').html();
	var anno=$('#divanno').html();
    if(anno==2016)
    {
       showPopupReport('schema=&tipo=pdf&nombrereporte=hl_anno_2016&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido2',700,600,'Hora Resumen');
    }
    else
    {
       showPopupReport('schema=&tipo=pdf&nombrereporte=hl_anno&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido2',700,600,'Hora Resumen'); 
    }
	
}
function generaroipdf(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
	var codigo=$('#divCodigo').html();
	var anno=$('#divanno').html();
	showPopupReport('schema=&tipo=pdf&nombrereporte=PU_OI_anno&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido2',700,600,'Hora Resumen');
}
function generarHrCompleto(codigo,anno,cod_pred,anexo,sub_anexo){
	showPopupReport('schema=&tipo=pdf&nombrereporte=rpto_declaraciondoc&param=CODIGO^'+codigo+'|ANNO^'+anno+'|COD_PRED^'+cod_pred+'|ANEXO^'+anexo+'|SUB_ANEXO^'+sub_anexo,'reportehrc',700,600,'Hoja de Resumen');
}
function generarcaratulapdf(){
	var codigo=$('#divCodigo').html();
	var anno=$('#divanno').html();
	showPopupReport('schema=&tipo=pdf&nombrereporte=CARATULA&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido2',700,600,'Hora Resumen');
}
function generarcargopdf(){
	var codigo=$('#divCodigo').html();
	var anno=$('#divanno').html();
	showPopupReport('schema=&tipo=pdf&nombrereporte=CARGO_RECEPCION&param=CODIGO^'+codigo+'|ANNO^'+anno,'pouprptemitido2',700,600,'Hora Resumen');
}
function Imprime_Reporte(){

	if($("#chHR").is(':checked')){generarhrpdf();}
	if($("#chPU").is(':checked')){generarpupdf();}
	if($("#chOI").is(':checked')){generaroipdf();}
	if($("#chHLP").is(':checked')){generarhlppdf();}
	if($("#chHLA").is(':checked')){generarhlapdf();}
	if($("#chCARA").is(':checked')){generarcaratulapdf();}
	if($("#chCARGO").is(':checked')){generarcargopdf();}
	//if(check_pu==true){generarpupdf();}
}
function selectcuponera(obj){
	$('.chcuponera').attr('checked',false);
	$(obj).attr('checked',true);
}
function NuevoPu(cad){
	var divanno=$('#divanno').html();
	var grid = Ext.getCmp('xgridtblpredio');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if (divanno==""){
		infoMessage('Alerta','Debe Registrar su Declaracion Jurada!');
	}
	else{
		if(selectedRecordsArray.length>0){
			if(selectedRecordsArray.length==1)
			{
				var codigo=$('#divCodigo').html();
				var anno=$('#divanno').html();
				var cod_pred = "";
				var anexo="";
				var sub_anexo="";
				
				Ext.each(selectedRecordsArray, function (item) {		  	
					cod_pred = item.data.cod_pred; 
					anexo=item.data.anexo.substring(0,4);
                    sub_anexo=item.data.anexo.substring(5);
				});
				
				showPopup(cad+'?codigo='+codigo+'&anno='+anno+'&cod_pred='+cod_pred+'&anexo='+anexo+'&sub_anexo='+sub_anexo+'&tipo=I','#popuppred','1100','630','Mantenimiento de Predios','frmpredios');

			}
			else{
				infoMessage('Alerta','No se puede generar anexos a varios predios a la vez !');
			}
		}
		else{
			showPopup(cad+'?tipo=N','#popuppred','1100','630','Mantenimiento de Predios','frmpredios');
		}
	}
	//showPopup(cad,'#popuppred','1100','630','Mantenimiento de Predios','frmpredios');
}
function Inscripcion(cad){
	var divanno=$('#divanno').html();
	if (divanno==""){
		infoMessage('Alerta','Debe Registrar su Declaracion Jurada!');
	}
	else{
		showPopup(cad+'?tipo=M','#popuppred','1100','630','Registro de Predios','frmpredios');
	}
}

function Verbajapredi()
{
	var codigo=$('#divCodigo').html();

	showPopup('mantbajapre/buscar?codigobaja='+codigo,'#bajapredio','740','510','Baja de Predio','frmbajapredio');

}

function Cargarbajapredio()
{
	//var codigo=$('#divCodigo').html();

	//showPopup('mantbajapre/buscar?codigobaja='+codigo,'#bajapredio','740','510','Baja de Predio','frmbajapredio');
	$('#gridCargabajapre').html('');

	Ext.define('Cargabajapre', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'codigo'},            
            {name: 'anno', type: 'string'},
			{name: 'cod_pred', type: 'string'},
            {name: 'anexo', type: 'string'},
            {name: 'sub_anexo', type: 'string'},
			{name: 'direccion', type: 'string'},
			{name: 'fechdescargo', type: 'string'},
			{name: 'dj_predial', type: 'string'}
        ]
    });
	
	var store = Ext.create('Ext.data.Store', {
    	model: 'Cargabajapre',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantbajapre/consulta?codigobaja='+$('#divCodigo').html(),
            reader: {
               type: 'json',
               root: 'rowsbaja'
            }
        }
    });
	
	var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridCargabajapre',
        store: store, 
		//height:100,
        title: 'Contribuyente Baja de Predio ',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Codigo',
            width: 80,            
            dataIndex: 'codigo'
        },{
            dataIndex: 'dj_predial',
			hidden: true
        },{
            text: 'Año',
            width: 80,            
            dataIndex: 'anno'
        },{        
        	text: 'Codigo Pred',
        	width: 100,
            dataIndex: 'cod_pred'
        },{        
        	text: 'Anexo',
        	width: 60,
            dataIndex: 'anexo'
        },{
            text: 'SubAnexo',
            width: 80,
            dataIndex: 'sub_anexo'            
        },{
            text: 'Direcci&oacute;n',
            flex: 1,
            dataIndex: 'direccion'
        },{
            text: 'Fecha Descargo',
            width: 100,
            dataIndex: 'fechdescargo'
        },{
            xtype:'actioncolumn',
            width:50,
            items: [
				{
					icon: urljs + 'img/export.gif',
					tooltip: 'Reporte Baja Predio',
					handler: function(grid, rowIndex, colIndex) {
						var rec = grid.getStore().getAt(rowIndex);
						
						var codigo=rec.get('codigo');
						var anno=rec.get('anno');
						var cod_pred=rec.get('cod_pred');						
						var anexo=rec.get('anexo');
						var sub_anexo=rec.get('sub_anexo');
						var dj_predial=rec.get('dj_predial');
					generarBajapredios(codigo,anno,cod_pred,anexo,sub_anexo,dj_predial);
					}
                }
			]
        }]
       	
    });
	
	grid.render('gridCargabajapre');
	
}

function generarBajapredios(codigo,anno,cod_pred,anexo,sub_anexo,dj_predial){
	showPopupReport('schema=&tipo=pdf&nombrereporte=rptdescargo&param=codigo^'+codigo+'|anno^'+anno+'|cod_pred^'+cod_pred+'|anexo^'+anexo+'|sub_anexo^'+sub_anexo+'|dj_predial^'+dj_predial,'reportebajapredio',700,600,'Baja de Predio');
}
function determinacionPredio(cad,codigo){
	var anno=$('#divanno').html();
	showPopup(cad+'?codigo='+codigo+'&anno='+anno,'#popupdeterminacion','1100','630','Determinacion de Predios','frmpredios');
}
/*

$('#btnCargabaja').click(function() {
   
   Cargarbajapredio();
	
});
*/
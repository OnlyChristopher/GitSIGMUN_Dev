Ext.onReady(function(){
    Ext.QuickTips.init();
    	
    Ext.define('RecContri', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idrecibo'},
			{name: 'codigo', type: 'string'},			  
			{name: 'tipo', type: 'string'},
			{name: 'anno', type: 'string'},
			{name: 'cod_pred', type: 'string'},
			{name: 'anexo', type: 'string'},
			{name: 'sub_anexo', type: 'string'},
			{name: 'tipo_rec', type: 'string'},
			{name: 'periodo', type: 'string'},
			{name: 'imp_insol', type: 'string'},
			{name: 'fact_reaj', type: 'string'},
			{name: 'imp_reaj', type: 'string'},
			{name: 'fact_mora', type: 'string'},
			{name: 'mora', type: 'string'},
			{name: 'costo_emis', type: 'string'},
			{name: 'des_tipo', type: 'string'},
			{name: 'ubica', type: 'string'},
			{name: 'total', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'RecContri',
    	autoLoad: true,
        proxy: {
            type: 'ajax',
            url : 'tesocajareccontri/conreccontri',
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
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridRecContri',
        store: store,        
        title: 'Recibos',
        height: 360,
        viewConfig: {
        	loadMask: {msg: 'Listado de Recibos...'},
        	getRowClass: function(record, index){
				if(record.get('ubica')=='EM* ' || record.get('ubica')=='EM*')
					return 'xrow-red';
//				else
//					return 'xrow-blue';
				//green
			}

    	},
               selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode: 'MULTI',
        	checkOnly: true
        }),

        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Recibo',
            width: 100,            
            dataIndex: 'idrecibo',
            hidden:	true
        },{        
        	text: 'Codigo',
        	//flex: 1,
            width:55,
        	dataIndex: 'codigo',
			hidden:	false
        },{        
        	text: 'Tributo',
        	//flex: 1,
            width:55,
        	dataIndex: 'des_tipo'
        },{
            text: 'Año',
            width: 40,
            dataIndex: 'anno'            
        },{
            text: 'Predio',
            width: 60,
            dataIndex: 'cod_pred',
        	hidden:	false
            	//,            hidden:	true//cod_pred//
            	
        },{
            text: 'Anexo',
            width: 60,
            dataIndex: 'anexo'
			//,        	hidden:	true
            	//,            hidden:	true//cod_pred//anexo
            	
        },{
            text: 'Sub Anexo',
            width: 70,
            dataIndex: 'sub_anexo'
			//,        	hidden:	true
            	//,            hidden:	true//cod_pred//anexo
            	
        },{
            text: 'Periodo',
            width: 55,
            dataIndex: 'periodo'
        },{
            text: 'Imp. Insol',
            width: 85,
			align:'right',
            dataIndex: 'imp_insol'
        },{
            text: 'Fact. Reaj',
            width: 80,
			align:'right',
            dataIndex: 'fact_reaj'
        },{
            text: 'Imp. Reaj',
            width: 80,
			align:'right',
            dataIndex: 'imp_reaj'
        },{
            text: 'Fact Mora.',
            width: 80,
			align:'right',
            dataIndex: 'fact_mora'
        },{
            text: 'Interes',
            width: 80,
			align:'right',
            dataIndex: 'mora'
            	//tipo_rec
        },{
            text: 'Costo Emis.',
            width: 80,
			align:'right',
            dataIndex: 'costo_emis'
        },{
            text: 'Total',
            width: 80,
			align:'right',
            dataIndex: 'total'
            	//tipo_rec
        },{        
        	text: 'Tipo',
        	//flex: 1,
            width:60,
        	dataIndex: 'tipo',
        	hidden:	true
        } ,{
            text: 'Tipo Recibo',
            width: 60,
            dataIndex: 'tipo_rec',
            	hidden:	true
            	
        },{
            text: 'ubica',
            width: 40,
			align:'center',
            dataIndex: 'ubica'
            	//,            hidden:	true//
            	
        }],
//        listeners: {    		
//			deselect: function(model, record, index) {
//			//alert(record.get('interes'));
//    		deuda(false, record);
//        },
        listeners: {    		
		deselect: function(model, record, index) {
			//alert(record.get('interes'));
    		deuda(false, record);
        },
        select: function(model, record, index) {
        	//alert(record.get('ubica'));
        	deuda(true, record);
      	        	
        }
    }
    });
    
    grid.render('gridRecContri');
    
});


function deuda(est, obj){
	var str="";
	var txmonto="";
	var txannios='';
	var txtiprec='';
	var txtperiodo='';
	var txtimpinsol='';
	var txtfacreaj='';
	var txtimpreaj='';
	var txtfactmora='';
	var txtemision='';
	var txtcodpred='';
	var txtinteres=0;
	
	var dt=0;
	var text='';
	var textano='';
	var texttiprec='';
	var textperiodo='';
	var textimpinsol='';
	var textfactreaj='';
	var textimpreaj='';
	var textfactmora='';
	var textemision='';
	var textcodpre='';
	var textinteres=0;
	
	var destexto='';
	//for (i=0; ele=obj.get[i]; i++){
	if(est==true)
	{
	//deuda
		dt=Number($('#txtpago').val());
		dt +=Number( obj.get('total')) ;
		dt=Math.round(dt*100)/100;
	
		$('#txtpago').val(dt);
	//recibos	
	str=$('#txtcajarecibos').val();
	str += obj.get('idrecibo') + "/";

	$('#txtcajarecibos').val(str);
	
	//deudas
	txmonto=$('#txtcajamonto').val();
	txmonto += obj.get('total') + "/";

	$('#txtcajamonto').val(txmonto);
	
	//años
	txannios=$('#txtcajaanno').val();
	txannios += obj.get('anno') + "/";

	$('#txtcajaanno').val(txannios);
	
	//tiporec
	
	txtiprec=$('#txtcajatiporec').val();
	txtiprec += obj.get('tipo_rec') + "/";

	$('#txtcajatiporec').val(txtiprec);
	
	//periodo
	
	txtperiodo=$('#txtcajaperiodo').val();
	txtperiodo += obj.get('periodo') + "/";

	$('#txtcajaperiodo').val(txtperiodo);
	
	//impinsol				
	txtimpinsol=$('#txtcajaimpinsol').val();
	txtimpinsol += obj.get('imp_insol') + "/";

	$('#txtcajaimpinsol').val(txtimpinsol);
	
	//facreaj		
	
	txtfacreaj=$('#txtcajafactreaj').val();
	txtfacreaj += obj.get('fact_reaj') + "/";

	$('#txtcajafactreaj').val(txtfacreaj);
	
	//impreaj		
	txtimpreaj=$('#txtcajaimpreaj').val();
	txtimpreaj += obj.get('imp_reaj') + "/";

	$('#txtcajaimpreaj').val(txtimpreaj);
	
	//factor mora 								
	txtfactmora=$('#txtcajafactmora').val();
	txtfactmora += obj.get('fact_mora') + "/";

	$('#txtcajafactmora').val(txtfactmora);
	//emision						
	txtemision=$('#txtcajacostoemis').val();
	txtemision += obj.get('costo_emis') + "/";

	$('#txtcajacostoemis').val(txtemision);
	
	//cod_pred						
	txtcodpred=$('#txtcajacodpred').val();
	txtcodpred += obj.get('cod_pred') + "/";

	$('#txtcajacodpred').val(txtcodpred);
	
	//interes
	xd=Number($('#txtcajainteres').val());
	xd +=Number( obj.get('interes')) ;
	xd=Math.round(xd*100)/100;

	$('#txtcajainteres').val(xd);
	
	}
	//}
	else if(est==false)
	{
	//pago
		dt=Number($('#txtpago').val());
		dt=Number(dt)-Number(obj.get('total'));
		dt=Math.round(dt*100)/100;
		
		$('#txtpago').val(dt);
	
	//interes
		xd=Number($('#txtcajainteres').val());
		xd =Number(xd)-Number( obj.get('interes')) ;
		xd=Math.round(xd*100)/100;

		$('#txtcajainteres').val(xd);

		
	//recibos	
		str=$('#txtcajarecibos').val();
		
		destexto = obj.get('idrecibo') + "/";
			str = str.replace(destexto,'');

		$('#txtcajarecibos').val(str);
	//deudas
		
		txmonto=$('#txtcajamonto').val();
		
		text = obj.get('total') + "/";
		txmonto = txmonto.replace(text,'');

		$('#txtcajamonto').val(txmonto);
		
	//años
		txannios=$('#txtcajaanno').val();
		
		textano = obj.get('anno') + "/";
		txannios = txannios.replace(textano,'');

		$('#txtcajaanno').val(txannios);
	
	//tiporec
		
		txtiprec=$('#txtcajatiporec').val();
		
		texttiprec = obj.get('tipo_rec') + "/";
		txtiprec = txtiprec.replace(texttiprec,'');

		$('#txtcajatiporec').val(txtiprec);
		
	//periodo
		txtperiodo=$('#txtcajaperiodo').val();
		
		textperiodo = obj.get('periodo') + "/";
		txtperiodo = txtperiodo.replace(textperiodo,'');

		$('#txtcajaperiodo').val(txtperiodo);
		
	//impinsol
		txtimpinsol=$('#txtcajaimpinsol').val();
		
		textimpinsol = obj.get('imp_insol') + "/";
		txtimpinsol = txtimpinsol.replace(textimpinsol,'');

		$('#txtcajaimpinsol').val(txtimpinsol);
	
	//facreaj
		txtfacreaj=$('#txtcajafactreaj').val();
		textfactreaj = obj.get('fact_reaj') + "/";
		txtfacreaj = txtfacreaj.replace(textfactreaj,'');

		$('#txtcajafactreaj').val(txtfacreaj);
		
	//impreaj
		txtimpreaj=$('#txtcajaimpreaj').val();
		textimpreaj = obj.get('imp_reaj') + "/";
		txtimpreaj = txtimpreaj.replace(textimpreaj,'');

		$('#txtcajaimpreaj').val(txtimpreaj);
		
	//factor mora 
		txtfactmora=$('#txtcajafactmora').val();
		textfactmora = obj.get('fact_mora') + "/";
		txtfactmora = txtfactmora.replace(textfactmora,'');

		$('#txtcajafactmora').val(txtfactmora);
	
	//emision
		txtemision=$('#txtcajacostoemis').val();
		textemision = obj.get('costo_emis') + "/";
		txtemision = txtemision.replace(textemision,'');

		$('#txtcajacostoemis').val(txtemision);
		
	//cod_pred
		txtcodpred=$('#txtcajacodpred').val();
		textcodpre = obj.get('cod_pred') + "/";
		txtcodpred = txtcodpred.replace(textcodpre,'');

		$('#txtcajacodpred').val(txtcodpred);
		
		
	}
		
	
	
	
	
}
function pulsar(e) { 
	  tecla = (document.all) ? e.keyCode :e.which; 
	  return (tecla!=13); 
	} 
function mostrbotones() {

	//div = document.getElementById('flotante');
	botones = document.getElementById('divboton');
	boton=document.getElementById('boton');//
	radioss=document.getElementById('radios');
	
//	nombres = document.getElementById('bnombres');
//	razon = document.getElementById('brazon');

	botones.style.display = 'none';
	boton.style.display = '';
	radioss.style.display = '';
	
	botones.enabled='false';
	boton.enabled='false';
	radioss.enabled='false';	}

function ocultabotones() {

	//div = document.getElementById('flotante');
	botones = document.getElementById('divboton');
	boton=document.getElementById('boton');
	radioss=document.getElementById('radios');
//	nombres = document.getElementById('bnombres');
//	razon = document.getElementById('brazon');

	botones.style.display = 'none';
	boton.style.display = 'none';
	radioss.style.display = 'none';
	
	botones.enabled='false';
	boton.enabled='false';
	radioss.enabled='false'; 
	}
function efectivo(){
	efectiv=document.getElementById('divboton');
	efectiv.style.display = 'none';
	efectiv.enabled='false';
	
	}
function cheque(){
	efectiv=document.getElementById('divboton');
	efectiv.style.display ='';

	efectiv.enabled='false';
	
}

function enviadeuda(){
	
	var grid = Ext.getCmp('xgridRecContri');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	
	if(selectedRecordsArray.length>0){
		
		 var showResult = function(btn){
			if(btn=='yes'){
				
				var idList = [];
				Ext.each(selectedRecordsArray, function (item) {
					idList.push(
						{
							idrecibo: item.data.idrecibo,
							montotal: item.data.total,
							codigo:item.data.codigo,
							cod_pred: item.data.cod_pred,
							anexo:item.data.anexo,
							sub_anexo:item.data.sub_anexo,
							anno: item.data.anno, 
							tipo_rec: item.data.tipo_rec,
							periodo: item.data.periodo, 
							imp_insol: item.data.imp_insol, 
							fact_reaj: item.data.fact_reaj, 
							imp_reaj: item.data.imp_reaj, 
							fact_mora: item.data.fact_mora, 
							imp_mora: item.data.mora, 
							costo_emis: item.data.costo_emis, 
							observacion:'',
							operador:'',
							estacion:'',
							fech_ing:'01/01/2010'
						});
				});
				/*
				var idList1[];
				idList1.push(
						{
							tipo: $('#tipo').val(),
						});
				
				var data = [idList1, idList2];
				*/
				showPopupData(idList,'tesoprepago/index','#popprepago','800','400','Pago');
				/*
				Ext.Ajax.request({
		            url: urljs + "tesocajareccontri/registrapago",
		            method: "POST",
		            params: {json: JSON.stringify(idList)},
		            success: function(){
		                //console.log("ok");		            	
		            	//grid.getStore().loadPage(grid.getStore().currentPage);
		            },
		            failure: function(response, opts){
		                //console.log("failed");
		            	infoMessage('Contribuyentes','Error al intentar eliminar!');
		            }
		        });*/
			}
 		};
 		
 		confirmMessage('Contribuyentes','Seguro de Cancelar?',showResult);
		
	}
	else{
		infoMessage('Contribuyentes','Seleccione al menos un registro!');
	}
}
function prepago(){

	data = [1];
	showPopupData(data,'tesoprepago/index','#popprepago','800','400','Pago');
	//showPopupData('tesoprepago/index','#popprepago','800','400','Recibos del Contribuyente');
}

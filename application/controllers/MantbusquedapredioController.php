<?php

class MantbusquedapredioController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {    	    	    	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
			
		$fn = new Libreria_Pintar();
		
		
		
		// $evt[] = array('#btnSearchContri',"click","buscarContri()");
		// $evt[] = array('#btnNewContri',"click","showPopup('mantcontri/formu','#popcontri','800','670','Nuevo Contribuyente');"); // ventana nuevo contribuyente
		// $evt[] = array('#btnRefreshContri',"click","actualizarContri()");
		// $evt[] = array('#btnPagoTupa',"click","pagotupaNuevo()");
		$evt[] = array('#btn_Busvia',"click","mostrarVias();");
		$evt[] = array('#btnSearchPredio',"click","buscarPredio()");
		
		$bus_anno="";
		for($i=1992;$i<=date('Y');$i++){
		$bus_anno.="<option value='".$i."'";
			if($i==date('Y'))
			{
				$bus_anno.=" selected ";
			}
		$bus_anno.=">".$i."</option>";
		}
		$this->view->cb_anno=$bus_anno;
		
		
			
		$fn->PintarEvento($evt);		
    }
    
  
    public function consultapredioAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
		$codigocontri = $_REQUEST['codigocontri'];
		
    	$anno = trim($_REQUEST['anno']);
    	$cod_via = trim($_REQUEST['cod_via']);
		$nro = trim($_REQUEST['nro']);
		$dpto = trim($_REQUEST['dpto']);
		$mza = trim($_REQUEST['mza']);
		$lte = trim($_REQUEST['lte']);
		$sublote = trim($_REQUEST['sublote']);
    	unset($parametros);
    	//Para el total
    	$parametros[] = array('@busc',15);		
		$parametros[] = array('@anno',$anno);
		$parametros[] = array('@id_via',$cod_via);
		$parametros[] = array('@nro',$nro);
		$parametros[] = array('@dpto',$dpto);
		$parametros[] = array('@Mza',$mza);
		$parametros[] = array('@Lte',$lte);
		$parametros[] = array('@SubLte',$sublote );
		
		$parametros[] = array('@codigo',$codigocontri);
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente_coac', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',14);
		$parametros[] = array('@anno',$anno);
		$parametros[] = array('@id_via',$cod_via);
		$parametros[] = array('@nro',$nro);
		$parametros[] = array('@dpto',$dpto);
		$parametros[] = array('@Mza',$mza);
		$parametros[] = array('@Lte',$lte);
		$parametros[] = array('@SubLte',$sublote );
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		$parametros[] = array('@codigo',$codigocontri);
		
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente_coac', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],				  
						'nombre'=>utf8_encode($row[1]),
						'cod_pred'=>utf8_encode($row[2]),
						'anexo'=>utf8_encode($row[3]),
						'sub_anexo'=>utf8_encode($row[4]),
						'direccionpredio'=>utf8_encode($row[5]),
						'zona'=>utf8_encode($row[6])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	
	public function contribusviasAction(){
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$anno = $this->_request->getParam('anno','');
		
		$fn = new Libreria_Pintar();
				
		$evt[] = array('#btnBusquedacri',"click","buscarDatos()");
		$this->view->anno=$anno;
		$fn->PintarEvento($evt);
  }
    
}



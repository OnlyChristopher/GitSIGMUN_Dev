<?php


class MantpredController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		
    }
      
    public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$rdcriterio = $_REQUEST['rdcriterio'];
    	$criterio = $_REQUEST['criterio'];
    	
    	switch($rdcriterio)
    	{
    		case 'C': $codigo = $criterio; break;
    		case 'N': $nombre = $criterio; break;
    		case 'D': $documento = $criterio; break;
    	}
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@codigo',$codigo);
		$parametros[] = array('@nombres',$nombre);
		$parametros[] = array('@num_doc',$documento);
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.sp_MPredio', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@codigo',$codigo);
		$parametros[] = array('@nombres',$nombre);
		$parametros[] = array('@num_doc',$documento);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.sp_MPredio', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		foreach($rows AS $row){
			$entry = array(
					'cod_pre'=>$row[0],				  
					'nombre_via'=>utf8_encode($row[3]),
					'id_via'=>utf8_encode($row[4]),
					'nombabr'=>utf8_encode($row[5])
			);
			$jsonData['rows'][] = $entry;
		}
		
		$this->view->data = json_encode($jsonData);
    }
    
	public function buscarAction()
    {    	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;		
		
		$fn = new Libreria_Pintar();		
		
		$evt[] = array('#btnBusPers',"button","");
		$evt[] = array('#btnNuevaPers',"button","");
		
		$evt[] = array('#btnBusPers',"click","buscarPers()");
		$evt[] = array('#btnNuevaPers',"click","showPopup('mantcontri/formu','#popcontri','600','450','Nuevo Contribuyente');");
		$evt[] = array('#btnCerrarPers',"click","closePopup('#popBusPre')");
				
		$fn->PintarEvento($evt);
    }
       
}


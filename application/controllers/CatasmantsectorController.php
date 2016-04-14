<?php

/**
 * CatasmantsectorController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class CatasmantsectorController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() 
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$this->view->title = "Mantenimiento de Sector";
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnBusCatasector',"click","buscarSector()");
		$evt[] = array('#btnNuevocatasector',"click","showPopup('catasmantsector/formu','#popsectorcata','420','140','Nuevo Sector');");
		
		
		$fn->PintarEvento($evt);	
	}
	
    public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$rdcriterio = trim($_REQUEST['rdcriterio']);
    	$criterio = trim($_REQUEST['criterio']);
    	
    	switch($rdcriterio)
    	{
    		case 'C': $codigo = $criterio;break;
    		case 'N': $nombre = $criterio;break;
    	}
    	
    	//Para el total
    	$parametros[] = array('@msquery',2);
		$parametros[] = array('@csector',$codigo);
		$parametros[] = array('@nsector',$nombre);
		
		
		$rowsTotal = $cn->ejec_store_procedura_sqlc('MntCatas.pxCSector', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',1);		
		$parametros[] = array('@csector',$codigo);
		$parametros[] = array('@nsector',$nombre);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCSector', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],
						'sector'=>$row[1]	
				);
			
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
    
    public function formuAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		
		$evt[] = array('#btnGrabaVia',"button","");
		$evt[] = array('#btnSalirVia',"button","");
		
		$evt[] = array('#txtNomsector',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtNomsector',"blur","this.value = this.value.toUpperCase();");

		$evt[] = array('#txtCodcatasector',"keypress","return validaTeclas(event,'number');");

		
		$csector=$this->_request->getParam('codigo','');
		$csector_sector=$this->_request->getParam('codigo','');
		
		$parametros[] = array('@msquery',3);
		$parametros[] = array('@csector',$csector);
		$rowSector = $cn->ejec_store_procedura_sqlc('MntCatas.pxCSector', $parametros);
		
       
        if(count($rowSector)>0)
        {
        	$nombresector = $rowSector[0][1];
        }
		else
		{
		}
			
		$val[] = array('#txtCodcatasector',$csector,'val');
		$val[] = array('#txtNomsector',$nombresector,'val');
		$val[] = array('#txtCodcatasector_sector',$csector_sector,'val');
		
		$evt[] = array('#btnGrabaSector',"click","goToFormulario('frmcatasector');");
		$evt[] = array('#btnSalirSector',"click","closePopup('#popsectorcata');");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);    
    }  
    
	public function grabarAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$codsector = $this->_request->getPost('txtCodcatasector_sector');
			
			if(strlen(trim($codsector))>0)
			{
				$codsector = $this->_request->getPost('txtCodcatasector_sector');
				
				$parametros[] = array('@msquery',5);
				$parametros[] = array('@csector_sector',$codsector);
				$parametros[] = array('@csector',$this->_request->getPost('txtCodcatasector'));
				$parametros[] = array('@nsector',$this->_request->getPost('txtNomsector'));															
				@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCSector', $parametros);
				echo "Se grabo correctamente";
			}
			else 
			{
				$parametros[] = array('@msquery',7);
	    		$parametros[] = array('@csector',$this->_request->getPost('txtCodcatasector'));
	    		$rowsSector = $cn->ejec_store_procedura_sqlc('MntCatas.pxCSector', $parametros);
				//var_dump($rowsVias);
				if(!empty($rowsSector[0][1]))
					echo 'El codigo ingresado ya existe';
				else 
				{
					$codsector = $this->_request->getPost('txtCodcatasector_sector');
					
					unset($parametros);
					$parametros[] = array('@msquery',4);
					$parametros[] = array('@csector_sector',$codsector);
					$parametros[] = array('@csector',$this->_request->getPost('txtCodcatasector'));
					$parametros[] = array('@nsector',$this->_request->getPost('txtNomsector'));															
					@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCSector', $parametros);
					echo "Se grabo correctamente";
				}
			}
    	}    	
    	
    }
    
	public function eliminarAction()
    {		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
		
    		$cn = new Model_DbDatos_Datos();
    		
			$codigo = $this->_request->getParam('codigo','');
			
			$parametros[] = array('@msquery',6);
			$parametros[] = array('@csector',$codigo);
			
			@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCSector', $parametros);	
			
			echo "Se elimino correctamente";
		}

	}
}
<?php

/**
 * CatasmantviaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class CatasmantviaController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() 
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$this->view->title = "Mantenimiento de Vías";
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnBusCatavia',"click","buscarViacata()");
		$evt[] = array('#btnNuevocatavia',"click","showPopup('catasmantvia/formu','#popviacata','420','140','Nueva Via');");
		
		
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
    		case 'T': $tipo = $criterio;break;
    		case 'N': $nombre = $criterio;break;
    	}
    	
    	//Para el total
    	$parametros[] = array('@msquery',2);
		$parametros[] = array('@codivia',$codigo);
		$parametros[] = array('@tipovia',$tipo);
		$parametros[] = array('@nombvia',$nombre);
		
		
		$rowsTotal = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',1);		
		$parametros[] = array('@codivia',$codigo);
		$parametros[] = array('@tipovia',$tipo);
		$parametros[] = array('@nombvia',$nombre);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],
						'tipo'=>$row[1],
						'nombrevia'=>$row[2]	
				
						//'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
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
		
		$evt[] = array('#txtNomvia',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtNomvia',"blur","this.value = this.value.toUpperCase();");

		$evt[] = array('#txtCodcatavia',"keypress","return validaTeclas(event,'number');");
		
		$codvia=$this->_request->getParam('codigo','');
		$codvia_via=$this->_request->getParam('codigo','');
		
		$parametros[] = array('@msquery',3);
		$parametros[] = array('@codivia',$codvia);
		$rowVia = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);
		
       
        if(count($rowVia)>0)
        {
        	$tipovia = $rowVia[0][1];
			$nombrevia = $rowVia[0][2];
			$codvia_via = $rowVia[0][0];
        }
		else
		{
		}
		
		unset($parametros);
		$parametros[] = array('@msquery',5);	
		$tipoviacata = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);
		$arTipoviacata = $ar->RegistrosCombo($tipoviacata,0,1);
		$val[] = array('#cmbTipovia',$fn->ContenidoCombo($arTipoviacata,'[Seleccione]',trim($tipovia)),'html');
		
		$val[] = array('#txtCodcatavia',$codvia,'val');
		$val[] = array('#txtNomvia',$nombrevia,'val');
		$val[] = array('#txtCodcatavia_via',$codvia_via,'val');
		
		$evt[] = array('#btnGrabaVia',"click","goToFormulario('frmcatavia');");
		$evt[] = array('#btnSalirVia',"click","closePopup('#popviacata');");
		
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
			$codvia = $this->_request->getPost('txtCodcatavia_via');
			
			if(strlen(trim($codvia))>0)
			{
					$codvia = $this->_request->getPost('txtCodcatavia_via');
					
					//unset($parametros);		
					$parametros[] = array('@msquery',6);
					$parametros[] = array('@codivia_via',$codvia);
					$parametros[] = array('@codivia',$this->_request->getPost('txtCodcatavia'));
					$parametros[] = array('@tipovia',$this->_request->getPost('cmbTipovia'));
					$parametros[] = array('@nombvia',$this->_request->getPost('txtNomvia'));
																
					@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);
					
					echo "Se grabo correctamente";
			}
			else
			{
				$parametros[] = array('@msquery',8);
	    		$parametros[] = array('@codivia',$this->_request->getPost('txtCodcatavia'));
	    		$rowsVias = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);
				//var_dump($rowsVias);
				if(!empty($rowsVias[0][3]))
					echo 'El codigo ingresado ya existe';
				else
				{
					$codvia = $this->_request->getPost('txtCodcatavia_via');
					
					unset($parametros);		
					$parametros[] = array('@msquery',4);
					$parametros[] = array('@codivia_via',$codvia);
					$parametros[] = array('@codivia',$this->_request->getPost('txtCodcatavia'));
					$parametros[] = array('@tipovia',$this->_request->getPost('cmbTipovia'));
					$parametros[] = array('@nombvia',$this->_request->getPost('txtNomvia'));
					@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);
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
			
			$parametros[] = array('@msquery',7);
			$parametros[] = array('@codivia',$codigo);
			
			@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);	
			
			echo "Se elimino correctamente";
		}

	}

}


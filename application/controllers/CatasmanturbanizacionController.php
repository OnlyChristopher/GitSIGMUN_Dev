<?php

/**
 * CatasmanturbanizacionController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class CatasmanturbanizacionController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() 
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$this->view->title = "Mantenimiento de Habilitacion Urbana";
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnBusCataurba',"click","buscarUrbani()");
		$evt[] = array('#btnNuevocataurba',"click","showPopup('catasmanturbanizacion/formu','#popurbacata','420','140','Nueva Habilitacion Urbana');");
		
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
		$parametros[] = array('@codihab',$codigo);
		$parametros[] = array('@tipohab',$tipo);
		$parametros[] = array('@nombhab',$nombre);
		
		
		$rowsTotal = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',1);		
		$parametros[] = array('@codihab',$codigo);
		$parametros[] = array('@tipohab',$tipo);
		$parametros[] = array('@nombhab',$nombre);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],
						'tipo'=>$row[1],
						'nombreurba'=>$row[2]	
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
		
		$evt[] = array('#btnGrabaUrbani',"button","");
		$evt[] = array('#btnSalirUrbani',"button","");
		
		$evt[] = array('#txtNomurba',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtNomurba',"blur","this.value = this.value.toUpperCase();");

		$evt[] = array('#txtCodcataurba',"keypress","return validaTeclas(event,'number');");

		
		$codurba=$this->_request->getParam('codigo','');
		$codurba_urba=$this->_request->getParam('codigo','');
		
		$parametros[] = array('@msquery',4);
		$parametros[] = array('@codihab',$codurba);
		$rowUrbani = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);
		
       
        if(count($rowUrbani)>0)
        {
        	$tipourbani = $rowUrbani[0][1];
			$nombreurbani = $rowUrbani[0][2];
			$codurba_urba = $rowUrbani[0][0];
        }
		else
		{
		}
		
		unset($parametros);
		$parametros[] = array('@msquery',3);	
		$tipourbacata = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);
		$arTipourbacata = $ar->RegistrosCombo($tipourbacata,0,1);
		$val[] = array('#cmbTipourba',$fn->ContenidoCombo($arTipourbacata,'[Seleccione]',trim($tipourbani)),'html');
		
		$val[] = array('#txtCodcataurba',$codurba,'val');
		$val[] = array('#txtNomurba',$nombreurbani,'val');
		$val[] = array('#txtCodcataurba_urba',$codurba_urba,'val');
		
		$evt[] = array('#btnGrabaUrba',"click","goToFormulario('frmcataurbani');");
		$evt[] = array('#btnSalirUrba',"click","closePopup('#popurbacata');");
		
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
			$codurba = $this->_request->getPost('txtCodcataurba_urba');
			
			if(strlen(trim($codurba))>0)
			{
				$codurba = $this->_request->getPost('txtCodcataurba_urba');
				$parametros[] = array('@msquery',6);
				$parametros[] = array('@codihab_hab',$codurba);
				$parametros[] = array('@codihab',$this->_request->getPost('txtCodcataurba'));
				$parametros[] = array('@tipohab',$this->_request->getPost('cmbTipourba'));
				$parametros[] = array('@nombhab',$this->_request->getPost('txtNomurba'));
															
				@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);
				
				echo "Se grabo correctamente";
			}
			else 
			{
				$parametros[] = array('@msquery',8);
	    		$parametros[] = array('@codihab',$this->_request->getPost('txtCodcataurba'));
	    		$rowsUrba = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);
				
				if(!empty($rowsUrba[0][3]))
					echo 'El codigo ingresado ya existe';
				else
				{
					$codurba = $this->_request->getPost('txtCodcataurba_urba');
					
					unset($parametros);
					$parametros[] = array('@msquery',5);
					$parametros[] = array('@codihab_hab',$codurba);
					$parametros[] = array('@codihab',$this->_request->getPost('txtCodcataurba'));
					$parametros[] = array('@tipohab',$this->_request->getPost('cmbTipourba'));
					$parametros[] = array('@nombhab',$this->_request->getPost('txtNomurba'));
																
					@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);
					
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
			$parametros[] = array('@codihab',$codigo);
			
			@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);	
			
			echo "Se elimino correctamente";
		}

	}

}


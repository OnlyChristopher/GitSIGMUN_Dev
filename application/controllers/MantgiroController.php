<?php

class MantgiroController extends Zend_Controller_Action
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
    	
    	$rdcriterio = $_REQUEST['rdCritBusGiro'];
    	$criterio = $_REQUEST['txtCritBusGiro'];
    	
    	switch($rdcriterio)
    	{
    		case 'C': $idgiro = $criterio; break;
    		case 'N': $descrip = $criterio; break;
    	}
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@idgiro',$idgiro);
		$parametros[] = array('@descrip',$descrip);
				
		$rowsTotal = $cn->ejec_store_procedura_sql('WbSpGiro', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@idgiro',$idgiro);
		$parametros[] = array('@descrip',$descrip);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('WbSpGiro', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
        if(count($rows))
        {
		foreach($rows AS $row){
			$entry = array(
					'idgiro'=>$row[0],				  
					'descrip'=>utf8_encode($row[1]),
					
			);
			    $jsonData['rows'][] = $entry;
		    }
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
		
		$evt[] = array('#btnBusGiro',"click","buscarGiro()");
		$evt[] = array('#btnNuevoGiro',"click","showPopup('mantgiro/formu','#popNgiro','600','400','Nuevo Giro');");
		$evt[] = array('#btnCerrarGiro',"click","closePopup('#popBusGiro')");
				
		$fn->PintarEvento($evt);
    }
	
	public function formuAction()
    {    		
    	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$idgiro = $this->_request->getParam('idgiro','');
		$this->view->idgiro=$idgiro;
		
		$readonly = $this->_request->getParam('readonly','');
		$this->view->readonly=$readonly;
		
		if(strlen($idgiro)>0)
		{
			$parametros[] = array('@busc',4);
			$parametros[] = array('@idgiro',$idgiro);
			$rowGiro = $cn->ejec_store_procedura_sql('WbSpGiro', $parametros);
			
			//$idgiro = $rowGiro[0][0];
			$descrip = $rowGiro[0][1];
			$rdm = $rowGiro[0][2];
			$rda = $rowGiro[0][3];
			$vt = $rowGiro[0][4];
			$cv = $rowGiro[0][5];
			$cz = $rowGiro[0][6];
			$cm = $rowGiro[0][7];
			$i1 = $rowGiro[0][8];
			$i2 = $rowGiro[0][9];
			$i3 = $rowGiro[0][10];
			$i4 = $rowGiro[0][11];
			$nestado = $rowGiro[0][12];
			$operador = $rowGiro[0][13];
			$estacion = $rowGiro[0][14];
			$fech_ing = $rowGiro[0][15];
		}
		else
		{
			$idgiro = '';
			$id_dist = '003';
			$nestado = 1;
			$operador = 'prueba';
			$estacion = 'prueba';
		}
		
		$val[] = array('#txtCodGiro',$idgiro,'val');
		$val[] = array('#txtDesGiro',$descrip,'val');
		$val[] = array('#txtrdm',$rdm,'val');
		$val[] = array('#txrda',$rda,'val');
		$val[] = array('#txtvt',$vt,'val');
		$val[] = array('#txtcv',$cv,'val');
		$val[] = array('#txtcz',$cz,'val');
		$val[] = array('#txtcm',$cm,'val');
		$val[] = array('#txti1',$i1,'val');
		$val[] = array('#txti2',$i2,'val');
		$val[] = array('#txti3',$i3,'val');
		$val[] = array('#txti4',$i4,'val');
		$val[] = array('#nestado',$nestado,'val');
		$val[] = array('#operador',$operador,'val');
		$val[] = array('#estacion',$estacion,'val');
		
		$evt[] = array('#btnGrabaGiro',"click","goToFormulario('frmgiro');");
		$evt[] = array('#btnSalirGiro',"click","closePopup('#popNgiro');");
		
				
				
		
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
			$idgiro = $this->_request->getPost('txtCodGiro');
			
			if(strlen($idgiro)>0)
				$tip = 2;
			else
				$tip = 1;
			
			$parametros[] = array('@busc',$tip);
			$parametros[] = array('@idgiro',$idgiro);
			$parametros[] = array('@descrip',$this->_request->getPost('txtDesGiro'));
			$parametros[] = array('@rdm',$this->_request->getPost('txtrdm'));
			$parametros[] = array('@rda',$this->_request->getPost('txrda'));
			$parametros[] = array('@vt',$this->_request->getPost('txtvt'));
			$parametros[] = array('@cv',$this->_request->getPost('txtcv'));
			$parametros[] = array('@cz',$this->_request->getPost('txtcz'));
			$parametros[] = array('@cm',$this->_request->getPost('txtcm'));
			$parametros[] = array('@i1',$this->_request->getPost('txti1'));
			$parametros[] = array('@i2',$this->_request->getPost('txti2'));
			$parametros[] = array('@i3',$this->_request->getPost('txti3'));
			$parametros[] = array('@i4',$this->_request->getPost('txti4'));
			$parametros[] = array('@estado',$this->_request->getPost('nestado'));
			$parametros[] = array('@operador',$this->_request->getPost('operador'));
			$parametros[] = array('@estacion',$this->_request->getPost('estacion'));
																	
			@$rows = $cn->ejec_store_procedura_sql('WbSpGiro', $parametros);
			
			echo "Se grabo correctamente";
			}    	
    }
    
       
}


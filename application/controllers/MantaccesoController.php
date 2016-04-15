<?php

require_once 'Zend/Controller/Action.php';

class MantaccesoController extends Zend_Controller_Action {
	
	public function indexAction() {
	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$this->view->title = "Buscar  Acceso";
		
		$arTipos = $ar->TipoObjeto();
		$val[] = array('#cmbTipo',$fn->ContenidoCombo($arTipos,'[Todos]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',8);
		$menus = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso', $parametros);
		$arMenus = $ar->RegistrosCombo($menus,0,1);
		$val[] = array('#cmbMenu',$fn->ContenidoCombo($arMenus,'[Todos]',''),'html');
		$evt[] = array('#cmbMenu',"change","changeMenu(this.value);");
		
		unset($parametros);
		$parametros[] = array('@busc',9);
		$parametros[] = array('@id_acceso',$orditem);
		$pantallas = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso', $parametros);
		$arPantallas = $ar->RegistrosCombo($pantallas,0,1);
		$val[] = array('#cmbPantalla',$fn->ContenidoCombo($arPantallas,'[Todos]',''),'html');
		
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#btnBusAcceso',"button","");
		$evt[] = array('#btnNuevoAcceso',"button","");
		$evt[] = array('#btnBusAcceso',"click","buscarAcceso()");
		$evt[] = array('#btnNuevoAcceso',"click","showPopup('mantacceso/formu','#popNuevoAcceso','420','280','Nuevo Acceso');");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
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
		$tipo = $_REQUEST['tipo'];
		$menu = $_REQUEST['menu'];
		$pantalla = $_REQUEST['pantalla'];
    	
    	switch($rdcriterio)
    	{
    		case 'C': $id_acceso = $criterio; break;
    	    case 'D': $nombre = $criterio; break;
    	}
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@id_acceso',$id_acceso);
		$parametros[] = array('@nombre',$nombre);
		$parametros[] = array('@orden',$tipo);
		$parametros[] = array('@menu',$menu);
		$parametros[] = array('@pantalla',$pantalla);
	
		$rowsTotal = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@id_acceso',$id_acceso);
		$parametros[] = array('@nombre',$nombre);
		$parametros[] = array('@orden',$tipo);
		$parametros[] = array('@menu',$menu);
		$parametros[] = array('@pantalla',$pantalla);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		$rows = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'id_acceso'=>$row[0],	
						'orden'=>trim($row[1])=='M' ? 'MENU' : 'OBJETO',
						'nombre'=>utf8_encode($row[2]),
						'id_objeto'=>utf8_encode($row[3]),
						'icono'=>utf8_encode($row[4]),
						'doform'=>utf8_encode($row[5]),
						'nestado'=>$row[6]==1 ? 'ACTIVADO' : 'DESACTIVADO',
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
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();		
		
		$id_acceso = $this->_request->getParam('id_acceso','');
		
		if(!empty($id_acceso))
		{			
			$parametros[] = array('@busc',3);
			$parametros[] = array('@id_acceso',$id_acceso);			
			$rowsAcceso = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso', $parametros);
			
			$id_acceso = $rowsAcceso[0][0];
			$tipo = trim($rowsAcceso[0][1]);
			$nombre = $rowsAcceso[0][2];
			$id_objeto = $rowsAcceso[0][3];
			$menu = substr($id_acceso,0,2).'.00.00';
			$pantalla = substr($id_acceso,0,5).'.00';
			$icono = $rowsAcceso[0][4];
			$doform = $rowsAcceso[0][5];
			$chkestado = $rowsAcceso[0][6];
			
			if($tipo=='M'){
				$att[] = array('#cmbMenux',"disabled","true");
				$att[] = array('#cmbPantallax',"disabled","true");
				$att[] = array('#txtIdobjeto',"disabled","true");
			}			
		}
		else 
		{
			$id_acceso = "";
			$tipo = "";
			$nombre = "";
			$id_objeto = "";
			$menu = "";
			$pantalla = "";
			$icono = "";
			$doform = "";
			$chkestado = "";
		}
		
		$att[] = array ("#chkestado","checked",$chkestado==1 ? 'true' : 'false');
		
		$arTipos = $ar->TipoObjeto();
		$val[] = array('#cmbTipox',$fn->ContenidoCombo($arTipos,'[Seleccione]',$tipo),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',8);
		$menus = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso', $parametros);
		$arMenus = $ar->RegistrosCombo($menus,0,1);
		$val[] = array('#cmbMenux',$fn->ContenidoCombo($arMenus,'[Seleccione]',$menu),'html');
		$evt[] = array('#cmbMenux',"change","changeCombox(2,this.value);");
		
		unset($parametros);
		$parametros[] = array('@busc',9);
		$parametros[] = array('@id_acceso',$id_acceso);
		$pantallas = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso', $parametros);
		$arPantallas = $ar->RegistrosCombo($pantallas,0,1);
		$val[] = array('#cmbPantallax',$fn->ContenidoCombo($arPantallas,'[Seleccione]',$pantalla),'html');
		
		$val[] = array('#txtidacceso',$id_acceso,'val');
		$val[] = array('#txtidacceso_old',$id_acceso,'val');
		$val[] = array('#txtNomacceso',$nombre,'val');
		$val[] = array('#txtIdobjeto',$id_objeto,'val');
		$val[] = array('#txtIcono',$icono,'val');
		$val[] = array('#txtDoform',$doform,'val');
		
		$evt[] = array('#cmbTipox',"change","changeTipox(this.value);");
		$evt[] = array('#txtidacceso',"keypress","return validaTeclas(event,'numeric');");
		
		$evt[] = array('#btnGrabaAcceso',"button","");
		$evt[] = array('#btnSalirAcceso',"button","");
	
		$evt[] = array('#btnGrabaAcceso',"click","goToFormulario('frmacceso');");
		$evt[] = array('#btnSalirAcceso',"click","closePopup('#popNuevoAcceso');");
		
		if($tipo=='M'){
			$val[] = array('#cmbMenux','','val');
			$val[] = array('#cmbPantallax','','val');
		}
			
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		if(count($att)){
			$fn->AtributoComponente($att);
		}
    }
    //--------------------------------------------------------------------------------------------------------------------- 
	public function grabarAction()
	{    	
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		
		if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			
			$id_acceso = $this->_request->getPost('txtidacceso');
			$id_acceso_old = $this->_request->getPost('txtidacceso_old');
	 
			if(!empty($id_acceso_old))
				$tip = 2;
			else
				$tip = 1;
			
			$parametros[] = array("@busc",$tip);
			$parametros[] = array("@id_acceso",$id_acceso);
			$parametros[] = array("@acceso_antiguo",$id_acceso_old);
			$parametros[] = array("@orden",$this->_request->getPost('cmbTipox'));
			$parametros[] = array("@nombre",$this->_request->getPost('txtNomacceso'));
			$parametros[] = array("@id_objeto",$this->_request->getPost('txtIdobjeto'));
			$parametros[] = array('@icono',$this->_request->getPost('txtIcono'));
			$parametros[] = array('@doform',$this->_request->getPost('txtDoform'));			
			$parametros[] = array('@nestado',$this->_request->getPost('chkestado'));
			
			@$rows = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso',$parametros);
			
			echo "Se grab&oacute; correctamente";
		}
	}	
    //--------------------------------------------------------------------------------------------------------------------- 
    
	public function eliminarAction(){
				
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	
    	if($this->getRequest()->isXmlHttpRequest()){
    	
			$idacceso=$this->_request->getParam('id_acceso','');
			$array=explode('*',$idacceso);
			$cn = new Model_DbDatos_Datos();
			$nombreprocedure="Acceso.SP_MAcceso";
			$arraydatos[0] = array("@busc",4);
			$arraydatos[1] = array("@id_acceso", $array[0]);
			@$rows=$cn->ejec_store_procedura_sql($nombreprocedure,$arraydatos);
		
			echo 'Se elimino correctamente';
    	}
	}
	
	//--------------------------------------------------------------------------------------------------------------------- 
    
	public function combosAction(){
				
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	
    	if($this->getRequest()->isXmlHttpRequest()){
    	
			$cn = new Model_DbDatos_Datos();
			
			$opt = $this->_request->getPost('opt');
			$valor = $this->_request->getPost('valor');
			
			switch($opt){				
				case 2:
					$arraydatos[] = array("@busc",9);
					$arraydatos[] = array("@id_acceso", $valor);
					$rows = $cn->ejec_store_procedura_sql("Acceso.SP_MAcceso",$arraydatos);
					
					$strHtml = '<option value="">[Seleccione]</option>';
					for ($i=0;$i<count($rows);$i++)
						$strHtml .= '<option value="'.$rows[$i][0].'" >'.utf8_encode($rows[$i][1]).'</option>';
					
					echo $strHtml;
				break;
				case 3:
					$arraydatos[] = array("@busc",9);
					$arraydatos[] = array("@id_acceso", $valor);
					$rows = $cn->ejec_store_procedura_sql("Acceso.SP_MAcceso",$arraydatos);
					
					$strHtml = '<option value="">[Todos]</option>';
					for ($i=0;$i<count($rows);$i++)
						$strHtml .= '<option value="'.$rows[$i][0].'" >'.utf8_encode($rows[$i][1]).'</option>';
					
					echo $strHtml;
				break;
			}
			
    	}
	}
	
	public function validaccesoAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$cn = new Model_DbDatos_Datos ();
		
		$idacceso = $this->_request->getPost('idacceso');
		$idacceso_old = $this->_request->getPost('idacceso_old');
		
		if(strcmp($idacceso, $idacceso_old)){
			$arraydatos[] = array("@busc", 4);
			$arraydatos[] = array("@id_acceso", $idacceso);
			$rows = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso',$arraydatos);
				
			if(count($rows)>0)
				echo false;
			else
				echo true;
		}
		else
			echo true;
	}
				
}


<?php

require_once 'Zend/Controller/Action.php';

class MantperfilController extends Zend_Controller_Action {

	public function init(){
		Zend_Session::start();
		$id_acceso=new Zend_Session_Namespace('id_acceso');
	}
	
	public function indexAction() {
		
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Buscar Perfil";
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
		$arEstados = $ar->EstadosUsuario();
		$val[]  = array('#cmbEst',$fn->ContenidoCombo($arEstados,'[Todos]',''),'html');
		
		$evt[] = array('#btnSearchUsuario',"click","buscarPerfil()");
		$evt[] = array('#btnNewPerfil',"click","showPopup('mantperfil/formu','#popNewPerfil','890','400','Nuevo Perfil');");
		
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
		$estado = $_REQUEST['estado'];
    	
    	switch($rdcriterio)
    	{
    		case 'C': $id_perfil = $criterio; break;
    		case 'N': $nombre = $criterio; break;
    	}
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@id_perfil',$id_perfil);
		$parametros[] = array('@nombre',$nombre);
		$parametros[] = array('@nestado',$estado);
		$rowsTotal = $cn->ejec_store_procedura_sql('Acceso.sp_TblPerfil', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@id_perfil',$id_perfil);
		$parametros[] = array('@nombre',$nombre);
		$parametros[] = array('@nest',$estado);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblPerfil', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'id_perfil'=>$row[0],
						'nombre'=>utf8_encode($row[1]),
						'nestado'=>$row[2]=='1'? 'ACTIVADO' : 'DESACTIVADO'					
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
		
		$id_perfil = $this->_request->getParam('id_perfil','');
		
		if(!empty($id_perfil))
		{
			$arraydatos[] = array("@busc",4);
			$arraydatos[] = array("@id_perfil",$id_perfil);
			$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblPerfil',$arraydatos);
			
			$id_usuario = $rows[0][0];
			$nombre = $rows[0][1];
			$nestado = $rows[0][2];
		}
				
		if($nestado=='1')
			$att[] = array('#chkEstPerfil','checked','true');
		
		$val[] = array('#txtIdPerfil',$id_usuario,"val");
		$val[] = array('#txtNomPerfil',$nombre,"val");
		
		$evt[] = array('#btnGrabaPerfil',"click","goToFormulario('frmperfil');");
		$evt[] = array('#btnSalirPerfil',"click","closePopup('#popNewPerfil');");
				
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		if(count($att))
			$fn->AtributoComponente($att);
    }
    
	public function conmodulosAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
		$perfil = $this->_request->getParam('perfil','');
		
		if(!empty($perfil)){
			
			$parametros[] = array('@busc',7);		
			$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblPerfil', $parametros);
			
			$jsonData = array('total'=>count($rows),'rows'=>array());
			foreach($rows AS $row){
				$entry = array(
						'id_acceso'=>utf8_encode($row[0]),
						'nombre'=>utf8_encode($row[1])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		else
			$jsonData['rows'][] = null;
			
		$this->view->data = json_encode($jsonData);
    }
    
	public function conaccesoAction(){
	    	
		$cn = new Model_DbDatos_Datos();
		
		$id_acceso = $this->_request->getParam('id_acceso','');		
		$perfil = $this->_request->getParam('perfil','');
		
		if(!empty($perfil)){
			
			$parametros[] = array('@busc',8);
			$parametros[] = array('@id_acceso',$id_acceso);		
			$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblPerfil', $parametros);
			
			$jsonData = array('total'=>count($rows),'rows'=>array());
			foreach($rows AS $row){ 
				$entry = array(
						'id_acceso'=>utf8_encode($row[0]),
						'nombre'=>utf8_encode($row[1])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		else
			$jsonData['rows'][] = null;
			
		$this->view->data = json_encode($jsonData);
	}    
    
	public function grabarAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();		
		if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			
			$id_perfil = $this->_request->getPost('txtIdPerfil');
			$nombre = trim($this->_request->getPost('txtNomPerfil'));
			$nestado = trim($this->_request->getPost('chkEstPerfil'));
			
			if(!empty($id_perfil))
				$busc = 2;
			else
				$busc = 1;
				
			$arraydatos[] = array("@busc", $busc);
			$arraydatos[] = array("@id_perfil", $id_perfil);
			$arraydatos[] = array("@nombre", $nombre);
			$arraydatos[] = array("@nestado",$nestado);
			
			$rows = $cn->ejec_store_procedura_sql("Acceso.sp_TblPerfil",$arraydatos);
			
			echo trim($rows[0][0]);
		}
	}
    
	public function eliminarAction(){
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	
		$id_perfil = $this->_request->getParam('id_perfil','');
		
		$cn = new Model_DbDatos_Datos();
		
		$arraydatos[] = array("@busc", 3);
		$arraydatos[] = array("@id_perfil",$id_perfil);
		
		@$rows = $cn->ejec_store_procedura_sql("Acceso.sp_TblPerfil",$arraydatos);
		
		echo 'Registro eliminado correctamente';
		
	}
			
	public function gaccesoAction(){
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();    	
	    if($this->getRequest()->isXmlHttpRequest()){
		
			$cn = new Model_DbDatos_Datos();
			
			$id_perfil = $this->_request->getPost('idperfil');
			$id_acceso = $this->_request->getPost('idacceso');
			$bacceso = $this->_request->getPost('bacceso');
			
			$arraydatos[] = array("@busc", 9);
			$arraydatos[] = array("@id_perfil", $id_perfil);
			$arraydatos[] = array("@id_acceso", $id_acceso);
			$arraydatos[] = array("@bacceso", $bacceso);
			
			$rows = $cn->ejec_store_procedura_sql("Acceso.sp_TblPerfil",$arraydatos);
		}
	}
	
	public function cargachecksAction(){		
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		if($this->getRequest()->isXmlHttpRequest()){
	    		
			$cn = new Model_DbDatos_Datos();
			
			$id_perfil = $this->_request->getPost('id_perfil');
			
			$arraydatos[] = array("@busc",10);
			$arraydatos[] = array("@id_perfil",$id_perfil);
			$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblPerfil',$arraydatos);
			
			$cadena = "";
			
			foreach($rows AS $row){
				if($row[3])
					$cadena .= $row[2]."|";
			}
			
			echo $cadena;
	    }
	}	
			
	public function objectAction()
    {    		    	   	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$id_perfil = $this->_request->getParam('idPerfil','');
		$id_pantalla = $this->_request->getParam('idPantalla','');
		
		$this->view->pantalla = $id_pantalla;
		
		$val[] = array('#hdnPerfAccess',$id_perfil,'val');
		$val[] = array('#hdnPantAccess',$id_pantalla,'val');
		
		$evt[] = array('#btnSalirPerfilAccess',"click","closePopup('#popMantPerfilObj');");
			
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);		
	}
	
	public function conobjectAction(){
	    	
		$cn = new Model_DbDatos_Datos();
				
		$id_perfil = $this->_request->getParam('perfil','');
		$id_acceso = $this->_request->getParam('pantalla','');
		
		if(!empty($id_perfil)){
			
			$parametros[] = array('@busc',11);
			$parametros[] = array('@id_acceso',$id_acceso);		
			$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblPerfil', $parametros);
			
			$jsonData = array('total'=>count($rows),'rows'=>array());
			foreach($rows AS $row){ 
				$entry = array(
						'id_acceso'=>utf8_encode($row[0]),
						'nombre'=>utf8_encode($row[1]),
						'id_objeto'=>utf8_encode($row[2])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		else
			$jsonData['rows'][] = null;
			
		$this->view->data = json_encode($jsonData);
	}
}		

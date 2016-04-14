<?php

require_once 'Zend/Controller/Action.php';

class MantusuarioController extends Zend_Controller_Action {

	public function init(){
		//...
	}
	
	public function indexAction() {
		
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Buscar Usuario";
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$areas = $cn->ejec_store_procedura_sql('sp_tccostos', $parametros);
		$arAreas = $ar->RegistrosCombo($areas,0,1);
		$val[] = array('#cmbAre',$fn->ContenidoCombo($arAreas,'[Todos]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',4);
		$perfiles = $cn->ejec_store_procedura_sql('Acceso.sp_TblPerfil', $parametros);
		$arPerfiles = $ar->RegistrosCombo($perfiles,0,1);
		$val[] = array('#cmbPerf',$fn->ContenidoCombo($arPerfiles,'[Todos]',''),'html');
		
		$arEstados = $ar->EstadosUsuario();
		$val[]  = array('#cmbEst',$fn->ContenidoCombo($arEstados,'[Todos]',''),'html');
		
		$evt[] = array('#btnBusUsuario',"click","buscarUsuario()");
		$evt[] = array('#btnNuevoUsuario',"click","showPopup('mantusuario/formu?actionUsuario=A','#popNuevoUsu','470','420','Nuevo Usuario');");
		$evt[] = array('#btnNuevaCajaUsuario',"click","showPopup('mantusuario/caja?actionCaja=B','#popNuevaUsuCaj','300','70','Nueva Caja');");
		
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
		$area = $_REQUEST['area'];
		$perfil = $_REQUEST['perfil'];
		$estado = $_REQUEST['estado'];
    	
    	switch($rdcriterio)
    	{
    		case 'C': $id_usuario = $criterio; break;
    		case 'N': $nombre = $criterio; break;
			case 'U': $vlogin = $criterio; break;
    	}
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@id_usuario',$id_usuario);
		$parametros[] = array('@area',$area);
		$parametros[] = array('@id_perfil',$perfil);
		$parametros[] = array('@nombres',$nombre);
		$parametros[] = array('@vlogin',$vlogin);
		$parametros[] = array('@nest',$estado);
		$rowsTotal = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@id_usuario',$id_usuario);
		$parametros[] = array('@area',$area);
		$parametros[] = array('@id_perfil',$perfil);
		$parametros[] = array('@nombres',$nombre);
		$parametros[] = array('@vlogin',$vlogin);
		$parametros[] = array('@nest',$estado);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'id_usuario'=>$row[0],	
						'nombre'=>utf8_encode($row[1]),
						'area'=>utf8_encode($row[2]),
						'perfil'=>utf8_encode($row[3]),
						'vlogin'=>utf8_encode($row[4]),
						'nestado'=>$row[5]=='1'? 'ACTIVADO' : 'DESACTIVADO'					
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
		
		$id_usuario = $this->_request->getParam('id_usuario','');
		
		if(!empty($id_usuario))
		{
			$arraydatos[] = array("@busc",9);
			$arraydatos[] = array("@id_usuario",$id_usuario);
			$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios',$arraydatos);
			
			$this->view->cod=$id_usuario;
			$this->view->login=$rows[0][1];
			$this->view->pass=$rows[0][2];
			$this->view->confir=$rows[0][3];
			$this->view->nombre=$rows[0][4];
			$this->view->apellido=$rows[0][5];
			$this->view->documento=$rows[0][7];
			$this->view->cargo=$rows[0][8];
			
			$cajero = $rows[0][9];
			if($cajero){
				$fun[] = array("habilitaCajero(true);");
				$att[] = array("#chkUsuCaj","checked",'true');
			}
			else
				$fun[] = array("habilitaCajero(false);");
			
			$fun[] = array("validaText('".$rows[0][6]."');");
		}
		else{
			$cajero = 0;
			$fun[] = array("habilitaCajero(false);");
			$fun[] = array("validaText('01/8');");
		}
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$areas = $cn->ejec_store_procedura_sql('sp_tccostos', $parametros);
		$arAreas = $ar->RegistrosCombo($areas,0,1);
		$val[] = array('#cmbArea',$fn->ContenidoCombo($arAreas,'[Seleccione]',$rows[0][0]),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',8);
		$documentos = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbDoc',$fn->ContenidoCombo($arDocumentos,'',$rows[0][6]),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',10);
		$cajeros = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios', $parametros);
		$arCajeros = $ar->RegistrosCombo($cajeros,0,0);
		$val[] = array('#cmbCajero',$fn->ContenidoCombo($arCajeros,'[...]',$rows[0][10]),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',4);
		$perfiles = $cn->ejec_store_procedura_sql('Acceso.sp_TblPerfil', $parametros);
		$arPerfiles = $ar->RegistrosCombo($perfiles,0,1);
		$val[] = array('#cmbPerfil',$fn->ContenidoCombo($arPerfiles,'[Seleccione]',$rows[0][12]),'html');
		
		if($rows[0][11]=='1')
			$att[] = array('#rdEstado1','checked','true');
		if($rows[0][11]=='0')
			$att[] = array('#rdEstado2','checked','true');
		
		$evt[] = array('#txtNomUsuario',"keypress","return validaTeclas(event,'text');");		
		$evt[] = array('#txtNomUsuario',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtApeUsuario',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtApeUsuario',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtdocUsuario',"keypress","return validaTeclas(event,'numeric');");
				
		$evt[] = array('#btnGrabaUsuarios',"click","goToFormulario('frm_mantenimiento');");
		$evt[] = array('#btnSalirUsuarios',"click","closePopup('#popNuevoUsu');");
		
		$evt[] = array('#btnClaveUsuarios',"click",'claveUsuario();');
		
		$evt[] = array('#cmbDoc',"change","validaText($(this).val());");
	
		$evt[] = array('#chkUsuCaj','click',"habilitaCajero(this.checked);"); 
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		if(count($att))
			$fn->AtributoComponente($att);
		if(count($fun))
			$fn->EjecutarFuncion($fun);
    }
    
    
	public function grabarAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			
			$id_usuario = $this->_request->getPost('txtUsuUsuario');
			$criterios = $this->_request->getPost('txtUsuUsuario')=='' ? '1' : '2';
			$comboarea = trim($this->_request->getPost('cmbArea'));
			$nombres = trim($this->_request->getPost('txtNomUsuario'));
			$apellidos = trim($this->_request->getPost('txtApeUsuario'));
			$combodoc = trim($this->_request->getPost('cmbDoc'));
			$num_doc = trim($this->_request->getPost('txtdocUsuario'));
			$vlogin = trim($this->_request->getPost('txtVlogUsuario'));
			$password = trim($this->_request->getPost('txtContraUsuario'));
			$confirm = trim($this->_request->getPost('txtConfirUsuario'));
			$cargo = trim($this->_request->getPost('txtaCargo'));
			$cajero = $this->_request->getPost('chkUsuCaj');
			$caja = trim($this->_request->getPost('cmbCajero'));
			$id_perfil = trim($this->_request->getPost('cmbPerfil'));
			$nestado = trim($this->_request->getPost('rdEstado'));
			
			$arraydatos[] = array("@busc", $criterios);               
			$arraydatos[] = array("@id_usuario", $id_usuario);
			$arraydatos[] = array("@area", $comboarea);
			$arraydatos[] = array("@nombres", $nombres);
			$arraydatos[] = array("@apellidos", $apellidos);
			$arraydatos[] = array("@id_doc", $combodoc);   
			$arraydatos[] = array("@num_doc",$num_doc);
			$arraydatos[] = array("@vlogin", $vlogin);
			$arraydatos[] = array("@password",$password);
			$arraydatos[] = array("@confir",$confirm);
			$arraydatos[] = array("@cargo",$cargo);
			$arraydatos[] = array("@cajero",$cajero);
			$arraydatos[] = array("@caja",$caja);
			$arraydatos[] = array("@id_perfil",$id_perfil);
			$arraydatos[] = array("@nestado",$nestado);
			
			$rows = $cn->ejec_store_procedura_sql("Acceso.sp_TblUsuarios",$arraydatos);
			
			echo trim($rows[0][0]);
		}
	}
    
	public function eliminarAction(){
	
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		if($this->getRequest()->isXmlHttpRequest()){
			
			$id_usuario=$this->_request->getParam('id_usuario','');
			
			$array=explode('*',$id_usuario);
			
			$cn = new Model_DbDatos_Datos();
			
			$nombreprocedure="Acceso.sp_TblUsuarios";
			$arraydatos[0] = array("@busc", 3);
			$arraydatos[1] = array("@id_usuario", $array[0]);
			
			@$rows=$cn->ejec_store_procedura_sql($nombreprocedure,$arraydatos);
			
			echo 'Registro eliminado correctamente';
		}
	}
			
	//--------------------------------------------------------------------------------------------
	public function cajaAction(){
	    $path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
					
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#btnGrabaCaja',"button","");                                                                   
		$evt[] = array('#btnSalirCaja',"button","");
		$evt[] = array('#btnGrabaCaja',"click","goToFormulario('frm_caja');");
		$evt[] = array('#btnSalirCaja',"click","closePopup('#popNuevaUsuCaj');");
		
		$fn->PintarEvento($evt);
	}
		
	//--------------
	public function insertcajaAction(){
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		if($this->getRequest()->isXmlHttpRequest()){
			$cn = new Model_DbDatos_Datos();
			
			$caja = $this->_request->getPost('txtCajaUsuario');
			
			$arraydatos[] = array("@busc", 19);
			$arraydatos[] = array("@caja", $caja);
			$rowsCaja = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios',$arraydatos);
			
			if(!empty($rowsCaja[0][0])){
				echo 'El codigo ingresado ya existe';
			}
			else{
				$arraydatos[0] = array("@busc", 14);
				$arraydatos[1] = array("@caja", $caja);
				
				@$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios',$arraydatos);
					
				echo 'Se grabo correctamente';
			}
		}
	}
    	
    //-----------------------------------------------------------------------------------------------	
	
	public function passwordAction(){
		 
		$fn = new Libreria_Pintar();
		
		$id_usuario = $this->_request->getParam('idUsuario','');
		
		$evt[] = array('#contentBox',"tabs","");
		
		$val[] = array('#hidIdUsuario',$id_usuario,'val');
		
		$evt[] = array('#btnGrabaPassword',"click","goToFormulario('frm_password');");
		$evt[] = array('#btnSalirPassword',"click","closePopup('#popNuevaUsupass');");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
			
	}
	//----------------------------------------------------------------------------
		
	public function grabarpassAction(){
			
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();    	
		if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			$id_usuario = $this->_request->getPost('hidIdUsuario');
						
			$arraydatos[] = array("@busc",15);
			$arraydatos[] = array("@id_usuario",$id_usuario);
			$arraydatos[] = array("@password",$this->_request->getPost('txtNuevaContr'));
			$arraydatos[] = array("@confir",$this->_request->getPost('txtConfirContr'));
			
			@$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios',$arraydatos);
			
			echo "Se grabo correctamente";
		}
	}
	
	public function validaloginAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos ();
			
			$vlogin = $this->_request->getPost ( 'vlogin' );
			$vloginOld = $this->_request->getPost ( 'vloginOld' );
			
			if(strcmp(strtolower($vlogin), strtolower($vloginOld))){
				$arraydatos [] = array ("@busc", 18);
				$arraydatos [] = array ("@vlogin", $vlogin );
				$rows = $cn->ejec_store_procedura_sql ('Acceso.sp_TblUsuarios',$arraydatos);
					
				if(count($rows)>0)
					echo false;
				else
					echo true;
			}
			else
				echo true;
		}
	}
	
	public function changepassAction()
    {	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;		
		$this->view->title = "Cambiar Contraseña";		
		
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		$login = new Zend_Session_Namespace('login');
		
		$val[] = array('#usu_cod',$login->user,'val');
				
		$evt[]= array('#btnAceptar',"click","aceptar();");
		$evt[]= array('#btnCancelar','click',"cancelar();" );
				
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);	
	}
	
	public function oldpassAction()
    {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos ();
			$login = new Zend_Session_Namespace('login');	
			
			$old = $this->_request->getParam('old_pass','');
			
			$arraydatos[] = array("@busc", 20);
			$arraydatos[] = array("@vlogin", $login->user);
			$arraydatos[] = array("@password", $old);
			
			$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios',$arraydatos);
			
			if(count($rows))
				echo 'true';		
			else
				echo 'false';
		}
	}
	
	public function newpassAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos ();
			$login = new Zend_Session_Namespace('login');
			
			$new_pass = $this->_request->getPost('new_pass');
			
			$arraydatos[] = array("@busc", 21);
			$arraydatos[] = array("@vlogin", $login->user);
			$arraydatos[] = array("@password", $new_pass);
			
			$rows = $cn->ejec_store_procedura_sql('Acceso.sp_TblUsuarios',$arraydatos);
			
			$msj = "Contrase&ntilde;a actualizada correctamente";
		
			echo $msj;
		}
	}
		
}		

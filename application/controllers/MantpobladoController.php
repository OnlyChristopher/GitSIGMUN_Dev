<?php

/**
 * MantpobladoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class MantpobladoController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Mantenimiento de Centro Poblado";
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnBusPoblado',"click","buscarPoblad()");
		
		$evt[] = array('#btnVerPoblado',"click","verdetalle();");
		$evt[] = array('#btnNuevoPoblado',"click","showPopup('mantpoblado/formu?actionPoblado=A','#popNueZona','380','300','Ingreso de Centro Poblado');");
		
		$fn->PintarEvento($evt);
	}
	
	public function formuAction()
    {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$codigo  = $this->_request->getParam('codigo');
		
		if(!empty($codigo))
		{
			$arraydatos [] = array ("@busc",3);
			$arraydatos [] = array ("@cod_via", $codigo );	
			$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $arraydatos);
			
			$codigo	= $rows[0][0];
			$id_urba =  $rows[0][1];
			$tipovia = $rows[0][2];
			$vcuadra = $rows[0][3];
			$id_zona = $rows[0][4];
			$nombre_via = $rows[0][5];
			$id_tipozona =  $rows[0][6];
			$nestado =  $rows[0][7];
			
			$val[] = array('#txtFecha',$fecha,'val');
		}
		
		//para visualizar el codigo de la via
		$val[] = array('#txtCodPoblado',$codigo,'val');
		
		//para saber el tipo (via ó denominacion)
		if($id_urba=='0001')
		{
			$cad[] = array("#rdTipoVia","checked", true);
			//mostramos los items en el combo
			unset($parametros);
			$parametros[] = array('@busc',6);	
			$tipoTarjeta = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $parametros);
			$arComboTipoTarjeta = $ar->RegistrosCombo($tipoTarjeta,0,1);
			$val[] = array('#cmbUbicacion',$fn->ContenidoCombo($arComboTipoTarjeta,'[Seleccione]',$tipovia),'html');
						
		}
		else 
		{
		    $cad[] = array("#rdTipoDenominacion","checked", true);
		 
			//mostramos los item en el combo
			unset($parametros);
			$parametros[] = array('@busc',5);	
			$tipoTarjeta = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $parametros);
			$arComboTipoTarjeta = $ar->RegistrosCombo($tipoTarjeta,0,1);
			$val[] = array('#cmbUbicacion',$fn->ContenidoCombo($arComboTipoTarjeta,'[Seleccione]',$tipovia),'html');
		}
		
			
		//para visualizar la vcuadra
		$val[] = array('#txtCuadra',$vcuadra,'val');
		$evt[] = array('#txtCuadra',"keypress","return validaTeclas(event,'numeric');");
		
		//para visualizar el nombre de la via
		$val[] = array('#txtNombre',$nombre_via,'val');
		$evt[] = array('#txtNombre',"blur","this.value = this.value.toUpperCase();");
		
		//para visualizar el estado 
		if($nestado==1)
			$cad[] = array("#nestado","checked", true);
	

		
			
		//para visualizar la zona
			unset($parametros);
			$parametros[] = array('@busc',7);	
			$tipoTarjeta = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $parametros);
			$arComboTipoTarjeta = $ar->RegistrosCombo($tipoTarjeta,0,1);
			$val[] = array('#cmbZona',$fn->ContenidoCombo($arComboTipoTarjeta,'[Seleccione]',$id_zona),'html');
		
		//para visualizar el tipo de zona
		if($id_tipozona=='1')
			$cad[] = array("#rdalta","checked", true);
		else
			$cad[] = array("#rdbaja","checked", true);
		
		if(count($cad)>0)
			$fn->AtributoComponente($cad);
			
		$evt[] = array('#btnSalirPoblados',"click","closePopup('#popNueZona');");
		$evt[] = array('#btnGrabaPoblado',"click","goToFormulario('frmpoblado');");
		
		$fn->PintarEvento($evt);	
		$fn->PintarValor($val);
	}
	
    /*public function formuAction()
    {    		
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#txtNombre',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtNombre',"blur","this.value = this.value.toUpperCase();");
		
		$cn = new Model_DbDatos_Datos();
           	        	
        $cod=$this->_request->getParam('codigo','');
        if(!empty($cod))
        {
        	$nombreprocedure="Rentas.SP_MCUrba";
			$arraydatos[0] = array("@msquery", 7);
			$arraydatos[1] = array("@id_urba", $cod);
			$rows = $cn->ejec_store_procedura_sql($nombreprocedure, $arraydatos);
			
			$this->view->cod=$cod;
			$this->view->nombre=$rows[0][1];
				
			$combostore1 ='Rentas.SP_MCUrba';
			$arraydatos[0] = array("@msquery",5);
			$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos);
			$cb_Urba='<option value="">[Seleccione]</option>';
	
			for ($i=0;$i<count($rows1);$i++){
					if($rows1[$i][0]==$rows[0][0])
					{
						$cb_Urba.='<option value="'.$rows1[$i][0].'" selected>'.$rows1[$i][1].'</option>';
					}
					else{
						$cb_Urba.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][1].'</option>';						
					}
        		}
        
			$combostore2 ='Rentas.SP_MCUrba';
	        $arraydatos[0] = array("@msquery", 6);
	        $rows2 = $cn->ejec_store_procedura_sql($combostore2,$arraydatos);
	        $cb_Zona='<option value="">[Seleccione]</option>';
		    //var_dump($rows[0][2]);
			for ($i=0;$i<count($rows2);$i++){
					if($rows2[$i][0]==$rows[0][2])
					{
						$cb_Zona.='<option value="'.$rows2[$i][0].'" selected>'.$rows2[$i][1].'</option>';
						//echo "A--";
					}
					else{
            			$cb_Zona.='<option value="'.$rows2[$i][0].'" >'.$rows2[$i][1].'</option>';
					}
        		}
	        $val[] = array('#cmbUrba',$cb_Urba,'html');	
	        $val[] = array('#cmbZona',$cb_Zona,'html');	
        }
        else{
        	   
        	$combostore1 ='Rentas.SP_MCUrba';
			$arraydatos1[0] = array("@msquery",5);
			$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);
			$cb_Urba='<option value="">[Seleccione]</option>';
    

			for ($i=0;$i<count($rows1);$i++){
            	$cb_Urba.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][1].'</option>';
        		}
		
        	$val[] = array('#cmbUrba',$cb_Urba,'html');	
        	
	    	$combostore2 ='Rentas.SP_MCUrba';
	        $arraydatos2[0] = array("@msquery", 6);
	        $rows2 = $cn->ejec_store_procedura_sql($combostore2,$arraydatos2);
		    $cb_Zona='<option value="">[Seleccione]</option>';
    
			for ($i=0;$i<count($rows2);$i++){
            	$cb_Zona.='<option value="'.$rows2[$i][0].'" >'.$rows2[$i][1].'</option>';
        		}
        	$val[] = array('#cmbZona',$cb_Zona,'html');	
        }
        
		$evt[] = array('#btnGrabaPoblado',"click","goToFormulario('frmpoblado');");
		$evt[] = array('#btnSalirPoblados',"click","closePopup('#popNueZona');");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
    }*/
    
	public function constubicacionAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
		$fn = new Libreria_Pintar();
	    
	    if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$id_tipo = $this->_request->getPost('id_tipo');
			
			if($id_tipo=='1')
			{
				$combostore1 ='[Rentas].[sp_Mant_Vias]';
				$arraydatos1[0] = array("@busc",6);
				//$arraydatos1[1] = array("@id_tipocontri",$id_tipocontri);
				$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);
				
				$cb_Tipocontri='<option value="">[Seleccione]</option>';
				for ($i=0;$i<count($rows1);$i++){
					$cb_Tipocontri.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][1].'</option>';
				}
			}
			if($id_tipo=='2')
			{
				$combostore1 ='[Rentas].[sp_Mant_Vias]';
				$arraydatos1[0] = array("@busc",5);
				//$arraydatos1[1] = array("@id_tipocontri",$id_tipocontri);
				$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);
				
				$cb_Tipocontri='<option value="">[Seleccione]</option>';
				for ($i=0;$i<count($rows1);$i++){
					$cb_Tipocontri.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][1].'</option>';
				}
			}
			echo $cb_Tipocontri;
			
    	}  
    }
	
	
    public function consultaAction()
    { 
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$codigo=$_REQUEST['codigo'];
		$zona=$_REQUEST['zona'];
		$urbanizacion=$_REQUEST['urbanizacion'];
		$nomvia=$_REQUEST['nomvia'];
		$estado=$_REQUEST['estado'];
		
		$parametros[]=array('@busc',2);
		$parametros[]=array("@cod_via",$codigo);
		$parametros[]=array("@nom_zona",$zona);
		$parametros[]=array("@nom_urba",$urbanizacion);
		$parametros[]=array("@nombre_via",$nomvia);
		$parametros[]=array("@nestado",$estado);
		
		
		
		$rowsTotal = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $parametros);
    	
		//Para las filas
		unset($parametros);
		$parametros[]=array('@busc',1);
		$parametros[]=array("@cod_via",$codigo);
		$parametros[]=array("@nom_zona",$zona);
		$parametros[]=array("@nom_urba",$urbanizacion);
		$parametros[]=array("@nombre_via",$nomvia);
		$parametros[]=array("@nestado",$estado);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'		=>$row[0],
						'zona'			=>utf8_encode($row[1]),
						'urbanizacion'	=>utf8_encode($row[2]),
						'via'			=>utf8_encode($row[3]),
						'cuadra'		=>utf8_encode($row[4]),
						'estado'		=>utf8_encode($row[5])
				
						//'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
				);
			
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
		
    }
    
   
	public function grabarAction()
    {
    	$login = new Zend_Session_Namespace('login');
		
		$usuario = $login->user;
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$codpobaldo = $this->_request->getPost('txtCodPoblado');
			
			if(strlen($codpobaldo)>0)
				$tip = 4;
			else
				$tip = 8;
				
			unset($parametros);	
			$parametros[] = array('@busc',$tip);
			$parametros[] = array('@cod_via',$codpobaldo);
			$urba=$this->_request->getPost('rdCriterioTipo');
			if($urba==1)$newurba='0001';
			else $newurba='0002';
			$parametros[] = array('@id_urba',$newurba);
			//$parametros[] = array('@nombres',$this->_request->getPost('txtNomPoblado'));
			$parametros[] = array('@tipovia',$this->_request->getPost('cmbUbicacion'));
			$parametros[] = array('@vcuadra',$this->_request->getPost('txtCuadra'));
			$parametros[] = array('@id_zona',$this->_request->getPost('cmbZona'));													
			$parametros[] = array('@nombre_via',$this->_request->getPost('txtNombre'));	
			$t_zona=$this->_request->getPost('rdTipozona');
			if($t_zona=='baja')$newt_zona=2;
			else $newt_zona=1;
			$parametros[] = array('@id_tipozona',$newt_zona);
			//$check=$this->_request->getPost('nestado');
			$parametros[] = array('@nestado',$this->_request->getPost('nestado'));
			$parametros[] = array('@operador',$usuario);
			//$mi_estacion=gethostname();
			$IP = $_SERVER['REMOTE_ADDR'];// Obtains the IP address
			//$computerName = gethostbyaddr($IP);
			$parametros[]= array ('@estacion',$IP);
			
			
			
			@$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $parametros);
			
			echo "Se grabo correctamente";
    	}    	
    	
    }
    
	public function eliminarAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	
    	if($this->getRequest()->isXmlHttpRequest()){
    	
		$cod=$this->_request->getParam('codigo','');
		$array=explode('*',$cod);
		$cn = new Model_DbDatos_Datos();
		$nombreprocedure="Rentas.SP_MCUrba";
		$arraydatos[0] = array("@msquery", 9);
		$arraydatos[1] = array("@id_urba", $array[0]);
		@$rows=$cn->ejec_store_procedura_sql($nombreprocedure,$arraydatos);
		
			echo 'Se elimino correctamente';
    	}
	}
	

		///////////////////////////////////////////////////////////////////////////////////////
		
		public function consultadetalleAction()
		{
			$cn = new Model_DbDatos_Datos();
    	
			$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
			$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
			$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
			
			$start = (($page-1) * $limit)+1;
			$end = $start + $limit - 1;
			
			$codigo  = $this->_request->getParam('codigovia','');
			
			//para el total de filas
			unset($arraydatos);
			$arraydatos[]=array('@busc',10);
			$arraydatos[]=array("@cod_via", $codigo);
			
			$rowsTotal = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $arraydatos);
			
			//para las filas
			unset($arraydatos);
			$arraydatos[] = array('@busc',9);
			$arraydatos[]=array("@cod_via", $codigo);
			$arraydatos[]=array("@inicio",$start);
			$arraydatos[]=array("@final",$end);
			
			$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $arraydatos);
			
			$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
			if(count($rows))
			{
				foreach($rows AS $row){
					$entry = array(
						'codigo' =>$row[0],				  
						'codvia'=>$row[1],
						'anno'=>$row[2],
						'arancel'=>$row[3],
						'estado'=>$row[4],
					);
					$jsonData['rows'][] = $entry;
				}
			}
			
			$this->view->data = json_encode($jsonData);
		}
		
		public function frmdetalleAction()
		{
			$path = new Zend_Session_Namespace('path');
			$this->view->ruta = $path->data;
			$cn = new Model_DbDatos_Datos();
			$fn = new Libreria_Pintar ();
			
			$codigo  = $this->_request->getParam('codigo','');
			//Pintamos el codigo de la via
			$val[] = array('#codigo',$codigo,'val');
			
			$arraydatos[] = array("@busc", 14);
			$arraydatos[] = array("@cod_via", $codigo);
			$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $arraydatos);
			
			$id_urba	=$rows[0][1];
			$urba		=$rows[0][2];
			$tipo_via	=utf8_encode($rows[0][3]);
			$nom_via	=$rows[0][4];
			$cuadra		=$rows[0][5];
			
			$nom_completo=$urba."-".$tipo_via." ".$nom_via." cdra. ".$cuadra;
			
			$val[] = array('#txtid_urba',$id_urba,'val');
			$val[] = array('#nom_urb',$nom_completo,'val');
			
			
			$evt [] = array ('#btn_cerrarVia',"click", "closePopup('#popNueVia');");
			$evt[] = array('#btn_nuevaVia',"click","CodDetalle()");
			
			$fn->PintarValor ($val);
			$fn->PintarEvento ( $evt );
		
		}
		
		public function frmarancelAction()
		{
			$path = new Zend_Session_Namespace('path');
			$this->view->ruta = $path->data;
			$cn = new Model_DbDatos_Datos();
			
			$fn = new Libreria_Pintar ();
			//capturamos el cod de la via
			$codigo  = $this->_request->getParam('codigo_via','');
			
			//capturamos el codigo de la tabla detalle de vias
			$codigo_id_tbl  = $this->_request->getParam('codigo','');
			
			//pintamos sel cod de via
			$val[] = array('#txtCodVia',$codigo,'val');
			
			//pintamos el codigo de id_tbl de detalle de via
			$val[] = array('#txtcod_arancel',$codigo_id_tbl,'val');
			
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
			
			if(!empty($codigo_id_tbl))
			{	
				$arraydatos [] = array ("@busc",12);
				$arraydatos [] = array ("@id_tbl", $codigo_id_tbl);	
				$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $arraydatos);
				
				$anno	 =  $rows[0][0];
				$arancel =  $rows[0][1];
				$estado  =  $rows[0][2];
				
				$bus_anno2="";
				for($i=1992;$i<=date('Y');$i++){
				$bus_anno2.="<option value='".$i."'";
					if($i==$anno)
					{
						$bus_anno2.=" selected ";
					}
				$bus_anno2.=">".$i."</option>";
				}
				
				$this->view->cb_anno=$bus_anno2;
			}
			
			
			//para visualizar el arancel
			$val[] = array('#txtArancel',$arancel,'val');
			$evt[] = array('#txtArancel',"keypress","return validaTeclas(event,'numeric');");
			//para el estado
			if($estado==1)
			$cad[] = array("#chbxTuestado","checked", true);
			
			if(count($cad)>0)
			$fn->AtributoComponente($cad);
			
			
			$evt [] = array ('#btnSalirPoblados',"click", "closePopup('#popEditArancel');");
			$evt[] = array('#btnGrabaPoblado',"click","goToFormulario('frmpoblado');");
			
			$fn->PintarValor ($val);
			$fn->PintarEvento ( $evt );
				
		}
		
		public function grabararancelAction()
		{	$login = new Zend_Session_Namespace('login');
		
			$usuario = $login->user;
			
			$this->_helper->getHelper('ajaxContext')->initContext();
			$this->_helper->viewRenderer->setNoRender();
			$this->_helper->layout->disableLayout();
			
			if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			//Capturamos el codigo id_tbl
			$codigo = $this->_request->getPost('txtcod_arancel');
			
			if(strlen($codigo)>0)
				$tip = 13;
			else{
				$tip = 11;
			}	
			$parametros[] = array('@busc',$tip);
			$parametros[] = array('@id_tbl',$codigo);
			$parametros[] = array('@cod_via',$this->_request->getPost('txtCodVia'));
			$parametros[] = array('@anno',$this->_request->getPost('cmbAnno'));
			$parametros[] = array('@arancel',$this->_request->getPost('txtArancel'));
			$parametros[] = array('@nestado',$this->_request->getPost('chbxTuestado'));
			$parametros[] = array('@operador',$usuario);
			$IP = $_SERVER['REMOTE_ADDR'];// Obtains the IP address
			//$computerName = gethostbyaddr($IP);
			$parametros[]= array ('@estacion',$IP);
			
			$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $parametros);
			
			echo "Se grabo correctamente";
    	} 
	
		
		}
		
		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}


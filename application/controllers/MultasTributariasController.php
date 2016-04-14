<?php

class MultastributariasController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;		
		
		$mod = $this->_request->getParam('mod','');
		$this->view->mod = $mod;
		
		
		$this->view->title = "Mantenimiento de Multas Tributarias";
		$fn = new Libreria_Pintar();		
		$evt[] = array('#contentBox1',"tabs","");
		
		$evt[] = array('#btnSearchContri',"click","buscarContri()"); // buscar en la grilla multas tributarias
		$evt[]= array('#btnNuevo','click',"showPopup('multasadmin/editar','#popupMultas','800','720','Nuevo Multas Administrativas');" );
				
		$fn->PintarEvento ( $evt );
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
		$criterionombre = $_REQUEST['criterionombre'];
		$criteriopaterno = $_REQUEST['criteriopaterno'];
		$criteriomaterno = $_REQUEST['criteriomaterno'];
		$criteriorazon = $_REQUEST['criteriorazon'];
		$documento = $_REQUEST['documento'];
    	
		//{rdcriterio: rdCriterio, criterio: txtCriterio, criterionombre:txtCriterioNombre,criteriopaterno: txtCriterioAPaterno,criteriomaterno:txtCriterioAMaterno,criteriorazon:txtCriterioRazon,documento:txtDocumento};
		
    	/*switch($rdcriterio)
    	{
    		case 'C': $codigo = $criterio; break;
    		case 'N': $nombre = $criterio; break;
    		case 'D': $documento = $criterio; break;
			case 'R': $documento = $criterio; break;
    	}*/
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@codigo',$criterio);
		$parametros[] = array('@nombres',$criterionombre);
		$parametros[] = array('@paterno',$criteriopaterno);
		$parametros[] = array('@materno',$criteriomaterno);
		$parametros[] = array('@razon',$criteriorazon);
		$parametros[] = array('@num_doc',$documento);
		$parametros[] = array('@tipo_busqueda',$rdcriterio );
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@codigo',$criterio);
		$parametros[] = array('@nombres',$criterionombre);
		$parametros[] = array('@paterno',$criteriopaterno);
		$parametros[] = array('@materno',$criteriomaterno);
		$parametros[] = array('@razon',$criteriorazon);
		$parametros[] = array('@num_doc',$documento);
		$parametros[] = array('@tipo_busqueda',$rdcriterio );
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],				  
						'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
						'documento'=>utf8_encode($row[3]),
						'direccion'=>utf8_encode($row[15])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	
	public function multasAction()
	{
		$path = new Zend_Session_Namespace ('path');
		$this->view->ruta = $path->data;
		$codigo=$this->_request->getParam('codigo','');
		
		$fn = new Libreria_Pintar ();
		$val[] = array('#codigoContri',$codigo,'val');
		$evt [] = array ('#btnSalirListado', "click", "closePopup('#popupVerMultas');" );
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	}
	
	public function consultatributariaAction(){
	
			$cn = new Model_DbDatos_Datos();
    	
			$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
			$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
			$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
			
			$start = (($page-1) * $limit)+1;
			$end = $start + $limit - 1;
			
			$codigo  = $this->_request->getParam('codigo','');
			
			//para el total de filas
			unset($arraydatos);
			$arraydatos[]=array('@msquery',3);
			$arraydatos[]=array("@codigo", $codigo);
			
			$rowsTotal = $cn->ejec_store_procedura_sql('[Rentas].[sp_MultasTributarias]', $arraydatos);
			
			//para las filas
			unset($arraydatos);
			$arraydatos[] = array('@msquery',2);
			$arraydatos[]=array("@codigo", $codigo);
			$arraydatos[]=array("@inicio",$start);
			$arraydatos[]=array("@final",$end);
			
			$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_MultasTributarias]', $arraydatos);
			
			$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
			if(count($rows))
			{
				foreach($rows AS $row){
					$entry = array(
						'anno' =>$row[0],				  
						'cod_pred'=>$row[1],
						'direccion'=>$row[2],
						'glosa'=>utf8_encode($row[3]),
						'tipo_rec'=>$row[4],
						'motivo'=>$row[5],
						'insoluto'=>$row[6],
						'interes'=>$row[7],
						'f_ingreso'=>$row[8],
						'f_vigencia'=>$row[9],
						'condicion'=>$row[10],
						'estado'=>$row[11],
						'num_docu'=>$row[12],
						'ubica'=>rtrim($row[13]),
					);
					$jsonData['rows'][] = $entry;
				}
			}
			
			$this->view->data = json_encode($jsonData);
	}
	
	public function editartributariaAction(){
	
	}
	
	public function eliminartributariaAction(){
	
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	
    	if($this->getRequest()->isXmlHttpRequest()){
    	
		$numero=$this->_request->getParam('numero','');
		$codigo=$this->_request->getParam('codigo','');
		
		$cn = new Model_DbDatos_Datos();
		$nombreprocedure="[Rentas].[sp_MultasTributarias]";
		$arraydatos[] = array("@msquery", 4);
		$arraydatos[] = array("@id_multa", $numero);
		$arraydatos[] = array("@codigo", $codigo);
		@$rows=$cn->ejec_store_procedura_sql($nombreprocedure,$arraydatos);
		
			echo 'Se elimino correctamente';
    	}
	
	}
	
	public function editarAction() 
	{
		
		$path = new Zend_Session_Namespace ('path');
		$this->view->ruta = $path->data;

        $codigo=$this->_request->getParam('codigo','');
        $this->view->codigo = $codigo;
		
		
		
		$cn = new Model_DbDatos_Datos ();
		$fn = new Libreria_Pintar ();
		$ar = new Libreria_ArraysFunctions(); // libreria pa

		$evt[] = array('#anios_deuda',"change","cargarMulta();");
		$evt[]= array('#btnVerMultas','click',"Tributaria()" );
		
        $store="store_caja_framework";
        $arraydatos_store[]=array("@msquery",'17');
        $arraydatos_store[]=array("@codigo",$codigo);
        $cn = new Model_DbDatos_Datos();

        $rows = $cn->ejec_store_procedura_sql($store, $arraydatos_store);
        $codigo=$rows[0][0];
        $nombre=$rows[0][1];
        $numdoc=$rows[0][2];
        $direccion=$rows[0][3];


        //divPredios
        $val[] = array('#divCodigo',"$codigo","html");//en caso de de div html - input val
        $val[] = array('#divContri',"$nombre","html");
        $val[] = array('#divDirec',"$direccion","html");
        $val[] = array('#divDocu',"$numdoc","html");

        $cod = trim($this->_request->getParam ('codigo'));

        $this->view->codigo=$cod;
		
		$arAnios = $ar->AniosTributos();
		$val[] = array('#anios_deuda',$fn->ContenidoCombo($arAnios,'[Seleccione]',''),'html');
		
		
		
		unset($parametros);
		$parametros[] = array('@msquery',16);	
		//$parametros[] = array('@msquery',18);
		$comboImporteanual = $cn->ejec_store_procedura_sql('dbo.store_caja_framework', $parametros);
		$arArea = $ar->RegistrosCombo($comboImporteanual,0,1);
		$val[] = array('#cmbImporteanual',$fn->ContenidoCombo($arArea,'[Seleccione]',''),'html');
		
		
		
		//$val[] = array('#cmbTipoActuContri',$fn->ContenidoCombo($arComboTipoActucontribuyente,'[Seleccione]',trim($TipoActuContri)),'html');
		
    	$evt [] = array ('#btnSalir', "click", "closePopup('#popupMultas');" );
		$evt [] = array ('#btnGrabar', "click", "goToFormulario('frmempedit')" );
		
		// $evt[] = array('#btnAddConst',"click","eventConst('A');");
		// $evt[] = array('#btnEditConst',"click","eventConst('E');");
		// $evt[] = array('#btnCancelConst',"click","eventConst('C');");
		$evt[] = array('#btnAgregar',"click","eventConst('S');");
		$evt[] = array('#btnEliminar',"click","eventConst('D');");
		
		//////////// validad la caja de texto importe trimestral ///  casimiro ////
		
		$evt[] = array('#cant',"keypress","return validaTeclas(event,'numeric');"); 
		//////////// validad la caja de texto importe trimestral ///  casimiro ////
		
		$evt[] = array('#btnGenerar',"click","goToFormulario('frmultedit');");
			
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}

    public function fechaAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	
    	
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$parametros[] = array('@msquery',19);
			$rows = $cn->ejec_store_procedura_sql('dbo.store_caja_framework', $parametros);
			
			echo $rows[0][0];
    	}   
    }	

	
		
	public function grabarAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$cn = new Model_DbDatos_Datos ();
			$login = new Zend_Session_Namespace('login');
			
			$emp_cod     = $this->_request->getPost ('emp_cod');
			$depen_cod 	 = $this->_request->getPost ('depen_cod');
			$emp_nombres = $this->_request->getPost ('emp_nombres');			
			$emp_appat   = $this->_request->getPost ('emp_appat');
			$emp_apmat   = $this->_request->getPost ('emp_apmat');
						
			if(!empty($emp_cod)){
				//actualiza
				$arraydatos[] = array("@busc", 4);
				$arraydatos[] = array("@emp_cod",  $emp_cod );
				$arraydatos[] = array("@depen_cod",  $depen_cod );
				$arraydatos[] = array("@emp_nombres", $emp_nombres );
				$arraydatos[] = array("@emp_appat", $emp_appat );
				$arraydatos[] = array("@emp_apmat", $emp_apmat );
				$arraydatos[] = array("@fechact",date('d/m/Y H:i:s'));
				$arraydatos[] = array("@usuario",$login->user);
				
				@$rows = $cn->ejec_store_procedura_sql ( 'req_empleado', $arraydatos );
				
			}else{
				//ingresa
				$arraydatos[] = array("@busc", 3);
				$arraydatos[] = array("@emp_cod", $emp_cod );
				$arraydatos[] = array("@depen_cod", $depen_cod );
				$arraydatos[] = array("@emp_nombres",$emp_nombres );
				$arraydatos[] = array("@emp_appat", $emp_appat );
				$arraydatos[] = array("@emp_apmat", $emp_apmat );
				$arraydatos[] = array("@fechins",date('d/m/Y H:i:s'));
				$arraydatos[] = array("@usuario",$login->user);
					
				@$rows = $cn->ejec_store_procedura_sql ( 'req_empleado', $arraydatos );
			}
			
			echo 'Se guardo correctamente';
		}
	}

   	public function eliminarAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$cn = new Model_DbDatos_Datos ();
		
		$emp_cod = $this->_request->getParam ( 'emp_cod' );
		
		$arraydatos [] = array ("@busc", 6 );
		$arraydatos [] = array ("@emp_cod", $emp_cod );		
		@$rows = $cn->ejec_store_procedura_sql ( 'req_empleado', $arraydatos );
		
		echo 'se elimino correctamente';
	}	
	
	public function consultamultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
		$divcodigo = $_REQUEST['divcodigo'];
		$cmbAnio = $_REQUEST['cmbAnio'];
		
    	$parametros[] = array('@busc',2);		
		$parametros[] = array('@codigo',$divcodigo);
		$parametros[] = array('@anno',$cmbAnio);
		$rowsMulta = $cn->ejec_store_procedura_sql('Rentas.sp_Multatributaria', $parametros);
		
		$jsonData = array();
		if(count($rowsMulta))
		{
			foreach($rowsMulta AS $row){
				$entry = array(
						'codigo'=>$row[0],
						'anno'=>$row[1],	
						'codpred'=>$row[2],
						'anexo'=>utf8_encode($row[9]),
						'subanexo'=>utf8_encode($row[10]),
						'direccion'=>utf8_encode($row[4])
				);
				$jsonData[] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	
	public function consubtribuAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
	    
	    if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$id_tipomulta = $this->_request->getPost('id_tipomulta');
			
			$combostore1 ='dbo.store_caja_framework';
			$arraydatos1[] = array("@msquery",18);
			$arraydatos1[] = array("@tipo",$id_tipomulta);
			$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);
			
			$cb_Tipomulta='<option value="">[Seleccione]</option>';
			for ($i=0;$i<count($rows1);$i++){
            	$cb_Tipomulta.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][2].'</option>';
        	}
		
			echo $cb_Tipomulta;
    	}
    }
	
	public function annosAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
	    
	    if($this->getRequest()->isXmlHttpRequest())
		{
    		
			$cn = new Model_DbDatos_Datos();
			
			$anno_venci = $this->_request->getPost('anno_venci');
			
			$combostore1 ='store_caja_framework';
			$arraydatos1[] = array("@msquery",20);
			$arraydatos1[] = array("@anno",$anno_venci);
			$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);
			
			echo $rows1[0][0];
    	}  
    }
	
	
	public function gmultasAction()
	{
    	
    	$login = new Zend_Session_Namespace('login');
		$usuario = $login->user;
		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
				
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			
			$path=	new Zend_Session_Namespace('path');
		
			// $codigo=$path->codigo;
			// $anno=$path->anno;
						
			// /*********DATOS MULTASTRIBUTARIAS**************/
			if(!empty($data->Multribu)){			
				$dataMultribu = $data->Multribu;
				 
				
				foreach($dataMultribu as $multribu){
				
				$codigo = trim($multribu->codigo);
				$codpred = trim($multribu->codpred);
				$anexo = trim($multribu->anexo);
				$subanexo = trim($multribu->subanexo);
				
				 }
			}	
			
			// /*********DATOS MULTAS**************/
			if(!empty($data->Mult)){			
				$dataDoc = $data->Mult;
				 
				foreach($dataDoc as $mult){
				
				unset($parametros);
				
				$parametros[] = array('@msquery',1);
				$parametros[] = array('@codigo',$codigo);	
				$parametros[] = array('@codpred',$codpred);	
				$parametros[] = array('@anexo',$anexo);	
				$parametros[] = array('@subanexo',$subanexo);	
				$parametros[] = array('@anno',trim($mult->anno));		
				$parametros[] = array('@tipo',trim($mult->tributohide));	
				$parametros[] = array('@tipo_rec',trim($mult->subtributohide));	
				$parametros[] = array('@insoluto',trim($mult->importe));	
				$parametros[] = array('@derecho_emision',trim($mult->emision));	
				$parametros[] = array('@importe_original',trim($mult->subtotal));	
				$parametros[] = array('@periodo',trim($mult->periodo));	
				$parametros[] = array('@glosa',trim($mult->observ));
				$parametros[] = array('@fecha_registro',trim($mult->fecha));	
				$parametros[] = array('@fecha_vencimiento',trim($mult->fechavenci));
				
				//operador
				$parametros[] = array('@operador',$usuario);
				
				//estacion
				//$mi_estacion=gethostname();
					$IP = $_SERVER['REMOTE_ADDR'];// Obtains the IP address
					//$computerName = gethostbyaddr($IP);
					$parametros[]= array ('@estacion',$IP);
				
				
				$parametros[] = array('@tipo_obligacion_id',trim($data->tipo_obligacion));
				$rows = $cn->ejec_store_procedura_sql('Rentas.sp_MultasTributarias', $parametros);
				
				//var_dump($rows );			
				}
			}	
		}
    }
}
	



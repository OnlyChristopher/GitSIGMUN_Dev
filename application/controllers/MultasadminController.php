<?php

class MultasadminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;		
		$this->view->title = "Mantenimiento de Multas Administrativas";		
		$fn = new Libreria_Pintar();		
		$evt[] = array('#contentBox1',"tabs","");
		$cn = new Model_DbDatos_Datos();
		
		$evt[] = array('#btnMostrar',"click","mostrarRecContri();");
		$evt[] = array('#btnRecibo',"click","mostrarRecibos();");
				
    	$codigo=$this->_request->getparam('codigo');
		
    	$arraydatos[]=array("@busc",4);
		$arraydatos[]=array("@codigo",$codigo);
		@$rowsContri = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $arraydatos);
		
		$val[] = array('#codigoContri', $rowsContri [0][0], 'val');
		$val[] = array('#num_doc', $rowsContri[0][3], 'val');
		$val[] = array('#txtNomContri', $rowsContri [0][4].' '.$rowsContri [0][5].' '.$rowsContri [0][6], 'val');
		$val[] = array('#tipocontri', $rowsContri[0][31], 'val');//mostrar el tipo de contribuyente
		$codtipocontri = $rowsContri[0][31];
		
		$evt[]= array('#btnNuevo','click',"showPopup('multasadmin/editar?codigo=$codigo&codtipocontri=$codtipocontri','#popnewmulta','500','450','Nuevo Multas Administrativas');" );
		
		$evt[] = array('#btnSalir', 'click', "closePopup('#popcontri');" );
		
		$fn->PintarEvento ( $evt );
		$fn->PintarValor ( $val );
	}	

	public function consultaAction() {
	

		$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page'])   ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] :0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] :10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$codigo=$this->_request->getParam('codigo');
    	    	    	    	
    	//Para el total
    	$arraydatos[]=array('@busc',2);    	
    	$arraydatos[]=array("@codigo",$codigo );
		
		@$rowsTotal = $cn->ejec_store_procedura_sql('rentas.sp_multasadmin', $arraydatos);
    
		//Para las filas
		unset($arraydatos);
    	$arraydatos[] = array('@busc',1);
    	$arraydatos[] = array("@codigo",$codigo );
    	$arraydatos[] = array("@inicio",$start);
		$arraydatos[] = array("@final",$end);
		@$rows = $cn->ejec_store_procedura_sql('rentas.sp_multasadmin', $arraydatos);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
			foreach($rows AS $row){
				$entry = array(  
					'preimpreso' => $row[1],
					'anno_multa' => $row[2],
					'numero_multa' => $row[3],
					'fecha_multa' => $row[4],
					'anno_area'	=> $row[5],
					'infraccion' => $row[6],
					'monto_multa' => $row[7],
					'antecedente' => $row[8],
					'numero_notificacion' => $row[9],
					'estado' => $row[16],
					'numero_recibo' => $row[11],
					'cta_cte' => $row[12],
					'cantidad' => $row[13],
					'codigo_autoridad_municipal'=> utf8_decode($row[19]),
					'base'=>$row[15],
					'numero'=>$row[17],
					'fecha_notificacion'=>$row[18],
					'e_resolucion'=>$row[20],
				);
				$jsonData['rows'][] = $entry;
		}
		
		$this->view->data=json_encode($jsonData);
	}
	
	public function editarAction() {
		
		$path = new Zend_Session_Namespace ( 'path' );
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos ();
		$fn = new Libreria_Pintar ();
		$ar = new Libreria_ArraysFunctions();
		
		$codigo = $this->_request->getParam ('codigo','');	
		$numero_multa = $this->_request->getParam ('numero_multa','');
		$codtipocontri = $this->_request->getParam ('codtipocontri','');
		
		
		
		if(!empty ($numero_multa)) {
			//---------------------
			//PARA LA OPCION EDITAR
			//---------------------
			
			//verificar el tipo de contribuyente
			$miarraydatos[]=array("@busc",4);
			$miarraydatos[]=array("@codigo",$codigo);
			@$mirowsContri = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $miarraydatos);
			$micodtipocontri = $mirowsContri[0][31];
			
			//CAPTURAMOS LOS DATOS
			
			unset ( $arraydatos );
			$arraydatos [0] = array ("@busc", 5 );
			$arraydatos [1] = array ("@numero_multa", $numero_multa);
			$arraydatos [2] = array ("@codigo", $codigo);
			$rowsMulta = $cn->ejec_store_procedura_sql ( 'rentas.sp_multasadmin', $arraydatos );
			
			//-PONEMOS EN VARIABLES
			
			$codigo		 = $rowsMulta [0] [0];
			$preimpreso  = $rowsMulta [0] [1];
			$anno_multa  		= $rowsMulta [0] [2];
			//$numero_multa= $rowsMulta [0] [3];
			$fecha_multa 		= $rowsMulta [0] [5];
			$anno_multa  		= substr($rowsMulta[0][5],6);
			$anno_area  		= $rowsMulta [0] [6];

			$this->view->anno_area=$anno_area;
				
			$codigo_area  		= $rowsMulta [0] [7];
			$tipo_infraccion 	= $rowsMulta [0] [8];
			$codigo_infraccion 	= $rowsMulta [0] [9];
			$codigo_adicional 	= $rowsMulta [0] [10];						
			$monto_multa 		= $rowsMulta [0] [11];
			$antecedente 		= $rowsMulta [0] [12];
			$numero_notificacion=$rowsMulta [0] [13];
			$estado       		= $rowsMulta [0] [14];
			$numero_recibo		= $rowsMulta[0] [15];
			$anno_multa  		= $rowsMulta[0] [16];
			$cantidad    		= $rowsMulta [0] [18];
			$codigo_autoridad_municipal=$rowsMulta [0] [19];
			$base 		 		= $rowsMulta [0] [20];
			$factor 	 		= $rowsMulta [0] [21];//ERROR DETECTED
			$fecha_notificacion = $rowsMulta [0] [22];
			$glosa 				= $rowsMulta [0][23];  //// añadido Casimiro	
			$fecha_resolucion 	= @date('d/m/Y');
			
			$e_resolucion		= $rowsMulta [0][24];
			if($e_resolucion=='1')
			$cad[] = array("#chkResoluciones","checked", true);
			
			
			$fech_notificacion2	= $rowsMulta [0][25];
			$val[] = array('#txtFechResolucion', $fech_notificacion2, 'val');
			$n_resolucion		= $rowsMulta [0][26];
			$glosa_notificacion	= $rowsMulta [0][27];
			
			$t_vehiculo		= $rowsMulta [0][29];
			
			if($t_vehiculo=='M'){  
			$cad[] = array("#chkGuardiania2","checked", true);
			$cad[] = array("#rbtmototaxi","checked", true);
			$val[] = array('#txtdias', $base, 'val');
			$val[] = array('#txtvalor', $factor, 'val');
			$val[] = array('#txtvalorinternamiento', $monto_multa, 'val');
			}
			else if($t_vehiculo=='C'){
			$cad[] = array("#chkGuardiania2","checked", true);
			$cad[] = array("#rbtcarreta","checked", true);
			$val[] = array('#txtdias', $base, 'val');
			$val[] = array('#txtvalor', $factor, 'val');
			$val[] = array('#txtvalorinternamiento', $monto_multa, 'val');
			}
			
		}else{ 			
			$preimpreso  = "";
			$anno_multa  = "";
			$numero_multa= "";				
			$fecha_multa = @date('d/m/Y');
			//---------------añadio manuel-------------------
			$fecha_resolucion = @date('d/m/Y');
			$val[] = array('#txtFechResolucion', $fecha_resolucion, 'val');
			//-----------------------------------------------
			$anno_multa  = substr($fecha_multa,6);	
			$infraccion	 = "";
			$monto_multa = "";
			$antecedente = "";
			$numero_notificacion="";
			$estado		 = "";
			$numero_recibo= "";
			$cantidad    = 1;
			$codigo_autoridad_municipal	= "";
			$base = "";
			$factor = "";
			$fecha_notificacion = ""; 
			$glosa = ""; //// añadido Casimiro
									
		}	
		
		unset ( $arraydatos );
		$arraydatos [0] = array ('@busc', 7 );
		$rows = $cn->ejec_store_procedura_sql ( 'rentas.sp_multasadmin', $arraydatos );
		$arRows = $ar->RegistrosCombo($rows,0,1);				
		$val[] = array('#codigo_autoridad_municipal',$fn->ContenidoCombo($arRows,'[Seleccione]',$codigo_autoridad_municipal,''),'html');
		
		
		unset ( $arraydatos );
		$arraydatos [0] = array ('@busc', 9 );
		$rows = $cn->ejec_store_procedura_sql ( 'rentas.sp_multasadmin', $arraydatos );
		$arRows = $ar->RegistrosCombo($rows,0,1);				
		$val[] = array('#estado',$fn->ContenidoCombo($arRows,'[Seleccione]',$estado),'html');
		
		//------ PRIMER COMBO ------//
		unset ( $arraydatos );
		$arraydatos [] = array ('@busc', 8 );
		$rows = $cn->ejec_store_procedura_sql ( 'rentas.sp_multasadmin', $arraydatos );
		$arRows = $ar->RegistrosCombo($rows,0,1);				
		//$val[] = array('#codigo_area',$fn->ContenidoCombo($arRows,'[Seleccione]',ltrim($codigo_area).'*'.ltrim($anno_area),''),'html');
		$val[] = array('#codigo_area',$fn->ContenidoCombo($arRows,'[Seleccione]',ltrim($codigo_area),''),'html');
		$evt [] = array ('#codigo_area', "change", "FiltraCodArea(this.value);" );	
		
		//------ SEGUNDO COMBO ------//
		$tip_infrac=$codigo_area.':'.$tipo_infraccion;	
		unset ( $arraydatos );
		$arraydatos [] = array ('@anno_area', $anno_area);
		$arraydatos [] = array ('@codigo_area', $codigo_area);
		$rows = $cn->ejec_store_procedura_sql ( 'stpMultas_Qry_Tipo_Infracciones', $arraydatos );
		$arRows = $ar->RegistrosCombo($rows,0,1);				
		$val[] = array('#tipo_infraccion',$fn->ContenidoCombo($arRows,'[Seleccione]',$tip_infrac,''),'html');
		$evt [] = array ('#tipo_infraccion', "change", "FiltraTipInfrac(this.value);" );
		
		$cod_infrac=$codigo_area.':'.$tipo_infraccion.':'.$codigo_infraccion.':'.$codigo_adicional.':1'.':'.$factor	;
		
		//echo $cod_infrac;
		
		//------ TERCER COMBO ------//
		unset ( $arraydatos );
		$arraydatos [] = array ('@anno_area', $anno_area );
		$arraydatos [] = array ('@codigo_area', $codigo_area);
		$arraydatos [] = array ('@tipo_infraccion', $tipo_infraccion);
		$arraydatos [] = array ('@id_tipocontri', $micodtipocontri);
		$rows = $cn->ejec_store_procedura_sql ( 'stpMultas_Qry_Detalle_Tipo_Infracciones', $arraydatos );
		$arRows = $ar->RegistrosCombo($rows,0,1);				
		$val[] = array('#codigo_infraccion',$fn->ContenidoCombo($arRows,'[Seleccione]',$cod_infrac,''),'html');
		
		$evt[] = array('#codigo_infraccion', "change", "calcTotal(this.value);" );
		
		
		$val[] = array('#codigo', $codigo, 'val');
		$val[] = array('#preimpreso', $preimpreso, 'val');
		$val[] = array('#anno_multa', $anno_multa, 'val');	
		$val[] = array('#numeroMulta', $numero_multa, 'val');
		$val[] = array('#montoTotal', $monto_multa, 'val');
		$val[] = array('#fecha_multa', $fecha_multa, 'val');
		//---------------------añadio manuel----------------
		
		
		$val[] = array('#txtNResolucion', $n_resolucion, 'val');
		$val[] = array('#txaObservacion', $glosa_notificacion, 'val');
		//--------------------------------------------------
		$val[] = array('#numero_notificacion', $numero_notificacion, 'val');
		$val[] = array('#antecedente', $antecedente, 'val');
		$val[] = array('#numero_recibo', $numero_recibo, 'val');
		$val[] = array('#cantidad', $cantidad, 'val');
		$val[] = array('#numeroMulta', $numero_multa, 'val');
		$val[] = array('#fecha_notificacion', $fecha_notificacion, 'val');
		$val[] = array('#glosa', $glosa, 'val');
		$val[] = array('#tipocontri_multa', $codtipocontri, 'val');
		
		$evt[] = array('#fecha_multa',"change","calcUIT(this.value);");
		$evt[] = array('#fecha_multa',"datepicker","");
		//----------------añadio manuel------------------
		$evt[] = array('#txtFechResolucion',"datepicker","");
		//----------------------------------------------------
		$evt[] = array('#fecha_notificacion',"datepicker","");
		
		$evt[] = array('#cantidad',"keypress","return validaTeclas(event,'sinceros');");
		
		$evt [] = array ('#btnCloseForm', "click", "closePopup('#popnewmulta');" );
		$evt [] = array ('#btnSaveForm', "click", "goToFormulario('frmultedit')" );
		
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		if(count($cad)>0)
			$fn->AtributoComponente($cad);
	}
	
	public function selectareaAction()
    {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
    		$cn = new Model_DbDatos_Datos();
			
			$codigo_area = $this->_request->getPost('codigo_area');
			$anno_area = $this->_request->getPost('anno_area');
			
			$parametros [] = array ('@anno_area', $anno_area);
			$parametros [] = array ('@codigo_area', $codigo_area);
			$rows = $cn->ejec_store_procedura_sql('stpMultas_Qry_Tipo_Infracciones', $parametros);
			
			if(count($rows)){
				print("<option value=''>-seleccione-</option>");
				foreach($rows AS $row)
					print("<option value='".utf8_encode($row[0])."'>".utf8_encode($row[1])."</option>");
			}
			else{
				print("<option value=''>[ Sin registros ]</option>");
			}
		}		
	}
	
	public function selecttipoAction()
    {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
    		$cn = new Model_DbDatos_Datos();
			
			$tipo_infraccion = $this->_request->getPost('tipo_infraccion');
			$tipocontri_multa = $this->_request->getPost('tipocontri_multa');
			$anno_area = $this->_request->getPost('anno_area');
			
			$partes = explode(':',$tipo_infraccion);
			
			$parametros [] = array ('@anno_area', $anno_area);
			$parametros [] = array ('@codigo_area', $partes[0]);
			$parametros [] = array ('@tipo_infraccion', $partes[1]);
			$parametros [] = array ('@id_tipocontri', $tipocontri_multa);
			$rows = $cn->ejec_store_procedura_sql('stpMultas_Qry_Detalle_Tipo_Infracciones', $parametros);
			
			if(count($rows)){
				print("<option value=''>-seleccione-</option>");
				foreach($rows AS $row)
					print("<option value='".utf8_encode($row[0])."'>".utf8_encode($row[1])."</option>");
			}
			
			//echo $partes[0]."-".$partes[1]."-".$partes[2];
		}		
	}
	
	public function selectuitAction()
    {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
    		$cn = new Model_DbDatos_Datos();
			
			$anno = $this->_request->getPost('anno');
			
			$parametros [] = array ('@busc', 4);
			$parametros [] = array ('@anno', $anno);
			$rows = $cn->ejec_store_procedura_sql('Rentas.sp_uit', $parametros);
			
			if(count($rows))
				echo $rows[0][2];
		}		
	}
	
	public function grabarAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		
		$login = new Zend_Session_Namespace('login');
		$usuario = $login->user;
		
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$cn = new Model_DbDatos_Datos ();
			
			$codigo      = $this->_request->getPost ('codigo');
			$codigo_autoridad_municipal = $this->_request->getPost ('codigo_autoridad_municipal');
			$fecha_multa = $this->_request->getPost ('fecha_multa');	
			$anno_multa  = $this->_request->getPost ('anno_multa');	
			$numero_multa  = $this->_request->getPost ('numeroMulta');		
			$antecedente = $this->_request->getPost ('antecedente');
			$preimpreso  = $this->_request->getPost ('preimpreso');
			$codigo_area = $this->_request->getPost ('codigo_area');
			$tipo_infraccion  = $this->_request->getPost ('tipo_infraccion');
			$codigo_infraccion= $this->_request->getPost ('codigo_infraccion');
			$cantidad = $this->_request->getPost ('cantidad');
			$base     = $this->_request->getPost ('base');
			$factor   = $this->_request->getPost ('factor');
			$montoTotal = $this->_request->getPost ('montoTotal');
			$estado = $this->_request->getPost ('estado');
			$fecha_notificacion = $this->_request->getPost ('fecha_notificacion');
			$glosa = $this->_request->getPost ('glosa');  // añadido Casimiro
			//-----------------MANUEL-----------------
			$e_resolucion = $this->_request->getPost ('chkResoluciones');
			$fech_notificacion2 = $this->_request->getPost ('txtFechResolucion');
			$n_resolucion = $this->_request->getPost ('txtNResolucion');
			$glosa_notificacion = $this->_request->getPost ('txaObservacion'); 
			
			//------------
			//guardiania
			//------------
			$e_guardiania   = $this->_request->getPost ('chkGuardiania2');
			$t_vehiculo   = $this->_request->getPost ('rbtvehiculomenor');
			$dias_inter  = $this->_request->getPost ('txtdias');
			$factor_inter= $this->_request->getPost ('txtvalor');
			$monto_inter = $this->_request->getPost ('txtvalorinternamiento');
			
			//----------------
			//--valorizacion
			//----------------
			$const_valor  = $this->_request->getPost ('txtvalorizacion');
			$factor_valor= $this->_request->getPost ('txtfactorvalorizacion');
			$monto_valor = $this->_request->getPost ('txtmultavalorizacion');
			
			
			// Obtains the IP address
				$IP = $_SERVER['REMOTE_ADDR'];
			//----------------------------------------
			$array=explode(':',$codigo_infraccion);
			$array_area=explode('*',$codigo_area);
			
						
			if(!empty($numero_multa)){
				//actualiza
				$arraydatos[] = array("@busc", 4);
				$arraydatos[] = array("@codigo",  $codigo );
				$arraydatos[] = array("@codigo_autoridad_municipal",  $codigo_autoridad_municipal );
				$arraydatos[] = array("@fecha_multa", $fecha_multa );
				$arraydatos[] = array("@anno_multa", $anno_multa );				
				$arraydatos[] = array("@numero_multa", $numero_multa );
				$arraydatos[] = array("@antecedente", $antecedente );
				$arraydatos[] = array("@preimpreso", $preimpreso );
				$arraydatos[] = array("@anno_area", $this->_request->getPost ('anno_area'));

 			 if($e_guardiania=='1')
			  {
				$arraydatos[] = array("@codigo_area", '10TRP' );
				$arraydatos[] = array("@tipo_infraccion", 2 );
				$arraydatos[] = array("@codigo_infraccion", 956);
				$arraydatos[] = array("@codigo_adicional", 10 );
				$arraydatos[] = array("@cantidad", 1 );
				$arraydatos[] = array("@base", $dias_inter );
				$arraydatos[] = array("@factor", $factor_inter );
				$arraydatos[] = array("@monto_multa", $monto_inter );
				$arraydatos[] = array("@t_vehiculo", $t_vehiculo );
			  }
			  else{
				$arraydatos[] = array("@codigo_area", $array_area[0] );
				$arraydatos[] = array("@tipo_infraccion", $array[1] );
				$arraydatos[] = array("@codigo_infraccion", $array[2]);
				$arraydatos[] = array("@codigo_adicional", $array[3] );
				$arraydatos[] = array("@cantidad", $cantidad );
				
				$lavalorizacion=substr($codigo_infraccion,0,13);
				echo($lavalorizacion);
				if($lavalorizacion=='07URB:1:102:7' || $lavalorizacion=='07URB:1:103:7' || $lavalorizacion=='07URB:1:104:7'){
				$arraydatos[] = array("@base", $const_valor );
				$arraydatos[] = array("@factor", $factor_valor );
				$arraydatos[] = array("@monto_multa", $monto_valor );
				}
				else{
				$arraydatos[] = array("@base", $base );
				$arraydatos[] = array("@factor", $factor );
				$arraydatos[] = array("@monto_multa", $montoTotal );
				}
				
			  }
				$arraydatos[] = array("@estado", $estado );
				$arraydatos[] = array("@fecha_notificacion", $fecha_notificacion );	
				$arraydatos[] = array("@glosa", $glosa );
				
				//-----------MANUEL------------
				$arraydatos[] = array('@usuario_id_mod',$usuario);
				$arraydatos[] = array('@ip_mod',$IP);
				$arraydatos[] = array("@e_resolucion", $e_resolucion);
				if($e_resolucion=='1') 
				{				
					$arraydatos[] = array("@fech_notificacion2", $fech_notificacion2 );
					$arraydatos[] = array("@n_resolucion", $n_resolucion );	
					$arraydatos[] = array("@glosa_notificacion", $glosa_notificacion );
				}
				else
				{
					$arraydatos[] = array("@fech_notificacion2",'');
					$arraydatos[] = array("@n_resolucion",'');	
					$arraydatos[] = array("@glosa_notificacion",'');
				}
				///----------------------------
				
				
				
				@$rows = $cn->ejec_store_procedura_sql ( 'rentas.sp_multasadmin', $arraydatos );
				
			}else{
				//ingresa
				$arraydatos[] = array("@busc", 3);
				$arraydatos[] = array("@codigo",  $codigo );
				$arraydatos[] = array("@codigo_autoridad_municipal",  $codigo_autoridad_municipal );
				$arraydatos[] = array("@fecha_multa", $fecha_multa );
				$arraydatos[] = array("@numero_multa", $numero_multa );
				$arraydatos[] = array("@anno_multa", $anno_multa );
				$arraydatos[] = array("@antecedente", $antecedente );
				$arraydatos[] = array("@preimpreso", $preimpreso );
				$arraydatos[] = array("@anno_area", $this->_request->getPost ('anno_area'));
			  
			  if($e_guardiania=='1')
			  {
				$arraydatos[] = array("@codigo_area", '10TRP' );
				$arraydatos[] = array("@tipo_infraccion", 2 );
				$arraydatos[] = array("@codigo_infraccion", 956);
				$arraydatos[] = array("@codigo_adicional", 10 );
				$arraydatos[] = array("@cantidad", 1 );
				$arraydatos[] = array("@base", $dias_inter );
				$arraydatos[] = array("@factor", $factor_inter );
				$arraydatos[] = array("@monto_multa", $monto_inter );
				$arraydatos[] = array("@t_vehiculo", $t_vehiculo );
			  }
			  else{
				$arraydatos[] = array("@codigo_area", $array_area[0] );
				$arraydatos[] = array("@tipo_infraccion", $array[1] );
				$arraydatos[] = array("@codigo_infraccion", $array[2]);
				$arraydatos[] = array("@codigo_adicional", $array[3] );
				$arraydatos[] = array("@cantidad", $cantidad );
				
				$lavalorizacion=substr($codigo_infraccion,0,13);
				echo($lavalorizacion);
				if($lavalorizacion=='07URB:1:102:7' || $lavalorizacion=='07URB:1:103:7' || $lavalorizacion=='07URB:1:104:7'){
				$arraydatos[] = array("@base", $const_valor );
				$arraydatos[] = array("@factor", $factor_valor );
				$arraydatos[] = array("@monto_multa", $monto_valor );
				}
				else{
				$arraydatos[] = array("@base", $base );
				$arraydatos[] = array("@factor", $factor );
				$arraydatos[] = array("@monto_multa", $montoTotal );
				}
			  }
				$arraydatos[] = array("@estado", $estado );
				$arraydatos[] = array("@fecha_notificacion", $fecha_notificacion );	
				$arraydatos[] = array("@glosa", $glosa );
				
				$arraydatos[] = array('@operador',$usuario);
				$arraydatos[]= array ('@estacion',$IP);
				//-----------MANUEL------------
				$arraydatos[] = array("@e_resolucion", $e_resolucion);
				if($e_resolucion=='1') 
				{				
					$arraydatos[] = array("@fech_notificacion2", $fech_notificacion2 );
					$arraydatos[] = array("@n_resolucion", $n_resolucion );	
					$arraydatos[] = array("@glosa_notificacion", $glosa_notificacion );
				}
				///----------------------------
				@$rows = $cn->ejec_store_procedura_sql ( 'rentas.sp_multasadmin', $arraydatos );					
				
			}
			
			echo 'Verifique si los datos fueron guardados correctamente!!!!';
		}
	}

   	public function eliminarAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$cn = new Model_DbDatos_Datos ();
		
		$numero = $this->_request->getParam ( 'numero' );
		$codigo=$this->_request->getParam('codigo');
		
		$arraydatos [] = array ("@busc", 6 );
		$arraydatos [] = array ("@numero_multa", $numero );
		$arraydatos [] = array ("@codigo", $codigo );	
		@$rows = $cn->ejec_store_procedura_sql ( 'rentas.sp_multasadmin', $arraydatos );
		
		echo 'se elimino correctamente';
	}	
	
}
	



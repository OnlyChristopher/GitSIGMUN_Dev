<?php

class Mantcontriajuste2Controller extends Zend_Controller_Action
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
    	
		$this->view->title = "Busqueda de Contribuyentes";
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnSearchContri',"click","buscarContri()");
		$evt[] = array('#btnNewContri',"click","showPopup('mantcontri/formu','#popcontri','800','670','Nuevo Contribuyente');"); // ventana nuevo contribuyente
		$evt[] = array('#btnRefreshContri',"click","actualizarContri()");
		$evt[] = array('#btnPagoTupa',"click","pagotupaNuevo()");
		
		if($mod==14){
			$evt[] = array('#btnNewContri',"hide","");
			$evt[] = array('#btnRefreshContri',"hide","");
		}
		if($mod==2)
			$evt[] = array('#btnPagoTupa',"hide","");
			
		$fn->PintarEvento($evt);		
    }
    
	public function gestionAction()
	{
		$codigo=$this->_request->getParam('codigo','');

		$path = new Zend_Session_Namespace('path');
		$login = new Zend_Session_Namespace('login');
		$this->view->ruta = $path->data;
		$ar = new Libreria_ArraysFunctions();
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#chkTotPeriodos',"click","marcaChecks(this,'chkperiodo[]')");
		$evt[] = array('#chkTotArbitrio',"click","marcaChecks(this,'chkarbitrio[]')");
		$evt[] = array('#chkTotAnios',"click","marcaChecks(this,'chkanio[]')");
		$evt[] = array('#chkTotPreddd',"click","marcaChecks(this,'predio[]')");
		$evt[] = array('#chkTotConceptos',"click","marcaChecks(this,'conceptos[]')");
		$evt[] = array('#chkTotConceptos',"click","marcaChecks(this,'conceptos[]')");

		$evt[] = array('#btnPagarRecibos',"click","enviadeuda();");
		$evt[] = array('#btnMostrar',"click","mostrarRecContri(1);");
		//------------------agregó manuel----------------
		$evt[] = array('#btnSinInteres',"click","mostrarRecContri(2);");
		//-----------------------------------------------
		$evt[] = array('#btnVerRec',"click","mostrarRecibos();");
		$evt[] = array('#btnAnular',"click","anulaRecibos()");
		$evt[] = array('#btnEstCta',"click","imprimeCuentaPdf()");
		$evt[] = array('#btnCerrar',"click","closePopup('#poptesore');");
		$evt[] = array('#btnFraccionar',"click","fraccionar();");
		$evt[] = array('#btnMostrarAnulado',"click","mostrarRecibos();");
		$evt[] = array('#btnAjustecuenta',"click","imputardeuda();");
		
		$evt[] = array('#btnBaja',"click","getAjustecuenta();");
		$evt[] = array('#btnImputar',"click","getCompensacuenta();");

		//$evt[] = array('#btnBaja',"click","darbaja();");

		$fn->PintarEvento($evt);

		$nomcombo="rentas.sp_cuentacorriente";//"store_caja_framework";
		$arraydatos_nomcombo[]=array("@msquery",'2');//("@msquery",'1');
		$arraydatos_nomcombo[]=array("@codigo",$codigo);
		$cn = new Model_DbDatos_Datos();
	
		$rows = $cn->ejec_store_procedura_sql($nomcombo, $arraydatos_nomcombo);
		/*for ($i=0;$i<count($rows);$i++){*/
			$codigo=$rows[0][0];
			$nombre=$rows[0][1];
			//$doc=$rows[$i][2];
			$numdoc=$rows[0][3];
			$direccion=$rows[0][2];
			//$cantpred=$rows[$i][5];----------------
			//$apepater=$rows[$i][6];
			//$apemater=$rows[$i][7];
		//}
	//var_dump($rows);
	$contricaja = new Zend_Session_Namespace('contri');
    $contricaja->contri=$codigo;
    $contricaja->nombre=$nombre;
    $contricaja->numdoc=$numdoc;
    $contricaja->direccion=$direccion;
                
	 //divPredios
	 $val[] = array('#divCodigo',$codigo,"html");//en caso de de div html - input val	 
	 $val[] = array('#divContri',$nombre,"html");
	 $val[] = array('#divDirec',$direccion,"html");
	 $val[] = array('#divDocu',$numdoc,"html");
	 
	 $this->view->codigocaja=$codigo;
	 
	unset ($arraydatos);
	$arraydatos []= array ('@msquery',11);
	$rows=$cn->ejec_store_procedura_sql('[Rentas].[sp_cuentacorriente]',$arraydatos);
	$arRows= $ar->RegistrosCombo($rows,0,1);
	$val[]=array('#tipo_doc_sustento',$fn->ContenidoCombo($arRows,'[Seleccione]','',''),'html');
	 
	
	$fn->PintarValor($val);

	}
	
	
    public function formuAction()
    {    		
    	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		//$evt[] = array('#btnBusqueda',"click","showPopup('mantbusqueda/busqueda','#popbusqueda','700','280','Busqueda','frmbusqueda');");
		
		$evt[] = array('#btnBusqueda',"click","showPopup('mantbusqueda/buscar','#popbuscar','700','280','Busquedas','frmbuscar');");
		
		$evt[] = array('#cmbDocContri',"change","validaText($(this).val());");
		
		$codigo = $this->_request->getParam('codigo','');
		
		if(strlen($codigo)>0)
		{
			$parametros[] = array('@busc',4);
			$parametros[] = array('@codigo',$codigo);
			$rowContri = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);
			
			$id_pers = $rowContri[0][1];
			
			//$id_docu = $rowContri[0][2];
			
			unset($parametros);
			$parametros[] = array('@msquery',1);
			$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametros);
			$id_docu='';
			
			for ($i=0;$i<count($rowsDocumento);$i++){
				$cad=explode('/',$rowsDocumento[$i][0]);
				
				if(trim($cad[0])==$rowContri[0][2])
				{
					$id_docu.='<option value="'.$rowsDocumento[$i][0].'" selected>'.$rowsDocumento[$i][1].'</option>';
				}
				else{
					$id_docu.='<option value="'.$rowsDocumento[$i][0].'" >'.$rowsDocumento[$i][1].'</option>';						
				}
       		}
			
			
			
			$num_doc = $rowContri[0][3];
			$nombres = $rowContri[0][4];
			$paterno = $rowContri[0][5];
			$materno = $rowContri[0][6];
			$id_dist = $rowContri[0][7];
			$tipourb = $rowContri[0][8];
			$des_urb = $rowContri[0][9];
			$tipovia = $rowContri[0][10];
			$des_via = $rowContri[0][11];
			$id_zona = $rowContri[0][12];
			$id_urba = $rowContri[0][13];
			$id_via = $rowContri[0][14];
			$referencia = $rowContri[0][15];
			$manzana = $rowContri[0][16];
			$lote = $rowContri[0][17];
			$sub_lote = $rowContri[0][18];
			$numero = $rowContri[0][19];
			$departam = $rowContri[0][20];
			$nestado = $rowContri[0][21];
			$operador = $rowContri[0][22];
			$estacion = $rowContri[0][23];
			$fech_ing = $rowContri[0][24];
			$nomzona = $rowContri[0][27];
			$nomurba = $rowContri[0][28]." ".$rowContri[0][29];
			$nomvia = $rowContri[0][30];
			$tipocontri = $rowContri[0][31];
			$subtipocontri = $rowContri[0][32];
			$TipoActuContri = $rowContri[0][33];//CASIMIRO MODIFICO ACTULIZACION CONTRTIBUYENTE
			$TipoInteContri =$rowContri[0][42];// CASIMIRO MODIFICO INTERIOR CONTRTIBUYENTE
			$TipoEdifContri =$rowContri[0][45];// CASIMIRO MODIFICO EDIFICACION CONTRTIBUYENTE
			$TipoIngresoContri =$rowContri[0][44];// CASIMIRO MODIFICO INTERIOR CONTRTIBUYENTE
			$TipoAgrupaContri =$rowContri[0][43];// CASIMIRO MODIFICO AGRUPAMIENTO CONTRTIBUYENTE
			$letra1 =$rowContri[0][39];
			$numero2 =$rowContri[0][40];
			$letra2 =$rowContri[0][41];
			$nombre_ingreso=$rowContri[0][47];
			$nombre_agrupamiento=$rowContri[0][48];
			$nombre_edificio=$rowContri[0][46];
			$piso=$rowContri[0][49];
			$letra_interno=$rowContri[0][50];
			$numero_interno=$rowContri[0][51];
			$this->view->action = 'E';
	
		}
		else
		{
			//$id_docu = '01';
			
			unset($parametros);
			$parametros[] = array('@msquery',1);
			$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametros);
			$id_docu='';		
			
	    	for ($i=0;$i<count($rowsDocumento);$i++){
	            	$id_docu.='<option value="'.$rowsDocumento[$i][0].'" >'.$rowsDocumento[$i][1].'</option>';
	        }
			
			
			$id_dist = '012';
			$nestado = 1;
			$operador = 'prueba';
			$estacion = 'prueba';
			$this->view->action = 'N';

		}
		
		$val[] = array('#txtCodContri',$codigo,'val');
		$val[] = array('#id_pers',$id_pers,'val');
		$val[] = array('#txtNumDoc',$num_doc,'val');
		$val[] = array('#txtNomContri',$nombres,'val');
		$val[] = array('#txtApePatContri',$paterno,'val');
		$val[] = array('#txtApeMatContri',$materno,'val');
		$val[] = array('#txtDesUrb',$des_urb,'val');
		$val[] = array('#txtDesVia',$des_via,'val');
		$val[] = array('#txtRef',$referencia,'val');
		$val[] = array('#txtMz',$manzana,'val');
		$val[] = array('#txtLt',$lote,'val');
		$val[] = array('#txtSubLt',$sub_lote,'val');
		$val[] = array('#txtNum',$numero,'val');
		$val[] = array('#txtDpto',$departam,'val');
		$val[] = array('#nestado',$nestado,'val');
		$val[] = array('#operador',$operador,'val');
		$val[] = array('#estacion',$estacion,'val');
		$val[] = array('#txtViacodigo',$id_via,'val'); 
		$val[] = array('#txtUrbacodigo',$id_urba,'val');
		$val[] = array('#txtZonacodigo',$id_zona,'val');
		$val[] = array('#txtZona',$nomzona,'val');
		$val[] = array('#txtUrbanizacion',$nomurba,'val');
		$val[] = array('#txtViacontri',$nomvia,'val');	
		$val[] = array('#cmbDocContri',$id_docu,'html');
		$val[] = array('#txtletra1',$letra1,'val');
		$val[] = array('#txtnumero2',$numero2,'val');
		$val[] = array('#txtletra2',$letra2,'val');
		$val[] =array('#txtpiso',$piso,'val');
		$val[] = array('#txtIngreso',$nombre_ingreso,'val');
		$val[] = array('#txtAgrupamiento',$nombre_agrupamiento,'val');
		$val[] = array('#txtEdificacion',$nombre_edificio,'val');
		$val[] = array('#txtletra_interno',$letra_interno,'val');
		$val[] = array('#txtnumero_interno',$numero_interno,'val');
		
		
		
		unset($parametros);
		$parametros[] = array('@busc',7);	
		$tipoContribuyente = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);
		$arComboTipocontribuyente = $ar->RegistrosCombo($tipoContribuyente,0,1);
		$val[] = array('#cmbTipoContri',$fn->ContenidoCombo($arComboTipocontribuyente,'[Seleccione]',trim($tipocontri)),'html');
		
		////CASIMIRO MODIFICO ACTULIZACION CONTRTIBUYENTE
		unset($parametros);
		$parametros[] = array('@busc',9);	
		$tipoActualizacionContribuyente = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);
		$arComboTipoActucontribuyente = $ar->RegistrosCombo($tipoActualizacionContribuyente,0,1);
		$val[] = array('#cmbTipoActuContri',$fn->ContenidoCombo($arComboTipoActucontribuyente,'[Seleccione]',trim($TipoActuContri)),'html');
		////CASIMIRO MODIFICO ACTULIZACION CONTRTIBUYENTE
		
		//////////////////////////////////////CASIMIRO INGRESO TIPO DE INTERIOR//////////////////////////////////////////////////////////////////
		unset($parametros);
		$parametros[] = array('@busc',10);	
		$tipoInteriorContribuyente = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);
		$arComboTipoInteriorcontribuyente = $ar->RegistrosCombo($tipoInteriorContribuyente,0,1);
		$val[] = array('#cmbTipoInterior',$fn->ContenidoCombo($arComboTipoInteriorcontribuyente,'[Seleccione]',trim($TipoInteContri)),'html');
		//////////////////////////////////////////CASIMIRO INGRESO TIPO DE EDIFICACION//////////////////////////////////////////////////
		unset($parametros);
		$parametros []=array('@busc',11);
		$tipoEdificacionContribuyente=$cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente',$parametros);
		$arComboEdificacionContibuyente=$ar->RegistrosCombo($tipoEdificacionContribuyente,0,1);
		$val[] = array('#cmbTipoEdificacion',$fn->ContenidoCombo($arComboEdificacionContibuyente,'[Seleccione]',trim($TipoEdifContri)),'html');
		//315 913 324
		
		//////////////////////////////////////////CASIMIRO INGRESO TIPO DE INGRESO//////////////////////////////////////////////////
		unset($parametros);
		$parametros []=array('@busc',12);
		$tipoIngresoContribuyente=$cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente',$parametros);
		$arComboIngresoContibuyente=$ar->RegistrosCombo($tipoIngresoContribuyente,0,1);
		$val[] = array('#cmbTipoIngreso',$fn->ContenidoCombo($arComboIngresoContibuyente,'[Seleccione]',trim($TipoIngresoContri)),'html');
		//cmbTipoAgrupamiento
		//////////////////////////////////////////////CASIMIRO INGRESO TIPO DE AGRUPAMIENTO//////////////////////////////////////////////////////////////
		unset($parametros);
		$parametros []=array('@busc',13);
		$tipoAgrupamientoContribuyente=$cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente',$parametros);
		$arComboAgrupamientoContibuyente=$ar->RegistrosCombo($tipoAgrupamientoContribuyente,0,1);
		$val[] = array('#cmbTipoAgrupamiento',$fn->ContenidoCombo($arComboAgrupamientoContibuyente,'[Seleccione]',trim($TipoAgrupaContri)),'html');
		//cmbTipoAgrupamiento
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($parametros);
		$parametros[] = array('@busc',8);	
		$parametros[] = array('@id_tipocontri',$tipocontri);// para relacionar p
		$subtipoContribuyente = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);
		$arCombosubTipocontribuyente = $ar->RegistrosCombo($subtipoContribuyente,0,1);
		$val[] = array('#cmbTipoContri2',$fn->ContenidoCombo($arCombosubTipocontribuyente,'[Seleccione]',trim($subtipocontri)),'html');
		
		unset($parametros);
		$parametros[] = array('@msquery',1);	
		$tipodistrito = $cn->ejec_store_procedura_sql('Contenedor.SP_TblDistrito', $parametros);
		$arCombodistrito = $ar->RegistrosCombo($tipodistrito,0,1);
		$val[] = array('#cmbDisContri',$fn->ContenidoCombo($arCombodistrito,'[Seleccione]',trim($id_dist)),'html');
		$evt[] = array('#cmbDisContri',"change","changeDisContri(this.value)");
		//$fun[] = array("changeDisContri('".trim($id_dist)."')");
		
		
		
		unset($parametros);
		$parametros[] = array('@msquery',5);	
		$tipourbanizacion = $cn->ejec_store_procedura_sql('Rentas.SP_MCUrba', $parametros);
		$arTipourbanizacion = $ar->RegistrosCombo($tipourbanizacion,0,1);
		$val[] = array('#cmbTipUrb',$fn->ContenidoCombo($arTipourbanizacion,'[Seleccione]',trim($tipourb)),'html');
		
		/*
		$tipurban['01'] = "Tipo Urba 1";
		$tipurban['02'] = "Tipo Urba 2";
		$tipurban['03'] = "Tipo Urba 3";
		$val[] = array('#cmbTipUrb',$fn->ContenidoCombo($tipurban,'[Seleccione]',trim($tipourb)),'html');
		$val[] = array('#cmbTipUrb',"100","width");
		*/
		
		unset($parametros);
		$parametros[] = array('@busc',7);	
		$tipvia = $cn->ejec_store_procedura_sql('Rentas.sp_MVias', $parametros);
		$arTipovia = $ar->RegistrosCombo($tipvia,0,1);
		$val[] = array('#cmbTipVia',$fn->ContenidoCombo($arTipovia,'[Seleccione]',trim($tipovia)),'html');
		
		/*
		$tipvia['01'] = "Tipo V�a 1";
		$tipvia['02'] = "Tipo V�a 2";
		$tipvia['03'] = "Tipo V�a 3";
		$val[] = array('#cmbTipVia',$fn->ContenidoCombo($tipvia,'[Seleccione]',trim($tipovia)),'html');
		$val[] = array('#cmbTipVia',"100","width");
		*/
		$zonas['01'] = "Zona 1";
		$zonas['02'] = "Zona 2";
		$zonas['03'] = "Zona 3";
		$val[] = array('#cmbZona',$fn->ContenidoCombo($zonas,'[Seleccione]',trim($id_zona)),'html');
		
		$urbans['0001'] = "Urbanizaci�n 1";
		$urbans['0002'] = "Urbanizaci�n 2";
		$urbans['0003'] = "Urbanizaci�n 3";
		$val[] = array('#cmbUrb',$fn->ContenidoCombo($urbans,'[Seleccione]',trim($id_urba)),'html');
		
		$vias['0001'] = "Via 1";
		$vias['0002'] = "Via 2";
		$vias['0003'] = "Via 3";
		$val[] = array('#cmbVia',$fn->ContenidoCombo($vias,'[Seleccione]',trim($id_via)),'html');
		
		$evt[] = array('#txtNomContri',"focus","");
		
		$evt[] = array('#btnGrabaContri',"click","goToFormulario('frmcontri');");
		$evt[] = array('#btnSalirContri',"click","closePopup('#popcontri');");
		
		//$evt[] = array('#txtNomContri',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtNomContri',"blur","this.value = this.value.toUpperCase();");

		//$evt[] = array('#txtApePatContri',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtApePatContri',"blur","this.value = this.value.toUpperCase();");
		
		//$evt[] = array('#txtApeMatContri',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtApeMatContri',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtRef',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtRef',"blur","this.value = this.value.toUpperCase();");

		$evt[] = array('#txtNumDoc',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtNumDoc',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtDesVia',"keypress","return validaTeclas(event,'alpha');");
		$evt[] = array('#txtDesVia',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtDesUrb',"keypress","return validaTeclas(event,'alpha');");
		$evt[] = array('#txtDesUrb',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtNum',"keypress","return validaTeclas(event,'alpha');");		
					
		$evt[] = array('#txtMz',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtLt',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtSubLt',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtNum',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtDpto',"blur","this.value = this.value.toUpperCase();");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		//$fn->EjecutarFuncion($fun);
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
    public function grabarAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			$cn = new Model_DbDatos_Datos();
			$codigo = $this->_request->getPost('txtCodContri');
			
			if(strlen($codigo)>0)
				$tip = 2;
			else
				$tip = 1;
				
			$parametros[] = array('@busc',$tip);
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@id_pers',$this->_request->getPost('id_pers'));
			$parametros[] = array('@id_docu',$this->_request->getPost('cmbDocContri'));
			$parametros[] = array('@num_doc',$this->_request->getPost('txtNumDoc'));
			$parametros[] = array('@nombres',$this->_request->getPost('txtNomContri'));
			$parametros[] = array('@paterno',$this->_request->getPost('txtApePatContri'));
			$parametros[] = array('@materno',$this->_request->getPost('txtApeMatContri'));
			$parametros[] = array('@id_dist',$this->_request->getPost('cmbDisContri'));
			$parametros[] = array('@tipourb',$this->_request->getPost('cmbTipUrb'));
			$parametros[] = array('@des_urb',$this->_request->getPost('txtDesUrb'));
			$parametros[] = array('@tipovia',$this->_request->getPost('cmbTipVia'));
			$parametros[] = array('@des_via',$this->_request->getPost('txtDesVia'));
			$parametros[] = array('@id_zona',$this->_request->getPost('txtZonacodigo'));
			$parametros[] = array('@id_urba',$this->_request->getPost('txtUrbacodigo'));
			$parametros[] = array('@id_via',$this->_request->getPost('txtViacodigo'));
			$parametros[] = array('@referencia',$this->_request->getPost('txtRef'));
			$parametros[] = array('@manzana',$this->_request->getPost('txtMz'));
			$parametros[] = array('@lote',$this->_request->getPost('txtLt'));
			$parametros[] = array('@sub_lote',$this->_request->getPost('txtSubLt'));
			$parametros[] = array('@numero',$this->_request->getPost('txtNum'));
			$parametros[] = array('@departam',$this->_request->getPost('txtDpto'));
			$parametros[] = array('@nestado',$this->_request->getPost('nestado'));
			$parametros[] = array('@operador',$this->_request->getPost('operador'));
			$parametros[] = array('@estacion',$this->_request->getPost('estacion'));
			$parametros[] = array('@id_tipocontri',$this->_request->getPost('cmbTipoContri'));
			$parametros[] = array('@id_subtipocontri',$this->_request->getPost('cmbTipoContri2'));
			$parametros[] = array('@id_motivo_actualizacion',$this->_request->getPost('cmbTipoActuContri'));//CASIMIRO MODIFICO ACTULIZACION CONTRTIBUYENTE
			$parametros[] = array('@tipo_interior_id',$this->_request->getPost('cmbTipoInterior'));//CASIMIRO MODIFICO ACTULIZACION CONTRTIBUYENTE
			$parametros[] = array('@tipo_edificio_id',$this->_request->getPost('cmbTipoEdificacion'));//CASIMIRO MODIFICO ACTULIZACION CONTRTIBUYENTE - Edificacion
			$parametros[] = array('@tipo_ingreso_id',$this->_request->getPost('cmbTipoIngreso'));//CASIMIRO MODIFICO ACTULIZACION CONTRTIBUYENTE - Ingreso
			$parametros[] = array('@tipo_agrupamiento_id',$this->_request->getpost('cmbTipoAgrupamiento'));//CASIMIRO MODIFICO ACTULIZACION CONTRTIBUYENTE - Agrupamiento
			$parametros[] = array('@letra1',$this->_request->getPost('txtletra1'));
			$parametros[] = array('@letra2',$this->_request->getPost('txtletra2'));
			$parametros[] = array('@numero2',$this->_request->getPost('txtnumero2'));
			$parametros[] = array('@nombre_ingreso',$this->_request->getPost('txtIngreso'));
			$parametros[] = array('@nombre_agrupamiento',$this->_request->getPost('txtAgrupamiento'));
			$parametros[] =array('@nombre_edificio',$this->_request->getPost('txtEdificacion'));
			$parametros[] =array('@piso',$this->_request->getPost('txtpiso'));
			$parametros[] =array('@numero_interno',$this->_request->getPost('txtnumero_interno'));
			$parametros[] =array('@letra_interno',$this->_request->getPost('txtletra_interno'));
			
											
			$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);
			echo $rows[0][0];
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
			
			//echo $codigo."<br/>";
			$parametros[] = array('@busc',3);
			$parametros[] = array('@codigo',$codigo);
			
			@$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);	
			
			echo "Registro eliminado correctamente!";
		}

	}
	
	
		public function conscontriAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
	    
	    if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$id_tipocontri = $this->_request->getPost('id_tipocontri');
			
			$combostore1 ='Rentas.SP_Mcontribuyente';
			$arraydatos1[0] = array("@busc",8);
			$arraydatos1[1] = array("@id_tipocontri",$id_tipocontri);
			$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);
			
			$cb_Tipocontri='<option value="">[Seleccione]</option>';
			for ($i=0;$i<count($rows1);$i++){
            	$cb_Tipocontri.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][1].'</option>';
        	}
		
			echo $cb_Tipocontri;
    	}  
    }
    
	public function grabarbajaAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		$login = new Zend_Session_Namespace('login');
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			$login = new Zend_Session_Namespace('login');
			$dataform=$this->_request->getPost('dataform');
			$nro_doc_sustento=$this->_request->getPost('nro_doc_sustento');
			$doc_sustento=$this->_request->getPost('doc_sustento');
			$tipo_doc_sustento=$this->_request->getPost('tipo_doc_sustento');
			
			$txtMontoajustar = $this->_request->getPost('txtMontoajustar');
			$txt_total = $this->_request->getPost('txt_total');
						
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			$dxml = '';
			foreach ($data as $key => $value){ 
				$dxml.="<row ";
				foreach ($value as $k => $v) { 
					$dxml.=$k.' = "'.$v.'" '; 
				}
				$dxml.=" />";
			}
			echo $data;
			
					
					unset($arraydatos);
					$arraydatos[]=array("@dataxml", $dxml);
					$arraydatos[]=array("@montoacuenta", $txtMontoajustar);
					$arraydatos[]=array("@monto", $txt_total);
					$arraydatos[]=array("@doc_sustento", $doc_sustento);
					$arraydatos[]=array("@nro_doc_sustento", $nro_doc_sustento);
					$arraydatos[]=array("@tipo_doc_sustento", $tipo_doc_sustento);
					$arraydatos[]=array("@tipo_baja", 2);
					$arraydatos[]=array("@operador", $login->user);
					$arraydatos[]=array("@estacion", php_uname('n'));
					$rows = $cn->ejec_store_procedura_sql("[Rentas].[sp_Anularrecibos]",$arraydatos);
						
				
			}
			
	}
	
	public function grabarcompensacionAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		$login = new Zend_Session_Namespace('login');
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			$login = new Zend_Session_Namespace('login');
			$dataform=$this->_request->getPost('dataform');
			$nro_doc_sustento=$this->_request->getPost('nro_doc_sustento');
			$doc_sustento=$this->_request->getPost('doc_sustento');
			$tipo_doc_sustento=$this->_request->getPost('tipo_doc_sustento');
			
			$txtMontoajustar = $this->_request->getPost('txtMontoajustar');
			$txt_total = $this->_request->getPost('txt_total');
						
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			$dxml = '';
			foreach ($data as $key => $value){ 
				$dxml.="<row ";
				foreach ($value as $k => $v) { 
					$dxml.=$k.' = "'.$v.'" '; 
				}
				$dxml.=" />";
			}
			echo $data;
			
					
					unset($arraydatos);
					$arraydatos[]=array("@dataxml", $dxml);
					$arraydatos[]=array("@montoacuenta", $txtMontoajustar);
					$arraydatos[]=array("@monto", $txt_total);
					$arraydatos[]=array("@doc_sustento", $doc_sustento);
					$arraydatos[]=array("@nro_doc_sustento", $nro_doc_sustento);
					$arraydatos[]=array("@tipo_doc_sustento", $tipo_doc_sustento);
					$arraydatos[]=array("@tipo_baja", 0);
					$arraydatos[]=array("@operador", $login->user);
					$arraydatos[]=array("@estacion", php_uname('n'));
					$rows = $cn->ejec_store_procedura_sql("[Rentas].[sp_Compensarrecibos]",$arraydatos);
						
				
			}
			
	}	
	
	
	public function prediosAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		$var='*';
		if($this->getRequest()->isXmlHttpRequest()){
			$cn = new Model_DbDatos_Datos();

			$codigo=$this->_request->getParam('codigocaja');
			
			$nombrestorepredios="store_caja_framework 15,@codigo='$codigo'";
			$rowpredios = $cn->ejec_store_procedura_sql($nombrestorepredios, null);
			$predios='';
			for ($i=0;$i<count($rowpredios);$i++){

				$predios.='<tr> <td><label style="font-size:10px;"><input type="checkbox" name="predio[]" id="predio[]" value="'.$var.$rowpredios[$i][2].$var.'" style="float:left;"> <div style="float:left; margin:0 0 5px 5px; width:280px">'.$rowpredios[$i][2].' - '.$rowpredios[$i][3].' '.utf8_encode($rowpredios[$i][4]).'</div></label></td>  </tr>';

			}

			$pred='<table  width="100%">'.$predios."</table>";
			echo $pred;
		}		 
	}
	
	public function avanzadoAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$cn = new Model_DbDatos_Datos();
		
		$val_min='0';
		
		$idrecibo = $this->_request->getParam('idrecibo');
		$codigo = $this->_request->getParam('codigo');
		$des_tipo = $this->_request->getParam('des_tipo');
		$cod_pred = $this->_request->getParam('cod_pred');
		$anexo = $this->_request->getParam('anexo');
		$sub_anexo = $this->_request->getParam('sub_anexo');
		$tipo_rec = $this->_request->getParam('tipo_rec');
		$periodo = $this->_request->getParam('periodo');
		$imp_insol = $this->_request->getParam('imp_insol');
		$imp_reaj = $this->_request->getParam('imp_reaj');
		$imp_reaj=$imp_reaj-$imp_insol;
		$mora = $this->_request->getParam('mora');
		$cost_emis = $this->_request->getParam('cost_emis');
		$total = $this->_request->getParam('total');
		$fec_venc = $this->_request->getParam('fec_venc');
		$estado = $this->_request->getParam('estado');

		$arraydatos[]=array("@busc",'1');
		$arraydatos[]=array("@codigo",$codigo);
		$arraydatos[]=array("@idrecibo",$idrecibo);
		
		$row = $cn->ejec_store_procedura_sql('[Rentas].[Ajuste_Avanzado]', $arraydatos);
		
		
		
		$this->view->in_deb_inicial=$imp_insol;
		$this->view->de_deb_inicial=$cost_emis;
		$this->view->re_deb_inicial=$imp_reaj;
		$this->view->mo_deb_inicial=$mora;
		$this->view->to_deb_inicial=$total;
		
		if($estado=='1')
		{
			$n_ntotal=$total;
			$tot_ant=$val_min;
		}
		else
		{
			$n_ntotal=$val_min;
			$tot_ant=$total;
		}	
			$this->view->in_cre_inicial=$n_ntotal;
			$this->view->de_cre_inicial=$val_min;
			$this->view->re_cre_inicial=$val_min;
			$this->view->mo_cre_inicial=$val_min;
			$this->view->to_cre_inicial=$n_ntotal;
			
			$this->view->in_sal_inicial=($imp_insol-$n_ntotal);
			
			$this->view->to_sal_inicial=$tot_ant;

		$this->view->data1=$row[0][0]." | ".$row[0][1]." | ".$row[0][2];
		$this->view->data2=$row[0][3]." | ".$row[0][4]." | ".$row[0][5]." - ".$row[0][6];		
		$this->view->data3=$row[0][7]." | ".$row[0][8]." | ".$row[0][9];		
			
			
		unset ($param);
		$param []= array ('@msquery',11);
		$param=$cn->ejec_store_procedura_sql('[Rentas].[sp_cuentacorriente]',$param);
		$arRows= $ar->RegistrosCombo($param,0,1);
		$val[]=array('#cmb_tipo',$fn->ContenidoCombo($arRows,'[Seleccione]','',''),'html');	
		
		$val[] =array('#txtidrecibo',$idrecibo,'val');
		$val[] =array('#txtcodigo',$codigo,'val');
		
		$evt[] = array('#btnClose',"click","closePopup('#popcatasmapa');");
		
		$evt[] = array('#btnProcesar',"click","RealizaOperacion(1);");
		$evt[] = array('#btnBajaP',"click","RealizaOperacion(2);");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);		
		
		//echo($idrecibo);
	}
	
	public function procesarAction()
	{
		$login = new Zend_Session_Namespace('login');
		$usuario = $login->user;
		
		$IP = $_SERVER['REMOTE_ADDR'];
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		
		//parametros recepcionados por url
		$idrecibo = $this->_request->getParam('idrecibo');
		$codigo = $this->_request->getParam('codigo');
		//parametros recepcionados del formulario
		$imp_insol	=$this->_request->getParam('imp');
		$imp_reaj	=$this->_request->getParam('reaj');
		$costo_emis	=$this->_request->getParam('de');
		$mora		=$this->_request->getParam('sal');
		
		$cmbtipo		=$this->_request->getPost('cmb_tipo');
		$sustento		=$this->_request->getPost('txtnumero');
		
		
		$cn = new Model_DbDatos_Datos();
		
		$array[]=array("@busc",'2');
		$array[]=array("@codigo",$codigo);
		$array[]=array("@idrecibo",$idrecibo);
		$array[]=array("@imp_insol",$imp_insol);
		$array[]=array("@imp_reaj",$imp_reaj);
		$array[]=array("@costo_emis",$costo_emis);
		$array[]=array("@mora",$mora);
		
		$array[]=array("@tipo",$cmbtipo);
		$array[]=array("@sustento",$sustento);
		
		$array[]=array("@usuario",$usuario);
		$array[]=array("@estacion",$IP);
		
		$cn->ejec_store_procedura_sql('[Rentas].[Ajuste_Avanzado]', $array);
		
	
	}
	
	
	
	
 	public function periodosAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		$var='*';
		if($this->getRequest()->isXmlHttpRequest()){
			$cn = new Model_DbDatos_Datos();
			$codigo=$this->_request->getParam('codigocaja');
			
			$nombrestoreperiodos="rentas.sp_cuentacorriente";//"store_caja_framework 5,@codigo='$codigo'";
			$arraydatos[]=array("@msquery",'4');//("@msquery",'1');
			$arraydatos[]=array("@codigo",$codigo);
			$rowperiodo = $cn->ejec_store_procedura_sql($nombrestoreperiodos, $arraydatos);
			$periodos='';
			for ($i=$rowperiodo[0][0];$i<=$rowperiodo[0][1];$i++){
				
				$periodos.='<tr> <td><label><input type="checkbox" name="chkperiodo[]" id="chkperiodo[]" value="'.$var.str_pad($i, 2, 0, STR_PAD_LEFT).$var.'"> '.str_pad($i, 2, 0, STR_PAD_LEFT).'</label></td>  </tr>';
	//			$periodos.='<tr> <td><label><input type="checkbox" name="chkperiodo[]" id="chkperiodo[]" value="'.$var.str_pad($i, 2, 0, STR_PAD_LEFT).$var.'" onclick="fraccionaperiodo(this);"> '.str_pad($i, 2, 0, STR_PAD_LEFT).'</label></td>  </tr>';

			}
			$periodo=$pred='<table width="100%">'.$periodos."</table>";
			echo $periodo;
		}
		 
	}
	
	public function aniosAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		if($this->getRequest()->isXmlHttpRequest()){
			$cn = new Model_DbDatos_Datos();
			
			$nombrestoreanios="rentas.sp_cuentacorriente";//"store_caja_framework 6,@codigo='$codigo'";
			$codigo=$this->_request->getParam('codigocaja');
			$arraydatos[]=array("@msquery",5);
			$arraydatos[]=array("@codigo",$codigo);
			$rowanios = $cn->ejec_store_procedura_sql($nombrestoreanios, $arraydatos);
			$anios='';
			$j=0;
			for ($i=$rowanios[0][0];$i<=$rowanios[0][1];$i++){
				$brper='';
				$j=$j+1;
				if($j==12 || $j==24){
					$branio='<br>';
				}else{
					$branio='';
				}
				$var='*';
//				$i=$var.$i.$var;
				$anios.='<tr> <td><label><input type="checkbox" name="chkanio[]" id="chkanio[]" value='.$var.$i.$var.'> '.$i.''.$branio.'</label></td>  </tr>';
//				$anios.='<tr> <td><label><input type="checkbox" name="chkanio[]" id="chkanio[]" value='.$var.$i.$var.' onclick="fraccionaperiodo(this);"> '.$i.''.$branio.'</label></td>  </tr>';
				//$rowpredios[$i][0];

			}
			$anio='<table width="100%">'.$anios."</table>";

			echo $anio;
		}		 
	}
	
	
	
}



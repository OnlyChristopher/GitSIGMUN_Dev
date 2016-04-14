<?php

class MantcontrimultaadminController extends Zend_Controller_Action
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
		
		$evt[] = array('#txtNomContri',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtNomContri',"blur","this.value = this.value.toUpperCase();");

		$evt[] = array('#txtApePatContri',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtApePatContri',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtApeMatContri',"keypress","return validaTeclas(event,'text');");
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
		
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente_MultasAdmin', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],	
						'tipocontri'=>utf8_encode($row[27]),	
						'documento'=>utf8_encode($row[25]),  
						'numdoc'=>utf8_encode($row[3]),
						'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
						'direccion'=>utf8_encode($row[15]),
						'predios'=>utf8_encode($row[28]),
						'multa'=>utf8_encode($row[29])
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
    
}



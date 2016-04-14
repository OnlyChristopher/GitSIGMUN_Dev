<?php

class PreventivaController extends Zend_Controller_Action
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
		//echo($mod);
    	$this->view->mod = $mod;
		
    	$val[] = array('#txtacceso',$mod,'val');
		
		$this->view->title = "Busqueda de Infractores";
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnSearchContri',"click","buscarContri()");
		$evt[] = array('#btnNewContri',"click","showPopup('mantinfractor/formu','#popinfractor','800','670','Nuevo Infractor');"); // ventana nuevo contribuyente
		$evt[] = array('#btnRefreshContri',"click","actualizarContri()");
		$evt[] = array('#btnPagoTupa',"click","pagotupaNuevo()");
		
		if($mod==14){
			$evt[] = array('#btnNewContri',"hide","");
			$evt[] = array('#btnRefreshContri',"hide","");
		}
		if($mod==2)
			$evt[] = array('#btnPagoTupa',"hide","");
			
		$fn->PintarEvento($evt);
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
		
		$rowsTotal = $cn->ejec_store_procedura_sql('[Mpreventiva].[sp_Infractor]', $parametros);
    	
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
		
		
		$rows = $cn->ejec_store_procedura_sql('[Mpreventiva].[sp_Infractor]', $parametros);
		
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
						'direccion'=>utf8_encode($row[15])
						// ,
						// 'predios'=>utf8_encode($row[28]),
						// 'multa'=>utf8_encode($row[29])
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
			
											
			$rows = $cn->ejec_store_procedura_sql('[Mpreventiva].[sp_Infractor]', $parametros);
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
	
	 public function localAction()
    {	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;		
		$this->view->title = "Registro de Preventivas";		
		$fn = new Libreria_Pintar();		
		$evt[] = array('#contentBox1',"tabs","");
		$cn = new Model_DbDatos_Datos();
		
		$evt[] = array('#btnMostrar',"click","mostrarRecContri();");
		$evt[] = array('#btnRecibo',"click","mostrarRecibos();");
				
    	$codigo=$this->_request->getparam('codigo');
		$mod=$this->_request->getparam('acceso');
		$val[] = array('#txtmod', $mod, 'val');
		//echo ($mod);
		
    	$arraydatos[]=array("@busc",8);
		$arraydatos[]=array("@codigo",$codigo);
		@$rowsContri = $cn->ejec_store_procedura_sql('Mpreventiva.Notificacion', $arraydatos);
		
		$val[] = array('#codigoContri', $rowsContri [0][0], 'val');
		$val[] = array('#num_doc', $rowsContri[0][1], 'val');
		$val[] = array('#txtNomContri', $rowsContri [0][2], 'val');
		$val[] = array('#tipocontri', $rowsContri[0][3], 'val');//mostrar el tipo de contribuyente
		$codtipocontri = $rowsContri[0][3];
		
		$evt[]= array('#btnNuevo','click',"showPopup('preventiva/notificacion?codigo=$codigo&codtipocontri=$codtipocontri','#popnewmulta','700','450','Nueva Preventiva');" );
		
		//$evt[]= array('#btnNuevo','click',"showPopup('multasadmin/editar?codigo=$codigo','#popnewmulta','600','450','Nuevo Multas Administrativas');" );
		$evt[] = array('#btnSalir', 'click', "closePopup('#popcontri');" );
		
		$fn->PintarEvento ( $evt );
		$fn->PintarValor ( $val );
	}
	
	public function consultalocalAction() {
	

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
		
		@$rowsTotal = $cn->ejec_store_procedura_sql('Mpreventiva.Notificacion', $arraydatos);
    
		//Para las filas
		unset($arraydatos);
    	$arraydatos[]=array('@busc',1);
    	$arraydatos[]=array("@codigo",$codigo );
		@$rows = $cn->ejec_store_procedura_sql('Mpreventiva.Notificacion', $arraydatos);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
			foreach($rows AS $row){
				$entry = array(  
					'id_notif' => $row[0],
					'anno_notif' => $row[1],
					'codigo' => $row[2],
					'n_notif' => $row[3],
					't_notif' => $row[4],
					'f_notif' => $row[5],
					'codigo_area' => $row[6],
					'tipo_infraccion' => $row[7],
					'codigo_adicional' => $row[8],
					'infraccion' => $row[9],
					'base_cal' => $row[10],
					'factor' => $row[11],
					'monto' => $row[12],
					
				);
				$jsonData['rows'][] = $entry;
		}
		
		$this->view->data=json_encode($jsonData);
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
	
	public function notificacionAction() {
		
		$path = new Zend_Session_Namespace ( 'path' );
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos ();
		$fn = new Libreria_Pintar ();
		$ar = new Libreria_ArraysFunctions();
		
		$codigo = $this->_request->getParam ('codigo','');	
		$val[] = array('#codigo', $codigo, 'val');
		
		$codtipocontri = $this->_request->getParam ('codtipocontri','');
		$val[] = array('#tipocontri_multa', $codtipocontri, 'val');
		
		
		//$numero_multa = $this->_request->getParam ('numero_multa','');
			
		$anno_notif = $this->_request->getParam ('anno_notif','');
		$id_notif = $this->_request->getParam ('id_notif','');
		
		$f_impo = @date('d/m/Y');
		
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
		
		
		$bus_anno2="";
				for($i=1992;$i<=date('Y');$i++){
				$bus_anno2.="<option value='".$i."'";
					if($i==date('Y'))
					{
						$bus_anno2.=" selected ";
					}
				$bus_anno2.=">".$i."</option>";
				}
				$this->view->cb_anno2=$bus_anno2;
				
		
		$id_docu='';
		
		
		if(!empty ($id_notif)) {
			//---------------------
			//PARA LA OPCION EDITAR
			//---------------------
			
			//verificar el tipo de contribuyente
			$miarraydatos[]=array("@busc",4);
			$miarraydatos[]=array("@codigo",$codigo);
			@$mirowsContri = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $miarraydatos);
			$micodtipocontri = $mirowsContri[0][31];
					
			
			unset ( $arraydatos );
			$arraydatos [0] = array ("@busc", 5 );
			$arraydatos [1] = array ("@codigo", $codigo);
			$arraydatos [2] = array ("@id_notif", $id_notif);
			$arraydatos [3] = array ("@anno_notif", $anno_notif);
			$rowsMulta = $cn->ejec_store_procedura_sql ( 'Mpreventiva.Notificacion', $arraydatos );
						
			
			$n_notif = $rowsMulta [0] [3];
			$t_notif = $rowsMulta [0] [4];
			$codigo_autoridad_municipal=$rowsMulta [0] [5];
			
			$f_impo = $rowsMulta [0] [6];
			
			//$anno_area  		= $rowsMulta [0] [6];

			$this->view->anno_area=$anno_area;
			
			$f_notificacion = $rowsMulta [0] [7];
			$h_notificacion = $rowsMulta [0] [8];
			
			//$factor== $rowsMulta [0] [23];
			
			
			
			
			
			$preimpreso  = $rowsMulta [0] [1];
			$anno_multa  		= $rowsMulta [0] [2];
				//$numero_multa= $rowsMulta [0] [3];
			//$fecha_multa 		= $rowsMulta [0] [5];
			$anno_multa  		= substr($rowsMulta[0][5],6);
			
			$anno_area  		= $rowsMulta [0] [17]; 
			$this->view->anno_area=$anno_area;
			
			$codigo_area  		= $rowsMulta [0] [18];
			//$codigo_area  		= $rowsMulta [0] [7];
			$tipo_infraccion 	= $rowsMulta [0] [19];
			$codigo_infraccion 	= $rowsMulta [0] [20];
			$codigo_adicional 	= $rowsMulta [0] [21];

			
			$monto_multa 		= $rowsMulta [0] [24];
			$antecedente 		= $rowsMulta [0] [12];
			$numero_notificacion=$rowsMulta [0] [13];
			$estado       		= $rowsMulta [0] [14];
			$numero_recibo		= $rowsMulta[0] [15];
			$anno_multa  		= $rowsMulta[0] [16];
			//$cantidad    		= $rowsMulta [0] [18];
			$cantidad    		= 1 ;
			
			$base 		 		= $rowsMulta [0] [20];
			$factor 	 		= $rowsMulta [0] [23];//ERROR DETECTED
			$fecha_notificacion = $rowsMulta [0] [22];
			
			
			$glosa 				= $rowsMulta [0][25];  //// añadido Casimiro
			
			$glosa_notificacion			= $rowsMulta [0][14];
			
			//$val[] = array('#glosa', $glosa, 'val');
			
			
			
			$fecha_resolucion 	= @date('d/m/Y');
			
			$sustento			= $rowsMulta [0][12];
			$n_carta			= $rowsMulta [0][13];
			
			
			$e_resolucion		= $rowsMulta [0][9];
			$cod_via_local		= $rowsMulta [0][26];
			
			//*******************
			//direccion del local
			//*******************
			
			unset ( $miarray);
			$miarray [0] = array ("@busc", 6 );
			$miarray [1] = array ("@cod_via_local", $cod_via_local);
			$rowsvia = $cn->ejec_store_procedura_sql ( 'Mpreventiva.Notificacion', $miarray );
			
			$nom_via_local= $rowsvia[0][1];
			$val[] = array('#txtnomvia', $nom_via_local, 'val');
				
			$sector_local =$rowsvia[0][3];
			$val[] = array('#txtsector', $sector_local, 'val');
			
			$idvia_local =$rowsvia[0][0];
			$val[] = array('#txtidvia', $idvia_local, 'val');
			
			
			$num_local		= $rowsMulta [0][27];
			$manz_local		= $rowsMulta [0][28];
			$lot_local		= $rowsMulta [0][29];
			
			$val[] = array('#txtnumero', $num_local, 'val');
			$val[] = array('#txtmanzana', $manz_local, 'val');
			$val[] = array('#txtnlote', $lot_local, 'val');
			
			$int_local		= $rowsMulta [0][30];
			$val[] = array('#txtinterior', $int_local, 'val');
			
			$uso_local		= $rowsMulta [0][31];
			$act_local		= $rowsMulta [0][32];
			
			$giro_local		= $rowsMulta [0][33];
			$val[] = array('#giro', $giro_local, 'val');
			
			$est_lic		= $rowsMulta [0][34];
			
			if($est_lic=='1'){
			
			$cad[] = array("#chkLicencia","checked", true);
			
			$n_lic		= $rowsMulta [0][35];
			$val[] = array('#nlicencia', $n_lic, 'val');
			
				$anno_lic = $rowsMulta [0][36];
			
				$bus_anno1="";
				for($i=1992;$i<=date('Y');$i++){
				$bus_anno1.="<option value='".$i."'";
					if($i==$anno_lic)
					{
						$bus_anno1.=" selected ";
					}
				$bus_anno1.=">".$i."</option>";
				}
				$this->view->cb_anno=$bus_anno1;
			
			}
			
			
			
			$n_informe			= $rowsMulta [0][15];
			$val[] = array('#informe', $n_informe, 'val');
			
			$anno_informe		= $rowsMulta [0][16];
			
				$bus_anno2="";
				for($i=1992;$i<=date('Y');$i++){
				$bus_anno2.="<option value='".$i."'";
					if($i==$anno_informe)
					{
						$bus_anno2.=" selected ";
					}
				$bus_anno2.=">".$i."</option>";
				}
				$this->view->cb_anno2=$bus_anno2;
			
			
			
			
			if($t_notif=='A')
			$cad[] = array("#rbttnoti1","checked", true);
			else
			$cad[] = array("#rbttnoti2","checked", true);
		
			
			
			$val[] = array('#txaObservacion', $obs_notif, 'val');
			
			if($e_resolucion=='1'){
				$cad[] = array("#chkResoluciones","checked", true);
				$val[] = array('#txtSustento', $sustento, 'val');
				$val[] = array('#txtNCarta', $n_carta, 'val');
				$val[] = array('#txaObservacion', $glosa_notificacion, 'val');
			}
			
			if(count($cad)>0)
			$fn->AtributoComponente($cad);
			
			
			$fech_notificacion2	= $rowsMulta [0][10];
			
			$fech_carta			= $rowsMulta [0][11];
			
			
			$nom_testigo		= $rowsMulta [0][37];
			$val[] = array('#txttestigo', $nom_testigo, 'val');
			
			$id_document		= $rowsMulta [0][38];
			
			/*para el documento de identidad*/
		
			unset($parametrosdoc);
			$parametrosdoc[] = array('@msquery',1);
			$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametrosdoc);
			
			
			
			for ($i=0;$i<count($rowsDocumento);$i++){
				$cad=explode('/',$rowsDocumento[$i][0]);
				
				if(trim($cad[0])==$id_document)
				{
					$id_docu.='<option value="'.$rowsDocumento[$i][0].'" selected>'.$rowsDocumento[$i][1].'</option>';
				}
				else{
					$id_docu.='<option value="'.$rowsDocumento[$i][0].'" >'.$rowsDocumento[$i][1].'</option>';						
				}
       		}
		
		/*--------------fin---------------*/
			
			$n_docu		= $rowsMulta [0][39];
			$val[] = array('#txtndoc', $n_docu, 'val');
			
			$ref_test		= $rowsMulta [0][46];
			$val[] = array('#txtRef_Dom_test', $ref_test, 'val');
			
			$manz_test		= $rowsMulta [0][47];
			$val[] = array('#txtinfmanz', $manz_test, 'val');
			
			$lot_test		= $rowsMulta [0][48];
			$val[] = array('#txtinflote', $lot_test, 'val');
			
			$num_test		= $rowsMulta [0][49];
			$val[] = array('#txtinfnum', $num_test, 'val');
			
			
			
			//--------------------
			$id_dist		= $rowsMulta [0][40];
			
			$cod_via_test	= $rowsMulta [0][45];
			
			if($id_dist=="012"){
			
			unset ( $miarray);
			$miarray [0] = array ("@busc", 6 );
			$miarray [1] = array ("@cod_via_local", $cod_via_test);
			$rowsvia = $cn->ejec_store_procedura_sql ( 'Mpreventiva.Notificacion', $miarray );
			
			
			
			$campo1		= $rowsvia [0][2];
			$val[] = array('#txtZonacodigo', $campo1, 'val');
				
			$campo2		= $rowsvia [0][3];
			$val[] = array('#txtZona', $campo2, 'val');
				
			$campo3		= $rowsvia [0][4];
			$val[] = array('#txtUrbacodigo', $campo3, 'val');
			
			$campo4		= $rowsvia [0][5];
			$val[] = array('#txtUrbanizacion', $campo4, 'val');
			
			$campo5		= $rowsvia [0][0];
			$val[] = array('#txtViacodigo', $campo5, 'val');
			
			$campo6		= $rowsvia [0][1];
			$val[] = array('#txtViacontri', $campo6, 'val');
			
			}
			
			else{
			
				$tipourb		= $rowsMulta [0][41];
				$desc_urb			= $rowsMulta [0][42];
				$val[] = array('#txtDesUrb', $desc_urb, 'val');
				$tipovia		= $rowsMulta [0][43];
				$desc_via			= $rowsMulta [0][44];
				$val[] = array('#txtDesVia', $desc_via, 'val');
			
			}
			
			
			
			$val[] = array('#notificacion', $n_notif, 'val');
			
			
			$val[] = array('#fecha_multa', $f_impo, 'val');
			$val[] = array('#fecha_notificacion', $f_notificacion, 'val');
			$val[] = array('#txthora', $h_notificacion, 'val');
			
			$val[] = array('#txtFechResolucion', $fech_notificacion2, 'val');
			
			$val[] = array('#txtFechCarta', $fech_carta, 'val');
			
			
			$val[] = array('#txtannonotif', $anno_notif, 'val');
			$val[] = array('#txtnotificacion', $id_notif, 'val');
			
			$n_resolucion		= $rowsMulta [0][26];
			
		}else{ 			
			$preimpreso  = "";
			$anno_multa  = "";
			$numero_multa= "";				
			
			//---------------añadio manuel-------------------
			$fecha_resolucion = @date('d/m/Y');
			$val[] = array('#txtFechResolucion', $fecha_resolucion, 'val');
			//-----------------------------------------------
			$anno_multa  = substr($f_impo,6);	
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
			
			
			unset($parametros);
			$parametros[] = array('@msquery',1);
			$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametros);
			$id_docu='';		
			
	    	for ($i=0;$i<count($rowsDocumento);$i++){
	            	$id_docu.='<option value="'.$rowsDocumento[$i][0].'" >'.$rowsDocumento[$i][1].'</option>';
	        }


			
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
				
		
		$val[] = array('#preimpreso', $preimpreso, 'val');
		$val[] = array('#anno_multa', $anno_multa, 'val');	
		$val[] = array('#numeroMulta', $numero_multa, 'val');
		$val[] = array('#montoTotal', $monto_multa, 'val');
		$val[] = array('#fecha_multa', $f_impo, 'val');
		//---------------------añadio manuel----------------
		
		
		$val[] = array('#txtNResolucion', $n_resolucion, 'val');
		$val[] = array('#txaObservacion', $glosa_notificacion, 'val');
		//--------------------------------------------------
		
		unset ( $arraydatos );
		$arraydatosu [] = array ('@msquery', 3 );
		$arraydatosu [] = array ('@tipo_predi', 1 );
		$rows = $cn->ejec_store_procedura_sql ( 'rentas.sp_predio', $arraydatosu );
		$arRows = $ar->RegistrosCombo($rows,0,1);				
		$val[] = array('#cmbuso',$fn->ContenidoCombo($arRows,'[Seleccione]',$uso_local,''),'html');
		
		$val[] = array('#cmbactividad',$fn->ContenidoCombo($arRows,'[Seleccione]',$area,''),'html');
		
		// unset ( $arraydatos );
		// $arraydatosu [] = array ('@msquery', 3 );
		// $arraydatosu [] = array ('@tipo_predi', 1 );
		// $rows = $cn->ejec_store_procedura_sql ( 'rentas.sp_predio', $arraydatosu );
		// $arRows = $ar->RegistrosCombo($rows,0,1);				
		// $val[] = array('#cmbactividad',$fn->ContenidoCombo($arRows,'[Seleccione]',$area,''),'html');
		
		//----------------------------------------------------
		//COMBO DISTRITO
		
		unset($parametros);
		$parametros[] = array('@msquery',1);	
		$tipodistrito = $cn->ejec_store_procedura_sql('Contenedor.SP_TblDistrito', $parametros);
		$arCombodistrito = $ar->RegistrosCombo($tipodistrito,0,1);
		$val[] = array('#cmbDisContri',$fn->ContenidoCombo($arCombodistrito,'[Seleccione]',trim($id_dist)),'html');
		$evt[] = array('#cmbDisContri',"change","changeDisContri(this.value)");
		
		//ZONA
		
		unset($parametros);
		$parametros[] = array('@msquery',5);	
		$tipourbanizacion = $cn->ejec_store_procedura_sql('Rentas.SP_MCUrba', $parametros);
		$arTipourbanizacion = $ar->RegistrosCombo($tipourbanizacion,0,1);
		$val[] = array('#cmbTipUrb',$fn->ContenidoCombo($arTipourbanizacion,'[Seleccione]',trim($tipourb)),'html');
		
		//VIA
		
		unset($parametros);
		$parametros[] = array('@busc',7);	
		$tipvia = $cn->ejec_store_procedura_sql('Rentas.sp_MVias', $parametros);
		$arTipovia = $ar->RegistrosCombo($tipvia,0,1);
		$val[] = array('#cmbTipVia',$fn->ContenidoCombo($arTipovia,'[Seleccione]',trim($tipovia)),'html');
		
		
		//---------------------------------------------------
		$val[] = array('#cmb_doctestigo',$id_docu,'html');
		
		$val[] = array('#numero_notificacion', $numero_notificacion, 'val');
		$val[] = array('#antecedente', $antecedente, 'val');
		$val[] = array('#numero_recibo', $numero_recibo, 'val');
		$val[] = array('#cantidad', $cantidad, 'val');
		$val[] = array('#numeroMulta', $numero_multa, 'val');
		//$val[] = array('#fecha_notificacion', $fecha_notificacion, 'val');
		$val[] = array('#glosa', $glosa, 'val');
		
		
		$evt[] = array('#fecha_multa',"change","calcUIT(this.value);");
		$evt[] = array('#fecha_multa',"datepicker","");
		//----------------añadio manuel------------------
		$evt[] = array('#txtFechResolucion',"datepicker","");
		
		$evt[] = array('#txtFechCarta',"datepicker","");
		
		//----------------------------------------------------
		$evt[] = array('#fecha_notificacion',"datepicker","");
		
		$evt[] = array('#cantidad',"keypress","return validaTeclas(event,'sinceros');");
		
		$evt[] = array('#btnBusVia',"click","mostrarVias(1);");
		$evt[] = array('#btnBusqueda',"click","mostrarVias(2);");
		
		$evt [] = array ('#btnCloseForm', "click", "closePopup('#popnewmulta');" );
		$evt [] = array ('#btnSaveForm', "click", "goToFormulario('frmultedit')" );
		
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#contentTabsLeft',"tabs","");
		
		$evt[] = array('#cmb_doctestigo',"change","validaText($(this).val());");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		if(count($cad)>0)
			$fn->AtributoComponente($cad);
	}
	
	 public function busviasAction(){
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$anno = $this->_request->getParam('anno','');
		$flag = $this->_request->getParam('valor','');
		
		$val[] = array('#txtflag', $flag, 'val');
		
		$fn = new Libreria_Pintar();
				
		$evt[] = array('#btnBusquedacri',"click","buscarDatos()");
		$this->view->anno=$anno;
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
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
				print("<option value=''>[ ... ]</option>");
				foreach($rows AS $row)
					print("<option value='".utf8_encode($row[1])."'>".utf8_encode($row[0])."</option>");
			}
			else{
				print("<option value=''>[ Sin registros ]</option>");
			}
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
			
			// $val[] = array('#anno_multa', $anno, 'val');
			// $fn = new Libreria_Pintar();
			// $fn->PintarValor($val);
			
			
			if(count($rows))
				echo $rows[0][2];
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
			
			$partes = explode(':',$tipo_infraccion);
			
			$parametros [] = array ('@anno_area', $partes[0]);
			$parametros [] = array ('@codigo_area', $partes[1]);
			$parametros [] = array ('@tipo_infraccion', $partes[2]);
			$parametros [] = array ('@id_tipocontri', $tipocontri_multa);
			$rows = $cn->ejec_store_procedura_sql('stpMultas_Qry_Detalle_Tipo_Infracciones', $parametros);
			
			if(count($rows)){
				print("<option value=''>[ ... ]</option>");
				foreach($rows AS $row)
					print("<option value='".utf8_encode($row[1])."'>".utf8_encode($row[0])."</option>");
			}
			
			//echo $partes[0]."-".$partes[1]."-".$partes[2];
		}		
	}
	
	public function grabarnotificacionAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		
		$login = new Zend_Session_Namespace('login');
		$usuario = $login->user;
		
		if($this->getRequest()->isXmlHttpRequest()){
		
			$cn = new Model_DbDatos_Datos ();
			
			
	        //*************************
			//Mpreventiva.MNotificacion
			//*************************
			
			$codigo      = $this->_request->getPost ('codigo');
			$id_notif    = $this->_request->getPost ('txtnotificacion');
			
			$anno_area_f = $this->_request->getPost ('anno_area');

			$anno_notifica    = $this->_request->getPost ('txtannonotif');
			
			$n_notif    = $this->_request->getPost ('notificacion');
			$t_notif    = $this->_request->getPost ('rbttnoti');
			$codigo_autoridad_municipal = $this->_request->getPost ('codigo_autoridad_municipal');
			$fec_imposicion = $this->_request->getPost ('fecha_multa');
			//$fec_notif = $this->_request->getPost ('fecha_notificacion');
			$fec_notif = $this->_request->getPost ('fecha_multa');
			$h_notif = $this->_request->getPost ('txthora');
			$est_subsa = $this->_request->getPost ('chkResoluciones');
			$fec_subsa = $this->_request->getPost ('txtFechResolucion');
			$fec_carta = $this->_request->getPost ('txtFechCarta');
			$sustento = $this->_request->getPost ('txtSustento');
			$n_carta = $this->_request->getPost ('txtNCarta');
			$obs_subsa = $this->_request->getPost ('txaObservacion');
			$n_informe = $this->_request->getPost ('informe');
			$anno_informe = $this->_request->getPost ('cb_fechinf');
			
			//**************************
			//Mpreventiva.MNotificacion
			//**************************
			
			$codigo_area = $this->_request->getPost ('codigo_area');
			$tipo_infraccion  = $this->_request->getPost ('tipo_infraccion');
			$codigo_infraccion= $this->_request->getPost ('codigo_infraccion');
			
			$base     = $this->_request->getPost ('base');
			$factor   = $this->_request->getPost ('factor');
			$montoTotal = $this->_request->getPost ('montoTotal');
			
			$observacion = $this->_request->getPost ('glosa');
			
			//local
			$cod_via_local = $this->_request->getPost ('txtidvia');
			$num_local = $this->_request->getPost ('txtnumero');
			$manz_local = $this->_request->getPost ('txtmanzana');
			$lot_local = $this->_request->getPost ('txtnlote');
			$int_local = $this->_request->getPost ('txtinterior');
			$uso_local = $this->_request->getPost ('cmbuso');
			$act_local = $this->_request->getPost ('cmbactividad');
			$giro_local = $this->_request->getPost ('giro');
			$est_lic = $this->_request->getPost ('chkLicencia');
			$n_lic = $this->_request->getPost ('nlicencia');
			$per_lic = $this->_request->getPost ('cmbanolic');
			
			//testigo
			$nom_testigo = $this->_request->getPost ('txttestigo');
			$doc_testigo = $this->_request->getPost ('cmb_doctestigo');
			$ndoc_testigo = $this->_request->getPost ('txtndoc');
			$dist_testigo = $this->_request->getPost ('cmbDisContri');
			
			if($dist_testigo=="012"){
			$cod_via_testigo = $this->_request->getPost ('txtViacodigo');
			}
			else {
			$urb_testigo = $this->_request->getPost ('cmbTipUrb');
			$urb_desc = $this->_request->getPost ('txtDesUrb');
			$via_testigo = $this->_request->getPost ('cmbTipVia');
			$desc_via = $this->_request->getPost ('txtDesVia');
			}
			
			$ref_dirf = $this->_request->getPost ('txtRef_Dom_test');
			
			$manz_test = $this->_request->getPost ('txtinfmanz');
			$lot_testigo = $this->_request->getPost ('txtinflote');
			$num_testigo = $this->_request->getPost ('txtinfnum');
			
			$IP = $_SERVER['REMOTE_ADDR'];

			$array=explode(':',$codigo_infraccion);
			$array_area=explode('*',$codigo_area);
		
			//**************************************************
			//**************************************************
		
			if(!empty($id_notif)){
			
			//actualiza
			
			$arraydatos[] =array ("@busc",4);
			//MNotificacion
			$arraydatos[] =array ("@id_notif",$id_notif);
			//no olvidarse sel anno
			
			$arraydatos[] =array ("@anno_notif",$anno_notifica);
			
			$arraydatos[] =array ("@codigo",$codigo);
			$arraydatos[] =array ("@n_notif",$n_notif);
			$arraydatos[] =array ("@t_notif",$t_notif);
			$arraydatos[] =array ("@id_autoridad",$codigo_autoridad_municipal);
			$arraydatos[] =array ("@fec_imposicion",$fec_imposicion);
			$arraydatos[] =array ("@fec_notif",$fec_notif);
			$arraydatos[] =array ("@h_notif",$h_notif);

			
			// $arraydatos[] =array ("@est_subsa",$est_subsa);	

				// if($est_subsa=='1') 
				// {				
					// $arraydatos[] =array ("@fec_subsa",$fec_subsa);
					// $arraydatos[] =array ("@fec_carta",$fec_carta);
					// $arraydatos[] =array ("@sustento",$sustento);
					// $arraydatos[] =array ("@n_carta",$n_carta);
					// $arraydatos[] =array ("@obs_subsa",$obs_subsa);
				// }
				// else
				// {
					// $arraydatos[] =array ("@fec_subsa",'');
					// $arraydatos[] =array ("@fec_carta",'');
					// $arraydatos[] =array ("@sustento",'');
					// $arraydatos[] =array ("@n_carta",'');
					// $arraydatos[] =array ("@obs_subsa",'');
				// }
			

			
			$arraydatos[] =array ("@n_informe",$n_informe);
			$arraydatos[] =array ("@anno_informe",$anno_informe);
			
			//DNotificacion
			$arraydatos[] = array("@anno_area", $array[0] );
			//$arraydatos[] = array("@anno_area", $anno_area_f );
			$arraydatos[] = array("@codigo_area", $array_area[0] );
			$arraydatos[] = array("@tipo_infraccion", $array[2]);
			$arraydatos[] = array("@codigo_infraccion", $array[3]);
			$arraydatos[] = array("@codigo_adicional", $array[4] );
			$arraydatos[] = array("@base_cal", $base );
			$arraydatos[] = array("@factor", $factor );
			$arraydatos[] = array("@monto", $montoTotal );
			
			$arraydatos[] = array("@observacion", $observacion );
			///local
			$arraydatos[] = array("@cod_via_local", $cod_via_local );
			$arraydatos[] = array("@num_local", $num_local );
			$arraydatos[] = array("@manz_local", $manz_local );
			$arraydatos[] = array("@lot_local", $lot_local );
			$arraydatos[] = array("@int_local", $int_local );
			$arraydatos[] = array("@uso_local", $uso_local );
			$arraydatos[] = array("@act_local", $act_local );
			$arraydatos[] = array("@giro_local", $giro_local );
			$arraydatos[] = array("@est_lic", $est_lic );
			
			if($est_lic=='1')
			{
				$arraydatos[] = array("@n_lic", $n_lic );
				$arraydatos[] = array("@per_lic", $per_lic );
			}
			
			else
			{
				$arraydatos[] = array("@n_lic",'');
				$arraydatos[] = array("@per_lic",'');
			}
			
			//testigo
			$arraydatos[] = array("@nom_testigo", $nom_testigo );
			$arraydatos[] = array("@doc_testigo", $doc_testigo );
			$arraydatos[] = array("@ndoc_testigo", $ndoc_testigo );
			$arraydatos[] = array("@dist_testigo", $dist_testigo );
			$arraydatos[] = array("@urb_testigo", $urb_testigo );
			$arraydatos[] = array("@urb_desc", $urb_desc );
			$arraydatos[] = array("@via_testigo", $via_testigo );
			$arraydatos[] = array("@desc_via", $desc_via );
			$arraydatos[] = array("@cod_via_testigo", $cod_via_testigo );
			$arraydatos[] = array("@ref_dirf", $ref_dirf );
			$arraydatos[] = array("@manz_test", $manz_test );
			$arraydatos[] = array("@lot_testigo", $lot_testigo );
			$arraydatos[] = array("@num_testigo", $num_testigo );
			
			$arraydatos[] = array('@usuario_act',$usuario);
			$arraydatos[] = array('@estacion_act',$IP);
			
			
			
			@$rows = $cn->ejec_store_procedura_sql ( 'Mpreventiva.Notificacion', $arraydatos );
			
			}
			//***********************************************
			//***********************************************
			
			else{
			//registra
			
			$arraydatos[] =array ("@busc",3);
			//MNotificacion
			$arraydatos[] =array ("@id_notif",$id_notif);
			//no olvidarse sel anno
			$arraydatos[] =array ("@codigo",$codigo);
			$arraydatos[] =array ("@n_notif",$n_notif);
			$arraydatos[] =array ("@t_notif",$t_notif);
			$arraydatos[] =array ("@id_autoridad",$codigo_autoridad_municipal);
			$arraydatos[] =array ("@fec_imposicion",$fec_imposicion);
			$arraydatos[] =array ("@fec_notif",$fec_notif);
			$arraydatos[] =array ("@h_notif",$h_notif);
			
			// $arraydatos[] =array ("@est_subsa",$est_subsa);		
			
			    // if($est_subsa=='1') 
				// {				
					// $arraydatos[] =array ("@fec_subsa",$fec_subsa);
					// $arraydatos[] =array ("@fec_carta",$fec_carta);
					// $arraydatos[] =array ("@sustento",$sustento);
					// $arraydatos[] =array ("@n_carta",$n_carta);
					// $arraydatos[] =array ("@obs_subsa",$obs_subsa);
				// }
			
			
			$arraydatos[] =array ("@n_informe",$n_informe);
			$arraydatos[] =array ("@anno_informe",$anno_informe);
			
			//DNotificacion
			$arraydatos[] = array("@anno_area", $array[0] );
			$arraydatos[] = array("@codigo_area", $array_area[0] );
			$arraydatos[] = array("@tipo_infraccion", $array[2]);
			$arraydatos[] = array("@codigo_infraccion", $array[3]);
			$arraydatos[] = array("@codigo_adicional", $array[4] );
			$arraydatos[] = array("@base_cal", $base );
			$arraydatos[] = array("@factor", $factor );
			$arraydatos[] = array("@monto", $montoTotal );
			
			$arraydatos[] = array("@observacion", $observacion );
			///local
			$arraydatos[] = array("@cod_via_local", $cod_via_local );
			$arraydatos[] = array("@num_local", $num_local );
			$arraydatos[] = array("@manz_local", $manz_local );
			$arraydatos[] = array("@lot_local", $lot_local );
			$arraydatos[] = array("@int_local", $int_local );
			$arraydatos[] = array("@uso_local", $uso_local );
			$arraydatos[] = array("@act_local", $act_local );
			$arraydatos[] = array("@giro_local", $giro_local );
			$arraydatos[] = array("@est_lic", $est_lic );
			
			if($est_lic=='1')
			{
				$arraydatos[] = array("@n_lic", $n_lic );
				$arraydatos[] = array("@per_lic", $per_lic );
			}
			
			//testigo
			$arraydatos[] = array("@nom_testigo", $nom_testigo );
			$arraydatos[] = array("@doc_testigo", $doc_testigo );
			$arraydatos[] = array("@ndoc_testigo", $ndoc_testigo );
			$arraydatos[] = array("@dist_testigo", $dist_testigo );
			$arraydatos[] = array("@urb_testigo", $urb_testigo );
			$arraydatos[] = array("@urb_desc", $urb_desc );
			$arraydatos[] = array("@via_testigo", $via_testigo );
			$arraydatos[] = array("@desc_via", $desc_via );
			$arraydatos[] = array("@cod_via_testigo", $cod_via_testigo );
			$arraydatos[] = array("@ref_dirf", $ref_dirf );
			$arraydatos[] = array("@manz_test", $manz_test );
			$arraydatos[] = array("@lot_testigo", $lot_testigo );
			$arraydatos[] = array("@num_testigo", $num_testigo );
			
			$arraydatos[] = array('@usuario',$usuario);
			$arraydatos[] = array('@estacion',$IP);
			
			@$rows = $cn->ejec_store_procedura_sql ('Mpreventiva.Notificacion', $arraydatos );	
			
			}
			echo 'Verifique si los datos fueron guardados correctamente!!!!';
		
		}
	
	}
	
	public function pruebaAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;

		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();


		//Para los tabs
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#contentTabsLeft',"tabs","");
		$evt[] = array('#txtFechaPre',"datepicker","");
        $evt[] = array('#txtFchViDel',"datepicker","");
        $evt[] = array('#txtFchViAl',"datepicker","");
		$evt[] = array('#txtArancel',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtNro',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtPhone',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtExp',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtNroLi',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtNroDocRe',"keypress","return validaTeclas(event,'number');");
        //$mask[] = array("txtAreaL");
        //$mask[] = array("txtAreaAl");
        $mask[] = array("txtAreaTo");
        $evt[] = array('#cmbTipAnun',"change","getTipoAnuncio()");
        //$evt[] = array('#chkOtro',"checked","getTipoAutorizacion()");
        $mask[] = array("txtlargo");
        $mask[] = array("txtancho");
        $mask[] = array("txtalto");
        $mask[] = array("txtarea");
        $evt[] = array('#txtNroEs',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtDptoEs',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtcaras',"keypress","return validaTeclas(event,'number');");


		$evt[] = array('#btnBusPerSol',"click","showPopup('mantpers/buscar','#popBusPersSol','700','400','Buscador de Personas');");
		$evt[] = array('#btnPredio',"click","showPopup('mantpred/buscar','#popBusPre','700','400','Buscador de Predios');");
		$evt[] = array('#btnAddDetPago',"click","maxRowPagos();");

		$evt[] = array('#btnSaveSol',"click","goToFormulario('frmlicendecjurada');");
		$evt[] = array('#btnSalirSol',"click","closePopup('#popanundecjurada ');");


		//Para los tabs
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#contentTabsLeft',"tabs","");



		unset($parametros);
		$parametros[] = array('@msquery',12);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipLicencia',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoLice)),'html');

		unset($parametros);
		$parametros[] = array('@msquery',2);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipPredio2',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoDoc)),'html');

		unset($parametros);
		$parametros[] = array('@msquery',3);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipInscrip',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoInscrip)),'html');

		unset($parametros);
		$parametros[] = array('@msquery',4);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipEmpresa',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoEmpr)),'html');

		unset($parametros);
		$parametros[] = array('@msquery',5);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipSector',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoSector)),'html');


		unset($parametros);
		$parametros[] = array('@msquery',2);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipPredio6',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoDoc)),'html');


		unset($parametros);
		$parametros[] = array('@msquery',6);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipPredio7',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoZonif)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',7);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipAnun',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipo)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',8);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipIlum',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipo)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',9);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipAuto',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipo)),'html');
/*
        unset($parametros);
        $parametros[] = array('@msquery',10);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbCptoLicencia',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim(idCpto)),'html');
*/
      /*
        $letras  = array("a","b","c","d","e","f","g","h","i","j","k","l","ll","m","n","ñ","o","p","q","r","s","t","u","v","w","x","y","z");

        $anuncio = '<table><tr><td valign="top"><table>';
        $id = 0;
        foreach($documentos as $documentos){
            $id++;

            $anuncio .= '<tr>';
            $anuncio .= '<td><table style="background:#f2f2f2;" ><tr style="font-size:9px"><td height="50">'.utf8_decode($letras[$id -1 ]).'.</td><td width="110">'.$documentos[1].'</td><td style="padding-right:5px;" bgcolor="#999"><input type="checkbox" id="TipoAnun" name="TipoAnun" value="'.$documentos[0].'" ></td></tr></table></td>';
            $anuncio .= '</tr>';

            if ($id % 5 == 0)
            $anuncio .= '</table></td><td valign="top"><table>';

        }
        $anuncio .= '</table></td></tr></table>';

        $val[] = array('#anuncio',$anuncio,'html');
*/

		$idSolLice = $this->_request->getParam('idSolLice','');
		$this->view->idSolLice=$idSolLice;


        $cod_sol = $this->_request->getParam('cod_sol','');
        $this->view->cod_sol=$cod_sol;

		$readonly = $this->_request->getParam('readonly','');
		$this->view->readonly=$readonly;

		$accion = $this->_request->getParam('accion','');
		$this->view->accion=$accion;

		unset($parametros);
		if(strlen($idSolLice)>0)
		{
			$parametros[] = array('@msql',3);
			$parametros[] = array('@idSolLice',$idSolLice);
            $parametros[] = array('@cod_sol',$cod_sol);
			$rowLicencia = $cn->ejec_store_procedura_sql('wbSpPredio', $parametros);

            $idSol = $rowLicencia[0][1];
            $codpro = $rowLicencia[0][2];
			$nompro = $rowLicencia[0][3];

            $tip_lice = $rowLicencia[0][5];
            $tip_doc = $rowLicencia[0][4];
            $nro_doc = $rowLicencia[0][7];
            $direccion = $rowLicencia[0][12];
            $deno = $rowLicencia[0][11];
            $nomest = $rowLicencia[0][10];
			$direpro = $rowLicencia[0][8];
			$fecha = $rowLicencia[0][31];
            $codpre = $rowLicencia[0][7];
            $numEs = $rowLicencia[0][13];
            $depEs = $rowLicencia[0][14];
            $StEs = $rowLicencia[0][15];
            $MaEs = $rowLicencia[0][16];
            $LoEs = $rowLicencia[0][17];
            $PuEs = $rowLicencia[0][18];
            $areal = $rowLicencia[0][20];
            $areaal = $rowLicencia[0][21];
            $areatotal = $rowLicencia[0][22];
            $nroh = $rowLicencia[0][23];
            $nrom = $rowLicencia[0][24];
            $tiposector = $rowLicencia[0][25];
            $tipoinscrip = $rowLicencia[0][26];
            $tipoempre = $rowLicencia[0][27];
            $idpredio = $rowLicencia[0][9];
            $observa = $rowLicencia[0][28];


		}

        $val[] = array('#txtNroLi',$idSol,'val');
        $val[] = array('#txtCodPer',$codpro,'val');
        $val[] = array('#txtNomPer',$nompro,'val');
        $val[] = array('#txtTipDoc',$tip_doc,'val');
        $val[] = array('#txtNroDoc',$nro_doc,'val');
        $val[] = array('#cmbTipLicencia',$tip_lice,'val');
        $val[] = array('#txtNomEs',$nomest,'val');
        $val[] = array('#txtUbiEs',$direccion,'val');
        $val[] = array('#txtUrb',$deno,'val');
		$val[] = array('#txtDirPer',$direpro,'val');
		$val[] = array('#txtFechaPre',$fecha,'val');
        $val[] = array('#txtCodPre',$codpre,'val');
        $val[] = array('#txtpredio',$idpredio,'val');
        $val[] = array('#txtNroEs',$numEs,'val');
        $val[] = array('#txtDptoEs',$depEs,'val');
        $val[] = array('#txtInEs',$StEs,'val');
        $val[] = array('#txtMzEs',$MaEs,'val');
        $val[] = array('#txtLtEs',$LoEs,'val');
        $val[] = array('#txtModEs',$PuEs,'val');
        $val[] = array('#txtAreaL',$areal,'val');
        $val[] = array('#txtAreaAl',$areaal,'val');
        $val[] = array('#txtAreaTo',$areatotal,'val');
        $val[] = array('#txtnh',$nroh,'val');
        $val[] = array('#txtnm',$nrom,'val');
        $val[] = array('#cmbTipSector',$tiposector,'val');
        $val[] = array('#cmbTipInscrip',$tipoinscrip,'val');
        $val[] = array('#cmbTipEmpresa',$tipoempre,'val');
        $val[] = array('#txtobs',$observa,'val');


		$evt[] = array('#btnCloseSol',"click","closePopup('#poplicendecjurada');");

		$fn->CampoDinero($mask);
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);


  }
	
	
	
	
	
	
	
	
	
    
}



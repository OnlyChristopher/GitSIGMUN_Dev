<?php

class FiscalizacionpredioController extends Zend_Controller_Action{

    public function init(){
        /* Initialize action controller here */			
    }

    public function indexAction(){
	
		$cn = new Model_DbDatos_Datos();
        $ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
        unset($parametros);
        $parametros[] = array('@buscar', 1);
        $combopredio = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Buscar', $parametros);
        $arpredio = $ar->RegistrosCombo($combopredio, 0, 1);
        
		$val[] = array('#cbPredio', $fn->ContenidoCombo($arpredio, '[Seleccione]', ''), 'html');
		$val[] = array('#cbPredio', '1', 'val');
		$evt[] = array('#btnCerrarPredio', "click", "closePopup('#popcrearpredio');");
/*
		$txtidx = $this->_request->getParam('txtidx','');	
	
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnNuevoHR',"click","NuevoHResumen();");
		$val[] = array('#txtidrq',$txtidx,"val");
*/  
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
    public function crearpredioAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){

			$dato_rq 	= trim($this->_request->getPost('txtidrq01'));
			$tipo_pu 	= trim($this->_request->getPost('cbPredio'));
			$anio_pu 	= trim($this->_request->getPost('txtAnio'));

			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',1);
			$parametros[] = array('@idrq',$dato_rq);
			$parametros[] = array('@tipo_pred',$tipo_pu);
			$parametros[] = array('@anio',$anio_pu);
			
			$rowspu = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Mante', $parametros);
			/*
			$rows = array('datos'=>array());
			foreach($rowsrq as $row){
				$entry = array(
						'idx'=>$row[0],
						'nros_rq'=>$row[1],
						'anio_rq'=>utf8_encode($row[2]),
						'estado'=>utf8_encode($row[3])
				);
				$rows['datos'][] = $entry;
			}
			echo json_encode($rows);
			*/
		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
		
	}
	
	public function gridpredioAction(){
		
    	$cn = new Model_DbDatos_Datos();
		$login = new Zend_Session_Namespace('login');
		
		$txtidre = $this->_request->getParam('txtidre','');
		//$txtaniohr = $this->_request->getParam('txtaniohr','');

    	$parametros[] = array('@buscar',2);
		$parametros[] = array('@idre',$txtidre);
		    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Buscar', $parametros);
				
    	$jsonData = array('rows'=>array());
    	foreach($rowRuta as $row){
			$entry = array(
					'idPu'=>$row[0],
					'tipopu'=>$row[3],
					'codigopu'=>utf8_encode($row[4]),
					'ubicacion'=>utf8_encode($row[5]),
					'autovaluo'=>$row[6],
					'porcen'=>utf8_encode($row[7]),
					'totalautovaluo'=>utf8_encode($row[8]),
					'tot_autoavaluo'=>utf8_encode($row[10]),
					'base_imponible'=>utf8_encode($row[11]),
					'impanual'=>utf8_encode($row[12]),		
			);
			$jsonData['rows'][] = $entry;
		}
		$this->view->data = json_encode($jsonData);	
	
    }
	
	public function modificarpredioAction(){
	
		$cn = new Model_DbDatos_Datos();
        $ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$txtidpu = $this->_request->getParam('idpu','0');
		
		$val[] = array('#txtidpu', $txtidpu, 'val');
		
        unset($parametros);
        $parametros[] = array('@buscar', 3);
        $combouso = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Buscar', $parametros);
        $aruso = $ar->RegistrosCombo($combouso, 0, 1);
        
		$val[] = array('#cbUsoPredio', $fn->ContenidoCombo($aruso, '[Seleccione]', ''), 'html');
		
        unset($parametros);
        $parametros[] = array('@buscar', 4);
        $combotipo = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Buscar', $parametros);
        $artipo = $ar->RegistrosCombo($combotipo, 0, 1);
        
		$val[] = array('#cbTipoPredio', $fn->ContenidoCombo($artipo, '[Seleccione]', ''), 'html');
		
        unset($parametros);
        $parametros[] = array('@buscar', 5);
        $comboestado = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Buscar', $parametros);
        $arestado = $ar->RegistrosCombo($comboestado, 0, 1);
        
		$val[] = array('#cbEstadoPredio', $fn->ContenidoCombo($arestado, '[Seleccione]', ''), 'html');

        unset($parametros);
        $parametros[] = array('@buscar', 6);
        $combopropiedad = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Buscar', $parametros);
        $arpropiedad = $ar->RegistrosCombo($combopropiedad, 0, 1);
        
		$val[] = array('#cbPropiedadPredio', $fn->ContenidoCombo($arpropiedad, '[Seleccione]', ''), 'html');
		
        unset($parametros);
        $parametros[] = array('@buscar', 7);
        $comboinspeccion = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Buscar', $parametros);
        $arinspeccion = $ar->RegistrosCombo($comboinspeccion, 0, 1);
        
		$val[] = array('#cbInspeccion', $fn->ContenidoCombo($arinspeccion, '[Seleccione]', ''), 'html');
		
        unset($parametros);
        $parametros[] = array('@buscar', 8);
        $comboubipar = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Buscar', $parametros);
        $arubipar = $ar->RegistrosCombo($comboubipar, 0, 1);
        
		$val[] = array('#cbUbipar', $fn->ContenidoCombo($arubipar, '[Seleccione]', ''), 'html');

		/* Consulta Predio*/
		
        unset($parametros);
		
        $parametros[] = array('@buscar', 3);
		$parametros[] = array('@idPk', $txtidpu);
        $rowsPredios = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Mante', $parametros);
        //print_r($rowsPredios);
		
		$val[] = array('#txtcodigopu', $rowsPredios[0][3], 'val');
		$val[] = array('#txtperiodopu', $rowsPredios[0][2], 'val');
		$val[] = array('#txtcodigovia', $rowsPredios[0][7], 'val');
		

		$val[] = array('#txtmzapu', $rowsPredios[0][8], 'val');
		$val[] = array('#txtltepu', $rowsPredios[0][9], 'val');
		$val[] = array('#txtsltepu', $rowsPredios[0][10], 'val');
		$val[] = array('#txtnropu', $rowsPredios[0][11], 'val');
		$val[] = array('#txtdptopu', $rowsPredios[0][12], 'val');		

		$val[] = array('#cbPropiedadPredio', trim($rowsPredios[0][13]), 'val');		
		$val[] = array('#cbUsoPredio', trim($rowsPredios[0][14]), 'val');
		$val[] = array('#cbEstadoPredio', trim($rowsPredios[0][15]), 'val');
		$val[] = array('#cbTipoPredio', trim($rowsPredios[0][16]), 'val');
		
		//$val[] = array('#txtfcompra', trim($rowsPredios[0][17]), 'val');
		$val[] = array('#txtfcompra', '2015-05-31', 'val');
		$val[] = array('#txtfventa', trim($rowsPredios[0][18]), 'val');
		
		$val[] = array('#txtterreno', number_format (trim($rowsPredios[0][21]), '2', '.', ',' ), 'val');
		$val[] = array('#txtarcomun', number_format (trim($rowsPredios[0][22]), '2', '.', ',' ), 'val');
		$val[] = array('#txtarancel', number_format (trim($rowsPredios[0][23]), '2', '.', ',' ), 'val');
		$val[] = array('#txtfrontis', trim($rowsPredios[0][20]), 'val');
		$val[] = array('#cbUbipar', trim($rowsPredios[0][19]), 'val');
		$val[] = array('#cbAfecta', trim($rowsPredios[0][30]), 'val');
		
		$val[] = array('#cbInspeccion', trim($rowsPredios[0][35]), 'val');
		$val[] = array('#txtnroacta', trim($rowsPredios[0][33]), 'val');
		$val[] = array('#txtanioacta', trim($rowsPredios[0][34]), 'val');
		$val[] = array('#txtsiglaacta', trim($rowsPredios[0][30]), 'val');
		$val[] = array('#cbTecnico', trim($rowsPredios[0][32]), 'val');
		$val[] = array('#txtfacta', trim($rowsPredios[0][36]), 'val');
		
		$val[] = array('#txtobservapu', trim($rowsPredios[0][31]), 'val');
		
		$val[] = array('#txttconstruccion', number_format ( trim($rowsPredios[0][25]), '2', '.', ',' ), 'val');
		$val[] = array('#txttterreno', number_format ( trim($rowsPredios[0][24]), '2', '.', ',' ), 'val');
		$val[] = array('#txttinstalacion', number_format ( trim($rowsPredios[0][26]), '2', '.', ',' ), 'val');
		$val[] = array('#txttautovaluo', number_format ( trim($rowsPredios[0][27]), '2', '.', ',' ), 'val');
		$val[] = array('#txttpropiedad', number_format ( trim($rowsPredios[0][28]), '2', '.', ',' ), 'val');
		$val[] = array('#txttavaluo', number_format ($rowsPredios[0][29], '2', '.', ',' ), 'val');
		
		$val[] = array('#txtdireccpu', trim($rowsPredios[0][38]), 'val');
		$val[] = array('#txtsiglaacta', trim($rowsPredios[0][39]), 'val');
		
		$evt[] = array('#btnCerrarPredio', "click", "closePopup('#popmodificarpredio');");
/*
		$txtidx = $this->_request->getParam('txtidx','');	
	
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnNuevoHR',"click","NuevoHResumen();");
		$val[] = array('#txtidrq',$txtidx,"val");
*/  
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
	public function grabarpredioAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){
		
			$txtidpu = trim($this->_request->getPost('txtidpu'));
			$txtcodigopu = trim($this->_request->getPost('txtcodigopu'));
			$txtperiodopu = $this->_request->getPost('txtperiodopu');
			$txtcodigovia = $this->_request->getPost('txtcodigovia');	
			$txtmzapu = $this->_request->getPost('txtmzapu');
			$txtltepu = $this->_request->getPost('txtltepu');
			$txtsltepu = $this->_request->getPost('txtsltepu');
			$txtnropu = $this->_request->getPost('txtnropu');
			$txtdptopu = $this->_request->getPost('txtdptopu');
			$cbPropiedadPredio = $this->_request->getPost('cbPropiedadPredio');
			$cbUsoPredio = $this->_request->getPost('cbUsoPredio');
			$cbEstadoPredio = $this->_request->getPost('cbEstadoPredio');
			$cbTipoPredio = $this->_request->getPost('cbTipoPredio');
			$txtfcompra = $this->_request->getPost('txtfcompra');
			$txtfventa = $this->_request->getPost('txtfventa');
			$txtterreno = str_replace(',','',$this->_request->getPost('txtterreno'));
			$txtarcomun = str_replace(',','',$this->_request->getPost('txtarcomun'));
			$txtarancel = str_replace(',','',$this->_request->getPost('txtarancel'));
			$txtfrontis = str_replace(',','',$this->_request->getPost('txtfrontis'));
			$cbUbipar = $this->_request->getPost('cbUbipar');
			$cbAfecta = $this->_request->getPost('cbAfecta');
			$cbInspeccion = $this->_request->getPost('cbInspeccion');
			$txtnroacta = $this->_request->getPost('txtnroacta');
			$txtanioacta = $this->_request->getPost('txtanioacta');
			$txtsiglaacta = $this->_request->getPost('txtsiglaacta');
			$cbTecnico = $this->_request->getPost('cbTecnico');
			$txtfacta = $this->_request->getPost('txtfacta');
			$txtobservapu = $this->_request->getPost('txtobservapu');
			$txttconstruccion = str_replace(',','',$this->_request->getPost('txttconstruccion'));
			$txttterreno = str_replace(',','',$this->_request->getPost('txttterreno'));
			$txttinstalacion = str_replace(',','',$this->_request->getPost('txttinstalacion'));
			$txttautovaluo = str_replace(',','',$this->_request->getPost('txttautovaluo'));
			$txttpropiedad = $this->_request->getPost('txttpropiedad');
			$txttavaluo = str_replace(',','',$this->_request->getPost('txttavaluo'));
			$txtsiglaacta = str_replace(',','',$this->_request->getPost('txtsiglaacta'));
			
			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',2);
			$parametros[] = array('@idPk',$txtidpu);
			$parametros[] = array('@cod_via',$txtcodigovia);
			$parametros[] = array('@num_manz',$txtmzapu);
			$parametros[] = array('@num_lote',$txtltepu);
			$parametros[] = array('@sub_lote',$txtsltepu);
			$parametros[] = array('@num_call',$txtnropu);
			$parametros[] = array('@num_depa',$txtdptopu);
			$parametros[] = array('@idcondi',$cbPropiedadPredio);
			$parametros[] = array('@iduso',$cbUsoPredio);
			$parametros[] = array('@idestado',$cbEstadoPredio);
			$parametros[] = array('@idtipo',$cbTipoPredio);
			$parametros[] = array('@fec_compra',$txtfcompra);
			$parametros[] = array('@fec_venta',$txtfventa);
			$parametros[] = array('@idubipar',$cbUbipar);
			$parametros[] = array('@frontis',$txtfrontis);
			$parametros[] = array('@area_terreno',$txtterreno);
			$parametros[] = array('@area_comun',$txtarcomun);
			$parametros[] = array('@arancel',$txtarancel);
			$parametros[] = array('@val_total_terreno',$txttterreno);
			$parametros[] = array('@val_total_constru',$txttconstruccion);
			$parametros[] = array('@val_total_instala',$txttinstalacion);
			$parametros[] = array('@val_autovaluo',$txttautovaluo);
			$parametros[] = array('@por_propiedad',$txttpropiedad);
			$parametros[] = array('@total_autovaluo',$txttavaluo);
			$parametros[] = array('@predio_afec',$cbAfecta);
			$parametros[] = array('@observacion',$txtobservapu);
			$parametros[] = array('@idtecnico',$cbTecnico);
			$parametros[] = array('@acta_nume',$txtnroacta);
			$parametros[] = array('@acta_anio',$txtanioacta);
			$parametros[] = array('@acta_base',$cbInspeccion);
			$parametros[] = array('@acta_sigla',$txtsiglaacta);
			$parametros[] = array('@fecha_fisc',$txtfacta);
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Mante', $parametros);
			/*
			$rows = array('datos'=>array());
			foreach($rowsrq as $row){
				$entry = array(
						'idx'=>$row[0],
						'nros_rq'=>$row[1],
						'anio_rq'=>utf8_encode($row[2]),
						'estado'=>utf8_encode($row[3])
				);
				$rows['datos'][] = $entry;
			}
			echo json_encode($rows);
			*/
		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
	}
	
    public function eliminarpredioAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){

			$idpu 	= trim($this->_request->getPost('idpu'));
			$idre 	= trim($this->_request->getPost('idre'));

			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',4);
			$parametros[] = array('@idPk',$idpu);
			$parametros[] = array('@idre',$idre);
			
			$rowspu = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Mante', $parametros);

		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
		
	}
	
	public function gridpisosAction(){
		
    	$cn = new Model_DbDatos_Datos();
		$login = new Zend_Session_Namespace('login');
		
		$txtidpu = $this->_request->getParam('txtidpu','');

    	$parametros[] = array('@buscar',9);
		$parametros[] = array('@idPk',$txtidpu);
		    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPredio_Buscar', $parametros);
				
    	$jsonData = array('rows'=>array());
    	foreach($rowRuta as $row){
			$entry = array(
					'idxxx'=>$row[0],
					'nivel'=>$row[2],
					'cx'=>utf8_encode($row[5]),
					'mx'=>utf8_encode($row[6]),
					'ex'=>utf8_encode($row[7]),
					'anio'=>utf8_encode($row[3]),
					'u1'=>utf8_encode($row[8]),
					'u2'=>utf8_encode($row[9]),
					'u3'=>utf8_encode($row[10]),
					'u4'=>utf8_encode($row[11]),
					'u5'=>utf8_encode($row[12]),
					'u6'=>utf8_encode($row[13]),
					'u7'=>utf8_encode($row[14]),
					'area_const'=>utf8_encode($row[20]),
					'valo_unita'=>utf8_encode($row[15]),
					'valo_areas'=>utf8_encode($row[21]),
					'area_comun'=>utf8_encode($row[23]),
					'valo_total'=>utf8_encode($row[24]),
			);
			$jsonData['rows'][] = $entry;
		}
		$this->view->data = json_encode($jsonData);	
	
    }
	public function crearpisoAction(){
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){
		
			$txtidpu = trim($this->_request->getPost('txtidpu'));
			$txtperiodopu = $this->_request->getPost('txtperiodopu');
			
			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',1);
			$parametros[] = array('@idpredio',$txtidpu);
			$parametros[] = array('@anio',$txtperiodopu);
			$parametros[] = array('@anio_cons',$txtperiodopu);
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);
		}
		
	}
	
	public function modificarpisoAction(){
		$cn = new Model_DbDatos_Datos();
        $ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();

		$txtidpi = $this->_request->getParam('idpi','0');
		
		$val[] = array('#txtidpi', $txtidpi, 'val');
		$evt[] = array('#btnCerrarPiso', "click", "closePopup('#popmodificarpiso');");
		
        unset($parametros);
        $parametros[] = array('@buscar', 4);
        $comboClasificacion = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);
        $arClasificacion = $ar->RegistrosCombo($comboClasificacion, 0, 1);
        
		$val[] = array('#cbClasificacion', $fn->ContenidoCombo($arClasificacion, '[Seleccione]', ''), 'html');

        unset($parametros);
        $parametros[] = array('@buscar', 5);
        $comboMaterial = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);
        $arMaterial = $ar->RegistrosCombo($comboMaterial, 0, 1);
        
		$val[] = array('#cbMaterial', $fn->ContenidoCombo($arMaterial, '[Seleccione]', ''), 'html');
		
        unset($parametros);
        $parametros[] = array('@buscar', 6);
        $comboEstado = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);
        $arEstado = $ar->RegistrosCombo($comboEstado, 0, 1);
        
		$val[] = array('#cbEstado', $fn->ContenidoCombo($arEstado, '[Seleccione]', ''), 'html');
		
		/* Consulta Piso */
		
        unset($parametros);
		
        $parametros[] = array('@buscar', 3);
		$parametros[] = array('@idPk', $txtidpi);
        $rowsPisos = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);
        //print_r($rowsPredios);
		
		$val[] = array('#txtpiso', $rowsPisos[0][3], 'val');
		$val[] = array('#txtantigueda', $rowsPisos[0][6], 'val');
		$val[] = array('#txtperiodopi', $rowsPisos[0][5], 'val');
		$val[] = array('#txtuni01', $rowsPisos[0][10], 'val');
		$val[] = array('#txtuni02', $rowsPisos[0][11], 'val');
		$val[] = array('#txtuni03', $rowsPisos[0][12], 'val');
		$val[] = array('#txtuni04', $rowsPisos[0][13], 'val');
		$val[] = array('#txtuni05', $rowsPisos[0][14], 'val');
		$val[] = array('#txtuni06', $rowsPisos[0][15], 'val');
		$val[] = array('#txtuni07', $rowsPisos[0][16], 'val');
		$val[] = array('#cbClasificacion', $rowsPisos[0][7], 'val');
		$val[] = array('#cbMaterial', $rowsPisos[0][8], 'val');
		$val[] = array('#cbEstado', $rowsPisos[0][9], 'val');
		$val[] = array('#txtareacons', $rowsPisos[0][22], 'val');
		$val[] = array('#txtareacomun', $rowsPisos[0][25], 'val');
		
		$val[] = array('#txtval01', number_format ( trim($rowsPisos[0][17]), '2', '.', ',' ), 'val');
		$val[] = array('#txtval02', number_format ( trim($rowsPisos[0][18]), '2', '.', ',' ), 'val');
		$val[] = array('#txtval03', number_format ( trim($rowsPisos[0][19]), '2', '.', ',' ), 'val');
		$val[] = array('#txtval04', number_format ( trim($rowsPisos[0][21]), '2', '.', ',' ), 'val');
		$val[] = array('#txtval05', number_format ( trim($rowsPisos[0][23]), '2', '.', ',' ), 'val');
		//$val[] = array('#txtval06', $rowsPisos[0][25], 'val');
		$val[] = array('#txtval07', number_format ( trim($rowsPisos[0][26]), '2', '.', ',' ), 'val');
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
	public function grabarpisoAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){

			$txtidpi = trim($this->_request->getPost('txtidpi'));
			$txtpiso = trim($this->_request->getPost('txtpiso'));
			$txtantigueda = trim($this->_request->getPost('txtantigueda'));
			$txtperiodopi = trim($this->_request->getPost('txtperiodopi'));
			$txtuni01 = trim($this->_request->getPost('txtuni01'));
			$txtuni02 = trim($this->_request->getPost('txtuni02'));
			$txtuni03 = trim($this->_request->getPost('txtuni03'));
			$txtuni04 = trim($this->_request->getPost('txtuni04'));
			$txtuni05 = trim($this->_request->getPost('txtuni05'));
			$txtuni06 = trim($this->_request->getPost('txtuni06'));
			$txtuni07 = trim($this->_request->getPost('txtuni07'));
			$cbClasificacion = trim($this->_request->getPost('cbClasificacion'));
			$cbMaterial = trim($this->_request->getPost('cbMaterial'));
			$cbEstado = trim($this->_request->getPost('cbEstado'));
			$txtareacons = str_replace(',','',trim($this->_request->getPost('txtareacons')));
			$txtareacomun = str_replace(',','',trim($this->_request->getPost('txtareacomun')));
			/*
			$txtval01 = trim($this->_request->getPost('txtidpu'));
			$txtval02 = trim($this->_request->getPost('txtidpu'));
			$txtval03 = trim($this->_request->getPost('txtidpu'));
			$txtval04 = trim($this->_request->getPost('txtidpu'));
			$txtval05 = trim($this->_request->getPost('txtidpu'));
			$txtval07 = trim($this->_request->getPost('txtidpu'));
			*/
			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',2);
			$parametros[] = array('@idPk',$txtidpi);
			$parametros[] = array('@nivel',$txtpiso);
			$parametros[] = array('@anio_cons',$txtperiodopi);
			$parametros[] = array('@anio_antig',$txtantigueda);
			$parametros[] = array('@id_clafica',$cbClasificacion);
			$parametros[] = array('@id_materia',$cbMaterial);
			$parametros[] = array('@id_estados',$cbEstado);
			$parametros[] = array('@cate_muros',$txtuni01);
			$parametros[] = array('@cate_techo',$txtuni02);
			$parametros[] = array('@cate_pisos',$txtuni03);
			$parametros[] = array('@cate_puert',$txtuni04);
			$parametros[] = array('@cate_reves',$txtuni05);
			$parametros[] = array('@cate_banno',$txtuni06);
			$parametros[] = array('@cate_insel',$txtuni07);
			$parametros[] = array('@area_const',$txtareacons);
			$parametros[] = array('@area_comun',$txtareacomun);

			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);
			/*
			$rows = array('datos'=>array());
			foreach($rowsrq as $row){
				$entry = array(
						'idx'=>$row[0],
						'nros_rq'=>$row[1],
						'anio_rq'=>utf8_encode($row[2]),
						'estado'=>utf8_encode($row[3])
				);
				$rows['datos'][] = $entry;
			}
			echo json_encode($rows);
			*/
		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
	}

	public function eliminarpisoAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){

			$txtidpi = trim($this->_request->getPost('getvalue'));
	
			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',7);
			$parametros[] = array('@idPk',$txtidpi);
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);

		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
	}
	
	public function muestrainstalacionAction(){
		$fn = new Libreria_Pintar();
		$evt[] = array('#btnCerrarxInstalacion', "click", "closePopup('#popmodificarinstalaciones');");
		$fn->PintarEvento($evt);
	}
	
	public function gridinstalacionAction(){
		
    	$cn = new Model_DbDatos_Datos();
		$login = new Zend_Session_Namespace('login');
		
		$txtidpu = $this->_request->getParam('txtidpu','');

    	$parametros[] = array('@buscar',3);
		$parametros[] = array('@idpredio',$txtidpu);
		    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MInstalacion_Mante', $parametros);
				
    	$jsonData = array('rows'=>array());
    	foreach($rowRuta as $row){
			$entry = array(
				'idxxx'=>$row[0],
				'idpu'=>$row[1],
				'anio'=>$row[2],
				'id_instala'=>$row[3],
				'mes_cons'=>$row[4],
				'anio_cons'=>$row[5],
				'anio_antig'=>$row[6],
				'id_clafica'=>$row[7],
				'id_materia'=>$row[8],
				'id_estados'=>$row[9],
				'val_estima'=>$row[10],
				'val_unitar'=>$row[11],
				'por_deprec'=>$row[12],
				'val_deprec'=>$row[13],
				'val_un_dep'=>$row[14],
				'cantidad'=>$row[15],
				'val_instal'=>$row[16],
				'instalacion'=>$row[17],
				'uni_med'=>$row[18],
			);
			$jsonData['rows'][] = $entry;
		}
		$this->view->data = json_encode($jsonData);	
	
    }
	
	public function crearinstalacionAction(){
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){
		
			$txtidpu = trim($this->_request->getPost('txtidpu'));
			$txtperiodopu = $this->_request->getPost('txtperiodopu');
			
			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',1);
			$parametros[] = array('@idpredio',$txtidpu);
			$parametros[] = array('@anio',$txtperiodopu);
			$parametros[] = array('@anio_cons',$txtperiodopu);
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MInstalacion_Mante', $parametros);
		}
		
	}
	
	public function grabarinstalacionAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		
    	if ($this->getRequest()->isPost()){

			$txtidpin = trim($this->_request->getPost('txtidpin'));
			$txtanioin = trim($this->_request->getPost('txtanioin'));
			$cbInstalacion = trim($this->_request->getPost('cbInstalacion'));
			$cbinClasificacion = trim($this->_request->getPost('cbinClasificacion'));
			$cbinMaterial = trim($this->_request->getPost('cbinMaterial'));
			$cbinEstado = trim($this->_request->getPost('cbinEstado'));
			$txtperiodopi = trim($this->_request->getPost('txtperiodopi'));
			$txtcantidad = str_replace(',','',trim($this->_request->getPost('txtcantidad')));
			
			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',2);
			$parametros[] = array('@idPk',$txtidpin);
			$parametros[] = array('@anio',$txtanioin);
			$parametros[] = array('@anio_cons',$txtperiodopi);
			$parametros[] = array('@anio_antig',$txtantigueda);
			$parametros[] = array('@id_instala',$cbInstalacion);
			$parametros[] = array('@id_clafica',$cbinClasificacion);
			$parametros[] = array('@id_materia',$cbinMaterial);
			$parametros[] = array('@id_estados',$cbinEstado);
			$parametros[] = array('@cantidad',$txtcantidad);
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MInstalacion_Mante', $parametros);
			/*
			$rows = array('datos'=>array());
			foreach($rowsrq as $row){
				$entry = array(
						'idx'=>$row[0],
						'nros_rq'=>$row[1],
						'anio_rq'=>utf8_encode($row[2]),
						'estado'=>utf8_encode($row[3])
				);
				$rows['datos'][] = $entry;
			}
			echo json_encode($rows);
			*/
		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
	}
	
	public function modificarinstalacionAction(){
		$cn = new Model_DbDatos_Datos();
        $ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$txtidin = $this->_request->getParam('idpin','');
		$txtanio = $this->_request->getParam('anio','');
		
		$val[] = array('#txtidpin', $txtidin, 'val');
		$val[] = array('#txtanioin', $txtanio, 'val');
		
		$evt[] = array('#btnCerrarInstalacion', "click", "closePopup('#popmodificarinstalacion');");
		
        unset($parametros);
        $parametros[] = array('@buscar', 4);
        $comboClasificacion = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);
        $arClasificacion = $ar->RegistrosCombo($comboClasificacion, 0, 1);
        
		$val[] = array('#cbinClasificacion', $fn->ContenidoCombo($arClasificacion, '[Seleccione]', ''), 'html');

        unset($parametros);
        $parametros[] = array('@buscar', 5);
        $comboMaterial = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);
        $arMaterial = $ar->RegistrosCombo($comboMaterial, 0, 1);
        
		$val[] = array('#cbinMaterial', $fn->ContenidoCombo($arMaterial, '[Seleccione]', ''), 'html');
		
        unset($parametros);
        $parametros[] = array('@buscar', 6);
        $comboEstado = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MPisos_Mante', $parametros);
        $arEstado = $ar->RegistrosCombo($comboEstado, 0, 1);
        
		$val[] = array('#cbinEstado', $fn->ContenidoCombo($arEstado, '[Seleccione]', ''), 'html');

        unset($parametros);
        $parametros[] = array('@buscar', 5);
		$parametros[] = array('@anio', 2014);
        $comboInstalacion = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MInstalacion_Mante', $parametros);
        $arInstalacion = $ar->RegistrosCombo($comboInstalacion, 0, 1);
        
		$val[] = array('#cbInstalacion', $fn->ContenidoCombo($arInstalacion, '[Seleccione]', ''), 'html');
		
        unset($parametros);
        $parametros[] = array('@buscar', 4);
		$parametros[] = array('@idPk', $txtidin);
        $rowsins = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MInstalacion_Mante', $parametros);
		
		$val[] = array('#cbInstalacion', $rowsins[0][3], 'val');
		$val[] = array('#cbinClasificacion', $rowsins[0][7], 'val');
		$val[] = array('#cbinMaterial', $rowsins[0][8], 'val');
		$val[] = array('#cbinEstado', $rowsins[0][9], 'val');
		
		$val[] = array('#txtantigueda', $rowsins[0][6], 'val');
		$val[] = array('#txtperiodopi', $rowsins[0][5], 'val');
		$val[] = array('#txtmedida', $rowsins[0][18], 'val');
		$val[] = array('#txtestimado', $rowsins[0][10], 'val');
		$val[] = array('#txtcantidad', $rowsins[0][15], 'val');
		$val[] = array('#txtvalotota', $rowsins[0][16], 'val');
		
		$val[] = array('#txtfactor', $rowsins[0][19], 'val');
		$val[] = array('#txtvaluni', $rowsins[0][11], 'val');
		$val[] = array('#txtporcendep', $rowsins[0][12], 'val');
		$val[] = array('#txtvalunidep', $rowsins[0][14], 'val');
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	}
	
	public function eliminarinstalacionAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){

			$txtidin = trim($this->_request->getPost('getvalue'));
	
			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',6);
			$parametros[] = array('@idPk',$txtidin);
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MInstalacion_Mante', $parametros);

		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
	}
	
	public function copiaprediosAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){

			$idpredio = trim($this->_request->getPost('getidpu'));
			$idrq = trim($this->_request->getPost('txtidrq'));
			$stranio = trim($this->_request->getPost('stranios'));
			$aorigen = trim($this->_request->getPost('aorige'));
	
			$cn = new Model_DbDatos_Datos();
			$parametros[] = array('@idpredio',$idpredio);
			$parametros[] = array('@idRq',$idrq);
			$parametros[] = array('@stranio',$stranio);
			$parametros[] = array('@aorigen',$aorigen);
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_CopiaPredios_ejecuta', $parametros);

		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
	}
	
	public function copiaprediosrentasAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){

			$codigo = trim($this->_request->getPost('codigo'));
			$txtidrq = trim($this->_request->getPost('txtidrq'));
			$xidhre = trim($this->_request->getPost('xidhre'));
			$aorige = trim($this->_request->getPost('aorige'));

			$cn = new Model_DbDatos_Datos();
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@idRq',$txtidrq);
			$parametros[] = array('@idRe',$xidhre);
			$parametros[] = array('@aorigen',$aorige);
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_CopiaPrediosRentas_ejecuta', $parametros);

		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
	}
}
?>
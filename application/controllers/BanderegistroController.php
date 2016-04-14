<?php

/**
 * MantbusquedaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class BanderegistroController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	// public function indexAction() {
	//	TODO Auto-generated MantbusquedaController::indexAction() default action
	// }	
	
	public function indexAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Registro";
		
		$cn = new Model_DbDatos_Datos ();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		
		$evt[] = array('#contentBox',"tabs","");
		
		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
		$this->view->tesodesde = $fecharow[0][0];
		$this->view->tesohasta = $fecharow[0][0];
		
		$arAnios = $ar->AniosTributos();
		$val[] = array('#cmbanno',$fn->ContenidoCombo($arAnios,'[Todos]',$ano_periodo),'html');		
		
		unset($parametros);
		$parametros[] = array('@msquery',19);	
		$comboUsuario = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
		$arUsuario = $ar->RegistrosCombo($comboUsuario,0,1);
		$val[] = array('#cmbUsuario',$fn->ContenidoCombo($arUsuario,'',''),'html');
		
		
		
		
		unset($parametros);
		$parametros[] = array('@msquery',5);	
		$combovalor = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
		$arValor = $ar->RegistrosCombo($combovalor,0,1);
		$val[] = array('#cmbvalortribu',$fn->ContenidoCombo($arValor,'[Todos]',''),'html');
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		
		$evt[] = array('#btnBuscRegistro',"click","buscarRegistro()");
		
		$evt[] = array('#btnNuevoRegistro',"click","showPopup('banderegistro/formu','#popRegistro','800','550','Ingreso de Registro');");
			
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
    	
    	$rdcriterio = trim($_REQUEST['rdcriterio']);
    	$criterio = trim($_REQUEST['criterio']);
		$anno = trim($_REQUEST['anno']);
		$valor = trim($_REQUEST['valor']);
		
		$num_expe = trim($_REQUEST['numexpe']);
		$ano_expe = trim($_REQUEST['anoexpe']);
		$usuario = trim($_REQUEST['usuario']);
		
		$tesodesde = $this->_request->getParam('tesodesde','');
		$tesohasta = $this->_request->getParam('tesohasta','');
  
    	switch($rdcriterio)
    	{
    		case 'C': $codigo = $criterio;break;
    		case 'N': $nombre = $criterio;break;
    		case 'D': $domifiscal = $criterio;break;
			case 'P': $domipredial = $criterio;break;
    		case 'Z': $zona = $criterio;break;
    		
    	}
    	
    	//Para el total
    	$parametros[] = array('@msquery',12);
		$parametros[] = array('@cod_contribuyente',$codigo);
		$parametros[] = array('@razon_social',$nombre);
		$parametros[] = array('@domicilio_fiscal',$domifiscal);
		$parametros[] = array('@domicilio_predio',$domipredial);
		$parametros[] = array('@sector_zona',$zona);
		$parametros[] = array('@ano_periodo',$anno);
		$parametros[] = array('@valor_tribu',$valor);
		
		$parametros[] = array('@num_expe',$num_expe);
		$parametros[] = array('@ano_expe',$ano_expe);
		$parametros[] = array('@nomdigitador',$usuario);
		
		$parametros[] = array('@fec_desde',$tesodesde);
		$parametros[] = array('@fec_hasta',$tesohasta);
		
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',11);
		$parametros[] = array('@cod_contribuyente',$codigo);
		$parametros[] = array('@razon_social',$nombre);
		$parametros[] = array('@domicilio_fiscal',$domifiscal);
		$parametros[] = array('@domicilio_predio',$domipredial);
		$parametros[] = array('@sector_zona',$zona);
		$parametros[] = array('@ano_periodo',$anno);
		$parametros[] = array('@valor_tribu',$valor);
		
		$parametros[] = array('@num_expe',$num_expe);
		$parametros[] = array('@ano_expe',$ano_expe);
		$parametros[] = array('@nomdigitador',$usuario);
		
		$parametros[] = array('@fec_desde',$tesodesde);
		$parametros[] = array('@fec_hasta',$tesohasta);
		
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigoregistro'=>$row[0],
						'codigocontri'=>$row[1],
						'razonsocial'=>utf8_encode($row[2]),
						'domiciliofiscal'=>utf8_encode($row[3]),
						'domiciliopredial'=>utf8_encode($row[4]),
						'zona'=>utf8_encode($row[5]),
						'anno'=>utf8_encode($row[6]),
						'valor'=>utf8_encode($row[7]),
						'valor_tribu'=>utf8_encode($row[8]),
						'num_expe'=>utf8_encode($row[9]),
						'ano_expe'=>utf8_encode($row[10]),
						'num_valor'=>utf8_encode($row[13])
						
				);
			
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	
	public function formuAction()
    {    		
    	$path = new Zend_Session_Namespace('path');
		$login = new Zend_Session_Namespace('login');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		
		$codigoregistro=$this->_request->getParam('codigoregistro','');
		
		
	
		$nomdigitador = "";
		$num_archivador = "";
		$cod_contribuyente = "";
		$razon_social = "";
		$domicilio_fiscal = "";
		$domicilio_predio = "";
		$sector_zona = "";
		$ano_periodo = "";
		$periodo = "";
		$valor_tribu = "";
		$num_valor_tribu = "";
		$monto = 0;
		$num_expe = "";
		$estado_coac = "";
		$rec1 = "";
		$fecha_rec1 = "";
		$observacion = "";
		$ano_expe = "";
		$num_doc = "";
		$fecha_registro = "";
		
		if(!empty($codigoregistro))
		{
			$parametros[] = array('@msquery',7);
			$parametros[] = array('@codregistro',$codigoregistro);
			$rowsRegistro = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
			
			$codigoregistro = $rowsRegistro[0][0]; 
			$nomdigitador = $rowsRegistro[0][1]; 			
			$num_archivador = $rowsRegistro[0][2];
			
			$cod_contribuyente = $rowsRegistro[0][3]; 
			$razon_social = $rowsRegistro[0][4]; 
			$domicilio_fiscal = $rowsRegistro[0][5]; 
			$domicilio_predio = $rowsRegistro[0][6]; 
			$sector_zona = $rowsRegistro[0][7]; 
			$ano_periodo = $rowsRegistro[0][8]; 
			
			$periodo = $rowsRegistro[0][9]; 
			$valor_tribu = $rowsRegistro[0][10]; 
			$num_valor_tribu = $rowsRegistro[0][11]; 
			
			$monto = $rowsRegistro[0][12]; 
			$num_expe = $rowsRegistro[0][13]; 
			$estado_coac = $rowsRegistro[0][14]; 
			
			$rec1 = $rowsRegistro[0][15]; 
			$fecha_rec1 = $rowsRegistro[0][16];
			
			$observacion = trim($rowsRegistro[0][17]);
			$ano_expe = $rowsRegistro[0][18];
			
			$num_doc = $rowsRegistro[0][19];
			$fecha_registro = $rowsRegistro[0][20];
			
		}
		
		
		$arAnios = $ar->AniosTributos();
		$val[] = array('#anios_deuda',$fn->ContenidoCombo($arAnios,'[Seleccione]',$ano_periodo),'html');
		
		$arAnios = $ar->AniosTributos();
		$val[] = array('#cmb_anno',$fn->ContenidoCombo($arAnios,'[Seleccione]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@msquery',5);	
		$combovalor = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
		$arValor = $ar->RegistrosCombo($combovalor,0,1);
		$val[] = array('#cmbvalor',$fn->ContenidoCombo($arValor,'[Seleccione]',$valor_tribu),'html');
		
		unset($parametros);
		$parametros[] = array('@msquery',13);	
		$comboestado = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
		$arEstado = $ar->RegistrosCombo($comboestado,0,1);
		$val[] = array('#cmbEstado',$fn->ContenidoCombo($arEstado,'[Seleccione]',$estado_coac),'html');
		
		
		unset($parametros);
		$parametros[] = array('@msquery',18);	
		$combovalor = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
		$arValor = $ar->RegistrosCombo($combovalor,0,1);
		$val[] = array('#cmb_tributo',$fn->ContenidoCombo($arValor,'[Seleccione]',$combovalor),'html');
		
		
		if($rec1=='1')
			$cad[] = array("#rdRecSi","checked", true);
		if($rec1=='0')
			$cad[] = array("#rdRecNo","checked", true);
		
		$val[] = array('#txtCodregistro',$codigoregistro,'val');
		//$val[] = array('#txtDigitador',$nomdigitador,'val');
		//$val[] = array('#txtItem',$item,'val');
		$val[] = array('#txtNumarchi',$num_archivador,'val');
		$val[] = array('#txtCodcontrib',$cod_contribuyente,'val');
		$val[] = array('#txtNombre',$razon_social,'val');
		$val[] = array('#txtDomifis',$domicilio_fiscal,'val');
		$val[] = array('#txtDomipre',$domicilio_predio,'val');
		$val[] = array('#txtSector',$sector_zona,'val');
		$val[] = array('#txtPeriodo',$periodo,'val');
		$val[] = array('#txtNumvalor',$num_valor_tribu,'val');
		$val[] = array('#txtNumexpedi',$num_expe,'val');
		$val[] = array('#txtAnoexpedien',$ano_expe,'val');
		$val[] = array('#txtMonto',$monto,'val');	
		$val[] = array('#txtFechrec',$fecha_rec1,'val');
		$val[] = array('#txtObservacion',$observacion,'val');
		$val[] = array('#txtDigitador',trim($login->user),'val');
		$val[] = array('#txtNumdocu',$num_doc,'val');	
		
		$val[] = array('#txtFechareg',$fecha_registro,'val');	
		
		$val[] = array('#fechainicio',date("d/m/Y H:i:s"),'val');
		
		
		$evt[] = array('#txtFechrec',"datepicker","");	
		
		$evt[] = array('#btnBusContrib',"click","showPopup('mantbusquedacontri/buscar','#popbuscacontri','1000','358','Busqueda Contribuyentess','frmBuscontricoac');");
		
		$evt[] = array('#btnBusPredio',"click","showPopup('mantbusquedapredio/index','#popbuscapredio','1000','358','Busqueda Predio','frmPredio');");
		
		$evt[] = array('#btnSalirRegistros',"click","closePopup('#popRegistro');");
		
		$evt[] = array('#btnGrabaRegistros',"click","goToFormulario('frmBanderegistro');");
		
		$evt[] = array('#txtMonto',"keypress","return validaTeclas(event,'numeric');");
		$evt[] = array('#txtNumexpedi',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtPeriodo',"keypress","return validaTeclas(event,'numcoma');");
		$evt[] = array('#txtItem',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtNumarchi',"keypress","return validaTeclas(event,'number');");		
		$evt[] = array('#txtAnoexpedien',"keypress","return validaTeclas(event,'alpha');");
		
		$evt[] = array('#txtNumvalor',"keypress","return validaTeclas(event,'number');");
		
		
		$evt[] = array('#txtDigitador',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtDigitador',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#btnAddConst',"click","eventConst('A');");
		$evt[] = array('#btnEditConst',"click","eventConst('E');");
		$evt[] = array('#btnCancelConst',"click","eventConst('C');");
		$evt[] = array('#btnSaveConst',"click","eventConst('S');");
		$evt[] = array('#btnDelConst',"click","eventConst('D');");
		
		
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		if(count($cad)>0)
			$fn->AtributoComponente($cad);
		
    }
	
	public function grabarAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){
			$json = $this->_request->getPost('json');
			$data = json_decode($json);

			
			
			$cn = new Model_DbDatos_Datos();
			$txtCodregistro = $data->txtCodregistro;
			
			if(strlen($data->txtCodregistro)>0)
				$tip = 8;
			else
				$tip = 4;
				
			$parametros[] = array('@msquery',$tip);
			$parametros[] = array('@codregistro',$data->txtCodregistro);
			$parametros[] = array('@nomdigitador',$data->txtDigitador);
			
			$parametros[] = array('@num_archivador',$data->txtNumarchi);
			$parametros[] = array('@cod_contribuyente',$data->txtCodcontrib);
			$parametros[] = array('@razon_social',$data->txtNombre);
			$parametros[] = array('@domicilio_fiscal',str_replace ("'","''",$data->txtDomifis));
			$parametros[] = array('@domicilio_predio',str_replace ("'","''",$data->txtDomipre));
			$parametros[] = array('@sector_zona',$data->txtSector);
			$parametros[] = array('@ano_periodo',$data->anios_deuda);
		    
			$parametros[] = array('@valor_tribu',$data->cmbvalor);
			$parametros[] = array('@num_valor_tribu',$data->txtNumvalor);
			$parametros[] = array('@monto',$data->txtMonto);
			$parametros[] = array('@num_expe',$data->txtNumexpedi);
			$parametros[] = array('@ano_expe',$data->txtAnoexpedien);
			$parametros[] = array('@estado_coac',$data->cmbEstado);
			$parametros[] = array('@rec1',$data->rdRec);
			$parametros[] = array('@fecha_rec1',$data->txtFechrec);
			$parametros[] = array('@observacion',$data->txtObservacion);
			$parametros[] = array('@num_doc',$data->txtNumdocu);
			$parametros[] = array('@estacion',php_uname('n'));			
			$parametros[] = array('@fech_inicio',$data->fechainicio);
					
			$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
			
			if($rows){
			
					if(!empty($data->Const)){
					$dataConst = $data->Const;			
						foreach($dataConst as $dConst){
						
							unset($parametros);
								
							$parametros[] = array('@msquery',15);
							
							$parametros[] = array('@iddetalle',$dConst->idDetalle);
							$parametros[] = array('@codregistro',$rows[0][0]);
							$parametros[] = array('@nomdigitador',$data->txtDigitador);
							$parametros[] = array('@num_archivador',$data->txtNumarchi);
							$parametros[] = array('@cod_contribuyente',$data->txtCodcontrib);
							$parametros[] = array('@TIPO_TRIBUTO',$dConst->tipo_tributo);
							$parametros[] = array('@ANNO',$dConst->anno);
							$parametros[] = array('@INSOLUTO',$dConst->insoluto);
							$parametros[] = array('@IMP_REAJ',trim($dConst->reajuste ));
							$parametros[] = array('@COSTO_EMIS',trim($dConst->costo_emision));
							$parametros[] = array('@MORA',trim($dConst->mora));
							$parametros[] = array('@PERIODOS',$dConst->periodo);
							$parametros[] = array('@TOTAL',$dConst->total);
							$parametros[] = array('@operador',$data->txtDigitador);
							$parametros[] = array('@estacion',php_uname('n'));

							$datos=$cn->ejec_store_procedura_sql("[Coactivo].[SP_Registro]",$parametros);		
						}
					}
			}
			//echo "Se grabo correctamente";
    	}    	
    	
    }
	
	
	public function detalleconsultaAction()
	{
		
				$idregistro = $this->_request->getPost('idregistro');
								
				$cn = new Model_DbDatos_Datos();
				$parametros[] = array('@msquery',14);
				$parametros[] = array('@idregistro',$idregistro);

				$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
				
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idDetalle'=>trim($row[0]),
							'num_archivador'=>trim($row[1]),
							'idregistro'=>trim($row[2]),
							'tipo_tributo'=>trim($row[3]),
							'anno'=>trim($row[4]),
							'insoluto'=>trim($row[5]),
							'reajuste'=>trim($row[6]),
							'costo_emision'=>trim($row[7]),
							'mora'=>trim($row[8]),
							 'periodo'=>trim($row[9]),
							'total'=>trim($row[10]),
							'nom_tribu'=>trim($row[12])
						);
						$jsonData[] = $entry;
					}
				}
			$this->view->data = json_encode($jsonData);	
	}
	
	public function eliminarAction()
    {
    	$this->_helper->layout->disableLayout();
  		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();	
		if ($this->_request->isPost())
    	{	
			$idDetalle=trim($this->_request->getPost('idDetalle'));
			//var_dump($data );
		
				//$id=trim($row->id); 
				
			$cn = new Model_DbDatos_Datos();
			unset($parametros);
			$parametros[] = array('@msquery',16);
			$parametros[] = array('@iddetalle',$idDetalle);		 
			$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
			
			echo $rows[0][0];
						
    	}
		
		
    }
	
	
	public function eliminarregistroAction()
    {		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
		
    		$cn = new Model_DbDatos_Datos();
    		
			$codigoregistro = $this->_request->getParam('codigoregistro','');
		
			
			$parametros[] = array('@msquery',17);
			$parametros[] = array('@idregistro',$codigoregistro);			
			$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
			
			echo "Se elimino correctamente";
		}

	}
	/*
	public function validanumtribuAction() 
	{
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		
		$cn = new Model_DbDatos_Datos ();
		
		$num_valor = $this->_request->getParam ('txtNumvalor');
		$parametros [] = array ('@msquery', 3 );	
		$parametros [] = array ('@num_valor_tribu', $num_valor );
		$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
				
		if(count($rows)>0)
			echo false;
		else
			echo true;
			
			
	}
	*/
	
}


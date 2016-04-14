<?php

class FiscalizaciondetalleController extends Zend_Controller_Action{

    public function init(){
        /* Initialize action controller here */			
    }

    public function indexAction(){

		$txtidx = $this->_request->getParam('txtidx','');
	
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnNuevoHR',"click","NuevoHResumen();");
		$evt[] = array('#btnEditaHR',"click","ModificarHResumen();");
		$evt[] = array('#btnEliminarHR',"click","EliminarHResumen();");
		$evt[] = array('#btnGeneraRD',"click","GenerarResolucion();");
		$evt[] = array('#btnGeneraMT',"click","GenerarResolucionmt();");
		$evt[] = array('#btnCerraIndex',"click","closePopup('#popdetallerequerimiento');");
		
		$val[] = array('#txtidrq',$txtidx,"val");

		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }
	
    public function indexcentroAction(){
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$evt[] = array('#btncrearPredio',"click","CrearPredio();");
		$evt[] = array('#btneditaPredio',"click","ModificarPredio();");
		$evt[] = array('#btnbajarPredio',"click","EliminarPredio();");
		$evt[] = array('#btnCopiaPredio',"click","CopiarPredio();");
		$evt[] = array('#btnCopiaRentas',"click","CopiarPredioRentas();");	

		$txtidrq = $this->_request->getParam('txtidrq','');
		
    	$parametros[] = array('@buscar',7);
    	$parametros[] = array('@idPk',$txtidrq);
		$rows = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_Requerimiento_Buscar', $parametros);

		$val[] = array('#txtmcodigo',$rows[0][7],"val");
		$val[] = array('#txtmnombre',$rows[0][8],"val");
		$val[] = array('#txtmdirecc',$rows[0][9],"val");
		$val[] = array('#txtmdoc',$rows[0][5],"val");
		
		unset($parametros);
    	$parametros[] = array('@buscar',8);
    	$parametros[] = array('@idPk',$txtidrq);
		$rowanio = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_Requerimiento_Buscar', $parametros);
		
		$val[] = array('#txtaniohr',$rowanio[0][2],"val");
		$val[] = array('#txtidre',$rowanio[0][3],"val");
		
		$this->view->txtdesde = $rowanio[0][1];
		$this->view->txthasta = $rowanio[0][2];
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
    }

    public function datostreeAction(){
    	
		$txtidx = $this->_request->getParam('txtidx','');
		
		$this->_helper->layout->disableLayout();
    	
    	$cn = new Model_DbDatos_Datos();
    	$ar = new Libreria_ArraysFunctions();    	
    	$login = new Zend_Session_Namespace('login');
    	
    	$parametros[] = array('@buscar',1);
    	$parametros[] = array('@idRq',$txtidx);
		$rows = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MReque_Resumen_Buscar', $parametros);
		
		//$jsonData = array();
	//$jsonData = array('root'=>array());
		$jsonData = array(
			'id'=>'______1',
			'text'=>"Documentos",
			'qtip'=>'Lista',
			'expanded'=>true,
			'children'=>array()
		);
		
		if(count($rows)){
			
			foreach($rows AS $row){
				
				unset($parametros);
				$parametros[] = array('@buscar',2);
				$parametros[] = array('@idRq',$row[0]);
	
				$rows1 = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MReque_Resumen_Buscar', $parametros);
				
				
				$mnuChildrens = array();
				
				if(count($rows1)){
				
					foreach($rows1 AS $row1){
						/*
						if(strlen($row1[2])>0)
							$url=$row1[2];
						else
							$url='main/blank';
						*/
						$childs = array(
							id=>$ar->EspecialChars($row1[2]),
							//qtip=>$row1[0].'-'.$row1[1],
							iconCls=>'iconImage', //icono del nodo,
							//hrefTarget=>$url."?mod=".$row1[0], //ruta enlace
							//cls=>$ar->EspecialChars($row[0]), //Text del menú padre
							text=>$ar->EspecialChars($row1[3]), //Text del menú hijo
							leaf=>true
						);
						$mnuChildrens[] = $childs;
					}
				}
				
					/*
					$root = array(
						text=>'000',
						expanded=>true,
						children=>$mnuChildrens
					);
					*/
					/*
					$fn = array(
						fn=>'|clickFun|'
					);
					*/
					/*
					$listeners = array(
						itemclick=>$fn
					);
					*/
					$mnuFathers = array(
						id=>'_001',//$row[0],
						//xtype=>'treepanel',
						title=>$row[0],
						//cls=>'file', //Text del menú padre
						text=>utf8_encode(ucwords($row[4])),
						//iconCls=>'nav',
						expanded=>true,
						leaf=>false,
						//rootVisible=>true,
						children=>$mnuChildrens
						//root=>$root
						//listeners=>$listeners
					);
				
				//$jsonData[] = $mnuFathers;
				$jsonData['children'][] = $mnuFathers;
			}
		}

		$this->view->data = json_encode($jsonData);
    
    }

	public function hresumenAction(){
	
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();

		$txtaction = $this->_request->getParam('txtaction','');
		$txtidrq = $this->_request->getParam('idrq','');
		$txtidre = $this->_request->getParam('txtidre','');
		
		$val[] = array('#txtIdrq',$txtidrq,"val");
		$val[] = array('#txtaction',$txtaction,"val");
		$val[] = array('#txtIdrh',$txtidre,"val");
		
		$evt[] = array('#btnCancelar', "click", "closePopup('#pophresumen');");

		unset($parametros);
        $parametros[] = array('@buscar', 3);
        $comboregimen = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MHResumen_Mante', $parametros);
        $arregimen = $ar->RegistrosCombo($comboregimen, 0, 1);
        $val[] = array('#cbRegimen', $fn->ContenidoCombo($arregimen, '[Seleccione]', ''), 'html');
		
		if($txtaction=='2'){
			unset($parametros);
			$parametros[] = array('@buscar', 5);
			$parametros[] = array('@idPk', $txtidre);

			$rowshr = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MHResumen_Mante', $parametros);
			
			$val[] = array('#txtAnio',$rowshr[0][1],"val");
			$val[] = array('#cbRegimen',$rowshr[0][2],"val");
			$val[] = array('#txtnpredio',$rowshr[0][7],"val");
			$val[] = array('#txttotavaluo',$rowshr[0][3],"val");
			$val[] = array('#txtbase_imp',$rowshr[0][4],"val");
			$val[] = array('#txtimpanual',$rowshr[0][5],"val");
			$val[] = array('#txtgasemis',$rowshr[0][8],"val");
			$val[] = array('#txt_observaciones',$rowshr[0][9],"val");
			
		}
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
    public function grabarhresumenAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){
			$buscar 	= (int)trim($this->_request->getPost('txtaction'));
			$txtidrq	= (int)trim($this->_request->getPost('txtIdrq'));
			$txtidrh 	= (int)trim($this->_request->getPost('txtIdhr'));
			$txtanio 	= trim($this->_request->getPost('txtAnio'));
			$cbregimen 	= trim($this->_request->getPost('cbRegimen'));
			$txtobservaciones 	= trim($this->_request->getPost('txt_observaciones'));	
			
			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',$buscar);
			$parametros[] = array('@idPk',$txtidrh);
			$parametros[] = array('@idrq',$txtidrq);
			$parametros[] = array('@anio',$txtanio);								
			$parametros[] = array('@idregimen',$cbregimen);
			$parametros[] = array('@observacion',$txtobservaciones);
			
			//@idPk,@tipo_rq,@anio_rq,@nros_rq,@fech_rq,@idDocum,@idmotivo,@codigo,@nombre,@domfis,@observ
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MHResumen_Mante', $parametros);
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
			echo $rowsrq[0][0];
		    exit;
		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
		
	}
	
	public function eliminarresumenAction(){
	
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		
		if ($this->getRequest()->isPost()){
		
			$txtidrq = trim($this->_request->getPost('idrq'));
			$txtidre = trim($this->_request->getPost('txtidre'));		
			
			unset($parametros);
			$parametros[] = array('@buscar', 6);
			$parametros[] = array('@idPk', $txtidre);

			$rowshr = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MHResumen_Mante', $parametros);
		}

	}
	
	public function generaresolucionAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){

			$txtidrq = trim($this->_request->getPost('txtidrq'));

			$cn = new Model_DbDatos_Datos();
			$parametros[] = array('@idrq',$txtidrq);
			
			//$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.Genera_Resolucion', $parametros);
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.Genera_Resolucion_anio', $parametros);
			
			echo $rowsrq[0][0];
		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
	}

	public function generaresolucionmtAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){

			$txtidrq = trim($this->_request->getPost('txtidrq'));
			$txtidbl = trim($this->_request->getPost('txtidbl'));

			$cn = new Model_DbDatos_Datos();
			$parametros[] = array('@idrq',$txtidrq);
			$parametros[] = array('@idsancion',$txtidbl);
			
			//$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.Genera_Resolucion', $parametros);
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.Genera_Resolucion_multa_anio', $parametros);
			
			echo $rowsrq[0][0];
		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
	}
}


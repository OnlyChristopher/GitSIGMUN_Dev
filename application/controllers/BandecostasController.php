<?php

/**
 * MantbusquedaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class BandecostasController extends Zend_Controller_Action {
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
		
		$this->view->title = "Costas";
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
				
		//$evt[] = array('#btnBusquedacri',"click","buscarDatos()");
		$evt[] = array('#btnSearchBaja',"click","buscarBajapre()");
						
		$fn->PintarEvento($evt);
	}

    public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$rdcriteriobus = $_REQUEST['rdCriteriobus'];
    	$criteriobus = $_REQUEST['txtCriteriobus'];
		$criterionombrebus = $_REQUEST['txtCriterioNombrebus'];
		$criteriopaternobus = $_REQUEST['txtCriterioAPaternobus'];
		$criteriomaternobus = $_REQUEST['txtCriterioAMaternobus'];
		$criteriorazonbus = $_REQUEST['txtCriterioRazonbus'];
		$documentobus = $_REQUEST['txtDocumentobus'];
    	
		//{rdCriteriobus: rdCriteriobus, txtCriteriobus: txtCriteriobus, txtCriterioNombrebus:txtCriterioNombrebus
			//,txtCriterioAPaternobus: txtCriterioAPaternobus,txtCriterioAMaternobus:txtCriterioAMaternobus,txtCriterioRazonbus:txtCriterioRazonbus,txtDocumentobus:txtDocumentobus};
		//{rdcriterio: rdCriterio, criterio: txtCriterio, criterionombre:txtCriterioNombre,criteriopaterno: txtCriterioAPaterno,criteriomaterno:txtCriterioAMaterno,criteriorazon:txtCriterioRazon,documento:txtDocumento};
		
    
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@codigo',$criteriobus);
		$parametros[] = array('@nombres',$criterionombrebus);
		$parametros[] = array('@paterno',$criteriopaternobus);
		$parametros[] = array('@materno',$criteriomaternobus);
		$parametros[] = array('@razon',$criteriorazonbus);
		$parametros[] = array('@num_doc',$documentobus);
		$parametros[] = array('@tipo_busqueda',$rdcriteriobus );
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyentecostas', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@codigo',$criteriobus);
		$parametros[] = array('@nombres',$criterionombrebus);
		$parametros[] = array('@paterno',$criteriopaternobus);
		$parametros[] = array('@materno',$criteriomaternobus);
		$parametros[] = array('@razon',$criteriorazonbus);
		$parametros[] = array('@num_doc',$documentobus);
		$parametros[] = array('@tipo_busqueda',$rdcriteriobus );
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rowsBaja = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyentecostas', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rowsbaja'=>array());
		if(count($rowsBaja))
		{
			foreach($rowsBaja AS $row){
				$entry = array(
						'codigo'=>$row[0],				  
						'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
						'documento'=>utf8_encode($row[3]),
						'direccion'=>utf8_encode($row[15]),
						'tipodoc'=>utf8_encode($row[25]),
						'expediente'=>utf8_encode($row[28]),  
						'num_exp'=>utf8_encode($row[29]),
						'ano_exp'=>utf8_encode($row[30])
				);
				$jsonData['rowsbaja'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	
	public function costasAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
    	$path->codigo = $this->_request->getParam('codigo','');
		$path->num_exp = $this->_request->getParam('num_exp','');
		$path->ano_exp = $this->_request->getParam('ano_exp','');
    	
    	$codigo=$path->codigo ;
		$num_exp=$path->num_exp ;
		$ano_exp=$path->ano_exp ;
    	
		$nombrestore = 'Rentas.sp_Mcontribuyentecostas';
        $arraydatos[]= array('@busc','8');
        $arraydatos[]= array('@codigo',$codigo);
		$arraydatos[]= array('@num_exp',$num_exp);
		$arraydatos[]= array('@ano_exp',$ano_exp);
        
        $cn = new Model_DbDatos_Datos();
        $datoglobal = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);

        if (count($datoglobal)>0){
	        $path->nombres=$datoglobal[0][1];;
	        $path->num_doc=$datoglobal[0][2];;
	        $path->direccion=$datoglobal[0][3];
			$path->expediente=$datoglobal[0][4];
	        
	        $this->view->varcodigo = $path->codigo;
	        $this->view->varnombre = $path->nombres;
	        $this->view->vardocumen = $path->num_doc;
	        $this->view->vardomicilio = $path->direccion;
			$this->view->varexpediente = $path->expediente;
        }
        
		unset($parametros);
		$parametros[] = array('@msquery',2);
		$parametros[] = array('@anno',date('Y'));
		$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Costas', $parametros);
		$uit=$rows[0][0];
		
		
		// unset($parametros);
		// $parametros[] = array('@msquery',5);
		// $parametros[] = array('@codigo',$datoglobal[0][0]);
		// $parametros[] = array('@num_docu',$datoglobal[0][4]);
		// $rows = $cn->ejec_store_procedura_sql('Rentas.sp_CajaRecibos', $parametros);
		// $monto=$rows[0][0];
		
		$evt[] = array('#btnSalir',"click","closePopup('#popCosta');");
		
		$evt[] = array('#btnAddTupa',"click","eventPagoTupa('A');");
		$evt[] = array('#btnEditTupa',"click","eventPagoTupa('E');");
		$evt[] = array('#btnCancelTupa',"click","eventPagoTupa('C');");
		$evt[] = array('#btnSaveTupa',"click","eventPagoTupa('S');");
		$evt[] = array('#btnDelTupa',"click","eventPagoTupa('D');");
		
	//	$evt [] = array ('#btnImpPag', "click", "goToFormulario('frmcostass')" );
		
	//	$evt[] = array('#btnDelTupa',"click","eliminacostas()");
		
		$val[] = array('#txtUit',$uit,'val');
	//	$val[] = array('#txtTotPag',$monto,'val');
		
		$val[] = array('#txtcodigo',$datoglobal[0][0],'val');
		$val[] = array('#txtexpediente',$datoglobal[0][4],'val');
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
    }
	
	 public function gridcostasAction()
	 {
			// $codigo = $_REQUEST['codigo'];
			// $expediente = $_REQUEST['expediente'];
		
			$codigo = $this->_request->getPost('codigo');
			$expediente = $this->_request->getPost('expediente');
			
			$cn = new Model_DbDatos_Datos();
			$parametros[] = array('@msquery',2);
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@num_docu',$expediente);
			$rows = $cn->ejec_store_procedura_sql('Rentas.sp_CajaRecibos', $parametros);
			
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'conceptos'=>trim($row[26]),//nombre
							'monto'=>trim($row[13]),//imp_reaj
							'cantidad'=>trim($row[12]),//fact_reaj
							'uit1'=>utf8_encode($row[11]),//imp_insol
							'tipo'=>trim($row[8]),//tipo
							'subtipo'=>trim($row[9]),//tipo_rec
							'idrecibo'=>trim($row[0])//idrecibo
						);
						$jsonData[] = $entry;
					}
				}
				$this->view->data = json_encode($jsonData);
  }
	
	
	
	/*public function gridcostasAction()
	{
			//$id_costas = $this->_request->getPost('id_costas');
			
			$cn = new Model_DbDatos_Datos();
			
			$uit = $this->_request->getParam('uitano','');
			
			unset($parametros);
			$parametros[] = array('@msquery',1);
			$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Costas', $parametros);
			
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
					
					$multi=$uit*$row[2]/100;
					//$oculto=$multi;
						$entry = array(
							'id_costas'=>trim($row[0]),
							'descripcion'=>utf8_encode($row[1]),
							'uit'=>trim($row[2]),							
							'multi'=>number_format($multi,2),
							'oculto'=>''
						);
						$jsonData[] = $entry;
					}
				}
				$this->view->data = json_encode($jsonData);
	}
	*/
	
	public function autocompletarAction() 
	{
	
		$cn = new Model_DbDatos_Datos();
    	$ar = new Libreria_ArraysFunctions();
		
		$variable = rtrim(ltrim($this->_request->getParam('name_startsWith','')));
    	$arraydatos[] = array('@msquery',3);
		$arraydatos[] = array('@variable',$variable);
		$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Costas', $arraydatos);
		
		if(count($rows)){
			$jsonData = array('total'=>count($rows),'rows'=>array());
				foreach($rows AS $row){
					$entry = array(
						'id' => utf8_encode($row[0]),				  
						'name' => trim($row[0])." | ".trim($row[1])." | ".trim($row[2])." | ".$row[4],
						'measure' => utf8_encode($row[2])
					);
					$jsonData['rows'][] = $entry;
			}
		}
				
		$this->view->data=json_encode($jsonData);
	}
	
	public function gcostasAction()
	{
    	
    	$login = new Zend_Session_Namespace('login');
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
				
			$cn = new Model_DbDatos_Datos();		
			$idredcibo = trim($this->_request->getPost('txtidrecibo'));
			
			if(strlen($idredcibo)>0)
				$tip = 3;
			else
				$tip = 1;
			
			unset($parametros);					
			$parametros[] = array('@msquery',$tip);
			$parametros[] = array('@idrecibo',$idredcibo);
			$parametros[] = array('@codigo',trim($this->_request->getPost('txtcodigo')));	
			$parametros[] = array('@anno',date('Y'));
			$parametros[] = array('@tipo',trim($this->_request->getPost('txttipo')));	
			$parametros[] = array('@tipo_rec',trim($this->_request->getPost('txtsubtipo')));
			$parametros[] = array('@imp_insol',trim($this->_request->getPost('txtprecio')));
			$parametros[] = array('@fact_reaj',trim($this->_request->getPost('txtcantidad')));
			$parametros[] = array('@imp_reaj',trim($this->_request->getPost('txttotal')));				
			$parametros[] = array('@num_docu',trim($this->_request->getPost('txtexpediente')));
			$rows = $cn->ejec_store_procedura_sql('Rentas.sp_CajaRecibos', $parametros);
			
			
			
			echo "Se grabo correctamente";
				
		}
    }
	
/*
	public function gcostasAction()
	{
    	
    	$login = new Zend_Session_Namespace('login');
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
						
			// /*********DATOS COSTAS**************
		
			$txtcodigo=strtoupper(trim($data->txtcodigo));
			$txttipo=strtoupper(trim($data->txttipo));
			$txtsubtipo=strtoupper(trim($data->txtsubtipo));
			$txtprecio=strtoupper(trim($data->txtprecio));
			$txtcantidad=strtoupper(trim($data->txtcantidad));
			$txttotal=strtoupper(trim($data->txttotal));
			$txtexpediente=strtoupper(trim($data->txtexpediente));
		
		
				
			unset($parametros);
			
			$parametros[] = array('@msquery',1);
			$parametros[] = array('@codigo',$txtcodigo);	
			$parametros[] = array('@anno',date('Y'));
			$parametros[] = array('@tipo',$txttipo);	
			$parametros[] = array('@tipo_rec',$txtsubtipo);
			$parametros[] = array('@imp_insol',$txtprecio);
			$parametros[] = array('@fact_reaj',$txtcantidad);
			$parametros[] = array('@imp_reaj',$txttotal);					
			$parametros[] = array('@num_docu',$txtexpediente);
			$rows = $cn->ejec_store_procedura_sql('Rentas.sp_CajaRecibos', $parametros);
				
		}
    }
*/
	
	public function eliminacostaAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		$json=$this->_request->getPost('json');		
		if($this->getRequest()->isXmlHttpRequest()){
    		
			$data = json_decode($json);
			//var_dump($data );
			foreach ($data as $row){ 
				//$id=trim($row->id); 
				
			$cn = new Model_DbDatos_Datos();
			unset($parametros);
			$parametros[] = array('@msquery',4);
			$parametros[] = array('@idrecibo',trim($row->idrecibo));			 
			$rows = $cn->ejec_store_procedura_sql('Rentas.sp_CajaRecibos', $parametros);
				
			}			
    	}
		
		
    }
	
}


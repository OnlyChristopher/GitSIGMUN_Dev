<?php

/**
 * MantbusquedaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class BandecostascontriController extends Zend_Controller_Action {
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
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyentecostascontri', $parametros);
    	
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
		
		
		$rowsBaja = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyentecostascontri', $parametros);
		
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
		$expediente = $this->_request->getParam('expediente','');
		
		$estadocosta = $this->_request->getParam('estadocosta','');
		$estadogasto = $this->_request->getParam('estadogasto','');
		
    	
    	$codigo=$path->codigo ;
    	
		$nombrestore = 'Rentas.sp_Mcontribuyentecostascontri';
        $arraydatos[]= array('@busc','8');
        $arraydatos[]= array('@codigo',$codigo);
        
        $cn = new Model_DbDatos_Datos();
        $datoglobal = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);

        if (count($datoglobal)>0){
	        $path->nombres=$datoglobal[0][1];;
	        $path->num_doc=$datoglobal[0][2];;
	        $path->direccion=$datoglobal[0][3];
	        
	        $this->view->varcodigo = $path->codigo;
	        $this->view->varnombre = $path->nombres;
	        $this->view->vardocumen = $path->num_doc;
	        $this->view->vardomicilio = $path->direccion;
        }
		
		if(!empty($expediente))
		{
			//$cad[] = array('#btnReporteliqui',"disabled","false");
			$cad[] = array('#txtexpediente',"readonly","true");
			$val[] = array('#txtexpediente',"caja","removeClass");
			$val[] = array('#txtexpediente',"cajaoff","addClass");
			
			
			//echo "seee";
		}
		
		$evt[] = array('#txtexpediente',"blur","this.value = this.value.toUpperCase();");
		//$evt[] = array('#txtexpediente',"keypress","return validaTeclas(event,'alpha');");

		$evt[] = array('#txtprecio',"keypress","return validaTeclas(event,'numeric');");
		$evt[] = array('#txtcantidad',"keypress","return validaTeclas(event,'numeric');");
		
		
		$evt[] = array('#btnGenerar',"click","generar_cuenta()");
		
		$evt[] = array('#btnReporteliqui',"click","imprimeLiquidacion()");
		
		$evt[] = array('#btnSalir',"click","closePopup('#popCostacontri');");
		
		$evt[] = array('#btnAddTupa',"click","eventPagoTupa('A');");
		$evt[] = array('#btnEditTupa',"click","eventPagoTupa('E');");
		$evt[] = array('#btnCancelTupa',"click","eventPagoTupa('C');");
		$evt[] = array('#btnSaveTupa',"click","eventPagoTupa('S');");
		$evt[] = array('#btnDelTupa',"click","eventPagoTupa('D');");

		$evt[] = array('#btnAddDeuda',"click","ingresedeuda()");
		
		$val[] = array('#txtcodigo2',$datoglobal[0][0],'val');
		$val[] = array('#txtexpediente',$expediente,'val');
		
		$val[] = array('#xestadocosta',$estadocosta,'val');
		$val[] = array('#xestadogasto',$estadogasto,'val');
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		if(count($cad)>0){
			$fn->AtributoComponente($cad);
		}
    }
	
	 public function gridcostasAction()
	 {
			$codigo = $this->_request->getPost('codigo');
			$expediente = $this->_request->getPost('expediente');
			
			$cn = new Model_DbDatos_Datos();
			$parametros[] = array('@msquery',2);
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@num_docu',$expediente);
			$rows = $cn->ejec_store_procedura_sql('Coactivo.sp_MCostas', $parametros);
			
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'conceptos'=>trim($row[28]),//nombre
							'monto'=>trim($row[12]),//imp_reaj
							'cantidad'=>trim($row[13]),//fact_reaj
							'uit1'=>utf8_encode($row[14]),//imp_insol
							'tipo'=>trim($row[9]),//tipo
							'subtipo'=>trim($row[10]),//tipo_rec
							'idcosta'=>trim($row[0]),//idrecibo
							'idrecibo'=>trim($row[1])//costo emision
						);
						$jsonData[] = $entry;
					}
				}
				$this->view->data = json_encode($jsonData);
  }
	
	
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
				
			$idcosta = trim($this->_request->getPost('txtidcosta'));
			$idrecibo = trim($this->_request->getPost('xidrecibo'));
			$numexpediente = trim($this->_request->getPost('txtexpediente'));
			
			if(strlen($idcosta)>0)
				$tip = 3;
			else
				$tip = 1;
			
			unset($parametros);					
			$parametros[] = array('@msquery',$tip);
			$parametros[] = array('@idcosta',$idcosta);
			$parametros[] = array('@idrecibo',$idrecibo);
			$parametros[] = array('@num_docu',$numexpediente);
			$parametros[] = array('@codigo',trim($this->_request->getPost('txtcodigo2')));	
			$parametros[] = array('@anno',date('Y'));
			$parametros[] = array('@tipo',trim($this->_request->getPost('txttipo')));	
			$parametros[] = array('@tipo_rec',trim($this->_request->getPost('txtsubtipo')));
			$parametros[] = array('@imp_insol',trim($this->_request->getPost('txttotal')));
			$parametros[] = array('@fact_reaj',trim($this->_request->getPost('txtcantidad')));
			$parametros[] = array('@imp_reaj',trim($this->_request->getPost('txtprecio')));		
			$rows = $cn->ejec_store_procedura_sql('Coactivo.sp_MCostas', $parametros);
			
			
			
			echo $rows[0][0]."|Se grabo correctamente";
				
		}
    }
	
	public function gcostasdeudaAction()
	{
    	
    	$login = new Zend_Session_Namespace('login');
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
				
			$cn = new Model_DbDatos_Datos();
			
			$idredcibo = trim($this->_request->getPost('txtdeudaidrecibo'));		
			
			if(strlen($idredcibo)>0)
				$tip = 3;
			else
				$tip = 1;
			
			
			//$tip = 1;
			
			unset($parametros);					
			$parametros[] = array('@msquery',$tip);
			$parametros[] = array('@codigo',trim($this->_request->getPost('txtdeudacodigo')));	
			$parametros[] = array('@anno',date('Y'));
			$parametros[] = array('@tipo',trim($this->_request->getPost('txtdeudatipo')));	
			$parametros[] = array('@tipo_rec',trim($this->_request->getPost('txtdeudasubtipo')));
			$parametros[] = array('@imp_insol',0);
			$parametros[] = array('@observacion',trim($this->_request->getPost('txtdeudacalc')));			
			$parametros[] = array('@fact_reaj',trim($this->_request->getPost('txtdeudacant')));
			$parametros[] = array('@imp_reaj',trim($this->_request->getPost('txtdeudauit')));		
			$rows = $cn->ejec_store_procedura_sql('Coactivo.sp_MCostas', $parametros);
			
			
			
			echo "Se grabo correctamente";
				
		}
    }
	
	public function actdeudaAction()
	{    	
    	$login = new Zend_Session_Namespace('login');
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
				
			$cn = new Model_DbDatos_Datos();		
			
			$parametros[] = array('@msquery',3);
			$parametros[] = array('@idrecibo',trim($this->_request->getPost('idrecibo')));
			$parametros[] = array('@tipo',trim($this->_request->getPost('tipo')));	
			$parametros[] = array('@tipo_rec',trim($this->_request->getPost('tipo_rec')));
			$parametros[] = array('@imp_insol',trim($this->_request->getPost('imp_insol')));
			$parametros[] = array('@fact_reaj',trim($this->_request->getPost('fact_reaj')));
			$parametros[] = array('@imp_reaj',trim($this->_request->getPost('imp_reaj')));	
			$parametros[] = array('@observacion',trim($this->_request->getPost('observacion')));		
			$rows = $cn->ejec_store_procedura_sql('Coactivo.sp_MCostas', $parametros);						
			
			echo "Se grabo correctamente";
		}
    }
	
	
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
			$parametros[] = array('@idcosta',trim($row->idcosta));		
			//$parametros[] = array('@idrecibo',trim($row->idrecibo));		 
			$rows = $cn->ejec_store_procedura_sql('Coactivo.sp_MCostas', $parametros);
				
			}			
    	}
		
		
    }
	
	public function deudaAction()
    {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		
		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		
		$codigo = $this->_request->getParam('codigo','');
		
		if(!empty($codigo))
        {
        	$parametros[] = array('@msquery',6);
			$parametros[] = array('@codigo',$codigo);		
			$rowDeuda = $cn->ejec_store_procedura_sql('Coactivo.sp_MCostas', $parametros);
			
			$idrecibo = $rowDeuda[0][0]; 
			$observacion = $rowDeuda[0][17];
        }
		else{
			
		}
		
		$evt[] = array('#btnGrabardeuda',"click","grabardeuda()");
		$evt[] = array('#btnCerrardeuda',"click","closePopup('#popDeuda');");
		
		
		$val[] = array('#txtdeudacodigo',$codigo,'val');
		$val[] = array('#txtdeudaidrecibo',$idrecibo,'val');
		$val[] = array('#txtdeudacalc',$observacion,'val');
		
		$fn->PintarValor($val);
						
		$fn->PintarEvento($evt);
		
    }
	
	public function bandejacostasAction()
    {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		
		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		
		$codigo = $this->_request->getParam('codigo','');
		
		$nombrestore = 'Rentas.sp_Mcontribuyentecostascontri';
        $arraydatos[]= array('@busc','8');
        $arraydatos[]= array('@codigo',$codigo);
        
        $cn = new Model_DbDatos_Datos();
        $datoglobal = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
		
		$codigo=$datoglobal[0][0];
		$nombres=$datoglobal[0][1];
		$num_doc=$datoglobal[0][2];
		$direccion=$datoglobal[0][3];
		
	    $evt[] = array('#btnCerrardeuda2',"click","closePopup('#popCostabandeja');");
		
		$evt[] = array('#btnNuevodeuda',"click","consulta()");
		
		$val[] = array('#divCodigo',$codigo,'html');
		$val[] = array('#divNombre',$nombres,'html');
		$val[] = array('#divDocumen',$num_doc,'html');
		$val[] = array('#divDomicilio',$direccion,'html');
	
		
	
		
		$fn->PintarValor($val);
						
		$fn->PintarEvento($evt);
		
    }
	
	public function consultabandejaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
		$codigo = $this->_request->getParam('codigo','');
	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',9);
		$parametros[] = array('@codigo',$codigo);
		
		
		$rows = $cn->ejec_store_procedura_sql('Coactivo.sp_MCostas', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'idrecibo'=>$row[0],
						'expediente'=>$row[2],
						'monto'=>$row[3],	
						'estadocosta'=>$row[4],
						'estadogasto'=>$row[5]
				
						//'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
				);
			
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	
	public function eliminarbandejaAction()
    {		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
		
    		$cn = new Model_DbDatos_Datos();
    		
			$codigo = $this->_request->getParam('codigo','');
			$expediente = $this->_request->getParam('expediente','');
			
			$parametros[] = array('@msquery',10);
			$parametros[] = array('@codigo',$codigo);	
			$parametros[] = array('@num_docu',$expediente);				
			$rows = $cn->ejec_store_procedura_sql('Coactivo.sp_MCostas', $parametros);
			
			echo "Se elimino correctamente";
		}

	}
	
	public function generarcuentaAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
		
			$codigo = $this->_request->getPost('codigo');
			$expediente = $this->_request->getPost('expediente');
			$montogasto = $this->_request->getPost('montogasto');
			$montocosta = $this->_request->getPost('montocosta');
	/*
			$idrecibo1=0;
			$idrecibo2=0;
	*/		
			$cn = new Model_DbDatos_Datos();
			
			$nombrestore00 = 'Coactivo.sp_MCostas';
			$arraydatos00[]= array('@msquery','12');
			$arraydatos00[]= array('@codigo',$codigo);
			$arraydatos00[]= array('@num_docu',$expediente);
			$datoglobal = $cn->ejec_store_procedura_sql($nombrestore00,$arraydatos00);
			
			$nombrestore01 = 'Caja.sp_Mrecibos';
			$arraydatos01[]= array('@buscar','1');
			$arraydatos01[]= array('@codigo',$codigo);
			$arraydatos01[]= array('@num_docu',$expediente);
			$arraydatos01[]= array('@imp_insol',$montogasto);
			$arraydatos01[]= array('@imp_reaj',$montogasto);
			$arraydatos01[]= array('@idrecibo',$idrecibo1);
			$arraydatos01[]= array('@tipo','90.09');
			$arraydatos01[]= array('@tipo_rec','90.09');
			$datoglobal = $cn->ejec_store_procedura_sql($nombrestore01,$arraydatos01);
			/*
				90.00	41.45	01	108.00
				90.09	90.09	01	38.63
			*/
			$nombrestore02 = 'Caja.sp_Mrecibos';
			$arraydatos02[]= array('@buscar','1');
			$arraydatos02[]= array('@codigo',$codigo);
			$arraydatos02[]= array('@num_docu',$expediente);
			$arraydatos02[]= array('@imp_insol',$montocosta);
			$arraydatos02[]= array('@imp_reaj',$montocosta);
			$arraydatos02[]= array('@idrecibo',$idrecibo2);
			$arraydatos02[]= array('@tipo','90.00');
			$arraydatos02[]= array('@tipo_rec','41.45');
			$datoglobal = $cn->ejec_store_procedura_sql($nombrestore02,$arraydatos02);

			echo "Se genero correctamente";
		}
	}
	
}


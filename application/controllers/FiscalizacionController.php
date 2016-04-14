<?php

class FiscalizacionController extends Zend_Controller_Action
{

    public function init()
	{	
    }

    public function indexAction(){
		$fn = new Libreria_Pintar();
		$evt[] = array('#btnNuevoRequerimiento',"click","muestrarequerimiento();");
		$evt[] = array('#btnEditaRequerimiento',"click","editarequerimiento();");
		$evt[] = array('#btnBuscar',"click","buscarRequerimiento();");
		$evt[] = array('#btnVerActas',"click","detalleRequerimiento();");

		$fn->PintarEvento($evt);
    }
	
    public function muestrarequerimientoAction(){
        
		$cn = new Model_DbDatos_Datos();
        $ar = new Libreria_ArraysFunctions();
        $fn = new Libreria_Pintar();
		
		$txtaction = $this->_request->getParam('txtaction','');
		$txtidx = $this->_request->getParam('txtidx','0');
		
		$fecharow = $cn->ejec_store_procedura_sql('dbo.sp_getfecha', null);
		
		$val[] = array('#txtAction', $txtaction, 'val');
		$val[] = array('#txtIdx', $txtidx, 'val');
		$val[] = array('#txtFecha', $fecharow[0][0], 'val');
		
		$evt[] = array('#txtFecha',"datepicker","");
		$evt[] = array('#btnCancelar', "click", "closePopup('#popmuestrarequerimiento');");
		$evt[] = array('#btnBusper',"click","showPopup('mantpers/buscar','#popBusPersSol','900','400','Buscador de Personas');");
				
        unset($parametros);
        $parametros[] = array('@buscar', 2);
        $combomotivo = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_Requerimiento_Buscar', $parametros);
        $armotivo = $ar->RegistrosCombo($combomotivo, 0, 1);
        $val[] = array('#cbMotivo', $fn->ContenidoCombo($armotivo, '[Seleccione]', ''), 'html');

        unset($parametros);
        $parametros[] = array('@buscar', 3);
        $comboDocumento = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_Requerimiento_Buscar', $parametros);
        $arDocumento = $ar->RegistrosCombo($comboDocumento, 0, 1);
        $val[] = array('#cbDocumento', $fn->ContenidoCombo($arDocumento, '[Seleccione]', ''), 'html');

		if( $txtaction == 2 ){
			unset($parametros);
			$parametros[] = array('@buscar',3);
			$parametros[] = array('@idPk',$txtidx);
			
			$datosrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_ManteMRequerimiento', $parametros);
			//print_r($datosrq);
			$val[] = array('#txtNumero', $datosrq[0][3], 'val');
			$val[] = array('#txtAnio', $datosrq[0][2], 'val');
			$val[] = array('#cbDocumento', $datosrq[0][1], 'val');
			$val[] = array('#cbMotivo', $datosrq[0][6], 'val');
			$val[] = array('#txtFecha', $datosrq[0][4], 'val');
			$val[] = array('#txtCodigo', $datosrq[0][7], 'val');
			$val[] = array('#txtContribuyente', $datosrq[0][8], 'val');
			$val[] = array('#txtObservaciones', $datosrq[0][10], 'val');
		}
        $fn->PintarEvento($evt);
        $fn->PintarValor($val);
    }
    
    public function grabarequerimientoAction(){
		
		$fn = new Libreria_Pintar();
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){
			$buscar 	= (int)trim($this->_request->getPost('txtAction'));
			$idPk		= (int)trim($this->_request->getPost('txtIdx'));
			$tipo_rq 	= trim($this->_request->getPost('cbDocumento'));
			$anio_rq 	= trim($this->_request->getPost('txtAnio'));
			$nros_rq 	= trim($this->_request->getPost('txtNumero'));
			$fech_rq 	= trim($this->_request->getPost('txtFecha'));
			$idmotivo 	= trim($this->_request->getPost('cbMotivo'));
			$codigo		= trim($this->_request->getPost('txtCodigo'));
			$observ		= trim($this->_request->getPost('txtObservaciones'));	
			
			$cn = new Model_DbDatos_Datos();			
			$parametros[] = array('@buscar',$buscar);
			$parametros[] = array('@idPk',$idPk);
			$parametros[] = array('@tipo_rq',$tipo_rq);								
			$parametros[] = array('@anio_rq',$anio_rq);
			$parametros[] = array('@nros_rq',$nros_rq);
			$parametros[] = array('@fech_rq',$fech_rq);
			$parametros[] = array('@idmotivo',$idmotivo);
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@observ',$observ);
			
			//@idPk,@tipo_rq,@anio_rq,@nros_rq,@fech_rq,@idDocum,@idmotivo,@codigo,@nombre,@domfis,@observ
			
			$rowsrq = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_ManteMRequerimiento', $parametros);
			
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
			
		}else{
		     echo "Error: No se pudo realizar la accion";
		     exit;
		}
		
	}
	
	public function buscarrequerimientoAction(){
		
    	$cn = new Model_DbDatos_Datos();
		$login = new Zend_Session_Namespace('login');
		
		$cmbtipos = $this->_request->getParam('cmbtipos','');
		$txtdatos = $this->_request->getParam('txtdatos','');

    	$parametros[] = array('@buscar',$cmbtipos);
		$parametros[] = array('@nombre',$txtdatos);
		
    	    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_Requerimiento_Buscar', $parametros);
    	
    	$jsonData = array('rows'=>array());
    	foreach($rowRuta as $row){
			$entry = array(
					'idPk'=>$row[0],
					'codigo'=>$row[7],
					'nombre'=>utf8_encode($row[8]),
					'documento'=>utf8_encode($row[12]),
					'fecha'=>$row[4],
					'motivo'=>utf8_encode($row[13]),
			);
			$jsonData['rows'][] = $entry;
		}
		$this->view->data = json_encode($jsonData);	
	
    }
	
	public function officeAction(){
	}
}

?>
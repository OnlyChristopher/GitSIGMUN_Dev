<?php

class CedulonController extends Zend_Controller_Action
{ 	
	//METODOS DEL FORMULARIO PRINCIPAL
    public function formuAction(){	
    	$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();

    	$codCarta = $this->_request->getParam('codCarta','');    
		
		//Combo TIPOS DE DOCUMENTOS
	
	   	unset($parametros);
    	$parametros[] = array('@mquery',17);    	
		$rowDocumentos = $cn->ejec_store_procedura_sql('[SP_FISCA_CEDULON]',$parametros);

		$documentosCombo = $ar->RegistrosComboc($rowDocumentos,0,1,'');
		$val[] = array('#cbTipoDoc1',$fn->ContenidoCombo($documentosCombo,'[Seleccione]',''),'html');
		$val[] = array('#cbTipoDoc2',$fn->ContenidoCombo($documentosCombo,'[Seleccione]',''),'html');   	
		$val[] = array('#cbTipoDoc3',$fn->ContenidoCombo($documentosCombo,'[Seleccione]',''),'html');   	

		unset($parametros);
		$parametros[] = array('@mquery',6);
		$parametros[] = array('@idCarta',$codCarta);
		$rowCEDULON = $cn->ejec_store_procedura_sql('[SP_FISCA_CEDULON]',$parametros);
		
		

		if ($rowCEDULON[0]) {
			
			$row = $rowCEDULON[0];
		
			$codCedulon = $row[0];
			$nroCedulon = $row[1]." - ".$row[2];
			
			$horaNotifica = $row[3];
			$fechaNotifica = (!empty($row[4]) && !empty($row[5]) && !empty($row[6])) ? $row[4]."/".$row[5]."/".$row[6] : "";
			$codContrib = $row[7];
			$nomContrib = trim( utf8_encode(strtoupper($row[9]." ".$row[10]." ".$row[8])));
			$personaIncapaz = $row[11];
			$domicilioCerrado = $row[12];
			$fechaV1 = (!empty($row[13]) && !empty($row[14]) && !empty($row[15])) ? $row[13]."/".$row[14]."/".$row[15] : "";
			$firmaV1 = $row[16];
			$fechaV2 = (!empty($row[17]) && !empty($row[18]) && !empty($row[19])) ? $row[17]."/".$row[18]."/".$row[19] : "";
			$firmaV2 = $row[20];
			$tempNroActaVisita = $row[21];
			$tempNroCargoNotifica = $row[22];
			$tempNroCarta = $row[23];

			$val[] = array('#hcodCartaReq',$codCarta,'val');
			$val[] = array('#hcodCedulon',$codCedulon,'val');
			$val[] = array('#txtNroCedulon',$nroCedulon,'val');

			$this->view->nroCedulon = $nroCedulon;
			$this->view->codContrib = $codContrib;
			$this->view->contribuyente = $nomContrib;

			$val[] = array('#txtHoraC',$horaNotifica,'val');
			$val[] = array('#dpFechaC',$fechaNotifica,'val');
			if($personaIncapaz==1){ $evt[] = array("#chkPersonaIncapaz","checked","true"); }
			if($domicilioCerrado==1){ $evt[] = array("#chkDomicilioCerrado","checked","true"); }
			$val[] = array('#dpFechaV1',$fechaV1,'val');
			$evt[] = array(( ($firmaV1==1) ? "#rdFirmaV1Si" : "#rdFirmaV1No"),"checked","true");
			$val[] = array('#dpFechaV2',$fechaV2,'val');
			$evt[] = array(( ($firmaV2==1) ? "#rdFirmaV2Si" : "#rdFirmaV2No"),"checked","true");
			$val[] = array('#tempNroActaVisita',$tempNroActaVisita,'val');
			$val[] = array('#tempNroCargoNotifica',$tempNroCargoNotifica,'val');
			$val[] = array('#tempNroCarta',$tempNroCarta,'val');
			
			//DETALLE DE CEDULON
			unset($parametros);
			$parametros[] = array('@mquery',18);
			$parametros[] = array('@idCedulon',$codCedulon);
			$rowDETALLECEDULON = $cn->ejec_store_procedura_sql('[SP_FISCA_CEDULON]',$parametros);
			
			if ($rowDETALLECEDULON[0]) {
				
				$cant = count($rowDETALLECEDULON[0]);
				for ($i=0; $i <$cant ; $i++) { 
					$row = $rowDETALLECEDULON[1][$i];
					$idDetalle = $row[0];
					$idTipoDoc = $row[1];
					$nroDoc = $row[2];

					$val[] = array('#cbTipoDoc'.($i+1),$idTipoDoc,'val');
					$val[] = array('#txtNroDoc'.($i+1),$nroDoc,'val');
				}

			}else{
				echo utf8_encode($rowDETALLECEDULON[1]);
			}

		}else{
			echo utf8_encode($rowCEDULON[1]);
		}

		$evt[] = array('#contentBox5',"tabs","");				
		$evt[] = array('#tabsCedulon',"tabs","");
		$evt[] = array('#radioFirmaV1',"buttonset","");
		$evt[] = array('#radioFirmaV2',"buttonset","");
		

		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }
 

  	public function grabarAction(){
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    

    	 if ($this->getRequest()->isPost()) {
    	 
    	 	$codCedulon = $this->_request->getPost('hcodCedulon');
    	 	$fechaNotifica = $this->_request->getPost('dpFechaC');
    	 	$horaNotifica = $this->_request->getPost('txtHoraC');    	 
			$PersonaIncapaz = (isset($_REQUEST['chkPersonaIncapaz'])) ? 1 : 0 ;
			$DomicilioCerrado = (isset($_REQUEST['chkDomicilioCerrado'])) ? 1 : 0 ;
			$fecPrimeraVisita = $this->_request->getParam('dpFechaV1','');
			$firmaPrimera = $this->_request->getParam('rdFirmaV1','');
			$fecSegundaVisita = $this->_request->getParam('dpFechaV2','');
			$firmaSegunda = $this->_request->getParam('rdFirmaV2','');

			$idTipo1 = $this->_request->getParam("cbTipoDoc1",'');
			$nroDocumento1 = $this->_request->getParam("txtNroDoc1",'');
			$idTipo2 = $this->_request->getParam("cbTipoDoc2",'');
			$nroDocumento2 = $this->_request->getParam("txtNroDoc2",'');
			$idTipo3 = $this->_request->getParam("cbTipoDoc3",'');
			$nroDocumento3 = $this->_request->getParam("txtNroDoc3",'');

			$cn = new Model_DbDatos_Datos();
			unset($parametros);
			
			$parametros[] = array('@mquery',2);
			$parametros[] = array('@idCedulon',$codCedulon);
			$parametros[] = array('@horaNotifica',$horaNotifica);
			$parametros[] = array('@fecNotifica',$fechaNotifica);
			$parametros[] = array('@PersonaIncapaz',$PersonaIncapaz);
			$parametros[] = array('@DomicilioCerrado',$DomicilioCerrado);
			$parametros[] = array('@fecPrimeraVisita',$fecPrimeraVisita);
			$parametros[] = array('@firmaPrimera',$firmaPrimera);
			$parametros[] = array('@fecSegundaVisita',$fecSegundaVisita);
			$parametros[] = array('@firmaSegunda',$firmaSegunda);

			$parametros[] = array('@idTipo1',$idTipo1);
			$parametros[] = array('@nroDocumento1',$nroDocumento1);
			$parametros[] = array('@idTipo2',$idTipo2);
			$parametros[] = array('@nroDocumento2',$nroDocumento2);
			$parametros[] = array('@idTipo3',$idTipo3);
			$parametros[] = array('@nroDocumento3',$nroDocumento3);

			$rowCEDULON = $cn->ejec_store_procedura_sql('[SP_FISCA_CEDULON]',$parametros);
			echo $rowCEDULON[0][0];

    	 }

	}
	
}

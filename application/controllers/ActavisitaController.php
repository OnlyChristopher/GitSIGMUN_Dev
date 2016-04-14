<?php

class ActavisitaController extends Zend_Controller_Action
{ 	
	//METODOS DEL FORMULARIO PRINCIPAL
    public function formuAction(){	
    	$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();

    	
    	$codCarta = $this->_request->getParam('codCarta','');    

    	//Combo FISCALIZADORES de INICIO y TERMINO DE INSPECCION
		unset($parametros);
    	$parametros[] = array('@mquery',21);
		$rowFicalizadores = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTA_REQ]',$parametros);

		$fiscalizadoresCombo = $ar->RegistrosComboc($rowFicalizadores,0,1,'');
		$val[] = array('#cbInspectorII',$fn->ContenidoCombo($fiscalizadoresCombo,'[Seleccione]',''),'html');
		$val[] = array('#cbInspectorTI',$fn->ContenidoCombo($fiscalizadoresCombo,'[Seleccione]',''),'html');
    	
    	//SETEANDO VALORES DE ACTA VISITA
		unset($parametros);
		$parametros[] = array('@mquery',6);
		$parametros[] = array('@idCarta',$codCarta);
		$rowACTAVISITA = $cn->ejec_store_procedura_sql('[SP_FISCA_ACTA_VISITA]',$parametros);

		if ($rowACTAVISITA[0]) {
			$row = $rowACTAVISITA[0];
			
			$codActaVisita = $row[0];
			$nroActaVisita = $row[1]."-".$row[2];
			
			$referenciaNroCarta = $row[3]."-".$row[2];
			$codContrib = $row[4];
			$nomContrib = trim(utf8_encode(strtoupper($row[6]." ".$row[7]." ".$row[5])));
			$fechaInspec = (!empty($row[8]) && !empty($row[9]) && !empty($row[10])) ? $row[8]."/".$row[9]."/".$row[10] : "";
			$horaInspec = (!empty($row[11])) ? $row[11] : "";
			$srosra = utf8_encode($row[12]);
			$observa = utf8_encode($row[13]);
			$iicodInspector = $row[14];
			$iifirmaInspector = $row[15];
			$iifirmaContrib = $row[16];
			$fechaCulmina = (!empty($row[17]) && !empty($row[18]) && !empty($row[19])) ? $row[17]."/".$row[18]."/".$row[19] : "";
			$horaCulmima = $row[20];
			$chk1 = $row[21];
			$chk2 = $row[22];
			$chk3 = $row[23];
			$chk4 = $row[24];
			$chk5 = $row[25];
			$chk6 = $row[26];
			$chk7 = $row[27];
			$chk8 = $row[28];
			$chk9 = $row[29];
			$chk10 = $row[30];
			$otrosDoc = $row[31];
			$ticodInspector = $row[32];
			$tifirmaInspector = $row[33];
			$tifirmaContrib = $row[34];
			
			$dirPrediosTodos = utf8_encode($row[35]);


			$val[] = array('#hcodCartaReq',$codCarta,'val');
			$val[] = array('#hcodActaVisita',$codActaVisita,'val');

			$this->view->nroActaVisita=$nroActaVisita;
			$this->view->codContrib = $codContrib;
			$this->view->contribuyente = $nomContrib;
			$this->view->nroCartaReq = $referenciaNroCarta;
			
			$val[] = array('#dpFechaII',$fechaInspec,'val');
			$val[] = array('#txtHoraII',$horaInspec,'val');
			$val[] = array('#txtRecibe',$srosra,'val');
			$val[] = array('#txaObservaciones',$observa,'val');
			$val[] = array('#cbInspectorII',$iicodInspector,'val');
			$evt[] = array(( ($iifirmaInspector==1) ? "#rdIIFirmaInspecSi" : "#rdIIFirmaInspecNo"),"checked","true");
			$evt[] = array(( ($iifirmaContrib==1) ? "#rdIIFirmaContribSi" : "#rdIIFirmaContribNo"),"checked","true");
			$val[] = array('#dpFechaTI',$fechaCulmina,'val');
			$val[] = array('#txtHoraTI',$horaCulmima,'val');
			if($chk1==1){ $evt[] = array("#chk1","checked","true"); }
			if($chk2==1){ $evt[] = array("#chk2","checked","true"); }
			if($chk3==1){ $evt[] = array("#chk3","checked","true"); }
			if($chk4==1){ $evt[] = array("#chk4","checked","true"); }
			if($chk5==1){ $evt[] = array("#chk5","checked","true"); }
			if($chk6==1){ $evt[] = array("#chk6","checked","true"); }
			if($chk7==1){ $evt[] = array("#chk7","checked","true"); }
			if($chk8==1){ $evt[] = array("#chk8","checked","true"); }
			if($chk9==1){ $evt[] = array("#chk9","checked","true"); }
			if($chk10==1){ $evt[] = array("#chk10","checked","true"); }
			$val[] = array('#txtotrosDoc',$otrosDoc,'val');
			$val[] = array('#cbInspectorTI',$ticodInspector,'val');
			$evt[] = array(( ($tifirmaInspector==1) ? "#rdTIFirmaInspecSi" : "#rdTIFirmaInspecNo"),"checked","true");
			$evt[] = array(( ($tifirmaContrib==1) ? "#rdTIFirmaContribSi" : "#rdTIFirmaContribNo"),"checked","true");
			$val[] = array('#txaPredios',$dirPrediosTodos,'val');


		}else{
			echo utf8_encode($rowACTAVISITA[1]);
		}

    	$evt[] = array('#contentBox4',"tabs","");				
		$evt[] = array('#tabsActaVisita',"tabs","");
		$evt[] = array('#radioIIFirmaContrib',"buttonset","");	
		$evt[] = array('#radioIIFirmaInspec',"buttonset","");	
		$evt[] = array('#radioTIFirmaContrib',"buttonset","");	
		$evt[] = array('#radioTIFirmaInspec',"buttonset","");	

		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		
    }
 

  public function grabarAction(){
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if ($this->getRequest()->isPost()){
			
			$codActaVisita = $this->_request->getPost('hcodActaVisita');
			$fecInspec = $this->_request->getPost('dpFechaII');
			$horaInspec = $this->_request->getPost('txtHoraII');
			$recibesrosra = $this->_request->getPost('txtRecibe');
			$observa = $this->_request->getPost('txaObservaciones');
			$iicodInspector = $this->_request->getPost('cbInspectorII');
			$iifirmaInspector = $this->_request->getPost('rdIIFirmaInspec');
			$iifirmaContrib = $this->_request->getPost('rdIIFirmaContrib');
			$fechaCulmina = $this->_request->getPost('dpFechaTI');
			$horaCulmima = $this->_request->getPost('txtHoraTI');
			
			$chk1 = (isset($_REQUEST['chk1'])) ? 1 : 0 ;
			$chk2 = (isset($_REQUEST['chk2'])) ? 1 : 0 ;
			$chk3 = (isset($_REQUEST['chk3'])) ? 1 : 0 ;
			$chk4 = (isset($_REQUEST['chk4'])) ? 1 : 0 ;
			$chk5 = (isset($_REQUEST['chk5'])) ? 1 : 0 ;
			$chk6 = (isset($_REQUEST['chk6'])) ? 1 : 0 ;
			$chk7 = (isset($_REQUEST['chk7'])) ? 1 : 0 ;
			$chk8 = (isset($_REQUEST['chk8'])) ? 1 : 0 ;
			$chk9 = (isset($_REQUEST['chk9'])) ? 1 : 0 ;
			$chk10 = (isset($_REQUEST['chk10'])) ? 1 : 0 ;
			
			$otrosDoc = $this->_request->getPost('txtotrosDoc');
			$ticodInspector = $this->_request->getPost('cbInspectorTI');
			$tifirmaInspector = $this->_request->getPost('rdTIFirmaInspec');
			$tifirmaContrib = $this->_request->getPost('rdTIFirmaContrib');
	
				$cn = new Model_DbDatos_Datos();
				unset($parametros);

				$parametros[] = array('@mquery',2);
				$parametros[] = array('@idActaVisita',$codActaVisita);
				$parametros[] = array('@ii_fechaInspeccion',$fecInspec);
				$parametros[] = array('@ii_horaInspeccion',$horaInspec);
				$parametros[] = array('@ii_SroSra',$recibesrosra);
				$parametros[] = array('@ii_observa',$observa);
				$parametros[] = array('@ii_idInspector',$iicodInspector);
				$parametros[] = array('@ii_FirmaInspector',$iifirmaInspector);
				$parametros[] = array('@ii_FirmaContrib',$iifirmaContrib);		
				$parametros[] = array('@ti_horaCulmina',$horaCulmima);
				$parametros[] = array('@ti_fecCulmina',$fechaCulmina);

				$parametros[] = array('@ti_FRegTituloMinuta',$chk1);
				$parametros[] = array('@ti_PDefunSucIntDecl',$chk2);
				$parametros[] = array('@ti_TesPartDivAcumu',$chk3);
				$parametros[] = array('@ti_DeclFabIndepReg',$chk4);
				$parametros[] = array('@ti_LicConConfObra',$chk5);
				$parametros[] = array('@ti_Planos',$chk6);
				$parametros[] = array('@ti_LicFuncAnunRUC',$chk7);
				$parametros[] = array('@ti_CertiNumeracion',$chk8);
				$parametros[] = array('@ti_CPoderFLegal',$chk9);
				$parametros[] = array('@ti_otros',$chk10);
				$parametros[] = array('@ti_OtrosDoc',$otrosDoc);

				$parametros[] = array('@ti_idInspector',$ticodInspector);
				$parametros[] = array('@ti_FirmaInspector',$tifirmaInspector);
				$parametros[] = array('@ti_FirmaContrib',$tifirmaContrib);
				
				$rowACTAVISITA = $cn->ejec_store_procedura_sql('[SP_FISCA_ACTA_VISITA]',$parametros);
			
				echo $rowACTAVISITA[0][0];		

	  	}	

	}
	
}

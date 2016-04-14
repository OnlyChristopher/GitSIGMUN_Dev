<?php

class CargonotificaController extends Zend_Controller_Action
{ 	
	//METODOS DEL FORMULARIO PRINCIPAL
    public function formuAction(){	
    	$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();

    	$codCarta = $this->_request->getParam('codCarta','');    
		
		
		//Cargando combos fiscalizadores

		unset($parametros);
    	$parametros[] = array('@mquery',22);
    	$parametros[] = array('@idCarta',$codCarta);
		$rowFicalizadores = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTA_REQ]',$parametros);

		$fiscalizadoresCombo = $ar->RegistrosComboc($rowFicalizadores,0,1,'');
		$val[] = array('#cbNotificadores',$fn->ContenidoCombo($fiscalizadoresCombo,'[Seleccione]',''),'html');
		    	

		//Cargando cabecera del cargo de notificacion		
		unset($parametros);
		$parametros[] = array('@mquery',20);
    	$parametros[] = array('@idCarta',$codCarta);
    	$rowCabecera = $cn->ejec_store_procedura_sql_noparamatro2('[SP_FISCA_CARGO_NOTIFICA]',$parametros);

    	if ($rowCabecera[0]) {
    		$row = $rowCabecera[1][0];  

    		$codCargoNotificacion = $row[0];
    		$nroCargoNotificacion = $row[1]." - ".$row[9];
    		$codContrib = $row[2];
    		$contribuyente = trim(utf8_encode( strtoupper($row[3]." ".$row[4]." ".$row[5])));
    		$idTipoDocIdent = $row[6];
    		$nroDocIdent = $row[7];
    		$domicilioFiscal = strtoupper(utf8_encode( $row[8]));
    		$anio = $row[9];
    		$nroCartareq = $row[10]." - ".$row[9];
    	
    		$val[] = array('#hcodCartaReq',$codCarta,'val');
    		$val[] = array('#txtNroNotificacion',$nroCargoNotificacion,'val');
    		$val[] = array('#hcodNotificacion',$codCargoNotificacion,'val');
    		$val[] = array('#txtCodigoContribuyenteN',$codContrib,'val');
    		$val[] = array('#txtContribuyenteN',$contribuyente,'val');
    		$val[] = array('#hTipoDocIdent',$idTipoDocIdent,'val');
    		$val[] = array('#hNroDocIdent',$nroDocIdent,'val');
    		$val[] = array('#txtDomicilioFiscal',$domicilioFiscal,'val');
    		$val[] = array('#txtAnioN',$anio,'val');
    		$val[] = array('#txtNroCartaReqN',$nroCartareq,'val');

    		//CARGANDO DATOS DE LA PERSONA QUIEN RECEPCIONA
    		unset($parametros);
			$parametros[] = array('@mquery',21);
	    	$parametros[] = array('@idCargoNotFisca',$codCargoNotificacion);
	    	$rowDatosPersona = $cn->ejec_store_procedura_sql_noparamatro2('[SP_FISCA_CARGO_NOTIFICA]',$parametros);
			

	    	if ($rowDatosPersona[0]) {
	    		
	    		$row = $rowDatosPersona[1][0];

			    unset($parametros);
				$parametros[] = array('@mquery',7);
		    	$rowTDoc = $cn->ejec_store_procedura_sql_noparamatro2('[SP_FISCA_T_DOC_IDEN]',$parametros);
		    	$row2 = $rowTDoc[1];
		    	
		    	foreach ($row2 as $f) {
		    		if ($f[0]==1) {	$val[]= array('#hmaxDni',$f[2],'val');}
		    		if ($f[0]==5) {	$val[]= array('#hmaxIdentidad',$f[2],'val');}
		    		if ($f[0]==9) {	$val[]= array('#hmaxExtranjeria',$f[2],'val');}
		    	}

	    		$nomRecepciona = trim(utf8_encode($row[0]));
	    		$idTipoDocIdent = trim($row[1]);
	    		$nomTDocIden = trim(utf8_encode($row[2]));
	    		$nroDocIdent = trim($row[3]);
	    		$fechaNotifica = (!empty($row[4]) && !empty($row[5]) && !empty($row[6]))? $row[4]."/".$row[5]."/".$row[6] : '';
	    		$horaNotifica = trim($row[7]);
	    		$NegoIdentificar = trim($row[8]);
	    		$NegoFirmar = $row[9];
	    		$NegoRecibir = $row[10];
	    		$firma = $row[11];
	    		$idVinculo = $row[12];
	    		$vinculo = $row[13];
	    		$vinculo_otros = trim($row[14]);

	    		$val[] = array('#txtNomApeRecepciona',$nomRecepciona,'val');
	    		if($idTipoDocIdent==1){$evt[] = array('#chkDni','checked','true');}
	    		if($idTipoDocIdent==5){$evt[] = array('#chkIdentidad','checked','true');}
	    		if($idTipoDocIdent==9){$evt[] = array('#chkExtranjeria','checked','true');}
	    		$val[] = array('#txtNroDocIdent',$nroDocIdent,'val');
	    		$val[] = array('#dpFechaNotifica',$fechaNotifica,'val');
	    		$val[] = array('#txtHoraNotifica',$horaNotifica,'val');
	    		if($NegoIdentificar==1){$evt[] = array('#chkNegoIdentificar','checked','true');}
	    		if($NegoFirmar==1){$evt[] = array('#chkNegoFirmar','checked','true');}
	    		if($NegoRecibir==1){$evt[] = array('#chkNegoRecibir','checked','true');}
	    		$evt[] = array(( ($firma==1) ? "#rdFirmaRecepcionaSi" : "#rdFirmaRecepcionaNo"),"checked","true");
	    		if($idVinculo==1){$evt[] = array('#chkTitular','checked','true');}
	    		if($idVinculo==2){$evt[] = array('#chkFamiliar','checked','true');}
	    		if($idVinculo==3){$evt[] = array('#chkVigilante','checked','true');}
	    		if($idVinculo==4){$evt[] = array('#chkEmpleado','checked','true');}
	    		if($idVinculo==5){$evt[] = array('#chkRepresentante','checked','true');}
	    		if($idVinculo==6){$evt[] = array('#chkVinculoOtros','checked','true');$val[] = array('#txtVinculoOtros',$vinculo_otros,'val');}


	    		//CARGANDO DATOS DEL CEDULON Y MOTIVOS DE NO ENTREGA

	    		unset($parametros);
				$parametros[] = array('@mquery',22);
		    	$parametros[] = array('@idCargoNotFisca',$codCargoNotificacion);
		    	$rowCedulonMovNoEntrega = $cn->ejec_store_procedura_sql_noparamatro2('[SP_FISCA_CARGO_NOTIFICA]',$parametros);
		    	
		    	if ($rowCedulonMovNoEntrega[0]) {
		    		$row = $rowCedulonMovNoEntrega[1][0]; 

		    		$codCedulon = trim($row[0]);
		    		$PersonaIncapaz = trim($row[1]);
		    		$DomicilioCerrado = trim($row[2]);
		    		$fechaCedulon = (!empty($row[3]) && !empty($row[4]) && !empty($row[5])) ? $row[3]."/".$row[4]."/".$row[5] : '' ;
		    		$horaCedulon = trim($row[6]);
		    		$direcccionIncorrecta = trim($row[7]);
		    		$direccionInexistente = trim($row[8]);
		    		$otrosMotNoEntrega = $row[9];
		    		$otrosValor = trim($row[10]);
		    		$codInmueble = trim($row[11]);
		    		$nroPisos = trim($row[12]);
		    		$color = trim($row[13]);
		    		$casa = trim($row[14]);
		    		$edificio = trim($row[15]);
		    		$PuertaMadera = trim($row[16]);
		    		$PuertaFierro = trim($row[17]);
		    		$SuminElect = trim($row[18]);
		    		$in_Otros = trim($row[19]);
		    		$in_OtrosValor = trim($row[20]);

		    		$val[] = array('#hcodCedulon',$codCedulon,'val');
		    		if($PersonaIncapaz==1){ $evt[] = array('#chkPersonaIncapaz','checked','true');}
		    		if($DomicilioCerrado==1){ $evt[] = array('#chkDomicilioCerrado','checked','true');}
		    		$val[] = array('#dpFechaCedulon',$fechaCedulon,'val');
		    		$val[] = array('#txtHoraCedulon',$horaCedulon,'val');
		    		if($direcccionIncorrecta==1){ $evt[] = array('#chkDireccionIncorrecta','checked','true');}
		    		if($direccionInexistente==1){ $evt[] = array('#chkDireccionInexistente','checked','true');}
		    		if($otrosMotNoEntrega==1){$evt[]=array('#chkMotNoEntregaOtros','checked','true');$val[] = array('#txtNoEntregaOtros',$otrosValor,'val');}
		    		
		    		$val[] = array('#hcodInmueble',$codInmueble,'val');
		    		$val[] = array('#txtNroPisos',$nroPisos,'val');
		    		$val[] = array('#txtColor',$color,'val');
		    		if($casa==1){ $evt[] = array('#chkCasa','checked','true');}
		    		if($edificio==1){ $evt[] = array('#chkEdificio','checked','true');}
		    		if($PuertaMadera==1){ $evt[] = array('#chkMadera','checked','true');}
		    		if($PuertaFierro==1){ $evt[] = array('#chkFierro','checked','true');}
		    		$val[] = array('#txtSuministroElectrico',$SuminElect,'val');
		    		if($in_Otros==1){$evt[]=array('#chkInmuebleOtros','checked','true');$val[] = array('#txtInmuebleOtros',$in_OtrosValor,'val');}
		    		


		    		//CARGA DE DATOS DE VISITA EFECTUADA
		    		unset($parametros);
					$parametros[] = array('@mquery',23);
			    	$parametros[] = array('@idCargoNotFisca',$codCargoNotificacion);
			    	$rowVisitaEfectuada = $cn->ejec_store_procedura_sql_noparamatro2('[SP_FISCA_CARGO_NOTIFICA]',$parametros);
			    	
			    	if ($rowVisitaEfectuada[0]) {
			    		$row = $rowVisitaEfectuada[1][0]; 

			    		$fechaVisita = (!empty($row[0]) && !empty($row[1]) && !empty($row[2])) ? $row[0]."/".$row[1]."/".$row[2] : '' ;
			    		$codNotificador = trim($row[3]);
			    		$dniNotificador = trim($row[4]);
			    		$firmaNotificador = trim($row[5]);

			    		$val[] = array('#dpFechaVisita',$fechaVisita,'val');
			    		$val[] = array('#cbNotificadores',$codNotificador.'-'.$dniNotificador,'val');
			    		$val[] = array('#txtdniNotificador',$dniNotificador,'val');
			    		$evt[] = array((($firmaNotificador==1) ? "#rdFirmaNotSi" : "#rdFirmaNotNo"),"checked","true");


			    	}else{
			    		echo utf8_encode($rowVisitaEfectuada[1]); 
			    	}

		    	}else{
					echo utf8_encode($rowCedulonMovNoEntrega[1]); 
		    	} 

	    	}else{
	    		echo utf8_encode($rowDatosPersona[1]); 
	    	}

    	}else{
    		echo utf8_encode($rowCabecera[1]);
    	}

		$evt[] = array('#contentBox6',"tabs","");	
		$evt[] = array('#contentBox7',"tabs","");	
		$evt[] = array('#contentBox8',"tabs","");				
		$evt[] = array('#tabsNotificacion',"tabs","");
		$evt[] = array('#radioFirmaRecep',"buttonset","");
		$evt[] = array('#radioFirmaNot',"buttonset","");
		
		

		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }
 

  	public function grabarAction(){
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    
    	if ($this->getRequest()->isPost()) {
    	 	
    	//--->Datos de persona 
    	$codCargoNotificacion = $this->_request->getParam('hcodNotificacion','');
    	$nomApeRecepciona = $this->_request->getParam('txtNomApeRecepciona','');
    	$codTipoDocIdent = (isset($_REQUEST['chkDni'])) ? 1 : ( (isset($_REQUEST['chkIdentidad'])) ? 5 : ( (isset($_REQUEST['chkExtranjeria']))  ? 9 : '' )) ;
    	$nroDocIdent = $this->_request->getParam('txtNroDocIdent','');
    	$fechaNotifica = $this->_request->getParam('dpFechaNotifica','');
    	$horaNotifica = $this->_request->getParam('txtHoraNotifica','');
    	$NegoIdentificar = (isset($_REQUEST['chkNegoIdentificar'])) ? 1 : 0; 
    	$NegoFirmar = (isset($_REQUEST['chkNegoFirmar'])) ? 1 : 0 ;
    	$NegoRecibir = (isset($_REQUEST['chkNegoRecibir'])) ? 1 : 0 ;
    	$firma = $this->_request->getParam('rdFirmaRecepciona','');

    	$codVinculo='';
    	$vinculoOtros='';
    	if (isset($_REQUEST['chkTitular'])) { $codVinculo = 1;}
    	if (isset($_REQUEST['chkFamiliar'])) { $codVinculo = 2;}
    	if (isset($_REQUEST['chkVigilante'])) { $codVinculo = 3;}
    	if (isset($_REQUEST['chkEmpleado'])) { $codVinculo = 4;}
    	if (isset($_REQUEST['chkRepresentante'])) { $codVinculo = 5;}
    	if (isset($_REQUEST['chkVinculoOtros'])) { $codVinculo = 6; $vinculoOtros=$this->_request->getParam('txtVinculoOtros','');}

    	//--->Notificacion por pegado de cedulon y motivos de no entrega
    	$codCedulon = $this->_request->getParam('hcodCedulon','');
    	$PersonaIncapaz = (isset($_REQUEST['chkPersonaIncapaz'])) ? 1 : 0 ;
    	$DomicilioCerrado = (isset($_REQUEST['chkDomicilioCerrado'])) ? 1 : 0 ;
		$fechaCedulon = $this->_request->getParam('dpFechaCedulon','');
    	$horaCedulon = $this->_request->getParam('txtHoraCedulon','');
    	
    	$direccionIncorrecta = (isset($_REQUEST['chkDireccionIncorrecta'])) ? 1 : 0 ;
    	$direccionInexistente = (isset($_REQUEST['chkDireccionInexistente'])) ? 1 : 0 ;


    	$ne_Otros = 0;
    	$ne_OtrosValor='';
    	if(isset($_REQUEST['chkMotNoEntregaOtros'])){
    		$ne_Otros = 1;
    		$ne_OtrosValor = $this->_request->getParam('txtNoEntregaOtros','');
    	}

    	$codInmueble = $this->_request->getParam('hcodInmueble','');
    	$nroPisos = $this->_request->getParam('txtNroPisos','');
    	$color = $this->_request->getParam('txtColor','');
    	$casa = (isset($_REQUEST['chkCasa'])) ? 1 : 0 ;
    	$edificio = (isset($_REQUEST['chkEdificio'])) ? 1 : 0 ;
    	$puertaMadera = (isset($_REQUEST['chkMadera'])) ? 1 : 0 ;
    	$puertaFierro =(isset($_REQUEST['chkFierro'])) ? 1 : 0 ;
    	$suministroElectrico = $this->_request->getParam('txtSuministroElectrico','');
    	$in_Otros = 0;
    	$in_OtrosValor='';
    	if(isset($_REQUEST['chkInmuebleOtros'])){
    		$in_Otros = 1;
    		$in_OtrosValor = $this->_request->getParam('txtInmuebleOtros','');
    	}

    	//--->Visita efectuada
    	$fechaVisita = $this->_request->getParam('dpFechaVisita','');
    	$codNotificador = trim($this->_request->getParam('cbNotificadores',''));
    	//extraccion solo del codigo
    	$codNotificador =  (!empty($codNotificador))? (substr($codNotificador,0,(strpos($codNotificador,'-')))) : $codNotificador;
    	$dniNotificador = $this->_request->getParam('txtdniNotificador','');
    	$firmaNot = $this->_request->getParam('rdFirmaNot','');
    	
    	// ---->GUARDANDO DATOS 
  
    	$cn = new Model_DbDatos_Datos();
			unset($parametros);
			
		$parametros[] = array('@mquery',2);
		$parametros[] = array('@idCargoNotFisca',$codCargoNotificacion);
		$parametros[] = array('@nomRecepciona',$nomApeRecepciona);
		$parametros[] = array('@idTipoDocIdent',$codTipoDocIdent);
		$parametros[] = array('@nroDocIdent',$nroDocIdent);
		$parametros[] = array('@fecNotifica',$fechaNotifica);
		$parametros[] = array('@horaNotifica',$horaNotifica);
		$parametros[] = array('@NegoIdentificar',$NegoIdentificar);
		$parametros[] = array('@NegoFirmar',$NegoFirmar);
		$parametros[] = array('@NegoRecibir',$NegoRecibir);
		$parametros[] = array('@Firma',$firma);
		$parametros[] = array('@idVinculo',$codVinculo);
		$parametros[] = array('@vinculo_otros',$vinculoOtros);
		$parametros[] = array('@DirecIncorrecta',$direccionIncorrecta);
		$parametros[] = array('@DirecInexistente',$direccionInexistente);
		$parametros[] = array('@ne_otros',$ne_Otros);
		$parametros[] = array('@ne_otrosValor',$ne_OtrosValor);
		$parametros[] = array('@fecVisita',$fechaVisita);
		$parametros[] = array('@idNotificador',$codNotificador);
		$parametros[] = array('@dniNotificador',$dniNotificador);
		$parametros[] = array('@FirmaNotificador',$firmaNot);
		
		$parametros[] = array('@idCedulon',$codCedulon);
		$parametros[] = array('@horaNotificaCedulon',$horaCedulon);
		$parametros[] = array('@fecNotificaCedulon',$fechaCedulon);
		$parametros[] = array('@PersonaIncapaz',$PersonaIncapaz);
		$parametros[] = array('@DomicilioCerrado',$DomicilioCerrado);
		

		$parametros[] = array('@idInmueble',$codInmueble);
		$parametros[] = array('@nroPisos',$nroPisos);
		$parametros[] = array('@color',$color);
		$parametros[] = array('@casa',$casa);
		$parametros[] = array('@edificio',$edificio);
		$parametros[] = array('@PuertaMadera',$puertaMadera);
		$parametros[] = array('@PuertaFierro',$puertaFierro);
		$parametros[] = array('@SuminElect',$suministroElectrico);
		$parametros[] = array('@in_Otros',$in_Otros);
		$parametros[] = array('@in_OtrosValor',$in_OtrosValor);
		
		$rowNOTIFICA = $cn->ejec_store_procedura_sql('[SP_FISCA_CARGO_NOTIFICA]',$parametros);
		echo "<br>".$rowNOTIFICA[0][0];  	



    	}
	}
	
}

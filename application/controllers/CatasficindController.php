<?php

class CatasficindController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$idficha = $this->_request->getParam('idficha','');
		$idficbco = $this->_request->getParam('idficbco','');
		$periodo = $this->_request->getParam('periodo','');
		$cidpers = $this->_request->getParam('cidpers','');
		
		if(strlen($idficha)>0)
		{
			//Recupera ficha cabecera
			$parametros[] = array('@busc',4);
			$parametros[] = array('@idficha',$idficha);
			$rowFicha = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
			
			$idficha = $rowFicha[0][0];
			$cidfich = $rowFicha[0][21];
			$cidpers = $rowFicha[0][1];
			$periodo = $rowFicha[0][2];
			$idubigeo = $rowFicha[0][10].$rowFicha[0][11].$rowFicha[0][12];
			$ubgdpto = $rowFicha[0][10];
			$ubgprov = $rowFicha[0][11];
			$ubgdist = $rowFicha[0][12];
			
			//Recupera ficha individual
			unset($parametros);
			$parametros[] = array('@busc',4);
			$parametros[] = array('@cidfich',$idficha);
			$rowFicInd = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMIndiv', $parametros);
			
			$idsigma = $rowFicInd[0][0];
			
			$fun[] = array("enableButton('#btnShowTitu');");
		}
		else{
			$idficha = "";
			$cidfich = $idficbco;
			$idubigeo = "150112";
			$ubgdpto = "15";
			$ubgprov = "01";
			$ubgdist = "12";
			
			$idsigma = "";
			
			$fun[] = array("disableButton('#btnShowTitu');");
		}		
				
		//Generales
		$val[] = array('#idficbco',$idficbco,'val');
		$val[] = array('#idficha',$idficha,'val');
		$val[] = array('#cidfich',$cidfich,'val');
		$val[] = array('#cidpers',$cidpers,'val');
		$val[] = array('#periodo',$periodo,'val');
		$val[] = array('#idsigma',$idsigma,'val');
		$val[] = array('#cidtipo','01','val');
		
		$val[] = array('#nrficha',$rowFicha[0][4],'val');
		$val[] = array('#nrficha_old',$rowFicha[0][4],'val');
		$val[] = array('#lote_01',$rowFicha[0][5],'val');
		$val[] = array('#lote_02',$rowFicha[0][6],'val');
		$val[] = array('#cunicat',$rowFicha[0][7],'val');
		$val[] = array('#cuc_cat',$rowFicha[0][8],'val');
		$val[] = array('#codhoja',$rowFicha[0][9],'val');		
		
		$val[] = array('#idubigeo',$idubigeo,'val');
		$val[] = array('#ubgdpto',$ubgdpto,'val');
		$val[] = array('#ubgprov',$ubgprov,'val');
		$val[] = array('#ubgdist',$ubgdist,'val');
		
		$val[] = array('#rsector',trim($rowFicha[0][13]),'val');
		$val[] = array('#rmanzan',trim($rowFicha[0][14]),'val');
		$val[] = array('#reflote',trim($rowFicha[0][15]),'val');
		$val[] = array('#redific',trim($rowFicha[0][16]),'val');
		$val[] = array('#rentrad',trim($rowFicha[0][17]),'val');
		$val[] = array('#refpiso',trim($rowFicha[0][18]),'val');
		$val[] = array('#refunid',trim($rowFicha[0][19]),'val');
		$val[] = array('#dc_cata',trim($rowFicha[0][20]),'val');
		
		$val[] = array('#idcontr',trim($rowFicha[0][22]),'val');
		$val[] = array('#idpredi',trim($rowFicha[0][23]),'val');
		$val[] = array('#unpredi',trim($rowFicha[0][24]),'val');
		
		if($rowFicha[0][27]=='1'){
			$att[] = array('#dfirmas','checked','true');
			$fun[] = array("actDec(true);");			
		}
		$val[] = array('#cidecla',$rowFicha[0][25],'val');
		$val[] = array('#fedecla',$ar->toDate($rowFicha[0][28]),'val');
		$val[] = array('#nombr01',$rowFicha[0][26],'val');
		
		if(strlen($idficbco)>0){
		
			//Recupera ficha de bien común
			unset($parametros);
			$parametros[] = array('@busc',4);
			$parametros[] = array('@idficha',$idficbco);
			$rowFichaBC = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
			
			if($rowFichaBC[0][32]=='1'){
				$att[] = array('#sfirmas','checked','true');
				$fun[] = array("actSup(true);");
			}
			$val[] = array('#cidsupe',$rowFichaBC[0][29],'val');
			$val[] = array('#fesuper',$ar->toDate($rowFichaBC[0][31]),'val');
			$val[] = array('#nombr02',$rowFichaBC[0][30],'val');
			
			if($rowFichaBC[0][36]=='1'){
				$att[] = array('#tfirmas','checked','true');
				$fun[] = array("actTec(true);");
			}
			$val[] = array('#cidtecn',$rowFichaBC[0][33],'val');
			$val[] = array('#fetecni',$ar->toDate($rowFichaBC[0][35]),'val');
			$val[] = array('#nombr03',$rowFichaBC[0][34],'val');
		}
		else{
			if($rowFicha[0][32]=='1'){
				$att[] = array('#sfirmas','checked','true');
				$fun[] = array("actSup(true);");
			}
			$val[] = array('#cidsupe',$rowFicha[0][29],'val');
			$val[] = array('#fesuper',$ar->toDate($rowFicha[0][31]),'val');
			$val[] = array('#nombr02',$rowFicha[0][30],'val');
			
			if($rowFicha[0][36]=='1'){
				$att[] = array('#tfirmas','checked','true');
				$fun[] = array("actTec(true);");
			}
			$val[] = array('#cidtecn',$rowFicha[0][33],'val');
			$val[] = array('#fetecni',$ar->toDate($rowFicha[0][35]),'val');
			$val[] = array('#nombr03',$rowFicha[0][34],'val');
		}		
		
		if($rowFicha[0][40]=='1'){
			$att[] = array('#vfirmas','checked','true');
			$fun[] = array("actVer(true);");
		}
		$val[] = array('#cidveri',$rowFicha[0][37],'val');
		$val[] = array('#feverif',$ar->toDate($rowFicha[0][39]),'val');
		$val[] = array('#nombr04',$rowFicha[0][38],'val');
		$val[] = array('#nroregi',$rowFicha[0][41],'val');
				
		//Eventos generales		
		$evt[] = array('#tabsFicInd',"tabs","");
		$evt[] = array('input[type=button]',"button","");
		$fun[] = array("veryFicBC();");
		
		
		$evt[] = array('#nrficha,#lote_01,#lote_02,#cunicat,#cuc_cat,#codhoja,#rsector,#rmanzan,#reflote,#redific,#rentrad,#refpiso,#refunid,#dc_cata,#idcontr,#idpredi,#unpredi',"keypress","return validaTeclas(event,'number');");
		
		$evt[] = array('#nrficha',"blur","fullZeros(this,7);");
		$evt[] = array('#lote_01',"blur","fullZeros(this,2);");
		$evt[] = array('#lote_02',"blur","fullZeros(this,2);");
		$evt[] = array('#cunicat',"blur","fullZeros(this,8);");
		$evt[] = array('#cuc_cat',"blur","fullZeros(this,4);");
		$evt[] = array('#codhoja',"blur","fullZeros(this,10);");
		$evt[] = array('#rsector',"blur","fullZeros(this,2);");
		$evt[] = array('#rmanzan',"blur","fullZeros(this,3);");
		$evt[] = array('#reflote',"blur","fullZeros(this,3);");
		$evt[] = array('#redific',"blur","fullZeros(this,2);");
		$evt[] = array('#rentrad',"blur","fullZeros(this,2);");
		$evt[] = array('#refpiso',"blur","fullZeros(this,2);");
		$evt[] = array('#refunid',"blur","fullZeros(this,3);");
		$evt[] = array('#idcontr',"blur","fullZeros(this,7);");
		
		$evt[] = array('#btnSaveFicha',"click","goToFormulario('frmficind');");
		$evt[] = array('#btnCloseFicha',"click","closePopup('#popcatasficind');");
		
		//Ubicación de predio		
		$val[] = array('#nomedif',$rowFicInd[0][2],'val');
		$val[] = array('#nrointe',$rowFicInd[0][5],'val');
		$val[] = array('#zosecet',$rowFicInd[0][7],'val');
		$val[] = array('#manzana',$rowFicInd[0][8],'val');
		$val[] = array('#cidlote',$rowFicInd[0][9],'val');
		$val[] = array('#sublote',$rowFicInd[0][10],'val');
		
		unset($parametros);
		$parametros[] = array('@msquery',8);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,4,5,'');
		$val[] = array('#cid_via',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPuerta', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidpuer',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCondPuer', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidcnum',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTEdifi', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidedif',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicInd[0][3])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTInter', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidinte',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicInd[0][4])),'html');
		
		unset($parametros);
		$parametros[] = array('@msquery',8);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,4,5,'');
		$val[] = array('#cidhurb',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicInd[0][6])),'html');
		
		//Eventos ubicación de predio
		$evt[] = array('#btnAddUbica',"click","eventUbica('A');");
		$evt[] = array('#btnEditUbica',"click","eventUbica('E');");
		$evt[] = array('#btnCancelUbica',"click","eventUbica('C');");
		$evt[] = array('#btnSaveUbica',"click","eventUbica('S');");
		$evt[] = array('#btnDelUbica',"click","eventUbica('D');");
		$evt[] = array('#btnVia',"click","selVia();");
		
		//Titular Catastral
		unset($parametros);
		$parametros[] = array('@busc',4);
		$parametros[] = array('@idsigma',$cidpers);
		$rowContri = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPerson', $parametros);
		
		$val[] = array('#txtTipDocTitu',$rowContri[0][41],'val');
		$val[] = array('#txtNumDocTitu',$rowContri[0][7],'val');
		$val[] = array('#txtNomTitu',$rowContri[0][2],'val');
		$val[] = array('#txtApePatTitu',$rowContri[0][3],'val');
		$val[] = array('#txtApeMatTitu',$rowContri[0][4],'val');
		
		//Eventos Titular Catastral
		$evt[] = array('#btnSelectTitu',"click","showPopup('cataspersona/buscar','#popcatasperson','600','400','Buscar Titular');");
		$evt[] = array('#btnShowTitu',"click","datosTitu();");
		$evt[] = array('#btnDelTitu',"click","clearPerson();");
			
		//Características
		$val[] = array('#feadqui',$ar->toDate($rowFicInd[0][13]),'val');
		$val[] = array('#nroreso',$rowFicInd[0][15],'val');
		$val[] = array('#nporcen',$rowFicInd[0][16],'val');
		$val[] = array('#fecinic',$ar->toDate($rowFicInd[0][17]),'val');
		$val[] = array('#fecvenc',$ar->toDate($rowFicInd[0][18]),'val');
		$val[] = array('#estruct',$rowFicInd[0][22],'val');
		$val[] = array('#zonific',$rowFicInd[0][23],'val');
		$val[] = array('#areterr',$rowFicInd[0][24],'val');
		$val[] = array('#aredecl',$rowFicInd[0][25],'val');
		$val[] = array('#areveri',$rowFicInd[0][26],'val');
		$val[] = array('#fremeca',$rowFicInd[0][27],'val');
		$val[] = array('#dermeca',$rowFicInd[0][28],'val');
		$val[] = array('#izqmeca',$rowFicInd[0][29],'val');
		$val[] = array('#fonmeca',$rowFicInd[0][30],'val');
		$val[] = array('#fremeti',$rowFicInd[0][31],'val');
		$val[] = array('#dermeti',$rowFicInd[0][32],'val');
		$val[] = array('#izqmeti',$rowFicInd[0][33],'val');
		$val[] = array('#fonmeti',$rowFicInd[0][34],'val');
		$val[] = array('#frecoca',$rowFicInd[0][35],'val');
		$val[] = array('#dercoca',$rowFicInd[0][36],'val');
		$val[] = array('#izqcoca',$rowFicInd[0][37],'val');
		$val[] = array('#foncoca',$rowFicInd[0][38],'val');
		$val[] = array('#frecoti',$rowFicInd[0][39],'val');
		$val[] = array('#dercoti',$rowFicInd[0][40],'val');
		$val[] = array('#izqcoti',$rowFicInd[0][41],'val');
		$val[] = array('#foncoti',$rowFicInd[0][42],'val');
		if($rowFicInd[0][43]=='1')
			$att[] = array('#servluz','checked','true');
		if($rowFicInd[0][44]=='1')
			$att[] = array('#seragua','checked','true');
		if($rowFicInd[0][45]=='1')
			$att[] = array('#telefon','checked','true');
		if($rowFicInd[0][46]=='1')
			$att[] = array('#desague','checked','true');
		$val[] = array('#sumiluz',$rowFicInd[0][47],'val');
		$val[] = array('#conagua',$rowFicInd[0][48],'val');
		$val[] = array('#ntelefo',$rowFicInd[0][49],'val');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCCTitul', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidtitu',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicInd[0][11])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCFAdqui', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidadqu',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicInd[0][12])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPreCas', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidcond',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicInd[0][14])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCClaPre', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidclas',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicInd[0][19])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPreCas', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidpcat',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicInd[0][20])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$parametros[] = array('@tipo','FIN');
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCodUsos', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidusos',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicInd[0][21])),'html');				
		
		//Eventos características		
		$evt[] = array('#feadqui',"datepicker","");
		$evt[] = array('#fecinic',"datepicker","");
		$evt[] = array('#fecvenc',"datepicker","");
		
		//Construcciones
		$val[] = array('#terlega',$rowFicInd[0][50],'val');
		$val[] = array('#terrfis',$rowFicInd[0][51],'val');
		$val[] = array('#consleg',$rowFicInd[0][52],'val');
		$val[] = array('#consfis',$rowFicInd[0][53],'val');
			
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCConAnt', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#uconant',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		//Eventos construcciones
		$evt[] = array('#btnAddConst',"click","eventConst('A');");
		$evt[] = array('#btnEditConst',"click","eventConst('E');");
		$evt[] = array('#btnCancelConst',"click","eventConst('C');");
		$evt[] = array('#btnSaveConst',"click","eventConst('S');");
		$evt[] = array('#btnDelConst',"click","eventConst('D');");
		
		//Instalaciones
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTInsta', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidinst',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCConAnt', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#uconanti',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		//Eventos instalaciones
		$evt[] = array('#btnAddInstal',"click","eventInstal('A');");
		$evt[] = array('#btnEditInstal',"click","eventInstal('E');");
		$evt[] = array('#btnCancelInstal',"click","eventInstal('C');");
		$evt[] = array('#btnSaveInstal',"click","eventInstal('S');");
		$evt[] = array('#btnDelInstal',"click","eventInstal('D');");
		
		//Documentos
		$val[] = array('#cidnota',$rowFicInd[0][54],'val');
		$val[] = array('#vkardex',$rowFicInd[0][55],'val');		
		$val[] = array('#feescri',$ar->toDate($rowFicInd[0][56]),'val');
			
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCDocCons', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#ciddocud',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		//Eventos documentos
		$evt[] = array('#btnAddDocu',"click","eventDocu('A');");
		$evt[] = array('#btnEditDocu',"click","eventDocu('E');");
		$evt[] = array('#btnCancelDocu',"click","eventDocu('C');");
		$evt[] = array('#btnSaveDocu',"click","eventDocu('S');");
		$evt[] = array('#btnDelDocu',"click","eventDocu('D');");
		$evt[] = array('#feescri',"datepicker","");
		
		//Registro de predios
		$val[] = array('#vnumero',$rowFicInd[0][58],'val');
		$val[] = array('#vafojas',$rowFicInd[0][59],'val');
		$val[] = array('#asiento',$rowFicInd[0][60],'val');
		$val[] = array('#fecinsp',$ar->toDate($rowFicInd[0][61]),'val');
		$val[] = array('#insfabr',$rowFicInd[0][63],'val');
		$val[] = array('#fecinsf',$ar->toDate($rowFicInd[0][64]),'val');
		$val[] = array('#lotecol',$rowFicInd[0][66],'val');
		$val[] = array('#arepubl',$rowFicInd[0][67],'val');
		$val[] = array('#jaraisl',$rowFicInd[0][68],'val');
		$val[] = array('#areinta',$rowFicInd[0][69],'val');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTRegis', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidregi',$fn->ContenidoCombo($arCombo,'[Seleccione]',$rowFicInd[0][57]),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTFabri', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidfabr',$fn->ContenidoCombo($arCombo,'[Seleccione]',$rowFicInd[0][62]),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCEvaCat', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cideval',$fn->ContenidoCombo($arCombo,'[Seleccione]',$rowFicInd[0][65]),'html');
		
		//Eventos registro de predios
		$evt[] = array('#fecinsp',"datepicker","");
		$evt[] = array('#fecinsf',"datepicker","");
		
		//Información Complementaria
		$val[] = array('#nrohabi',$rowFicInd[0][72],'val');
		$val[] = array('#nrofami',$rowFicInd[0][73],'val');
		//$val[] = array('#vobserv',$rowFicInd[0][75],'val');
		$this->view->vobserv = $rowFicInd[0][75];
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCParque', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidcerc',$fn->ContenidoCombo($arCombo,'[Seleccione]',$rowFicInd[0][76]),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCDeclar', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#ciddecla',$fn->ContenidoCombo($arCombo,'[Seleccione]',$rowFicInd[0][70]),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTDocum', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#ciddocuc',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCEFicha', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'number');
		$val[] = array('#cidestf',$fn->ContenidoCombo($arCombo,'[Seleccione]',$rowFicInd[0][71]),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMFicIn', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'number');
		$val[] = array('#cidmant',$fn->ContenidoCombo($arCombo,'[Seleccione]',$rowFicInd[0][74]),'html');
		
		//Eventos información Complementaria
		$evt[] = array('#btnAddComple',"click","eventComple('A');");
		$evt[] = array('#btnEditComple',"click","eventComple('E');");
		$evt[] = array('#btnCancelComple',"click","eventComple('C');");
		$evt[] = array('#btnSaveComple',"click","eventComple('S');");
		$evt[] = array('#btnDelComple',"click","eventComple('D');");
		
		if(count($val))
			$fn->PintarValor($val);
		if(count($evt))
			$fn->PintarEvento($evt);
		if(count($att))
			$fn->AtributoComponente($att);
		if(count($fun))
			$fn->EjecutarFuncion($fun);
    }
	
	public function datagridsAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	$ar = new Libreria_ArraysFunctions();
		
		$tip = $this->_request->getPost('tip');
		$cidindi = $this->_request->getPost('cidindi');		
    	
    	switch($tip)
    	{
    		case 'UBI': 
				$parametros[] = array('@busc',4);
				$parametros[] = array('@cidindi',$cidindi);
				$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMdirec', $parametros);
				
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idsigma'=>trim($row[0]),
							'cidindi'=>trim($row[1]),
							'cid_via'=>trim($row[2]),
							'nom_via'=>(trim($row[2])!='') ? utf8_encode($row[14]) : utf8_encode($row[3]),
							'cidpuer'=>trim($row[4]),
							'nompuer'=>utf8_encode($row[12]),
							'nromuni'=>utf8_encode($row[5]),
							'cidcnum'=>trim($row[6]),
							'nomcnum'=>utf8_encode($row[13]),
							'nrcernu'=>utf8_encode($row[7])
						);
						$jsonData[] = $entry;
					}
				}
			break;
			case 'CON': 
				$parametros[] = array('@busc',4);
				$parametros[] = array('@cidindi',$cidindi);
				$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPisos', $parametros);
				
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idpisos'=>trim($row[0]),
							'cidindi'=>trim($row[1]),
							'nropiso'=>trim($row[2]),							
							'iddepcl'=>trim($row[4]),
							'iddepma'=>trim($row[5]),
							'iddepco'=>trim($row[6]),
							'esmuros'=>trim($row[7]),
							'estecho'=>trim($row[8]),
							'acapiso'=>trim($row[9]),
							'acapuer'=>trim($row[10]),
							'acareve'=>trim($row[11]),
							'acabanio'=>trim($row[12]),
							'instele'=>trim($row[13]),
							'arconde'=>trim($row[14]),
							'arconve'=>trim($row[15]),
							'mescons'=>trim($row[16]),
							'aniocons'=>trim($row[17]),
							'uconant'=>trim($row[18]),
							'vdescri'=>utf8_encode($row[23])
						);
						$jsonData[] = $entry;
					}
				}
			break;
			case 'INS': 
				$parametros[] = array('@busc',4);
				$parametros[] = array('@cidindi',$cidindi);
				$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMinsta', $parametros);
				
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idinsta'=>trim($row[0]),
							'cidindi'=>trim($row[1]),
							'cidinst'=>trim($row[2]),
							'cidnomb'=>utf8_encode($row[18]),
							'mescons'=>trim($row[3]),
							'aniocons'=>trim($row[4]),
							'iddepcl'=>trim($row[5]),
							'iddepma'=>trim($row[6]),
							'iddepco'=>trim($row[7]),
							'dmlargo'=>trim($row[8]),
							'dmancho'=>trim($row[9]),
							'dmaltos'=>trim($row[10]),
							'protota'=>trim($row[11]),
							'vunimed'=>trim($row[12]),
							'uconant'=>trim($row[13]),
							'vdescri'=>utf8_encode($row[19])
						);
						$jsonData[] = $entry;
					}
				}
			break;
			case 'DOC': 
				$parametros[] = array('@busc',4);
				$parametros[] = array('@cidindi',$cidindi);
				$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMdocum', $parametros);
				
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idsigma'=>trim($row[0]),
							'cidindi'=>trim($row[1]),
							'ciddocu'=>trim($row[2]),
							'nomdocu'=>utf8_encode($row[10]),
							'nrodocu'=>trim($row[3]),
							'fecdocu'=>$ar->toDate($row[4]),
							'areauto'=>trim($row[5])
						);
						$jsonData[] = $entry;
					}
				}
			break;
			case 'COM': 
				$parametros[] = array('@busc',4);
				$parametros[] = array('@cidindi',$cidindi);
				$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCInfCom', $parametros);
				
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idsigma'=>trim($row[0]),
							'cidindi'=>trim($row[1]),
							'ciddocu'=>trim($row[2]),
							'nomdocu'=>utf8_encode($row[10]),
							'nrodocu'=>trim($row[3]),
							'vnombre'=>utf8_encode($row[4]),
							'cidcont'=>trim($row[5])
						);
						$jsonData[] = $entry;
					}
				}
			break;
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
			$ar = new Libreria_ArraysFunctions();
			$login = new Zend_Session_Namespace('login');
			
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			
			$idficha = $data->idficha;
			
			if(strlen($idficha)>0)
				$busc = 2;
			else
				$busc = 1;
			
			//Guardamos la ficha cabecera		
			$parametros[] = array('@busc',$busc);
			$parametros[] = array('@idficha',$data->idficha);
			$parametros[] = array('@cidpers',$data->cidpers);
			$parametros[] = array('@periodo',$data->periodo);
			$parametros[] = array('@cidtipo',$data->cidtipo);
			$parametros[] = array('@nrficha',$data->nrficha);
			$parametros[] = array('@lote_01',$data->lote_01);
			$parametros[] = array('@lote_02',$data->lote_02);
			$parametros[] = array('@cunicat',$data->cunicat);
			$parametros[] = array('@cuc_cat',$data->cuc_cat);
			$parametros[] = array('@codhoja',$data->codhoja);
			$parametros[] = array('@ubgdpto',$data->ubgdpto);
			$parametros[] = array('@ubgprov',$data->ubgprov);
			$parametros[] = array('@ubgdist',$data->ubgdist);
			$parametros[] = array('@rsector',$data->rsector);
			$parametros[] = array('@rmanzan',$data->rmanzan);
			$parametros[] = array('@reflote',$data->reflote);
			$parametros[] = array('@redific',$data->redific);
			$parametros[] = array('@rentrad',$data->rentrad);
			$parametros[] = array('@refpiso',$data->refpiso);
			$parametros[] = array('@refunid',$data->refunid);
			$parametros[] = array('@dc_cata',$data->dc_cata);
			$parametros[] = array('@cidfich',$data->cidfich);
			$parametros[] = array('@idcontr',$data->idcontr);
			$parametros[] = array('@idpredi',$data->idpredi);
			$parametros[] = array('@unpredi',$data->unpredi);
			
			if($data->dfirmas!='1'){
				$data->cidecla = '';
				$data->nombr01 = '';
				$data->dfirmas = '0';
				$data->fedecla = '';
			}
			$parametros[] = array('@cidecla',$data->cidecla);
			$parametros[] = array('@nombr01',$data->nombr01);
			$parametros[] = array('@dfirmas',intval($data->dfirmas));
			if($ar->isDate($data->fedecla))
				$parametros[] = array('@fedecla',$data->fedecla);
			else
				$parametros[] = array('@fedecla','');
			
			if($data->sfirmas!='1'){
				$data->cidsupe = '';
				$data->nombr02 = '';
				$data->sfirmas = '0';
				$data->fesuper = '';
			}			
			$parametros[] = array('@cidsupe',$data->cidsupe);
			$parametros[] = array('@nombr02',$data->nombr02);
			if($ar->isDate($data->fesuper))
				$parametros[] = array('@fesuper',$data->fesuper);
			else
				$parametros[] = array('@fesuper','');				
			$parametros[] = array('@sfirmas',intval($data->sfirmas));
			
			if($data->tfirmas!='1'){
				$data->cidtecn = '';
				$data->nombr03 = '';
				$data->tfirmas = '0';
				$data->fetecni = '';
			}			
			$parametros[] = array('@cidtecn',$data->cidtecn);
			$parametros[] = array('@nombr03',$data->nombr03);
			if($ar->isDate($data->fetecni))
				$parametros[] = array('@fetecni',$data->fetecni);
			else
				$parametros[] = array('@fetecni','');
			$parametros[] = array('@tfirmas',intval($data->tfirmas));
			
			if($data->vfirmas!='1'){
				$data->cidveri = '';
				$data->nombr04 = '';
				$data->vfirmas = '0';
				$data->feverif = '';
			}
			$parametros[] = array('@cidveri',$data->cidveri);
			$parametros[] = array('@nombr04',$data->nombr04);
			if($ar->isDate($data->feverif))
				$parametros[] = array('@feverif',$data->feverif);
			else
				$parametros[] = array('@feverif','');
			$parametros[] = array('@vfirmas',intval($data->vfirmas));
			
			$parametros[] = array('@nroregi',$data->nroregi);
			$parametros[] = array('@vobserv',$data->vobserv);
			$parametros[] = array('@usuario',$login->user);
			$parametros[] = array('@hostnam',php_uname('n'));
			$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
			
			@$dataFicha = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros, true);
			
			if(is_numeric($dataFicha[0][0]) && $dataFicha[0][0]>0){
				//Nueva ficha				
				$msj = "La ficha se ingres&oacute; correctamente.";
				$idficha = $dataFicha[0][0];
			}
			else{
				//Edita ficha
				$msj = "La ficha se actualiz&oacute; correctamente.";
				$idficha = $data->idficha;
			}
			
			$idsigma = $data->idsigma;
			
			if(strlen($idsigma)>0)
				$busc = 2;
			else
				$busc = 1;
			
			//Guardamos la ficha individual
			unset($parametros);
			$parametros[] = array('@busc',$busc);
			$parametros[] = array('@idsigma',$idsigma);
			$parametros[] = array('@cidfich',$idficha);
			//Ubicación
			$parametros[] = array('@nomedif',$data->nomedif);
			$parametros[] = array('@cidedif',$data->cidedif);
			$parametros[] = array('@cidinte',$data->cidinte);
			$parametros[] = array('@nrointe',$data->nrointe);
			$parametros[] = array('@cidhurb',$data->cidhurb);
			$parametros[] = array('@zosecet',$data->zosecet);
			$parametros[] = array('@manzana',$data->manzana);
			$parametros[] = array('@cidlote',$data->cidlote);
			$parametros[] = array('@sublote',$data->sublote);
			//Características
			$parametros[] = array('@cidtitu',$data->cidtitu);
			$parametros[] = array('@cidadqu',$data->cidadqu);
			if($ar->isDate($data->feadqui))
				$parametros[] = array('@feadqui',$data->feadqui);
			else
				$parametros[] = array('@feadqui','');
			$parametros[] = array('@cidcond',$data->cidcond);
			$parametros[] = array('@nroreso',$data->nroreso);
			$parametros[] = array('@nporcen',floatval($data->nporcen));
			if($ar->isDate($data->fecinic))
				$parametros[] = array('@fecinic',$data->fecinic);
			else
				$parametros[] = array('@fecinic','');
			if($ar->isDate($data->fecvenc))
				$parametros[] = array('@fecvenc',$data->fecvenc);
			else
				$parametros[] = array('@fecvenc','');
			$parametros[] = array('@cidclas',$data->cidclas);
			$parametros[] = array('@cidpcat',$data->cidpcat);
			$parametros[] = array('@cidusos',$data->cidusos);
			$parametros[] = array('@estruct',$data->estruct);
			$parametros[] = array('@zonific',$data->zonific);
			$parametros[] = array('@areterr',floatval($data->areterr));
			$parametros[] = array('@aredecl',floatval($data->aredecl));
			$parametros[] = array('@areveri',floatval($data->areveri));
			$parametros[] = array('@fremeca',floatval($data->fremeca));
			$parametros[] = array('@dermeca',floatval($data->dermeca));
			$parametros[] = array('@izqmeca',floatval($data->izqmeca));
			$parametros[] = array('@fonmeca',floatval($data->fonmeca));
			$parametros[] = array('@fremeti',floatval($data->fremeti));
			$parametros[] = array('@dermeti',floatval($data->dermeti));
			$parametros[] = array('@izqmeti',floatval($data->izqmeti));
			$parametros[] = array('@fonmeti',floatval($data->fonmeti));
			$parametros[] = array('@frecoca',$data->frecoca);
			$parametros[] = array('@dercoca',$data->dercoca);
			$parametros[] = array('@izqcoca',$data->izqcoca);
			$parametros[] = array('@foncoca',$data->foncoca);
			$parametros[] = array('@frecoti',$data->frecoti);
			$parametros[] = array('@dercoti',$data->dercoti);
			$parametros[] = array('@izqcoti',$data->izqcoti);
			$parametros[] = array('@foncoti',$data->foncoti);
			$parametros[] = array('@servluz',$data->servluz);
			$parametros[] = array('@seragua',$data->seragua);
			$parametros[] = array('@telefon',$data->telefon);
			$parametros[] = array('@desague',$data->desague);
			$parametros[] = array('@sumiluz',$data->sumiluz);
			$parametros[] = array('@conagua',$data->conagua);
			//Construcciones
			$parametros[] = array('@ntelefo',$data->ntelefo);
			$parametros[] = array('@terlega',floatval($data->terlega));
			$parametros[] = array('@terrfis',floatval($data->terrfis));
			$parametros[] = array('@consleg',floatval($data->consleg));
			$parametros[] = array('@consfis',floatval($data->consfis));
			//Documentos
			$parametros[] = array('@cidnota',$data->cidnota);
			$parametros[] = array('@vkardex',$data->vkardex);
			if($ar->isDate($data->feescri))
				$parametros[] = array('@feescri',$data->feescri);
			else
				$parametros[] = array('@feescri','');
			//Registro
			$parametros[] = array('@cidregi',$data->cidregi);
			$parametros[] = array('@vnumero',$data->vnumero);
			$parametros[] = array('@vafojas',$data->vafojas);
			$parametros[] = array('@asiento',$data->asiento);
			if($ar->isDate($data->fecinsp))
				$parametros[] = array('@fecinsp',$data->fecinsp);
			else
				$parametros[] = array('@fecinsp','');
			$parametros[] = array('@cidfabr',$data->cidfabr);
			$parametros[] = array('@insfabr',$data->insfabr);
			if($ar->isDate($data->fecinsf))
				$parametros[] = array('@fecinsf',$data->fecinsf);
			else
				$parametros[] = array('@fecinsf',$data->fecinsf);
			$parametros[] = array('@cideval',$data->cideval);
			$parametros[] = array('@lotecol',floatval($data->lotecol));
			$parametros[] = array('@arepubl',floatval($data->arepubl));
			$parametros[] = array('@jaraisl',floatval($data->jaraisl));
			$parametros[] = array('@areinta',floatval($data->areinta));
			//Información
			$parametros[] = array('@cidecla',$data->ciddecla);
			$parametros[] = array('@cidestf',$data->cidestf);
			$parametros[] = array('@nrohabi',$data->nrohabi);
			$parametros[] = array('@nrofami',$data->nrofami);
			$parametros[] = array('@cidmant',$data->cidmant);
			$parametros[] = array('@vobserv',$data->vobserv);
			$parametros[] = array('@cidcerc',$data->cidcerc);
			
			$parametros[] = array('@usuario',$login->user);
			$parametros[] = array('@hostnam',php_uname('n'));
			$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
			
			@$dataFicInd = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMIndiv', $parametros, true);			
			
			if(is_numeric($dataFicInd[0][0]) && $dataFicInd[0][0]>0){
				//Nueva ficha individual
				$idsigma = $dataFicInd[0][0];
			}
			else{
				//Edita ficha individual
				$idsigma = $data->idsigma;
			}
			
			//$msj = $idsigma;
			
			//Guardamos grid Ubicación
			$oldUbica = explode('|',$data->oldUbiItems);			
			$dataUbica = $data->Ubica;
			//Eliminamos si hicieron delete
			for($i=0;$i<count($oldUbica);$i++){
				$flag = false;
				if(count($dataUbica)){
					foreach($dataUbica as $dUbica){
						if($dUbica->idsigma==$oldUbica[$i]){
							$flag = true;
							break;
						}
					}
					if(!$flag){
						//Si ya no existen en la grilla eliminamos
						unset($parametros);
						$parametros[] = array('@busc',3);
						$parametros[] = array('@idsigma',$oldUbica[$i]);
						@$dataFicIndDir = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMdirec', $parametros);
					}
				}
				else{
					//Si ya no existen en la grilla eliminamos
					unset($parametros);
					$parametros[] = array('@busc',3);
					$parametros[] = array('@idsigma',$oldUbica[$i]);
					@$dataFicIndDir = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMdirec', $parametros);
				}
			}
			
			if(count($dataUbica)){
				foreach($dataUbica as $dUbica){
					$idcmdirec = $dUbica->idsigma;
					$idcmindiv = $idsigma;
					
					if(strlen($idcmdirec)>0)
						$busc = 2;
					else
						$busc = 1;
						
					unset($parametros);
					$parametros[] = array('@busc',$busc);
					$parametros[] = array('@idsigma',$idcmdirec);
					$parametros[] = array('@cidindi',$idcmindiv);
					$parametros[] = array('@cid_via',$dUbica->cid_via);
					$parametros[] = array('@nom_via',$dUbica->nom_via);
					$parametros[] = array('@cidpuer',$dUbica->cidpuer);
					$parametros[] = array('@nromuni',$dUbica->nromuni);
					$parametros[] = array('@cidcnum',$dUbica->cidcnum);
					$parametros[] = array('@nrcernu',$dUbica->nrcernu);					
					$parametros[] = array('@usuario',$login->user);
					$parametros[] = array('@hostnam',php_uname('n'));
					$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
					
					@$dataFicIndDir = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMdirec', $parametros, true);
				}
			}
			
			//Guardamos grid Construcciones
			$oldConst = explode('|',$data->oldConItems);			
			$dataConst = $data->Const;			
			//Eliminamos si hicieron delete
			for($i=0;$i<count($oldConst);$i++){
				$flag = false;
				if(count($dataConst)){
					foreach($dataConst as $dConst){
						if($dConst->idpisos==$oldConst[$i]){
							$flag = true;
							break;
						}
					}
					if(!$flag){
						//Si ya no existen en la grilla eliminamos
						unset($parametros);
						$parametros[] = array('@busc',3);
						$parametros[] = array('@idpisos',$oldConst[$i]);
						@$dataFicIndPis = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPisos', $parametros);
					}
				}
				else{
					//Si ya no existen en la grilla eliminamos
					unset($parametros);
					$parametros[] = array('@busc',3);
					$parametros[] = array('@idpisos',$oldConst[$i]);
					@$dataFicIndPis = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPisos', $parametros);
				}
			}
			
			if(count($dataConst)){
				foreach($dataConst as $dConst){
					$idcmpisos = $dConst->idpisos;
					$idcmindiv = $idsigma;
					
					if(strlen($idcmpisos)>0)
						$busc = 2;
					else
						$busc = 1;
						
					unset($parametros);
					$parametros[] = array('@busc',$busc);
					$parametros[] = array('@idpisos',$idcmpisos);
					$parametros[] = array('@cidindi',$idcmindiv);
					$parametros[] = array('@nropiso',$dConst->nropiso);
					$parametros[] = array('@iddepcl',$dConst->iddepcl);
					$parametros[] = array('@iddepma',$dConst->iddepma);
					$parametros[] = array('@iddepco',$dConst->iddepco);
					$parametros[] = array('@esmuros',$dConst->esmuros);
					$parametros[] = array('@estecho',$dConst->estecho);
					$parametros[] = array('@acapiso',$dConst->acapiso);
					$parametros[] = array('@acapuer',$dConst->acapuer);
					$parametros[] = array('@acareve',$dConst->acareve);
					$parametros[] = array('@acabanio',$dConst->acabanio);
					$parametros[] = array('@instele',$dConst->instele);
					$parametros[] = array('@arconde',(float)$dConst->arconde);
					$parametros[] = array('@arconve',(float)$dConst->arconve);
					$parametros[] = array('@mescons',$dConst->mescons);
					$parametros[] = array('@aniocons',$dConst->aniocons);
					$parametros[] = array('@uconant',$dConst->uconant);
					
					$parametros[] = array('@usuario',$login->user);
					$parametros[] = array('@hostnam',php_uname('n'));
					$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
					
					@$dataFicIndPis = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPisos', $parametros, true);
				}
			}
			
			//Guardamos grid Instalaciones
			$oldInstal = explode('|',$data->oldInsItems);			
			$dataInstal = $data->Instal;			
			//Eliminamos si hicieron delete
			for($i=0;$i<count($oldInstal);$i++){
				$flag = false;
				if(count($dataInstal)){
					foreach($dataInstal as $dInstal){
						if($dInstal->idinsta==$oldInstal[$i]){
							$flag = true;
							break;
						}
					}
					if(!$flag){
						//Si ya no existen en la grilla eliminamos
						unset($parametros);
						$parametros[] = array('@busc',3);
						$parametros[] = array('@idinsta',$oldInstal[$i]);
						@$dataFicIndIns = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMinsta', $parametros);
					}
				}
				else{
					//Si ya no existen en la grilla eliminamos
					unset($parametros);
					$parametros[] = array('@busc',3);
					$parametros[] = array('@idinsta',$oldInstal[$i]);
					@$dataFicIndIns = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMinsta', $parametros);
				}
			}
			
			if(count($dataInstal)){
				foreach($dataInstal as $dInstal){
					$idcminsta = $dInstal->idinsta;
					$idcmindiv = $idsigma;
					
					if(strlen($idcminsta)>0)
						$busc = 2;
					else
						$busc = 1;
						
					unset($parametros);
					$parametros[] = array('@busc',$busc);
					$parametros[] = array('@idinsta',$idcminsta);
					$parametros[] = array('@cidindi',$idcmindiv);
					$parametros[] = array('@cidinst',$dInstal->cidinst);
					$parametros[] = array('@mescons',$dInstal->mescons);
					$parametros[] = array('@aniocons',$dInstal->aniocons);
					$parametros[] = array('@iddepcl',$dInstal->iddepcl);
					$parametros[] = array('@iddepma',$dInstal->iddepma);
					$parametros[] = array('@iddepco',$dInstal->iddepco);
					$parametros[] = array('@dmlargo',(float)$dInstal->dmlargo);
					$parametros[] = array('@dmancho',(float)$dInstal->dmancho);
					$parametros[] = array('@dmaltos',(float)$dInstal->dmaltos);
					$parametros[] = array('@protota',(float)$dInstal->protota);
					$parametros[] = array('@vunimed',$dInstal->vunimed);
					$parametros[] = array('@uconant',$dInstal->uconant);
					
					$parametros[] = array('@usuario',$login->user);
					$parametros[] = array('@hostnam',php_uname('n'));
					$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
					
					@$dataFicIndIns = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMinsta', $parametros, true);
				}
			}
			
			//Guardamos grid Documentos
			$oldDocu = explode('|',$data->oldDocItems);			
			$dataDocu = $data->Docu;			
			//Eliminamos si hicieron delete
			for($i=0;$i<count($oldDocu);$i++){
				$flag = false;
				if(count($dataDocu)){
					foreach($dataDocu as $dDocu){
						if($dDocu->idsigma==$oldDocu[$i]){
							$flag = true;
							break;
						}
					}
					if(!$flag){
						//Si ya no existen en la grilla eliminamos
						unset($parametros);
						$parametros[] = array('@busc',3);
						$parametros[] = array('@idsigma',$oldDocu[$i]);
						@$dataFicIndIns = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMdocum', $parametros);
					}
				}
				else{
					//Si ya no existen en la grilla eliminamos
					unset($parametros);
					$parametros[] = array('@busc',3);
					$parametros[] = array('@idsigma',$oldDocu[$i]);
					@$dataFicIndIns = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMdocum', $parametros);
				}
			}
			
			if(count($dataDocu)){
				foreach($dataDocu as $dDocu){
					$idcmdocum = $dDocu->idsigma;
					$idcmindiv = $idsigma;
					
					if(strlen($idcmdocum)>0)
						$busc = 2;
					else
						$busc = 1;
						
					unset($parametros);
					$parametros[] = array('@busc',$busc);
					$parametros[] = array('@idsigma',$idcmdocum);
					$parametros[] = array('@cidindi',$idcmindiv);
					$parametros[] = array('@ciddocu',$dDocu->ciddocu);
					$parametros[] = array('@nrodocu',$dDocu->nrodocu);
					if($ar->isDate($dDocu->fecdocu))
						$parametros[] = array('@fecdocu',trim($dDocu->fecdocu));
					else
						$parametros[] = array('@fecdocu','');
					$parametros[] = array('@areauto',(float)$dDocu->areauto);				
					
					$parametros[] = array('@usuario',$login->user);
					$parametros[] = array('@hostnam',php_uname('n'));
					$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
					
					@$dataFicIndIns = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMdocum', $parametros, true);
				}
			}
			
			//Guardamos grid Inf. Complementaria
			$oldComple = explode('|',$data->oldComItems);			
			$dataComple = $data->Comple;			
			//Eliminamos si hicieron delete
			for($i=0;$i<count($oldComple);$i++){
				$flag = false;
				if(count($dataComple)){
					foreach($dataComple as $dComple){
						if($dComple->idsigma==$oldComple[$i]){
							$flag = true;
							break;
						}
					}
					if(!$flag){
						//Si ya no existen en la grilla eliminamos
						unset($parametros);
						$parametros[] = array('@busc',3);
						$parametros[] = array('@idsigma',$oldComple[$i]);
						@$dataFicIndIns = $cn->ejec_store_procedura_sqlc('MntCatas.pxCInfCom', $parametros);
					}
				}
				else{
					//Si ya no existen en la grilla eliminamos
					unset($parametros);
					$parametros[] = array('@busc',3);
					$parametros[] = array('@idsigma',$oldComple[$i]);
					@$dataFicIndIns = $cn->ejec_store_procedura_sqlc('MntCatas.pxCInfCom', $parametros);
				}
			}
			
			if(count($dataComple)){
				foreach($dataComple as $dComple){
					$idcinfcom = $dComple->idsigma;
					$idcmindiv = $idsigma;
					
					if(strlen($idcinfcom)>0)
						$busc = 2;
					else
						$busc = 1;
						
					unset($parametros);
					$parametros[] = array('@busc',$busc);
					$parametros[] = array('@idsigma',$idcinfcom);
					$parametros[] = array('@cidindi',$idcmindiv);
					$parametros[] = array('@ciddocu',$dComple->ciddocu);
					$parametros[] = array('@nrodocu',$dComple->nrodocu);
					$parametros[] = array('@vnombre',$dComple->vnombre);
					$parametros[] = array('@cidcont',$dComple->cidcont);				
					
					$parametros[] = array('@usuario',$login->user);
					$parametros[] = array('@hostnam',php_uname('n'));
					$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
					
					@$dataFicIndIns = $cn->ejec_store_procedura_sqlc('MntCatas.pxCInfCom', $parametros, true);
				}
			}
			
			echo $msj;
		}		
    }
}
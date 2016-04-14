<?php

class CatasficecoController extends Zend_Controller_Action
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
		$idficind = $this->_request->getParam('idficind','');
		
		//Recupera ficha individual
		$parametros[] = array('@busc',4);
		$parametros[] = array('@idficha',$idficind);
		$rowFicInd = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
		
		/*
		$val[] = array('#nrficha',$rowFicInd[0][4],'val');
		$val[] = array('#idubigeo',$rowFicInd[0][10].$rowFicInd[0][11].$rowFicInd[0][12],'val');
		$val[] = array('#ubgdpto',$rowFicInd[0][10],'val');
		$val[] = array('#ubgprov',$rowFicInd[0][11],'val');
		$val[] = array('#ubgdist',$rowFicInd[0][12],'val');
		$val[] = array('#rsector',$rowFicInd[0][13],'val');
		$val[] = array('#rmanzan',$rowFicInd[0][14],'val');
		$val[] = array('#reflote',$rowFicInd[0][15],'val');
		$val[] = array('#redific',$rowFicInd[0][16],'val');
		$val[] = array('#rentrad',$rowFicInd[0][17],'val');
		$val[] = array('#refpiso',$rowFicInd[0][18],'val');
		$val[] = array('#refunid',$rowFicInd[0][19],'val');
		$val[] = array('#dc_cata',$rowFicInd[0][20],'val');
		*/
		
		if(strlen($idficha)>0)
		{
			//Recupera ficha cabecera
			unset($parametros);
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
			
			//Recupera ficha económica			
			unset($parametros);
			$parametros[] = array('@busc',3);
			$parametros[] = array('@cidfich',$idficha);
			$rowFicEco = $cn->ejec_store_procedura_sqlc('MntCatas.pxCEconom', $parametros);
			
			$idsigma = $rowFicEco[0][0];
		}
		else{
			$idficha = "";
			$cidfich = $rowFicInd[0][0];
			$idubigeo = "150112";
			$ubgdpto = "15";
			$ubgprov = "01";
			$ubgdist = "12";
		}
		
		//Generales
		$val[] = array('#idficind',$idficind,'val');
		$val[] = array('#idficha',$idficha,'val');
		$val[] = array('#cidfich',$cidfich,'val');
		$val[] = array('#cidpers',$rowFicInd[0][1],'val');
		$val[] = array('#periodo',$rowFicInd[0][2],'val');		
		$val[] = array('#idsigma',$idsigma,'val');
		$val[] = array('#cidtipo','03','val');
		
		$val[] = array('#nrficha',$rowFicha[0][4],'val');
		$val[] = array('#nrficha_old',$rowFicha[0][4],'val');
		$val[] = array('#lote_01',trim($rowFicha[0][5]),'val');
		$val[] = array('#lote_02',trim($rowFicha[0][6]),'val');
		$val[] = array('#cunicat',trim($rowFicha[0][7]),'val');
		$val[] = array('#cuc_cat',trim($rowFicha[0][8]),'val');
		$val[] = array('#codhoja',trim($rowFicha[0][9]),'val');
		
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
		
		if($rowFicha[0][27]=='1'){
			$att[] = array('#dfirmas','checked','true');
			$fun[] = array("actDec(true);");			
		}
		$val[] = array('#cidecla',$rowFicha[0][25],'val');
		$val[] = array('#fedecla',$ar->toDate($rowFicha[0][28]),'val');
		$val[] = array('#nombr01',$rowFicha[0][26],'val');
		
		if(strlen($idficind)>0){
			//Recupera ficha individual
			unset($parametros);
			$parametros[] = array('@busc',4);
			$parametros[] = array('@idficha',$idficind);
			$rowFichaIN = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
			
			if($rowFichaIN[0][32]=='1'){
				$att[] = array('#sfirmas','checked','true');
				$fun[] = array("actSup(true);");
			}
			$val[] = array('#cidsupe',$rowFichaIN[0][29],'val');
			$val[] = array('#fesuper',$ar->toDate($rowFichaIN[0][31]),'val');
			$val[] = array('#nombr02',$rowFichaIN[0][30],'val');
			
			if($rowFichaIN[0][36]=='1'){
				$att[] = array('#tfirmas','checked','true');
				$fun[] = array("actTec(true);");
			}
			$val[] = array('#cidtecn',$rowFichaIN[0][33],'val');
			$val[] = array('#fetecni',$ar->toDate($rowFichaIN[0][35]),'val');
			$val[] = array('#nombr03',$rowFichaIN[0][34],'val');
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
		$evt[] = array('#tabsFicEco',"tabs","");
		$evt[] = array('input[type=button]',"button","");	
		
		$evt[] = array('#nrficha,#lote_01,#lote_02,#cunicat,#cuc_cat,#codhoja,#rsector,#rmanzan,#reflote,#redific,#rentrad,#refpiso,#refunid',"keypress","return validaTeclas(event,'number');");
		
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
		$evt[] = array('#refunid',"blur","fullZeros(this,3);");;				
		
		$evt[] = array('#btnSaveFicha',"click","goToFormulario('frmficeco');");
		$evt[] = array('#btnCloseFicha',"click","closePopup('#popcatasficeco');");
		
		//tabs identificación
		$val[] = array('#cid_per',$rowFicEco[0][2],'val');
		$val[] = array('#nom_per',$rowFicEco[0][28],'val');
		$val[] = array('#nombcom',$rowFicEco[0][4],'val');
		$val[] = array('#vobserv',$rowFicEco[0][23],'val');
		
		//Eventos identificación
		$evt[] = array('#btnSearchCond',"click","searchConduc();");
		
		//tabs actividades
		$val[] = array('#areaaup',$rowFicEco[0][6],'val');
		$val[] = array('#areaauv',$rowFicEco[0][7],'val');
		$val[] = array('#areaaub',$rowFicEco[0][8],'val');
		$val[] = array('#areaaut',$rowFicEco[0][9],'val');
		$val[] = array('#areavep',$rowFicEco[0][10],'val');
		$val[] = array('#areavev',$rowFicEco[0][11],'val');
		$val[] = array('#areaveb',$rowFicEco[0][12],'val');
		$val[] = array('#areavet',$rowFicEco[0][13],'val');
		
		$val[] = array('#nroexpex',$rowFicEco[0][14],'val');
		$val[] = array('#nrolicex',$rowFicEco[0][15],'val');
		$val[] = array('#fecexpe',$ar->toDate($rowFicEco[0][16]),'val');
		$val[] = array('#fecvenc',$ar->toDate($rowFicEco[0][17]),'val');
		$val[] = array('#fecinic',$ar->toDate($rowFicEco[0][18]),'val');
		
		//Eventos actividades
		$evt[] = array('#fecinic',"datepicker","");
		$evt[] = array('#fecinic','autotab',"{format:'date'}");
		$evt[] = array('#fecvenc',"datepicker","");
		$evt[] = array('#fecvenc','autotab',"{format:'date'}");
		$evt[] = array('#fecexpe',"datepicker","");
		$evt[] = array('#fecexpe','autotab',"{format:'date'}");
		
		$evt[] = array('#btnAddActivi',"click","eventActivi('A');");
		$evt[] = array('#btnEditActivi',"click","eventActivi('E');");
		$evt[] = array('#btnCancelActivi',"click","eventActivi('C');");
		$evt[] = array('#btnSaveActivi',"click","eventActivi('S');");
		$evt[] = array('#btnDelActivi',"click","eventActivi('D');");
		
		//tabs  anuncios		
		//...		
		
		//Eventos anuncios
		$evt[] = array('#fvencim',"datepicker","");
		$evt[] = array('#fvencim','autotab',"{format:'date'}");
		$evt[] = array('#fexpedi',"datepicker","");
		$evt[] = array('#fexpedi','autotab',"{format:'date'}");
		
		$evt[] = array('#btnAddAnunci',"click","eventAnunci('A');");
		$evt[] = array('#btnSaveAnunci',"click","eventAnunci('S');");
		$evt[] = array('#btnEditAnunci',"click","eventAnunci('E');");
		$evt[] = array('#btnCancelAnunci',"click","eventAnunci('C');");
		$evt[] = array('#btnDelAnunci',"click","eventAnunci('D');");
		
		//Rellena combos
		unset($parametros);
		$parametros[] = array('@busc',1);	
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTperso', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#xcidpers',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicEco[0][3])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);	
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCConduc', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidcond',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicEco[0][5])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCDeclar', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#ciddecl',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicEco[0][19])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCDocPre', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidocpe',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicEco[0][21])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCEFicha', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'number');
		$val[] = array('#cidesta',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicEco[0][20])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCManten', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidmant',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicEco[0][22])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTActeco', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidacti',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTanunc', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidanuc',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		$fn->PintarValor($val);
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
		$cideco = $this->_request->getPost('cideco');
    	
    	switch($tip)
    	{
    		case 'ACT': 
				$parametros[] = array('@busc',4);
				$parametros[] = array('@cidfich',$cideco);
				$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCActivi', $parametros);
				
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idsigma'=>trim($row[0]),
							'cidfich'=>trim($row[1]),
							'cidacti'=>trim($row[2]),
							'desacti'=>utf8_encode($row[8])
						);
						$jsonData[] = $entry;
					}
				}
			break;
			case 'ANU': 
				$parametros[] = array('@busc',4);
				$parametros[] = array('@cidfich',$cideco);
				$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCAnunci', $parametros);
				
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idsigma'=>$row[0],
							'cidfich'=>$row[1],
							'cidanuc'=>$row[2],
							'nomanuc'=>utf8_encode($row[14]),
							'nrolado'=>$row[3],
							'areauto'=>$row[4],
							'areveri'=>$row[5],
							'nroexpe'=>$row[6],
							'nrolice'=>$row[7],
							'fexpedi'=>$ar->toDate($row[8]),
							'fvencim'=>$ar->toDate($row[9])
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
			
			if(trim($data->dfirmas)!='1'){
				$data->cidecla = '';
				$data->nombr01 = '';
				$data->dfirmas = '0';
				$data->fedecla = '';
			}
			$parametros[] = array('@cidecla',trim($data->cidecla));
			$parametros[] = array('@nombr01',$data->nombr01);
			$parametros[] = array('@dfirmas',intval($data->dfirmas));
			if($ar->isDate(trim($data->fedecla)))
				$parametros[] = array('@fedecla',trim($data->fedecla));
			else
				$parametros[] = array('@fedecla','');
			
			if(trim($data->sfirmas)!='1'){
				$data->cidsupe = '';
				$data->nombr02 = '';
				$data->sfirmas = '0';
				$data->fesuper = '';
			}			
			$parametros[] = array('@cidsupe',trim($data->cidsupe));
			$parametros[] = array('@nombr02',$data->nombr02);
			if($ar->isDate(trim($data->fesuper)))
				$parametros[] = array('@fesuper',trim($data->fesuper));
			else
				$parametros[] = array('@fesuper','');				
			$parametros[] = array('@sfirmas',intval($data->sfirmas));			
			
			if(trim($data->tfirmas)!='1'){
				$data->cidtecn = '';
				$data->nombr03 = '';
				$data->tfirmas = '0';
				$data->fetecni = '';
			}			
			$parametros[] = array('@cidtecn',trim($data->cidtecn));
			$parametros[] = array('@nombr03',$data->nombr03);
			if($ar->isDate(trim($data->fetecni)))
				$parametros[] = array('@fetecni',trim($data->fetecni));
			else
				$parametros[] = array('@fetecni','');
			$parametros[] = array('@tfirmas',intval($data->tfirmas));
			
			if(trim($data->vfirmas)!='1'){
				$data->cidveri = '';
				$data->nombr04 = '';
				$data->vfirmas = '0';
				$data->feverif = '';
			}
			$parametros[] = array('@cidveri',trim($data->cidveri));
			$parametros[] = array('@nombr04',$data->nombr04);
			if($ar->isDate(trim($data->feverif)))
				$parametros[] = array('@feverif',trim($data->feverif));
			else
				$parametros[] = array('@feverif','');
			$parametros[] = array('@vfirmas',intval($data->vfirmas));
			
			$parametros[] = array('@nroregi',trim($data->nroregi));
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
			
			//Guardamos la ficha económica
			unset($parametros);
			$parametros[] = array('@busc',$busc);
			$parametros[] = array('@idsigma',$idsigma);
			$parametros[] = array('@cidfich',$idficha);
			//Actividades
			$parametros[] = array('@cid_per',$data->cid_per);
			$parametros[] = array('@cidpers',$data->xcidpers);
			$parametros[] = array('@nombcom',$data->nombcom);
			$parametros[] = array('@cidcond',$data->cidcond);
			$parametros[] = array('@areaaup',floatval($data->areaaup));
			$parametros[] = array('@areaauv',floatval($data->areaauv));
			$parametros[] = array('@areaaub',floatval($data->areaaub));
			$parametros[] = array('@areaaut',floatval($data->areaaut));
			$parametros[] = array('@areavep',floatval($data->areavep));
			$parametros[] = array('@areavev',floatval($data->areavev));
			$parametros[] = array('@areaveb',floatval($data->areaveb));
			$parametros[] = array('@areavet',floatval($data->areavet));
			$parametros[] = array('@nroexpe',$data->nroexpex);
			$parametros[] = array('@nrolice',$data->nrolicex);			
			if($ar->isDate($data->fecexpe))
				$parametros[] = array('@fecexpe',$data->fecexpe);
			else
				$parametros[] = array('@fecexpe','');
			if($ar->isDate($data->fecvenc))
				$parametros[] = array('@fecvenc',$data->fecvenc);
			else
				$parametros[] = array('@fecvenc','');
			if($ar->isDate($data->fecinic))
				$parametros[] = array('@fecinic',$data->fecinic);
			else
				$parametros[] = array('@fecinic','');
			$parametros[] = array('@ciddecl',$data->ciddecl);
			$parametros[] = array('@cidesta',$data->cidesta);
			$parametros[] = array('@cidocpe',$data->cidocpe);
			$parametros[] = array('@cidmant',$data->cidmant);
			$parametros[] = array('@vobserv',$data->vobserv);
			
			$parametros[] = array('@usuario',$login->user);
			$parametros[] = array('@hostnam',php_uname('n'));
			$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
			
			@$dataFicEco = $cn->ejec_store_procedura_sqlc('MntCatas.pxCEconom', $parametros, true);			
			
			if(is_numeric($dataFicEco[0][0]) && $dataFicEco[0][0]>0){
				//Nueva ficha económica
				$idsigma = $dataFicEco[0][0];
			}
			else{
				//Edita ficha económica
				$idsigma = $data->idsigma;
			}
			
			//Guardamos grid Actividades
			$oldActivi = explode('|',$data->oldActItems);			
			$dataActivi = $data->Activi;
			//Eliminamos si hicieron delete
			for($i=0;$i<count($oldActivi);$i++){
				$flag = false;
				if(count($dataActivi)){
					foreach($dataActivi as $dActivi){
						if($dActivi->idsigma==$oldActivi[$i]){
							$flag = true;
							break;
						}
					}
					if(!$flag){
						//Si ya no existen en la grilla eliminamos
						unset($parametros);
						$parametros[] = array('@busc',3);
						$parametros[] = array('@idsigma',$oldActivi[$i]);
						@$dataFicEcoAct = $cn->ejec_store_procedura_sqlc('MntCatas.pxCActivi', $parametros);
					}
				}
			}
			
			if(count($dataActivi)){
				foreach($dataActivi as $dActivi){
					$idcactivi = $dActivi->idsigma;
					$idceonom = $idsigma;
					
					if(strlen($idcactivi)>0)
						$busc = 2;
					else
						$busc = 1;
						
					unset($parametros);
					$parametros[] = array('@busc',$busc);
					$parametros[] = array('@idsigma',$idcactivi);
					$parametros[] = array('@cidfich',$idceonom);
					$parametros[] = array('@cidacti',$dActivi->cidacti);
					$parametros[] = array('@desacti',$dActivi->desacti);
					
					$parametros[] = array('@usuario',$login->user);
					$parametros[] = array('@hostnam',php_uname('n'));
					$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
					
					@$dataFicEcoAct = $cn->ejec_store_procedura_sqlc('MntCatas.pxCActivi', $parametros, true);
				}
			}
			//Guardamos grid Anuncios
			$oldAnunci = explode('|',$data->oldAnuItems);			
			$dataAnunci = $data->Anunci;			
			//Eliminamos si hicieron delete
			for($i=0;$i<count($oldAnunci);$i++){
				$flag = false;
				if(count($dataAnunci)){
					foreach($dataAnunci as $dAnunci){
						if($dAnunci->idsigma==$oldAnunci[$i]){
							$flag = true;
							break;
						}
					}
					if(!$flag){
						//Si ya no existen en la grilla eliminamos
						unset($parametros);
						$parametros[] = array('@busc',3);
						$parametros[] = array('@idsigma',$oldAnunci[$i]);
						@$dataFicEcoAnu = $cn->ejec_store_procedura_sqlc('MntCatas.pxCAnunci', $parametros);
					}
				}
			}
			
			if(count($dataAnunci)){
				foreach($dataAnunci as $dAnunci){
					$idcanunci = $dAnunci->idsigma;
					$idceonom = $idsigma;
					
					if(strlen($idcanunci)>0)
						$busc = 2;
					else
						$busc = 1;
						
					unset($parametros);
					$parametros[] = array('@busc',$busc);
					$parametros[] = array('@idsigma',$idcanunci);
					$parametros[] = array('@cidfich',$idceonom);
					$parametros[] = array('@cidanuc',$dAnunci->cidanuc);
					$parametros[] = array('@nrolado',$dAnunci->nrolado);
					$parametros[] = array('@areauto',floatval($dAnunci->areauto));
					$parametros[] = array('@areveri',floatval($dAnunci->areveri));
					$parametros[] = array('@nroexpe',$dAnunci->nroexpe);
					$parametros[] = array('@nrolice',$dAnunci->nrolice);
					if($ar->isDate(trim($dAnunci->fexpedi)))
						$parametros[] = array('@fexpedi',trim($dAnunci->fexpedi));
					else
						$parametros[] = array('@fexpedi','');
					if($ar->isDate(trim($dAnunci->fvencim)))
						$parametros[] = array('@fvencim',trim($dAnunci->fvencim));
					else
						$parametros[] = array('@fvencim','');				
					$parametros[] = array('@usuario',$login->user);
					$parametros[] = array('@hostnam',php_uname('n'));
					$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
					
					@$dataFicEcoAnu = $cn->ejec_store_procedura_sqlc('MntCatas.pxCAnunci', $parametros, true);
				}
			}
			
			echo $msj;
		}
		
		
    }	
}
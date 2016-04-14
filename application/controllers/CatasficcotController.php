<?php

class CatasficcotController extends Zend_Controller_Action
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
			
			//Recupera ficha cotitular			
			unset($parametros);
			$parametros[] = array('@busc',3);
			$parametros[] = array('@cidfich',$idficha);
			$rowFicCot = $cn->ejec_store_procedura_sqlc('MntCatas.pxCCotitu', $parametros);
			
			$idsigma = $rowFicCot[0][0];
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
		$val[] = array('#cidtipo','02','val');
		
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
		
		$evt[] = array('#btnAddCot',"click","eventCot('A');");
		$evt[] = array('#btnEditCot',"click","eventCot('E');");
		$evt[] = array('#btnCancelCot',"click","eventCot('C');");
		$evt[] = array('#btnSaveCot',"click","eventCot('S');");
		$evt[] = array('#btnDelCot',"click","eventCot('D');");
		
		$evt[] = array('#btnSearchCot',"click","searchCot();");
		
		$evt[] = array('#btnSaveFicha',"click","goToFormulario('frmficcot');");
		$evt[] = array('#btnCloseFicha',"click","closePopup('#popcatasficcot');");
		
		//Eventos datos cotitular
		$evt[] = array('#fecadqu',"datepicker","");
		$evt[] = array('#fecadqu','autotab',"{format:'date'}");
		
		//Rellena combos
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCFAdqui', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidfaqu',$fn->ContenidoCombo($arCombo,'[Seleccione]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCDeclar', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#ciddecl',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicCot[0][2])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCEFicha', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'number');
		$val[] = array('#cidesta',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowFicCot[0][3])),'html');
		
		$val[] = array('#vobserv',$rowFicCot[0][4],'val');
		
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
		
		$cidcot = $this->_request->getPost('cidcot');
    	
		$parametros[] = array('@busc',4);
		$parametros[] = array('@cidfich',$cidcot);
		$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxDCotitu', $parametros);
		
		$jsonData = array();
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
					'idsigma'=>trim($row[0]),
					'cidfich'=>trim($row[1]),
					'cidpers'=>trim($row[2]),
					'nompers'=>utf8_encode($row[12]),
					'nrodocu'=>utf8_encode($row[13]),
					'nrcotit'=>utf8_encode($row[3]),
					'nrtotal'=>utf8_encode($row[4]),
					'porcenc'=>utf8_encode($row[5]),
					'cidfaqu'=>trim($row[6]),
					'nomfaqu'=>utf8_encode($row[14]),
					'fecadqu'=>$ar->toDate($row[7])
				);
				$jsonData[] = $entry;
			}
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
			$parametros[] = array('@nrficha',trim($data->nrficha));			
			$parametros[] = array('@lote_01',trim($data->lote_01));
			$parametros[] = array('@lote_02',trim($data->lote_02));
			$parametros[] = array('@cunicat',trim($data->cunicat));			
			$parametros[] = array('@cuc_cat',trim($data->cuc_cat));			
			$parametros[] = array('@codhoja',trim($data->codhoja));
			$parametros[] = array('@ubgdpto',trim($data->ubgdpto));
			$parametros[] = array('@ubgprov',trim($data->ubgprov));
			$parametros[] = array('@ubgdist',trim($data->ubgdist));
			$parametros[] = array('@rsector',trim($data->rsector));
			$parametros[] = array('@rmanzan',trim($data->rmanzan));
			$parametros[] = array('@reflote',trim($data->reflote));
			$parametros[] = array('@redific',trim($data->redific));
			$parametros[] = array('@rentrad',trim($data->rentrad));
			$parametros[] = array('@refpiso',trim($data->refpiso));
			$parametros[] = array('@refunid',trim($data->refunid));
			$parametros[] = array('@dc_cata',trim($data->dc_cata));
			$parametros[] = array('@cidfich',trim($data->cidfich));			
			$parametros[] = array('@idcontr',trim($data->idcontr));
			$parametros[] = array('@idpredi',trim($data->idpredi));
			$parametros[] = array('@unpredi',trim($data->unpredi));
			
			if(trim($data->dfirmas)!='1'){
				$data->cidecla = '';
				$data->nombr01 = '';
				$data->dfirmas = '0';
				$data->fedecla = '';
			}
			$parametros[] = array('@cidecla',trim($data->cidecla));
			$parametros[] = array('@nombr01',trim($data->nombr01));
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
			$parametros[] = array('@nombr02',trim($data->nombr02));
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
			$parametros[] = array('@nombr03',trim($data->nombr03));
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
			$parametros[] = array('@nombr04',trim($data->nombr04));
			if($ar->isDate(trim($data->feverif)))
				$parametros[] = array('@feverif',trim($data->feverif));
			else
				$parametros[] = array('@feverif','');
			$parametros[] = array('@vfirmas',intval($data->vfirmas));
			
			$parametros[] = array('@nroregi',trim($data->nroregi));
			$parametros[] = array('@vobserv',trim($data->vobserv));
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
						
			//Guardamos la ficha cotitular
			unset($parametros);
			$parametros[] = array('@busc',$busc);
			$parametros[] = array('@idsigma',$idsigma);
			$parametros[] = array('@cidfich',$idficha);
			
			$parametros[] = array('@ciddecl',trim($data->ciddecl));
			$parametros[] = array('@cidesta',trim($data->cidesta));
			$parametros[] = array('@vobserv',$data->vobserv);
						
			$parametros[] = array('@usuario',$login->user);
			$parametros[] = array('@hostnam',php_uname('n'));
			$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
			
			@$dataFicCot = $cn->ejec_store_procedura_sqlc('MntCatas.pxCCotitu', $parametros, true);
			
			if(is_numeric($dataFicCot[0][0]) && $dataFicCot[0][0]>0){
				//Nueva ficha cotitular
				$idsigma = $dataFicCot[0][0];
			}
			else{
				//Edita ficha cotitular
				$idsigma = $data->idsigma;
			}
			
			//Guardamos grid Cotitulares
			$oldCot = explode('|',$data->oldCotItems);			
			$dataCot = $data->Coti;
			//Eliminamos si hicieron delete
			for($i=0;$i<count($oldCot);$i++){				
				$flag = false;
				if(count($dataCot)){
					foreach($dataCot as $dCot){
						if($dCot->idsigma==$oldCot[$i]){
							$flag = true;
							break;
						}
					}
					if(!$flag){
						//Si ya no existen en la grilla eliminamos
						unset($parametros);
						$parametros[] = array('@busc',3);
						$parametros[] = array('@idsigma',$oldCot[$i]);
						@$dataFicCotDet = $cn->ejec_store_procedura_sqlc('MntCatas.pxDCotitu', $parametros);
					}
				}
				else{
					//Si ya no existen en la grilla eliminamos
					unset($parametros);
					$parametros[] = array('@busc',3);
					$parametros[] = array('@idsigma',$oldCot[$i]);
					@$dataFicCotDet = $cn->ejec_store_procedura_sqlc('MntCatas.pxDCotitu', $parametros);
				}
			}
			
			if(count($dataCot)){
				foreach($dataCot as $dCot){
					$iddcotitu = $dCot->idsigma;
					$idccotitu = $idsigma;
					
					if(strlen($iddcotitu)>0)
						$busc = 2;
					else
						$busc = 1;
						
					unset($parametros);
					$parametros[] = array('@busc',$busc);
					$parametros[] = array('@idsigma',$iddcotitu);
					$parametros[] = array('@cidfich',$idccotitu);				
					$parametros[] = array('@cidpers',$dCot->cidpers);
					$parametros[] = array('@nrcotit',$dCot->nrcotit);
					$parametros[] = array('@nrtotal',$dCot->nrtotal);
					$parametros[] = array('@porcenc',floatval($dCot->porcenc));
					$parametros[] = array('@cidfaqu',$dCot->cidfaqu);
					if($ar->isDate(trim($dCot->fecadqu)))
						$parametros[] = array('@fecadqu',trim($dCot->fecadqu));
					else
						$parametros[] = array('@fecadqu','');
					
					$parametros[] = array('@usuario',$login->user);
					$parametros[] = array('@hostnam',php_uname('n'));
					$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
					
					@$dataFicCotDet = $cn->ejec_store_procedura_sqlc('MntCatas.pxDCotitu', $parametros, true);
				}						
			}
			
			echo $msj;
		}
				
    }	
}
<?php

class CataspersonaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$mod = $this->_request->getParam('mod','');
    	$this->view->mod = $mod;
    	
		$this->view->title = "Búsqueda de Contribuyentes";
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnSearchCPerson',"click","buscarCPerson()");
		//dest=1 -> Retorna a listado
		//dest=2 -> Retorna a buscar
		$evt[] = array('#btnNewCPerson',"click","showPopup('cataspersona/formu?dest=1','#popcperson','750','570','Nuevo Contribuyente');");
		$evt[] = array('#btnRefreshCPerson',"click","actualizarCPerson()");
		
		$fn->PintarEvento($evt);
		
    }
    
    public function formuAction()
    {    		    	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$idsigma = $this->_request->getParam('idsigma','');
		$dest = $this->_request->getParam('dest','');
		
		$this->view->dest = $dest;
		
		if(strlen($idsigma)>0)
		{
			$parametros[] = array('@busc',4);
			$parametros[] = array('@idsigma',$idsigma);
			$rowContri = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPerson', $parametros);
			
			$val[] = array('#idsigmax',$rowContri[0][0],'val');
			$val[] = array('#cidcont',trim($rowContri[0][1]),'val');
			$val[] = array('#old_cidcont',trim($rowContri[0][1]),'val');
			$val[] = array('#numedoc',trim($rowContri[0][7]),'val');
			$val[] = array('#old_numedoc',trim($rowContri[0][7]),'val');
			$val[] = array('#nombres',$rowContri[0][2],'val');
			$val[] = array('#apatern',$rowContri[0][3],'val');
			$val[] = array('#amatern',$rowContri[0][4],'val');
			$val[] = array('#numruc',trim($rowContri[0][44]),'val');
			$val[] = array('#razsoc',$rowContri[0][2],'val');			
			$val[] = array('#nroresop',$rowContri[0][10],'val');
			$val[] = array('#nrbolet',$rowContri[0][11],'val');		
			$val[] = array('#iniexon',$ar->toDate($rowContri[0][12]),'val');
			$val[] = array('#finexon',$ar->toDate($rowContri[0][13]),'val');
			$val[] = array('#nrtelef',$rowContri[0][18],'val');
			$val[] = array('#nranexo',$rowContri[0][19],'val');
			$val[] = array('#nromfax',$rowContri[0][20],'val');
			$val[] = array('#ve_mail',$rowContri[0][21],'val');
			$val[] = array('#nromunip',$rowContri[0][23],'val');
			$val[] = array('#nmbredip',$rowContri[0][24],'val');
			$val[] = array('#nrointep',$rowContri[0][25],'val');
			$val[] = array('#zosecetp',$rowContri[0][27],'val');
			$val[] = array('#manzanap',$rowContri[0][28],'val');
			$val[] = array('#cidlotep',$rowContri[0][29],'val');
			$val[] = array('#sublotep',$rowContri[0][30],'val');
			
			//datos del cónyuge
			$val[] = array('#cidconyu',$rowContri[0][31],'val');
			$val[] = array('#txtTipDocCon',$rowContri[0][36],'val');
			$val[] = array('#txtNumDocCon',$rowContri[0][37],'val');
			$val[] = array('#txtNomCon',$rowContri[0][38],'val');
			$val[] = array('#txtApePatCon',$rowContri[0][39],'val');
			$val[] = array('#txtApeMatCon',$rowContri[0][40],'val');
		}
				
		$evt[] = array('#tabsPerson',"tabs","");
		
		$fun[] = array("changeTtitu('".$rowContri[0][5]."')");
		
		unset($parametros);
		$parametros[] = array('@busc',1);	
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTperso', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidpersx',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][5])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);	
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCECivil', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidcivi',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][8])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);	
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTDocum', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#ciddocu',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][6])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);	
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPJurid', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidjuri',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][9])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);	
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCETitul', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cidespe',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][14])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCUbigeo', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#ciddpto',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][15])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',2);
		$parametros[] = array('@codidep',trim($rowContri[0][15]));
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCUbigeo', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,2,4,'');
		$val[] = array('#cidprov',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][16])),'html');
		
		unset($parametros);
		$parametros[] = array('@busc',3);
		$parametros[] = array('@codidep',trim($rowContri[0][15]));
		$parametros[] = array('@codipro',trim($rowContri[0][16]));
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCUbigeo', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,3,4,'');
		$val[] = array('#ciddist',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][17])),'html');
		
		unset($parametros);
		$parametros[] = array('@msquery',8);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMvias', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,4,5,'');
		$val[] = array('#cidvias',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][22])),'html');
		$val[] = array('#nomvia',$rowContri[0][42],'val');
		
		unset($parametros);
		$parametros[] = array('@msquery',8);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxChaburb', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,4,5,'');
		$val[] = array('#cidhabu',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowContri[0][26])),'html');
		$val[] = array('#nomhu',$rowContri[0][43],'val');
		
		$evt[] = array('#btnViaC',"click","selVI();");
		$evt[] = array('#btnHabU',"click","selHU();");
		
		$evt[] = array('#iniexon',"datepicker","");
		$evt[] = array('#iniexon','autotab',"{format:'date'}");
		$evt[] = array('#finexon',"datepicker","");
		$evt[] = array('#finexon','autotab',"{ format: 'date' }");
		$evt[] = array('#ciddpto',"change","cargaUbigeo('#cidprov',2,this.value,'');");
		$evt[] = array('#cidprov',"change","cargaUbigeo('#ciddist',3,$('#ciddpto').val(),this.value);");
		$evt[] = array('#ciddist',"change","selectDirPerson();");
		$evt[] = array('#nombres',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#apatern',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#amatern',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#cidcont',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#cidcont',"blur","fullZeros(this,7);");
		
		$evt[] = array('#cidpersx',"change","changeTtitu(this.value);");
		
		$evt[] = array('#numedoc',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#numruc',"keypress","return validaTeclas(event,'number');");
		
		$evt[] = array('#cidcont',"focus","");
		
		//tPeson: CON=>Cónyuge, COT=>Cotitular, DEC=>Declarante
		$evt[] = array('#btnSelectCon',"click","showPopup('cataspersona/buscarcon','#popcatascon','600','400','Buscar Cónyuge');");
		$evt[] = array('#btnConRentas',"click","showPopup('catasconrentas/index','#popcatasconrentas','1000','400','Buscar Contribuyente Rentas');");
		$evt[] = array('#btnGrabaContri',"click","goToFormulario('frmcperson');");
		$evt[] = array('#btnSalirContri',"click","closePopup('#popcperson');");		
		
		$fun[] = array("selectDirPerson();");
		$fun[] = array("selVI();");
		$fun[] = array("selHU();");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		$fn->EjecutarFuncion($fun);
    }
    
	public function validaAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			
			$numedoc = $this->_request->getPost('numedoc');
			$old_numedoc = $this->_request->getPost('old_numedoc');
			
			$flag = true;
			
			//Si se está editando
			if($numedoc==$old_numedoc)
				$numedoc = "-";
			
			$parametros[] = array('@busc',6);
			$parametros[] = array('@idsigma',null);
			$parametros[] = array('@numedoc',$numedoc);
			$parametros[] = array('@cidcont',null);
			
			$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPerson', $parametros);
			
			if(!empty($rows[0][8]))
				$flag = false;
			else
				$flag = true;
			
			echo $flag;
		}
	}
	
	public function ubigeoAction()
    {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			
			$busc = $this->_request->getPost('busc');
			$codidep = $this->_request->getPost('codidep');
			$codipro = $this->_request->getPost('codipro');
			
			$parametros[] = array('@busc',$busc);			
			$parametros[] = array('@codidep',$codidep);
			$parametros[] = array('@codipro',$codipro);			
			$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCUbigeo', $parametros);
							
			print("<option value=''>[Seleccione]</option>");
			foreach($rows AS $row)
				print("<option value='".utf8_encode($row[$busc])."'>".utf8_encode($row[4])."</option>");
		}		
	}
	
    public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$rdcriterio = $_REQUEST['rdcriterio'];
    	$criterio = $_REQUEST['criterio'];
		$tipo = $_REQUEST['tipo'];
    	
    	switch($rdcriterio)
    	{
    		case 'C': $idsigma = $criterio; break;
    		case 'N': $nombres = $criterio; break;
    		case 'D': $numedoc = $criterio; break;
			case 'O': $cidcont = $criterio; break;
    	}
		
    	//Para el total
    	$parametros[] = array('@busc',8);
		$parametros[] = array('@idsigma',$idsigma);
		$parametros[] = array('@nombres',$nombres);
		$parametros[] = array('@numedoc',$numedoc);
		$parametros[] = array('@cidcont',$cidcont);
		$parametros[] = array('@tipo',$tipo);
		
		$rowsTotal = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPerson', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',7);
		$parametros[] = array('@idsigma',$idsigma);
		$parametros[] = array('@nombres',$nombres);
		$parametros[] = array('@numedoc',$numedoc);
		$parametros[] = array('@cidcont',$cidcont);
		$parametros[] = array('@tipo',$tipo);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);		
		
		$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPerson', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'idsigma'=>$row[0],				  
						'nombres'=>utf8_encode($row[1])." ".utf8_encode($row[2])." ".utf8_encode($row[3]),
						'nombre'=>utf8_encode($row[1]),
						'apatern'=>utf8_encode($row[2]),
						'amatern'=>utf8_encode($row[3]),
						'tipodoc'=>utf8_encode($row[4]),
						'numedoc'=>utf8_encode($row[5]),
						'direccion'=>"",
						'cidcont'=>$row[6]
				);
				$jsonData['rows'][] = $entry;
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
			
			$idsigma = $this->_request->getPost('idsigmax');			
			$tPerson = $this->_request->getPost('cidpersx');
			
			$ciddocu = "";
			$nombres = "";
			
			if($tPerson=='1'){
				//Persona natural
				$ciddocu = $this->_request->getPost('ciddocu');
				$nombres = $this->_request->getPost('nombres');
				$cidconyu = $this->_request->getPost('cidconyu');
			}
			if($tPerson=='2'){
				//Persona jurídica
				$ciddocu = '00';
				$nombres = $this->_request->getPost('razsoc');
				$cidconyu = '';
			}
			
			
			if(strlen($idsigma)>0)
				$tip = 2;
			else
				$tip = 1;
				
			$parametros[] = array('@busc',$tip);
			$parametros[] = array('@idsigma',$idsigma);
			$parametros[] = array('@cidcont',$this->_request->getPost('cidcont'));
			$parametros[] = array('@nombres',$nombres);
			$parametros[] = array('@apatern',$this->_request->getPost('apatern'));
			$parametros[] = array('@amatern',$this->_request->getPost('amatern'));
			$parametros[] = array('@cidpers',$this->_request->getPost('cidpersx'));			
			$parametros[] = array('@ciddocu',$ciddocu);
			$parametros[] = array('@numedoc',$this->_request->getPost('numedoc'));
			$parametros[] = array('@numruc',$this->_request->getPost('numruc'));
			$parametros[] = array('@cidcivi',$this->_request->getPost('cidcivi'));
			$parametros[] = array('@cidjuri',$this->_request->getPost('cidjuri'));
			$parametros[] = array('@nroreso',$this->_request->getPost('nroresop'));
			$parametros[] = array('@nrbolet',$this->_request->getPost('nrbolet'));
			$parametros[] = array('@iniexon',$this->_request->getPost('iniexon'));
			$parametros[] = array('@finexon',$this->_request->getPost('finexon'));
			$parametros[] = array('@cidespe',$this->_request->getPost('cidespe'));
			$parametros[] = array('@ciddpto',$this->_request->getPost('ciddpto'));
			$parametros[] = array('@cidprov',$this->_request->getPost('cidprov'));
			$parametros[] = array('@ciddist',$this->_request->getPost('ciddist'));
			$parametros[] = array('@nrtelef',$this->_request->getPost('nrtelef'));
			$parametros[] = array('@nranexo',$this->_request->getPost('nranexo'));
			$parametros[] = array('@nromfax',$this->_request->getPost('nromfax'));
			$parametros[] = array('@ve_mail',$this->_request->getPost('ve_mail'));
			$parametros[] = array('@cidvias',$this->_request->getPost('cidvias'));
			$parametros[] = array('@nromuni',$this->_request->getPost('nromunip'));
			$parametros[] = array('@nmbredi',$this->_request->getPost('nmbredip'));
			$parametros[] = array('@nrointe',$this->_request->getPost('nrointep'));
			$parametros[] = array('@cidhabu',$this->_request->getPost('cidhabu'));
			$parametros[] = array('@zosecet',$this->_request->getPost('zosecetp'));
			$parametros[] = array('@manzana',$this->_request->getPost('manzanap'));
			$parametros[] = array('@cidlote',$this->_request->getPost('cidlotep'));
			$parametros[] = array('@sublote',$this->_request->getPost('sublotep'));
			$parametros[] = array('@cidconyu',$this->_request->getPost('cidconyu'));
			$parametros[] = array('@nomvia',$this->_request->getPost('nomvia'));
			$parametros[] = array('@nomhu',$this->_request->getPost('nomhu'));
			$parametros[] = array('@usuario',$login->user);
			$parametros[] = array('@hostnam',php_uname('n'));
			$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
											
			$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPerson', $parametros);
			
			echo "Se grab&oacute; correctamente!|".$rows[0][0]."|".$rows[0][1]."|".$rows[0][2]."|".$rows[0][3]."|".$rows[0][4];
    	}    	
    }
	
	public function eliminarAction()
    {		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
		
    		$cn = new Model_DbDatos_Datos();
    		$login = new Zend_Session_Namespace('login');
			
			$idsigma = $this->_request->getParam('idsigma','');
			
			//echo $codigo."<br/>";
			$parametros[] = array('@busc',5);
			$parametros[] = array('@idsigma',$idsigma);
			$parametros[] = array('@usuario',$login->user);
			$parametros[] = array('@hostnam',php_uname('n'));
			$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
			
			@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPerson', $parametros);	
			
			echo "Registro eliminado correctamente!";
		}

	}
	
	public function buscarAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
		$tPerson = $this->_request->getParam('tPerson','');
		$this->view->tPerson = $tPerson;
		
		$evt[] = array('#btnSearchCPersonx',"click","buscarCPersonx()");
		
		$evt[] = array('#btnNewCPersonx',"click","showPopup('cataspersona/formu?dest=1','#popcperson','750','570','Nuevo Contribuyente');");		
		$evt[] = array('#btnCloseCPersonx',"click","closePopup('#popcatasperson')");
		
		$fn->PintarEvento($evt);
		
    }
	
	public function buscarcotiAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnSearchCoti',"click","buscarCoti()");
		
		//dest=1 -> Retorna a listado
		//dest=2 -> Retorna a buscar
		$evt[] = array('#btnNewCoti',"click","showPopup('cataspersona/formu?dest=3','#popcperson','750','570','Nueva Persona');");
		
		$evt[] = array('#btnCloseCoti',"click","closePopup('#popcatascoti')");
		
		$fn->PintarEvento($evt);
		
    }
	
	public function buscarconAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnSearchCon',"click","buscarCPersony()");
		
		//$evt[] = array('#btnNewCon',"click","showPopup('cataspersona/formu?dest=3','#popcperson','750','570','Nueva Persona');");
		$evt[] = array('#btnNewCon',"click","showPopup('cataspersona/personx','#popcpersonx','340','150','Nueva Persona');");
		$evt[] = array('#btnCloseCon',"click","closePopup('#popcatascon')");
		
		$fn->PintarEvento($evt);
		
    }
	
	public function personxAction()
    {    		    	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$idsigma = $this->_request->getParam('idsigmaxx','');
		
		if(strlen($idsigma)>0)
		{
			$parametros[] = array('@busc',4);
			$parametros[] = array('@idsigma',$idsigma);
			$rowPerson = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPerson', $parametros);
			
			$val[] = array('#idsigmaxx',$rowPerson[0][0],'val');			
			$val[] = array('#numedocxx',trim($rowPerson[0][7]),'val');
			$val[] = array('#old_numedocxx',trim($rowPerson[0][7]),'val');
			$val[] = array('#nombresxx',$rowPerson[0][2],'val');
			$val[] = array('#apaternxx',$rowPerson[0][3],'val');
			$val[] = array('#amaternxx',$rowPerson[0][4],'val');
		}
		
		unset($parametros);
		$parametros[] = array('@busc',1);	
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCTDocum', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#ciddocuxx',$fn->ContenidoCombo($arCombo,'[Seleccione]',trim($rowPerson[0][6])),'html');
		
		$evt[] = array('#numedocxx',"keypress","return validaTeclas(event,'number');");
		
		$evt[] = array('#btnGrabaPersonxx',"click","goToFormulario('frmcpersonx');");
		$evt[] = array('#btnSalirPersonxx',"click","closePopup('#popcpersonx')");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
	public function grabarxAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			$login = new Zend_Session_Namespace('login');
			
			$idsigma = $this->_request->getPost('idsigmaxx');			
			
			if(strlen($idsigma)>0)
				$tip = 10;
			else
				$tip = 9;
				
			$parametros[] = array('@busc',$tip);
			$parametros[] = array('@idsigma',$idsigma);
			$parametros[] = array('@cidpers','1');
			$parametros[] = array('@nombres',$this->_request->getPost('nombresxx'));
			$parametros[] = array('@apatern',$this->_request->getPost('apaternxx'));
			$parametros[] = array('@amatern',$this->_request->getPost('amaternxx'));
			$parametros[] = array('@ciddocu',$this->_request->getPost('ciddocuxx'));
			$parametros[] = array('@numedoc',$this->_request->getPost('numedocxx'));
			$parametros[] = array('@cidcivi','02');
			$parametros[] = array('@usuario',$login->user);
			$parametros[] = array('@hostnam',php_uname('n'));
			$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
											
			@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCPerson', $parametros);
			
			echo "Se grabo correctamente";
    	}    	
    }
}
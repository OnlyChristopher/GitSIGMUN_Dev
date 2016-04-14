<?php

class CatasfichasController extends Zend_Controller_Action
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
		
		$this->view->title = "Búsqueda de Fichas";	
		
		$evt[] = array('#contentBox',"tabs","");
		
		$parametros[] = array('@busc',1);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCFichas', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#cmbTipo',$fn->ContenidoCombo($arCombo,'[Todos]',''),'html');
		
		$evt[] = array('#txtSector,#txtManzana,#txtLote,#txtNficha',"keypress","return validaTeclas(event,'number');");		
		$evt[] = array('#txtSector',"blur","fullZeros(this,2);");
		$evt[] = array('#txtManzana',"blur","fullZeros(this,3);");
		$evt[] = array('#txtLote',"blur","fullZeros(this,3);");
		$evt[] = array('#txtNficha',"blur","fullZeros(this,7);");
		
		$evt[] = array('.chkSubFich',"click","selectSubFicha(this);");
		
		$evt[] = array('#btnSearchFic',"click","buscarCMficha()");
		$evt[] = array('#btnPrintFicha',"click","printFicha()");
		
		$evt[] = array('#btnNewFicha',"click","newFicha();");
		
		$fn->PintarValor($val);
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
    	
    	$periodo = '2013';
    	$rsector = $_REQUEST['rsector'];
		$rmanzan = $_REQUEST['rmanzan'];
		$reflote = $_REQUEST['reflote'];
		$cidtipo = $_REQUEST['cidtipo'];
		$nrficha = $_REQUEST['nrficha'];
		$nombr01 = $_REQUEST['nombr01'];
		$nombr02 = $_REQUEST['nombr02'];
		$nombr03 = $_REQUEST['nombr03'];
    			
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@periodo',$periodo);
		$parametros[] = array('@rsector',$rsector);
		$parametros[] = array('@rmanzan',$rmanzan);
		$parametros[] = array('@reflote',$reflote);
		$parametros[] = array('@cidtipo',$cidtipo);
		$parametros[] = array('@nrficha',$nrficha);
		$parametros[] = array('@nombr01',$nombr01); //Titular
		$parametros[] = array('@nombr02',$nombr02); //Zona / sector / etapa
		$parametros[] = array('@nombr03',$nombr03); //Habilitación Urbana
		$rowsTotal = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@periodo',$periodo);
		$parametros[] = array('@rsector',$rsector);
		$parametros[] = array('@rmanzan',$rmanzan);
		$parametros[] = array('@reflote',$reflote);
		$parametros[] = array('@cidtipo',$cidtipo);
		$parametros[] = array('@nrficha',$nrficha);
		$parametros[] = array('@nombr01',$nombr01); //Titular
		$parametros[] = array('@nombr02',$nombr02); //Zona / sector / etapa
		$parametros[] = array('@nombr03',$nombr03); //Habilitación Urbana
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);		
		
		$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'idficha'=>$row[0],
						'periodo'=>$row[1],
						'nrficha'=>utf8_encode($row[2]),
						'cidtipo'=>$row[3],
						'fichtipo'=>utf8_encode($row[4]),						
						'nflote'=>utf8_encode($row[5]),
						'rsector'=>utf8_encode($row[6]),
						'rmanzan'=>utf8_encode($row[7]),
						'reflote'=>utf8_encode($row[8]),
						'redific'=>utf8_encode($row[9]),
						'rentrad'=>utf8_encode($row[10]),
						'refpiso'=>utf8_encode($row[11]),
						'refunid'=>utf8_encode($row[12]),
						'dc_cata'=>utf8_encode($row[13]),
						'refcat'=>utf8_encode($row[14]),
						'cidpers'=>utf8_encode($row[15]),
						'titular'=>utf8_encode($row[16]),
						'zona'=>utf8_encode($row[17]),
						'hurba'=>utf8_encode($row[18])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	
	public function eliminarAction()
    {		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
		
    		$cn = new Model_DbDatos_Datos();
    		$login = new Zend_Session_Namespace('login');
			
			$idficha = $this->_request->getParam('idficha','');
			$cidtipo = $this->_request->getParam('cidtipo','');
			
			//echo $codigo."<br/>";
			$parametros[] = array('@busc',3);
			$parametros[] = array('@idficha',$idficha);
			$parametros[] = array('@cidtipo',$cidtipo);
			
			@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);	
			
			echo "Registro eliminado correctamente!";
		}
	}
	
	public function enlaceAction()
    {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
    	$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();		
		
		$idficha = $this->_request->getParam('idficha','');
		
		//Recupera ficha cabecera
		$parametros[] = array('@busc',4);
		$parametros[] = array('@idficha',$idficha);
		$rowFicha = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
		
		$val[] = array('#idficha',$rowFicha[0][0],'val');
		$val[] = array('#cidtipo',$rowFicha[0][3],'val');
		$val[] = array('#txtTipoficha',$rowFicha[0][47],'val');
		$val[] = array('#txtNroficha',$rowFicha[0][4],'val');
		
		$evt[] = array('#txtDestficha',"keypress","return validaTeclas(event,'number');");		
		$evt[] = array('#txtDestficha',"blur","fullZeros(this,7);");
		
		$evt[] = array('#btnGrabaEnlace',"click","goToFormulario('frmenlace');");
		$evt[] = array('#btnSalirEnlace',"click","closePopup('#popcatasenlace');");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
    }
	
	public function grabarenlaceAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$login = new Zend_Session_Namespace('login');
			
			$idficha = $this->_request->getPost('idficha');
			$cidtipo = $this->_request->getPost('cidtipo');
			$nro = $this->_request->getPost('txtNroficha');
			
			//Recuperamos la ficha a enlazar
			$nrficha = $this->_request->getPost('txtDestficha');
			
			$parametros[] = array('@nrficha',$nrficha);
			@$rowsDest = $cn->ejec_store_procedura_sqlc('MntCatas.pxRptCatas', $parametros);
			
			//Para grabar
			unset($parametros);
			$parametros[] = array('@busc',$tip);
			$parametros[] = array('@idficha',$idficha);
			$parametros[] = array('@cidfich',$rowsDest[0][0]);
			$parametros[] = array('@usuario',$login->user);
			$parametros[] = array('@hostnam',php_uname('n'));
			$parametros[] = array('@fegraba',date("d/m/Y H:m:s"));
			
			if(count($rowsDest)){
				if($rowsDest[0][0]!=$cidfich){
					switch($cidtipo){
						case '01':
							if($rowsDest[0][3]=='04'){
								@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
								$msj = "La Ficha nro ".$nro." se enlaz&oacute; correctamente a la Ficha nro ".$rowsDest[0][4]." !";
							}
							else
								$msj = "La ficha destino debe ser una Ficha de Bien Com&uacute;n!";
						break;
						case '02':
							if($rowsDest[0][3]=='01'){
								@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
								$msj = "La Ficha nro ".$nro." se enlaz&oacute; correctamente a la Ficha nro ".$rowsDest[0][4]." !";
							}
							else
								$msj = "La ficha destino debe ser una Ficha Individual!";
						break;
						case '03':
							if($rowsDest[0][3]=='01'){
								@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
								$msj = "La Ficha nro ".$nro." se enlaz&oacute; correctamente a la Ficha nro ".$rowsDest[0][4]." !";
							}
							else
								$msj = "La ficha destino debe ser una Ficha Individual!";
						break;
						case '04':
							if($rowsDest[0][3]=='04'){
								@$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
								$msj = "La Ficha nro ".$nro." se enlaz&oacute; correctamente a la Ficha nro ".$rowsDest[0][4]." !";
							}
							else
								$msj = "La ficha destino debe ser una Ficha de Bien Com&uacute;n!";
						break;
					}
				}
				else
					$msj = "No puede enlazar la ficha a si misma!";
			}
			else
				$msj = "No se encontr&oacute; la ficha destino nro ".$nrficha." !";
			
			echo $msj;
    	}    	    	
    }
	
	public function mapaAction()
    {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		
		$idficha = $this->_request->getParam('idficha','');
		
		$parametros[] = array('@busc',4);
		$parametros[] = array('@idficha',$idficha);
		$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
		
		$strHtml = "<table width='100%'>";
		$strHtml .= "<tr>";
			$strHtml .= "<td class='tree_node_mas'></td><td colspan='100' class='tree_title'><b> Ficha N° ".$rows[0][4]." - ".$rows[0][5]."/".$rows[0][6]." - ".$rows[0][47]."</b></td>";
		$strHtml .= "</tr>";
		
		unset($parametros);
		$parametros[] = array('@busc',8);
		$parametros[] = array('@cidfich',$idficha);
		$rows0 = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
				
		if(count($rows0)){
			foreach($rows0 as $row0){
				$strHtml .= "<tr>";
					$strHtml .= "
								<td class='tree_node_blank'></td>
								<td class='tree_node_mas'></td>
								<td colspan='99' class='tree_title'> Ficha N° ".$row0[4]." - ".$row0[5]."/".$row0[6]." - ".$row0[47]."</td>";
				$strHtml .= "</tr>";
				
				unset($parametros);
				$parametros[] = array('@busc',8);
				$parametros[] = array('@cidfich',$row0[0]);
				$rows1 = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros);
				if(count($rows1)){
					foreach($rows1 as $row1){
						$strHtml .= "<tr>";
							$strHtml .= "
										<td class='tree_node_blank'></td>
										<td class='tree_node_blank'></td>
										<td class='tree_node_mas'></td>
										<td class='tree_title'> Ficha N° ".$row1[4]." - ".$row1[5]."/".$row1[6]." - ".$row1[47]."</td>";
						$strHtml .= "</tr>";				
					}
				}
			}			
		}
		
		$strHtml .= "</table>";
		
		$this->view->strHtml = $strHtml;
		
		$evt[] = array('#btnCloseMapa',"click","closePopup('#popcatasmapa');");
		
		$fn->PintarEvento($evt);		
    }
	
	public function validaAction() {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
					
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			
			$nrficha = $this->_request->getPost('nrficha');
			$nrficha = intval($nrficha);
			$nrficha_old = $this->_request->getPost('nrficha_old');
			$nrficha_old = intval($nrficha_old);
			$cidtipo = $this->_request->getPost('cidtipo');
			
			if($nrficha != $nrficha_old){
				$parametros[] = array("@busc",9);
				$parametros[] = array("@nrficha",$ar->AddZeros($nrficha,7));
				$parametros[] = array("@cidtipo",$cidtipo);
				$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCMficha', $parametros, true);
					
				if(count($rows)>0)
					echo false;
				else
					echo true;
			}
			else
				echo true;			
		}
	}
}
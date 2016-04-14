<?php

/**
 * MantbusquedaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class MantbusquedaController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated MantbusquedaController::indexAction() default action
	}
	
	public function busquedaAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		$this->view->tipo=$this->_request->getParam('pred','');
		
		$evt[] = array('#btnBusquedacri',"button","");
		$fn->PintarEvento($evt);
		
		if ($this->_request->isPost())
		{
			$this->_helper->viewRenderer->setNoRender ();
			$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
			//$radio=$this->_request->getPost('rdbCriterios');
			
			//if($radio==1)
			//{

			$parametros[0] = array("@msquery",1);
			$parametros[1] = array("@nombre_via",trim($this->_request->getPost('txtCriterio')));
			$rowZona = $cn->ejec_store_procedura_sql('Rentas.SP_vw_Mvias',$parametros); 		    
			//var_dump($parametros);
			//}
			$cadZona='';
			$cadZona='<table><tr><th><center><b>Codigo</b></center></th><th style="display: none;"><center><b>CodZona</b></center></th><th><center><b>Zona</b></center></th><th style="display: none;"><center><b>CodUrbanizaci&oacute;n</b></center></th><th><center><b>Urbanizaci&oacute;n</b></center></th><th><center><b>V&iacute;a</b></center></th><th><center><b></b></center></th></tr>';
				for($i = 0; $i < count ( $rowZona ); $i ++) 
				{
					$cadZona.="<tr><td>".$rowZona[$i][0]."</td>";
					$cadZona.="<td style='display: none;'>".$rowZona[$i][1]."</td>";
					$cadZona.="<td>".$rowZona[$i][2]."</td>";
					$cadZona.="<td style='display: none;'>".$rowZona[$i][3]."</td>";
					$cadZona.="<td>".$rowZona[$i][4]." ".$rowZona[$i][5]."</td>";
					$cadZona.="<td>".$rowZona[$i][6]." ".$rowZona[$i][7]."</td>";
					$cadZona.='<td><input type="button" id="btnMostrando" onclick="mostrarTodo('."'".trim($rowZona[$i][0])."'".','."'".trim($rowZona[$i][1])."'".','."'".trim($rowZona[$i][2])."'".','."'".trim($rowZona[$i][3])."'".','."'".trim($rowZona[$i][4])." ".trim($rowZona[$i][5])."'".','."'".trim($rowZona[$i][7])."'".','."'".trim($rowZona[$i][4])." ".trim($rowZona[$i][5])."'".','."'".trim($rowZona[$i][0])."'".','."'".trim($rowZona[$i][7])."'".','."'".trim($rowZona[$i][8])."'".')" value="OK"></td></tr>';
					//$cadZona.='<td><input type="button" id="btnMostrando" onclick="mostrarZona('."'".trim($rowZona[$i][0])."'".','."'".trim($rowZona[$i][1])."'".','."'".trim($rowZona[$i][2])."'".','."'".trim($rowZona[$i][3])."'".','."'".trim($rowZona[$i][4])." ".trim($rowZona[$i][5])."'".','."'".trim($rowZona[$i][7])."'".')" value="OK"></td></tr>';
				}	
			$cadZona.='</table>';	
		
			$val[] = array('#detallesBusqueda',$cadZona,'html');
			$evt[] = array('.btnMostrando',"button","");
			$fn->PintarValor($val);
			$fn->PintarEvento($evt);
		}
	}
	
	public function buscarAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
				
		$evt[] = array('#btnBusquedacri',"click","buscarDatos()");
						
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
    	
    	//$rdcriterio = trim($_REQUEST['rdcriterio']);
    	$criterio = trim($_REQUEST['criterio']);

    	/*switch($rdcriterio)
    	{
    		case 'C': $codigo = $criterio;break;
    		case 'N': $urbanizacion = $criterio;break;
    		case 'D': $zona = $criterio;break;
    		
    	}*/
    	//$codigo = $criterio;
    	//Para el total
    	$parametros[] = array('@msquery',3);
		$parametros[] = array('@nombre_via',$criterio);
		
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.SP_vw_Mvias', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',2);
		$parametros[] = array('@nombre_via',$criterio);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.SP_vw_Mvias', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>trim($row[0]),
						'codzona'=>trim($row[1]),
						'nomzona'=>utf8_encode($row[2]),
						'codurba'=>trim($row[3]),
						'nomurba'=>utf8_encode($row[4])." ".utf8_encode($row[5]),
						'nomvia'=>utf8_encode($row[7]),
						'arancel'=>utf8_encode($row[8])
				
						//'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
				);
			
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	

}


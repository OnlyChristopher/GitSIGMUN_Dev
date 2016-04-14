<?php

/**
 * MantpobladoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class DirpredController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Listado de Predios";
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		//Listado de annos
		
		$bus_anno="";
		for($i=1992;$i<=date('Y');$i++){
		$bus_anno.="<option value='".$i."'";
			if($i==date('Y'))
			{
				$bus_anno.=" selected ";
			}
		$bus_anno.=">".$i."</option>";
		}
		$this->view->Listado_annos=$bus_anno;
		
		//LISTADO DE VIAS
		$tipo1="";
		$tipo2="";
		unset($parametros);
			$parametros[] = array('@busc',6);	
			$tipoVIA = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $parametros);
			$arComboTipoVIA = $ar->RegistrosCombo($tipoVIA,0,1);
			$val[] = array('#cmbvias',$fn->ContenidoCombo($arComboTipoVIA,'[Seleccione]',$tipo1),'html');
			
			
		//LISTADO DE DENOMINACIONES
		unset($parametros);
			$parametros[] = array('@busc',5);	
			$tipoDENO = $cn->ejec_store_procedura_sql('[Rentas].[sp_Mant_Vias]', $parametros);
			$arComboTipoDENO = $ar->RegistrosCombo($tipoDENO,0,1);
			$val[] = array('#cmbdeno',$fn->ContenidoCombo($arComboTipoDENO,'[Seleccione]',$tipo2),'html');
			
		
		
		
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnSalir',"click","closePopup('#popbuscpredio');");
		$evt[] = array('#btnBuscaDireccion',"click","ListarPredios()");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
	public function consultaAction(){
	
		$cn = new Model_DbDatos_Datos ();
		
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
		
		$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
		
		//listado de opciones
		
		$cmbAnno = $_REQUEST['cmbAnno'];
		$cbxopcion = $_REQUEST['cbxopcion'];
		$cmbvias = $_REQUEST['cmbvias'];
		$txtnomvia = $_REQUEST['txtnomvia'];
		$cmbdeno = $_REQUEST['cmbdeno'];
		$txtnomden = $_REQUEST['txtnomden'];
		$txtnomcalle = $_REQUEST['txtnomcalle'];

		
    	if($cbxopcion=="V"){
	
			$id_urba='0001';
			$tipovia=$cmbvias;
			$nombre_via=$txtnomvia;
			$dir_predio="";
			
		}
		else if($cbxopcion=="D"){

			$id_urba='0002';
			$tipovia=$cmbdeno; 
			$nombre_via=$txtnomden;
			$dir_predio="";
			
		}
		else{
			
			$id_urba="";
			$tipovia="";
			$nombre_via="";
			$dir_predio=$txtnomcalle;
			
		}
		
		//echo($id_urba."*".$tipovia."*".$nombre_via."*".$dir_predio);
		
		//PARA EL TOTAL
		$parametros1 [] = array ('@busc', '2' );
		$parametros1 [] = array ('@anno', $cmbAnno );
		//$parametros [] = array ('@anno', '2015');
		$parametros1 [] = array ('@id_urba', $id_urba );
		$parametros1 [] = array ('@tipovia', $tipovia );
		$parametros1 [] = array ('@nombre_via', $nombre_via );
		$parametros1 [] = array ('@dir_predio', $dir_predio );
		
		$rowsTotal = $cn->ejec_store_procedura_sql ( '[Rentas].[Consulta_Dir_Predio]', $parametros1 );
		
		
		//PARA LAS FILAS
		unset($parametros2);
		$parametros2 [] = array ('@busc', '1' );
		$parametros2 [] = array ('@anno', $cmbAnno );
		//$parametros [] = array ('@anno', '2015');
		$parametros2 [] = array ('@id_urba', $id_urba );
		$parametros2 [] = array ('@tipovia', $tipovia );
		$parametros2 [] = array ('@nombre_via', $nombre_via );
		$parametros2 [] = array ('@dir_predio', $dir_predio );
		$parametros2 [] = array('@inicio',$start);
		$parametros2 [] = array('@final',$end);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Consulta_Dir_Predio]', $parametros2);
		
		
		$jsonData = array ('total'=>count($rows),'rows'=>array());
			foreach ( $rows as $row ) {
				$entry = array (
					'direccion'		=>utf8_encode($row[0]),
					'propietario'	=>utf8_encode($row[1]),
					'dj'		 	=>utf8_encode($row[2]),
					'motivo'		=>utf8_encode($row[3]),
					'fecha'			=>utf8_encode($row[4]),
					'cod_predio'	=>utf8_encode($row[5]),
					'porcentaje'	=>utf8_encode($row[6]),
					'sector'		=>utf8_encode($row[7]),
					'manzana'		=>utf8_encode($row[8]),
					'distrito'		=>utf8_encode($row[9]),
					't_via'			=>utf8_encode($row[10]),
					'via'			=>utf8_encode($row[11])
				);
				$jsonData ['rows'] [] = $entry;
		}		
		$this->view->data = json_encode ( $jsonData );	
	
	}
	
		
}


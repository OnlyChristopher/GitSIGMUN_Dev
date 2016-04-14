<?php

/**
 * BandedocumenController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class ConsultadocumenController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$path = new Zend_Session_Namespace('path');
		
		//$path->id_user ='0000001';
		//$user=$path->id_user;
		
		
		$this->view->ruta = $path->data;
		
		$this->view->title = "Bandeja de Documentos";
		
		
		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnBusBandeja',"click","buscarBandeja()");
		
		//$evt[] = array('#btnEnviar',"click","");
		
		$evt[] = array('#btnEnviar',"click","enviardocumento()");
		
		$evt[] = array('#btnVer',"click","enviarruta()");
		
		$evt[] = array('#btnVerdocumento',"click","verdocumento()");
		
		$evt[] = array('#btnExpendiente',"click","expediente()");
		
		$evt[] = array('#btnRecepcion',"click","recepcion()");
		
		
		$parametros[] = array('@msquery',2);
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		$contador=$rowsTotal[0][0];
		
		
		$this->view->conta=$contador;
		
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
    	
    	$rdcriterio = trim($_REQUEST['rdcriterio']);
    	$criterio = trim($_REQUEST['criterio']);
  
    	switch($rdcriterio)
    	{
    		case 'C': $codigo = $criterio;break;
    		case 'T': $contribuyente = $criterio;break;
    		case 'D': $documento = $criterio;break;
    		
    	}
    	
    	//Para el total
    	
    	$parametros[] = array('@msquery',2);
    	$parametros[] = array('@codigo',$codigo);
    	$parametros[] = array('@unombre',$contribuyente);
		$parametros[] = array('@num_val',$documento);
		
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.SP_Consultadocu', $parametros);
		
		//$contador=$rowsTotal[0][0];
	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',1);
    	$parametros[] = array('@codigo',$codigo);
    	$parametros[] = array('@unombre',$contribuyente);
		$parametros[] = array('@num_val',$documento);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.SP_Consultadocu', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],
						'contribuyente'=>utf8_encode($row[1]),
						'documento'=>$row[2]."-".$row[3]."-".$row[4],
						'monto'=>$row[5],
						'fecha'=>$row[6],
						'valor'=>$row[7],
						'num'=>$row[3],
						'ano'=>$row[4],
						'numero'=>$row[8],
						'anio'=>$row[9]
				);
			
				$jsonData['rows'][] = $entry;
			}
			
    	}
		$this->view->data = json_encode($jsonData);
    }
    
    public function rutaAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
    	$cn = new Model_DbDatos_Datos();
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnSalidaruta',"button","");
		$evt[] = array('#btnSalidaruta',"click","closePopup('#popupruta');");
		
		$cod = $this->_request->getParam('valor','');
		$num = $this->_request->getParam('num',''); 
		$ano = $this->_request->getParam('ano','');
		
		
		$this->view->valor = $cod;//nuevo
		$this->view->num = $num;//nuevo
		$this->view->ano = $ano;//nuevo
		
		//$this->view->valor = '01';//nuevo
		//$this->view->num = '000001';//nuevo
		//$this->view->ano = '2013';//nuevo
		
		
		$parametros[] = array('@msquery',4);
		$parametros[] = array('@id_valor',$cod);
		$parametros[] = array('@num_val',$num);
		$parametros[] = array('@ano_val',$ano);
		$rowBandeja = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		
		$documento = $rowBandeja[0][1];
		$codigo = $rowBandeja[0][0]; 
		$contribuyente = $rowBandeja[0][1]; 
		
		$num = $rowBandeja[0][3];
		$ano = $rowBandeja[0][4];
		
		$documento = $rowBandeja[0][2]."-".$rowBandeja[0][3]."-".$rowBandeja[0][4];
		
		//'documento'=>$row[2]."-".$row[3]."-".$row[4],
		
		$val[] = array('#txtValorruta',$cod,'val');
		$val[] = array('#txtNumruta',$num,'val');
		$val[] = array('#txtAnoruta',$ano,'val');
		
		$val[] = array('#lblDocumentoruta',$documento,'html');
		
		$val[] = array('#lblCodigoruta',$codigo,'html');
		$val[] = array('#lblContribuyenteruta',$contribuyente,'html');
    
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }
    
    public function detallerutaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$valor = $this->_request->getParam('valor',''); //recupero 
    	$num_val=$this->_request->getParam('num','');
		$ano_val=$this->_request->getParam('ano','');
    	
    	$parametros[] = array('@msquery',3);
    	$parametros[] = array('@id_valor',$valor); 
    	$parametros[] = array('@num_val',$num_val);
    	$parametros[] = array('@ano_val',$ano_val);
    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Rentas.SP_MHRuta', $parametros);
    	
    	$jsonData = array('total'=>count($rowRuta),'rows'=>array());
    	foreach($rowRuta AS $row){
			$entry = array(
					'orden'=>utf8_encode($row[0]),
					'areaorigen'=>utf8_decode($row[1]),
					'usuarioorigen'=>$row[2],
					'fechaorigen'=>$row[3],
					'areadestino'=>$row[4],
					'usuariodestino'=>$row[5],
					'fechadestino'=>$row[6],
					'observacion'=>$row[7],
					'situacion'=>$row[8]
			);
			$jsonData['rows'][] = $entry;
		}
		
		$this->view->data = json_encode($jsonData);
    }
    
    public function verdocumentoAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
    	$cn = new Model_DbDatos_Datos();
    	$fn = new Libreria_Pintar();
    	
  
		$evt[] = array('#btnEnviadocu',"button","");
		$evt[] = array('#btnSalidadocu',"button","");
		$evt[] = array('#btnReporteop',"button","");
		
		$evt[] = array('#btnReporteop',"click","imprimeOpPdf()");
		
		$evt[] = array('#btnSalidadocu',"click","closePopup('#popupdocumento');");
    	
		$cod = $this->_request->getParam('valor','');
		$num = $this->_request->getParam('num',''); 
		$ano = $this->_request->getParam('ano','');
		
		$parametros[] = array('@msquery',4);
		$parametros[] = array('@id_valor',$cod);
		$parametros[] = array('@num_val',$num);
		$parametros[] = array('@ano_val',$ano);		
		$rowBandeja = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		
		$codigo = $rowBandeja[0][0]; 
		$contribuyente = $rowBandeja[0][1]; 
		
		$num = $rowBandeja[0][3];
		$ano = $rowBandeja[0][4];
		
		$documento = $rowBandeja[0][2]."-".$rowBandeja[0][3]."-".$rowBandeja[0][4];
		
		
		
		$val[] = array('#txtValordocu',$cod,'val');
		$val[] = array('#txtNumdocu',$num,'val');
		$val[] = array('#txtAnodocu',$ano,'val');
		
		$val[] = array('#lblVerdocu',$documento,'html');
		
		$val[] = array('#lblCodigodocu',$codigo,'html');
		$val[] = array('#lblContribuyentedocu',$contribuyente,'html');
		
		
		$parametros[0] = array("@msquery",1);
	    $rows1 = $cn->ejec_store_procedura_sql('Rentas.SP_Dvalores',$parametros);
	    $cad1='';
		
   		for($i = 0; $i < count ( $rows1 ); $i ++) {
   			  
   			  if($rows1[$i][1]==0)
   			  {
   			  	$cad1.="<tr>";
	    		$cad1.="<td><input type='checkbox' onclick='mostrardetalle(this.checked,".$rows1[$i][8].")'></td>";
	    		$cad1.="<td><b>".$rows1[$i][2]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][3]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][4]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][5]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][6]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][7]."</b></td></tr>";
   			  }
 	    	  else{ 
	    	  	$cad1.="<tr class='chk".$rows1[$i][8]."' style='display:none'>";	
	    	    $cad1.="<td></td>";
	    	    $cad1.="<td align='center'>".$rows1[$i][2]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][3]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][4]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][5]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][6]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][7]."</td></tr>";
	    	  } /*	    	  	
	    		$cad1.="<td>".$rows1[$i][2]."</td>";
				$cad1.="<td>".$rows1[$i][3]."</td>";
				$cad1.="<td>".$rows1[$i][4]."</td>";
				$cad1.="<td>".$rows1[$i][5]."</td>";
				$cad1.="<td>".$rows1[$i][6]."</td>";
				$cad1.="<td>".$rows1[$i][7]."</td></tr>";*/
		}
		
		$val[] = array('#detallesDocumento',$cad1,'append');

		unset($parametros);
		$parametros[0] = array("@msquery",2);
		$parametros[1] = array('@id_valor',$cod);
		$parametros[2] = array('@num_val',$num);
		$parametros[3] = array('@ano_val',$ano);	
		$rowMonto = $cn->ejec_store_procedura_sql('Rentas.SP_Dvalores',$parametros);
		
		$montototal = $rowMonto[0][0]; 
		
		$val[] = array('#txtMontototal',$montototal,'val');
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }       
	
}


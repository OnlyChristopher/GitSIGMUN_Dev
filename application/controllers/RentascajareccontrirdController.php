<?php
require_once 'Zend/Controller/Action.php';

class rentascajareccontrirdController extends Zend_Controller_Action
{

	public function indexAction()
	{
		$getlogin = new Zend_Session_Namespace('login');
		
		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		
    	$json = $this->_request->getPost('json');		
		$data = json_decode($json);

		$predios='';

		$periodos=implode(',',$data[0]);
		$anios=implode(',',$data[1]);
		$conceptos=implode(',',$data[2]);
		$arbitrio=implode(',',$data[3]);
		
		
		$predios=implode(',',$data[4]);
		
		$predios = '['.$predios.'/'.$periodos.'-'.$anios.'x'.$conceptos.'s'.$arbitrio.']';

		$this->view->data = $predios;
    	$evt[] = array('#btnPagarRecibos',"button","");
		$evt[] = array('#btnFraccionar',"button","");
    	$evt[] = array('#btnCerrarRecibos',"button","");
		$evt[] = array('#btnPagarRecibos',"click","enviadeuda();");
		$evt[] = array('#btnCerrarRecibos',"click","closePopup('#popdeudapagar');");
		
		$this->view->predios=$predios.'dd'.$codigocontri;

		$fn->PintarEvento($evt);
	
		$contricaja = new Zend_Session_Namespace('contri');
		$this->view->codigocaja=$codigocontri= $contricaja->contri;
		$this->view->nombrecaja=$nombre=$contricaja->nombre;
        $this->view->direccaja=$direccion=$contricaja->direccion;

		$storecombo='store_caja_framework 9';

		$rowsx = $cn->ejec_store_procedura_sql($storecombo, null);
		
		$combo='';
		
		for ($f=0;$f<count($rowsx);$f++){	
			$combo.='<option selected value="'.$rowsx[$f][0].'">'.$rowsx[$f][1].'</option>';
		}
		
		$this->view->combo=$combo;
		$this->view->combo=$combo;
		//$fn->PintarValor($val);
	}
	
public function conreccontriAction()
	{
		$cn = new Model_DbDatos_Datos();
		
		$str_annos='';
		$str_tipos='';
		//$str_tiporec='';
		$str_periodos='';
		$str_predios='';
		
		$str_estado='0';
		
		$json = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		/*
		$str_codigo=$data_query[5];;
		$data_annos=$data_query[1];
		$data_tipos=$data_query[2];
		$data_tipo_deta=$data_query[3];
		$data_periodos=$data_query[0];
		$data_predios=$data_query[4];
		$str_estado=$data_query[6];*/
		
		$str_codigo=$data_query[0];
		$fecha_proyectado=$data_query[1];
		//$str_estado=$data_query[1];		
		
		
		//var_dump($data_tipos);
		//var_dump($data_tipo_deta);
		
		/*
		for($i=0; $i < count($data_annos); $i++){
            $str_annos .=$data_annos[$i].',';
        }
		$str_annos=substr($str_annos,0,-1);

		for($i=0; $i < count($data_tipos); $i++){
            $str_tipos .=$data_tipos[$i].',';
        }
		$str_tipos=substr($str_tipos,0,-1);
		
		for($i=0; $i < count($data_tipo_deta); $i++){
            $str_tiporec .=$data_tipo_deta[$i].',';
        }
		for($i=0; $i < count($data_tipos); $i++){
            $str_tiporec .=$data_tipos[$i].',';
        }
		$str_tiporec=substr($str_tiporec,0,-1);
		//echo $str_tiporec;
		for($i=0; $i < count($data_periodos); $i++){
            $str_periodos .=$data_periodos[$i].',';
        }
		$str_periodos=substr($str_periodos,0,-1);
		
		for($i=0; $i < count($data_predios); $i++){
            $str_predios .=$data_predios[$i].',';
        }		
		$str_predios=substr($str_predios,0,-1);
		*/
		
		
		/*
			echo $str_annos.'<br>';
			echo $str_tipos;
			echo $str_periodos.'<br>';
			echo $str_predios.'<br>';
		*/
		//print_r($data_annos);
		//print_r($data_annos);
		//echo $str_annos;
		
		////aquicomento
		//$codigo=$this->_request->getParam('codigo','');
		///////
		
		//$codigo=$codigocontri;
		
		
		//$concept=$concept;//'"02.01","11.00","11.04"';
		//$arbitrio='"11.00"';
		//$aniose=$data_query[0][0];//'"2004","2005","2006","2007","2008","2009","2010","2011","2012"';
		//$perio=$periodos;//'"01","02","03","04","05","06","07","08","09","10","11","12"';
		//$preddd=$predios;//'"0000003"';

		//$nombrestore='Caja.sp_EstCta_Rentasrd';
		$nombrestore='Caja.sp_EstCta_Rentasrd_proyectado';		
		//$arraydatos[]=array("@codigo", $codigo);
		$arraydatos[]=array("@codigo", $str_codigo);
		$arraydatos[]=array("@annos", $str_annos);
		$arraydatos[]=array("@tipos", $str_tipos);
		//$arraydatos[]=array("@tiporec", $str_tiporec);
		$arraydatos[]=array("@perio", $str_periodos);
		$arraydatos[]=array("@predio", $str_predios);
		$arraydatos[]=array("@estado", $str_estado);
		$arraydatos[]=array("@fecha_proyectada", $fecha_proyectado);

		@$rows = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
		
		$ntotal=count($rows);
		
		if($ntotal > 0){
			$jsonData = array('total'=>$ntotal,'rows'=>array());
			foreach($rows AS $row){
				$entry = array(
						'idrecibo'=>$row[0],				  
						'codigo'=>$row[1],
						'tipo'=>$row[2],
						'anno'=>$row[3],
						'cod_pred'=>$row[4],
						'anexo'=>$row[5],
						'sub_anexo'=>$row[6],
						'tipo_rec'=>$row[9],
						'periodo'=>$row[10],
						'imp_insol'=>$row[11],
						'fact_reaj'=>$row[13],
						'imp_reaj'=>$row[14],
						'fact_mora'=>$row[15],
						'mora'=>$row[16],
						'costo_emis'=>$row[12],
						'des_tipo'=>$row[27],
						'ubica'=>$row[19],
						'total'=>number_format($row[14]+$row[16]+$row[12],2)
				);
				$jsonData['rows'][] = $entry;
			}

			$this->view->data = json_encode($jsonData);
		}else{
			$this->view->data = 'No se encontraron registros';
		}

	}
	
public function registrapagoAction(){
	
		$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
		$this->_helper->Layout->disableLayout();		
		//$this->_helper->ViewRenderer->setNoRender();
		
		
		$codigo = $this->_request->getPost('codigo');
		$json = $this->_request->getPost('json');
		$formaPago = $this->_request->getPost('rdformaPago');
		
		$data = json_decode($json);
		$dxml = '';
		$cajero=$getlogin->caja;

		if(strlen($cajero)>0){
			foreach ($data as $key => $value){ 
	//			echo "<h2>$key</h2>";
				$dxml.="<row ";
				foreach ($value as $k => $v) { 
					$dxml.=$k.' = "'.$v.'" '; 
				}
				$dxml.=" />";
			}
			
//			echo $dxml;
			

			$cn = new Model_DbDatos_Datos();
	
			$nombrestore="Caja.sp_Genera_Recibos";
			$arraydatos[]=array("@codigo", $codigo);
			$arraydatos[]=array("@cajero", $cajero);
			$arraydatos[]=array("@dataxml", $dxml);
			$arraydatos[]=array("@tipo_pago", $formaPago);
			$arraydatos[]=array("@operador", $username);
			$arraydatos[]=array("@estacion", $hostname);

			$rowrecibos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);

			echo ' <script language=\'javascript\'>';
			//for($i=0;$i<2;$i++){
				echo 'window.open(\'tesoprepago/imprimirrecibopagos?nrorecibo='.$rowrecibos[0][0].'\',"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no"); ';
			//}	
			echo '  function ventanaSecundaria(){ ';
			//for($i=0;$i<2;$i++){			
				echo 'ventana=window.open(\'tesoprepago/imprimirrecibopagos?nrorecibo='.$rowrecibos[0][0].'\',"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no");ventana.focus();';
			//}
			echo '	} 
				    </script>				    
				   
				 <input type="button" name="imprimir" id="imprimir" value="Imprimir recibo" onClick="ventanaSecundaria()" />
				 <br />
				 <br />';
		}else{
			echo 'El usuario no tiene caja asignada';
		}
		

}

}


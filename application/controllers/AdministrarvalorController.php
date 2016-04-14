<?php

class AdministrarvalorController extends Zend_Controller_Action
{

    public function init(){
        /* Initialize action controller here */			
    }

    public function indexAction(){
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Buscar Valor";
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
			
		$arBusqueda = $ar->Busqueda();
		$val[]  = array('#cmbBusqueda',$fn->ContenidoCombo($arBusqueda,'[Todos]',''),'html');
		
		$evt[] = array('#btnBusBandeja',"click","buscarValor();");	
		
		$evt[] = array('#btnRecepcionar',"click","Recepcionarvalor()");
		$evt[] = array('#btnDevolver',"click","Devolvervalor()");
		$evt[] = array('#btnEnviar',"click","enviarvalor()");
//		$evt[] = array('#btnRuta',"click","ruta()");
//		$evt[] = array('#btnVerdocumento',"click","verdocumento()");
		$evt[] = array('#btnGenerarExpediente',"click","btnGenerarExpediente()");
		
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }
	
	public function verificarAction(){
		$login = new Zend_Session_Namespace('login');
		$cn = new Model_DbDatos_Datos();
		
		$SQL_string="select count(*) from recibido_valor_contrib";
		$rowsTotal = $cn->ejec_store_procedura_sql_script($SQL_string);
		echo $rowsTotal[0][0];
		exit;
	}
	
    public function retornadatosAction(){
	
		$login = new Zend_Session_Namespace('login');
		$cn = new Model_DbDatos_Datos();
	
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
		
		$flag 				= 	($_REQUEST['hdnFlag']) ? $_REQUEST['hdnFlag'] : 0;
		
	    $rdcriterio		=	isset($_REQUEST['cmbBusqueda']) ? $_REQUEST['cmbBusqueda'] : 0;
		$txtBusqueda		= 	isset($_REQUEST['txtBusqueda']) ? $_REQUEST['txtBusqueda'] : 0;
		$txtCodigo			= 	(trim($_REQUEST['txtCodigo'])) ? (int)ltrim($_REQUEST['txtCodigo'], '0') : 0;
		$txtContribuyente	= 	isset($_REQUEST['txtContribuyente']) ? $_REQUEST['txtContribuyente'] : '';
		
		$Rango1			= 	(trim($_REQUEST['txtRango1'])) ? $_REQUEST['txtRango1'] : '';
		$Rango2			= 	(trim($_REQUEST['txtRango2'])) ? $_REQUEST['txtRango2'] : $_REQUEST['txtRango1'];
		
		$RangoCargo1		= 	(trim($_REQUEST['txtRangoCargo1'])) ? $_REQUEST['txtRangoCargo1'] : '';		
		$RangoCargo2		= 	(trim($_REQUEST['txtRangoCargo2'])) ? $_REQUEST['txtRangoCargo2'] : $_REQUEST['txtRangoCargo1'];		
		$AnioCargo			= 	(trim($_REQUEST['txtAnioCargo'])) ? $_REQUEST['txtAnioCargo'] : '';		
		
		$Nromemorandum	= 	isset($_REQUEST['txtNromemorandum']) ? $_REQUEST['txtNromemorandum'] : '';
		
		
		$start = (($page-1) * $limit);
    	$end = $limit ;
		
		
		$SQL_columns=' count(*) ';    
		$SQL_inc = ' where cv.idarea=@4004@ and vruta.recepcion='.$flag.' and vruta.user_destin=@'.$login->id.'@  ';  
		$SQL_inc=$SQL_inc.$SQL_inc1;
		if($rdcriterio==1){

			$SQL_inc1 = ' and cv.idcontrib='.$txtCodigo;
		}
		
		if($rdcriterio==2){

			$nomContribuyente = '%'.str_replace(' ', '%',strtoupper($txtContribuyente)).'%';
			$SQL_inc1 = " and co.nomcontrib like '".$nomContribuyente."' ";
		}
		
		if($rdcriterio==3){
			$SQL_inc1 = " and cv.fechaemision >= '".$Rango1." 00:00:00' AND cv.fechaemision <= '".$Rango2." 23:59:59'";
		}
		
		if($rdcriterio==4){
			$SQL_inc1 = " and cargo_pr.numcargo >= ".$RangoCargo1." AND cargo_pr.numcargo <=".$RangoCargo2." and cargo_pr.anio='".$AnioCargo."'  ";
			//echo $SQL_inc1;
		}
		
		if($rdcriterio==6){
			$memovalor = '%'.str_replace(' ', '%',$Nromemorandum).'%';
			$SQL_inc1 = " and cargo_pr.doc_memo like '".$memovalor."' ";
		}
		
		$SQL_order=' order by cv.id_valor ';
				
		$SQL_string=' SELECT '.$SQL_columns.' 
		from rentas.Mvalores cv 
		inner join Rentas.MContribuyente co on cv.codigo = co.codigo
		left  join Rentas.MHruta vruta on cv.num_val = vruta.num_val and cv.ano_val=vruta.ano_val and cv.id_valor = vruta.id_valor
		'.$SQL_inc;
		$SQL_string=str_replace('@',"'",$SQL_string);

/**********************/
 		$SQL_columns=' 
		cv.id_valor as idvalor
		,cv.codigo as idcontrib
		,cv.nombre as nomcontrib
		,co.paterno as apellpat
		,co.materno as apellmat
		,([Rentas].[fn_tipo_valor] ( cv.id_valor )) as nomtvalor
		,cv.num_val as numvalor
		,cv.ano_val as anio
		,cv.fech_ing as fechaemision
		,(select cancelado from  [Rentas].[co_valor_monto](cv.id_valor,cv.num_val,cv.ano_val)) as cancelado
		,(select total from  [Rentas].[co_valor_monto](cv.id_valor,cv.num_val,cv.ano_val))  as sumtotal
		,(select top 1 orden from [Rentas].[co_ruta_valor] ( cv.id_valor, cv.num_val,cv.ano_val)) as orden
		,(select top 1 recepcion from [Rentas].[co_ruta_valor] (cv.id_valor, cv.num_val,cv.ano_val)) as flagrecepcion2
		,(select top 1 doc_memo from [Rentas].[co_cargo_valor] (cv.id_valor, cv.num_val,cv.ano_val)) as doc_memo
		,(select top 1 numcargo from [Rentas].[co_cargo_valor] (cv.id_valor, cv.num_val,cv.ano_val)) as numcargo
		,0 as columnac
		,(select top 1 anio from [Rentas].[co_cargo_valor] (cv.id_valor, cv.num_val,cv.ano_val)) as aniocargo
		,0 as columnad
		,0 as columnaa
		,0 as columnab
		,vruta.id_tbl as vidruta
		,(select top 1 fech_notifi from [Rentas].[co_cargo_valor] ( cv.id_valor, cv.num_val,cv.ano_val)) as fech_notifi
		,(select top 1 fech_notifi from [Rentas].[co_cargo_valor] ( cv.id_valor, cv.num_val,cv.ano_val)) as idarea
		,(select pendiente from  [Rentas].[co_valor_monto](cv.id_valor,cv.num_val,cv.ano_val)) as pendiente
		,vruta.user_destin ';

		$SQL_inc = ' where cv.area=@4004@ and vruta.recepcion='.$flag.' and vruta.user_destin=@'.$login->id.'@  ';  
		
		if($rdcriterio==1){

			$SQL_inc1 = ' and cv.idcontrib='.$txtCodigo;
		}
		
		if($rdcriterio==2){

			$nomContribuyente = '%'.str_replace(' ', '%',$txtContribuyente).'%';
			$SQL_inc1 = " and CONCAT(co.nomcontrib,co.apellpat,co.apellmat) like '".$nomContribuyente."' ";
		}
		
		if($rdcriterio==3){
			$SQL_inc1 = " and cv.fechaemision >= '".$Rango1." 00:00:00' AND cv.fechaemision <= '".$Rango2." 23:59:59'";
		}
		
		if($rdcriterio==4){
			$SQL_inc1 = " and cargo_pr.numcargo >= ".$RangoCargo1." AND cargo_pr.numcargo <=".$RangoCargo2." and cargo_pr.anio='".$AnioCargo."'  ";
			//echo $SQL_inc1;
		}
		
		if($rdcriterio==6){
			$memovalor = '%'.str_replace(' ', '%',$Nromemorandum).'%';
			$SQL_inc1 = " and cargo_pr.doc_memo like '".$memovalor."' ";
		}
		
		 
		$SQL_inc=$SQL_inc.$SQL_inc1;

		$SQL_order=' order by cv.id_valor ';
		$SQL_limits = ' where ROW BETWEEN '.$start.' AND '.$end; 
		
		$SQL_string=' SELECT * FROM ( select '.$SQL_columns.', ROW_NUMBER() OVER ('.$SQL_order.') AS ROW 
			from rentas.Mvalores cv 
inner join Rentas.MContribuyente co on cv.codigo = co.codigo
left  join Rentas.MHruta vruta on cv.num_val = vruta.num_val and cv.ano_val=vruta.ano_val and cv.id_valor = vruta.id_valor

		
		
		'.$SQL_inc.') AS ALIAS '.$SQL_limits; 
		
		
		$SQL_string=str_replace('@',"'",$SQL_string);
					
		$rows = $cn->ejec_store_procedura_sql_script($SQL_string);
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		
		
	
	
		if(count($rows)){
			foreach($rows AS $row){   
			/*
			echo "<pre>";
			print_r($row); 
			echo "</pre>";
			*/
			//echo "XXX".$flag."XXX";
			if($flag == 1){
			$recepcionados_TMP= "SELECT * FROM tmp_valor_contrib WHERE id_valor='".$row[0]."' and num_val = '".$row[6]."' and ano_val = '".$row[7]."'";
			$rowstmp = $cn->ejec_store_procedura_sql_script($recepcionados_TMP);
			}else if($flag == 0){
			$recibidos_TMP= "SELECT * FROM recibido_valor_contrib WHERE id_valor='".$row[0]."' and num_val = '".$row[6]."' and ano_val = '".$row[7]."'";
		//	echo $recibidos_TMP; 
			$rowstmp = $cn->ejec_store_procedura_sql_script($recibidos_TMP);
			}
		
		//echo $recepcionados_TMP;
		//echo $recibidos_TMP; 
				
				//$cancelado=($row[9])?$row[9]:0.00;
				//$pendiente=$row[10]-$cancelado;
				$entry = array(
					'idvalor'=>utf8_encode($row[0]),
					'desvalor'=>utf8_encode($row[5]),
					'numerval'=>utf8_encode(str_pad($row[6], 7, "0", STR_PAD_LEFT).'-'.$row[7]),
					'numval'=>$row[6],
					'anoval'=>$row[7],
					'fechaemi'=>date('d/m/Y H:i:s',strtotime(utf8_encode($row[8]))),
					'cidpers'=>utf8_encode(str_pad($row[1], 7, "0", STR_PAD_LEFT)),
					'nombre'=>utf8_encode($row[2]." ".$row[3]." ".$row[4]),
					'montotal'=>(float)utf8_encode($row[10]), //utf8_encode($row[7]);
					'cancelado'=>(float)utf8_encode($row[9]), //utf8_encode($row[7]),
					'pendiente'=>(float)utf8_encode($row[22]), //utf8_encode($row[7]),
					'numerocargo'=>(trim($row[14]))?utf8_encode(str_pad($row[14], 7, "0", STR_PAD_LEFT).'-'.$row[15]):'',
					'numcargo'=>utf8_encode($row[14]),
					'anocargo'=>utf8_encode($row[15]),
					'paso'=>str_pad($row[16],3, "0", STR_PAD_LEFT),//utf8_encode($row[13]),
					'idruta'=>utf8_encode($row[19]),
					'fechanotifica'=>date('d/m/Y H:i:s',strtotime($row[20])),//$row[20]),
					'doc_memo'=>$row[18],
					'estadotmp'=>((count($rowstmp))?1:2)
				);
				$jsonData['rows'][] = $entry;
			}
		}
		/*
		echo "<pre>";
		print_r($jsonData);
		echo "</pre>";
		*/
		//exit;
		$this->view->data = json_encode($jsonData);
		

    }
	
	public function verdocumentoAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		
    	$idvalor = (string)$this->_request->getParam('valor','');
		$numval = (string)$this->_request->getParam('numval','');
		$anoval = (string)$this->_request->getParam('anoval','');		
		
		//$SQL_string="select ca.idcontrib,co.nomcontrib,co.apellpat,co.apellmat,ca.numvalor,ca.anio,ex.numexpediente,ex.anio  as anioexp,(select descrip_valor from t_valor where idtvalor=ca.idtvalor) from cab_valor_trib ca INNER JOIN contribuyente co ON ca.idcontrib=co.idcontrib LEFT OUTER JOIN expediente ex ON ca.idexpediente=ex.idexpediente where ca.idvalor=".$idvalor;
		$SQL_string = "select ca.codigo,co.nombres,co.paterno,co.materno,ca.num_val,ca.ano_val,isnull(ex.numexpediente,@@),isnull(ex.anio,@@) as anioexp,([Rentas].[fn_tipo_valor] ( ca.id_valor ))
						from Rentas.Mvalores ca INNER JOIN Rentas.MContribuyente co ON ca.codigo=co.codigo LEFT OUTER JOIN expediente ex ON ca.idexpediente=ex.idexpediente 
						WHERE ca.id_valor=@".$idvalor."@ and ca.num_val=@".$numval."@ and ca.ano_val=@".$anoval."@ ";
		
		$SQL_string=str_replace('@',"'",$SQL_string);

		$rows = $cn->ejec_store_procedura_sql_script($SQL_string);	
	
		$valortipo = strtoupper($rows[0][8]);
		$valornumero = str_pad($rows[0][4], 7, "0", STR_PAD_LEFT)."-".$rows[0][5];
		$codigo = $rows[0][0]; 
		if(ltrim(rtrim($rows[0][1]))){
		$contribuyente = trim($rows[0][1])." ".trim($rows[0][2])." ".trim($rows[0][3]) ; 
		}else{
		$contribuyente = ""; 	
		}
		$expediente = str_pad($rows[0][6], 7, "0", STR_PAD_LEFT)."-".$rows[0][7];	
		
		$val[] = array('#lblTipoDocumentover',$valortipo,'html');
		$val[] = array('#txtverNumeroDocumento',$valornumero,'val');
		$val[] = array('#txtverCodigo',str_pad($codigo, 7, "0", STR_PAD_LEFT) ,'val');
		$val[] = array('#txtverContribuyente',$contribuyente,'val');
//		$val[] = array('#lblExperuta',$expediente,'html');

		$evt[] = array('#btnDetalleSalir',"click","closePopup('#popupverdocumento');");
		$val[] = array('#txtValorVerDocumen',$idvalor,'val');
		$val[] = array('#txtValorNumVal',$numval,'val');
		$val[] = array('#txtValorAnnoVal',$anoval,'val');
		$val[] = array('#txtDesValorVer',$desvalor,'val');
		

		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	

	
    }
	
		public function impregistrovaloresAction()
    {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$msquery = $this->_request->getParam('msquery','');
		$idvalor = $this->_request->getParam('idvalor','');
    	$desvalor = $this->_request->getParam('desvalor','');
		$fechaemi = $this->_request->getParam('fechaemi','');
		$cidpers = $this->_request->getParam('cidpers','');
		$nombre = $this->_request->getParam('nombre','');
		$numval = $this->_request->getParam('numval','');
		$anoval = $this->_request->getParam('anoval','');
		$check = (($_REQUEST['checkimp']==1)?1:2);
		
				$parametros[] = array($check);
				$parametros[] = array(1);
				$parametros[] = array($idvalor);
				$parametros[] = array($numval);
				$parametros[] = array($anoval);
				$parametros[] = array($cidpers);
				$parametros[] = array($nombre);
				$parametros[] = array($desvalor);
				$parametros[] = array($fechaemi);
				$rowAdqui = $cn->ejecutapgsql('fn_imp_registrovalores', $parametros);
				if($rowAdqui[0]){
					$datos=$rowAdqui[0][1];
					echo $datos;
				}
				else{
					echo utf8_encode($rowAdqui[0][1]);
					exit();
				}
		
		
		exit;
	}
	
	public function eliminarvaloresAction()
    {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();	
		$flag = $_REQUEST['flagtmp'];
		//echo $flag;	
	
		$parametros[] = array($flag);
		$rowAdqui = $cn->ejecutapgsql('eliminarvaloresselecionado_tmp', $parametros);
		exit;
	}
	
	public function agregartodosvaloresAction()
    {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		//$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();	
		
		$flag 				= 	($_REQUEST['hdnFlag']) ? $_REQUEST['hdnFlag'] : 0;
		
	    $rdcriterio		=	isset($_REQUEST['cmbBusqueda']) ? $_REQUEST['cmbBusqueda'] : 0;
		$txtBusqueda		= 	isset($_REQUEST['txtBusqueda']) ? $_REQUEST['txtBusqueda'] : 0;
		$txtCodigo			= 	(trim($_REQUEST['txtCodigo'])) ? (int)ltrim($_REQUEST['txtCodigo'], '0') : 0;
		$txtContribuyente	= 	isset($_REQUEST['txtContribuyente']) ? $_REQUEST['txtContribuyente'] : '';
		
		$Rango1			= 	(trim($_REQUEST['txtRango1'])) ? $_REQUEST['txtRango1'] : '';
		$Rango2			= 	(trim($_REQUEST['txtRango2'])) ? $_REQUEST['txtRango2'] : $_REQUEST['txtRango1'];
		
		$RangoCargo1		= 	(trim($_REQUEST['txtRangoCargo1'])) ? $_REQUEST['txtRangoCargo1'] : '';		
		$RangoCargo2		= 	(trim($_REQUEST['txtRangoCargo2'])) ? $_REQUEST['txtRangoCargo2'] : $_REQUEST['txtRangoCargo1'];		
		$AnioCargo			= 	(trim($_REQUEST['txtAnioCargo'])) ? $_REQUEST['txtAnioCargo'] : '';		
		
		$Nromemorandum	= 	isset($_REQUEST['txtNromemorandum']) ? $_REQUEST['txtNromemorandum'] : '';
		
		echo "<pre>";
		print_r($_REQUEST);
		echo "</pre>";
	
	$SQL_columns=' 
		cv.idvalor,
		cv.numvalor,
		cv.anio,		
		cv.idcontrib,
		co.nomcontrib,
		--co.apellpat,
		--co.apellmat,
		tv.nomtvalor,
		cv.fechaemision ';
		$SQL_inc = ' where cv.idarea=5 and vruta.flagrecepcion2='.$flag.' and COALESCE(cv.idexpediente,0)=0 and cv.idvalor IS NOT NULL '; 
		
		if($rdcriterio==1){

			$SQL_inc1 = ' and cv.idcontrib='.$txtCodigo;
		}
		
		if($rdcriterio==2){

			$nomContribuyente = '%'.str_replace(' ', '%',$txtContribuyente).'%';
			$SQL_inc1 = " and CONCAT(co.nomcontrib,co.apellpat,co.apellmat) like '".$nomContribuyente."' ";
		}
		
		if($rdcriterio==3){
			$SQL_inc1 = " and cv.fechaemision >= '".$Rango1." 00:00:00' AND cv.fechaemision <= '".$Rango2." 23:59:59'";
		}
		
		if($rdcriterio==4){
			$SQL_inc1 = " and cargo_pr.numcargo >= ".$RangoCargo1." AND cargo_pr.numcargo <=".$RangoCargo2." and cargo_pr.anio='".$AnioCargo."'  ";
			//echo $SQL_inc1;
		}
		
		if($rdcriterio==6){
			$memovalor = '%'.str_replace(' ', '%',$Nromemorandum).'%';
			$SQL_inc1 = " and cargo_pr.doc_memo like '".$memovalor."' ";
		}
		
		 
		$SQL_inc=$SQL_inc.$SQL_inc1;

		$SQL_order=' order by cv.idvalor ';
		$SQL_limits = ' LIMIT '.$end.' OFFSET '.$start; 
		
		$SQL_string=' select '.$SQL_columns.' FROM cab_valor_trib cv inner join contribuyente co on cv.idcontrib=co.idcontrib inner join t_valor tv on cv.idtvalor=tv.idtvalor left outer join lateral (select idvalor2,orden2,flagrecepcion2,vidruta from fn_valor_ruta(cv.idvalor)) vruta on cv.idvalor=vruta.idvalor2 
left outer join lateral (select idvalor,idcargo,numcargo,anio,fech_notifi,idarea,doc_memo from fn_registros_cargos(1,cv.idvalor)) cargo_pr on cv.idvalor=cargo_pr.idvalor		
		'.$SQL_inc.' '.$SQL_order; 
		
		$rows = $cn->ejecutapgsql_script($SQL_string);
		
	//	echo $SQL_string;
/*	
	echo "<pre>";
	print_r($rows);
	echo "</pre>";
*/

/*	
  idvalor integer,
  numvalor integer,
  anio character(4),
  codigo integer,
  contribuyente character(400),
  desvalor character varying(4),
  fechaemision timestamp without time zone
*/
		//$jsonData=null
		if(count($rows)){
			foreach($rows AS $row){                        
				$entry = array(
					'idvalor'=>$row[0],
					'numvalor'=>$row[1],
					'anio'=>$row[2],
					'codigo'=>$row[3],
					'contribuyente'=>$row[4],
					'desvalor'=>$row[5],
					'fechaemision'=>date('d/m/Y H:i:s',strtotime($row[6]))
				);
				$json[]=(object)$entry;
			}
		}
		
		
		//$cn = new Model_DbDatos_Datos();

		//if($jsonData){
			
			//echo $flag;
		if(count($json)){	
		$json=json_encode($json);
		$parametros[] = array($json);		
		$parametros[] = array($flag);		
		$rowAdqui = $cn->ejecutapgsql('todosvaloresselecionado_tmp', $parametros);
		}
		//}
		
//		echo ;
		exit;
		//$rowAdqui = $cn->ejecutapgsql_script('', $parametros);
	}
	
	
	public function tmpregistrovaloresAction()
    {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$msquery = $this->_request->getParam('msquery','');
		$idvalor = $this->_request->getParam('idvalor','');
    	$desvalor = $this->_request->getParam('desvalor','');
		$fechaemi = $this->_request->getParam('fechaemi','');
		$cidpers = $this->_request->getParam('cidpers','');
		$nombre = $this->_request->getParam('nombre','');
		$numval = $this->_request->getParam('numval','');
		$anoval = $this->_request->getParam('anoval','');
		$check = (($_REQUEST['check']==1)?1:2);
		$flag = (($_REQUEST['flag']==0)?2:1);

		$login = new Zend_Session_Namespace('login');
		/*
		echo "<pre>";
		print_r($_REQUEST);
		echo "<pre>";
		*/	
				$parametros[] = array("@flag",$flag);
				$parametros[] = array("@msquery",$check);
				$parametros[] = array("@idusuario",$login->user);
				$parametros[] = array("@idvalor",$idvalor);
				$parametros[] = array("@numvalor",$numval);
				$parametros[] = array("@anio",$anoval);
				$parametros[] = array("@codigo",$cidpers);
				$parametros[] = array("@contribuyente",$nombre);
				$parametros[] = array("@desvalor",$desvalor);
				$parametros[] = array("@fechaemision",$fechaemi);
				
				/*
				echo "<pre>";
				print_r($parametros);
				echo "</pre>";
				*/
				
				$rowAdqui = $cn->ejec_store_procedura_sql('Rentas.sp_tmp_registro_valores', $parametros);
				echo $rowAdqui[0][0];		
		
		exit;
	}
	
	
	public function rutaAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		
    	$idvalor = $this->_request->getParam('valor','');
    	$numval = $this->_request->getParam('num','');
    	$anoval = $this->_request->getParam('ano','');
		
    	$SQL_string = "select ca.codigo,co.nombres,co.paterno,co.materno,ca.num_val,ca.ano_val,isnull(ex.numexpediente,@@),isnull(ex.anio,@@) as anioexp,([Rentas].[fn_tipo_valor] ( ca.id_valor ))
						from Rentas.Mvalores ca INNER JOIN Rentas.MContribuyente co ON ca.codigo=co.codigo LEFT OUTER JOIN expediente ex ON ca.idexpediente=ex.idexpediente 
						WHERE ca.id_valor=@".$idvalor."@ and ca.num_val=@".$numval."@ and ca.ano_val=@".$anoval."@ ";
	
		$SQL_string=str_replace('@',"'",$SQL_string);
		$rows = $cn->ejec_store_procedura_sql_script($SQL_string);	
	
		$valortipo = strtoupper($rows[0][8]);
		$valornumero = str_pad($rows[0][4], 7, "0", STR_PAD_LEFT)."-".$rows[0][5];
		$codigo = $rows[0][0]; 
		if(ltrim(rtrim($rows[0][1]))){
		$contribuyente = trim($rows[0][1])." ".trim($rows[0][2])." ".trim($rows[0][3]) ; 
		}else{
		$contribuyente = ""; 	
		}
		$expediente = str_pad($rows[0][6], 7, "0", STR_PAD_LEFT)."-".$rows[0][7];	
			
		
		$val[] = array('#txtNumruta',$numval,'val');
		$val[] = array('#txtAnoruta',$anoval,'val');
		
		$val[] = array('#lblTipoDocumentovalor',$valortipo,'html');
		$val[] = array('#txtValNumeroDocumento',$valornumero,'val');
		$val[] = array('#txtvalCodigo',str_pad($codigo, 7, "0", STR_PAD_LEFT) ,'val');
		$val[] = array('#txtvalContribuyente',$contribuyente,'val');
		$val[] = array('#lblExperuta',$expediente,'html');

		$evt[] = array('#btnSalidaruta',"click","closePopup('#popupruta');");
		$val[] = array('#txtValorruta',$idvalor,'val');
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	
    }
	
	
	public function detallerutaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$valor = $this->_request->getParam('valor',''); 
    	$num = $this->_request->getParam('num',''); 
    	$ano = $this->_request->getParam('ano',''); 
		
		$SQL_string="select ru.recepcion
						,ru.id_valor
						,ru.orden
						,aor.nombre as areaorigen
						,uor.vlogin as usuarioorigen
						,uor.nombres as nombreorigen
						,uor.apellidos as apellidoorigen
						,ade.nombre as areadestino
						,ude.vlogin as usuariodestino
						,ude.nombres as nombredestino
						,ude.apellidos as apellidodestino
						,ru.fec_envios
						,ru.fec_recepcion
						,ru.observacion
						,si.nomsituacion
					from [Rentas].[MHRuta] ru
					left join Rentas.t_situaciondoc si on ru.id_situac=si.id_situac 
					left join t_ccostos aor on ru.area_origen = aor.area
					left join t_ccostos ade on ru.area_destin = ade.area
					left join Acceso.TblUsuarios uor on ru.user_origen = uor.id_usuario
					left join Acceso.TblUsuarios ude on ru.user_destin = ude.id_usuario
			where ru.id_valor=@".$valor."@ and ru.num_val=@".$num."@ and ru.ano_val=@".$ano."@ order by orden asc";

		$SQL_string=str_replace('@',"'",$SQL_string);

		$rows = $cn->ejec_store_procedura_sql_script($SQL_string);	
		
    	$jsonData = array('total'=>count($rows),'rows'=>array());
		
		if(count($rows)){
			foreach($rows AS $row){                        
				$entry = array(
					'orden'=>utf8_encode(str_pad($row[2], 3, "0", STR_PAD_LEFT)),
					'areaorigen'=>utf8_encode(strtoupper($row[3])),
					'usuarioorigen'=>utf8_encode(strtoupper($row[4])),
					'nombresorigen'=>utf8_encode(strtoupper(trim($row[5]).", ".trim($row[6]))),
					'fechaorigen'=>(date('d/m/Y',strtotime($row[11]))!='31/12/1969')?date('d/m/Y',strtotime($row[11])):'',//date('d/m/Y',strtotime($fech_ingreso)),'val')
					'areadestino'=>utf8_encode(strtoupper($row[7])),
					'usuariodestino'=>utf8_encode(strtoupper($row[8])),
					'nombresdestino'=>(trim($row[9]))?(utf8_encode(strtoupper(trim($row[9]).", ".trim($row[10])))):'',
					'frecepcion'=>(date('d/m/Y',strtotime($row[12]))!='31/12/1969')?date('d/m/Y',strtotime($row[12])):'',//,
					'observacion'=>utf8_encode($row[13]),//,
					'situacion'=>$row[14]//,

				);
				$jsonData['rows'][] = $entry;
			}
		}
	
		
		$this->view->data = json_encode($jsonData);
    }
	
	public function detallevalorAction()
    {
    	$cn = new Model_DbDatos_Datos();
		
		
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
		
		$valor = $this->_request->getParam('valor',''); 
		$num_val = $this->_request->getParam('num_val',''); 
		$ano_val = $this->_request->getParam('ano_val',''); 
    	
		$start = (($page-1) * $limit);
    	$end = $limit;
		
		$SQL_columns=' count(*) ';    
		$SQL_inc = ' where id_valor=@'.$valor.'@ and num_val=@'.$num_val.'@ and ano_val=@'.$ano_val.'@ ';
		$SQL_order=' order by periodo desc ';
		$SQL_string=' SELECT '.$SQL_columns.' FROM Rentas.Dvalores dv inner join Contenedor.TipoRec tr on dv.tipo=tr.tipo and dv.tipo_rec=tr.tipo_rec '.$SQL_inc;

		$SQL_string=str_replace('@',"'",$SQL_string);

		$rowsTotal = $cn->ejec_store_procedura_sql_script($SQL_string);
		
		$jsonData = array('total'=>count($rowsTotal),'rows'=>array());

 		$SQL_columns=' 
		tr.descrip2, dv.anno, periodo, dv.imp_insol, dv.fact_reaj, case when (dv.imp_reaj - dv.imp_insol)<0 then 0 else (dv.imp_reaj - dv.imp_insol) end AS reajuste , 
dv.fact_mora, dv.mora, dv.costo_emis, (dv.imp_reaj+dv.mora+dv.costo_emis) as total'; 

		$SQL_inc = ' where id_valor=@'.$valor.'@ and num_val=@'.$num_val.'@ and ano_val=@'.$ano_val.'@ ';

		$SQL_order=' order by periodo desc ';
		$SQL_limits = ' WHERE ROW BETWEEN  '.$start.' AND '.$end; 
		
		$SQL_string=' SELECT * FROM ( select '.$SQL_columns.', ROW_NUMBER() OVER ('.$SQL_order.') AS ROW 
			FROM Rentas.Dvalores dv inner join Contenedor.TipoRec tr on dv.tipo=tr.tipo and dv.tipo_rec=tr.tipo_rec '.$SQL_inc.') AS ALIAS '.$SQL_limits; 
		
		$SQL_string=str_replace('@',"'",$SQL_string);

		$rows = $cn->ejec_store_procedura_sql_script($SQL_string);
		if(count($rows)){
			foreach($rows AS $row){                        
				$entry = array(
					'tipo'=>utf8_encode($row[0]),
					'anio'=>utf8_encode($row[1]),
					'periodo'=>utf8_encode($row[2]),
					'insoluto'=>utf8_encode($row[3]),
					'factorreaj'=>utf8_encode($row[4]),
					'reajustado'=>utf8_encode($row[5]),
					'fatormora'=>utf8_encode($row[6]),
					'mora'=>utf8_encode($row[7]),
					'emision'=>utf8_encode($row[8]),
					'total'=>utf8_encode($row[9])

				);
				$jsonData['rows'][] = $entry;
			}
		}
	
		
		$this->view->data = json_encode($jsonData);

		
		
    }
	
	//devolvervalor
	
	
	public function enviarvalorAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
			
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnEnvia',"click","goToFormulario('frmbandejaenviar');");
		$evt[] = array('#btnSalida',"click","closePopup('#popupenvios');");

		$SQL_string=' select area,nombre  from t_ccostos'; 
		$comboarea = $cn->ejec_store_procedura_sql_script($SQL_string);
	
		$arArea = $ar->RegistrosCombo($comboarea,0,1);
		$val[] = array('#cmbAreades',$fn->ContenidoCombo($arArea,'[Seleccione]',''),'html');


		$SQL_stringTS='select * from Rentas.t_situaciondoc where flag_situacion=1'; 
		$combotsituacion = $cn->ejec_store_procedura_sql_script($SQL_stringTS);
	
		$arTsituacion = $ar->RegistrosCombo($combotsituacion,0,1);
		$val[] = array('#cmbAccion',$fn->ContenidoCombo($arTsituacion,'[Seleccione]',''),'html');
		
		
		

		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
	public function consusarioAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
	    
	    if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$idarea = $this->_request->getPost('area');
			
		$SQL_string='select id_usuario,vlogin from [Acceso].[TblUsuarios] where area='.$idarea; 
		$combousuario = $cn->ejec_store_procedura_sql_script($SQL_string);

		$cb_Areausu='<option value="">[Seleccione]</option>';
    
			for ($i=0;$i<count($combousuario);$i++){
            	$cb_Areausu.='<option value="'.$combousuario[$i][0].'" >'.utf8_encode(ucwords($combousuario[$i][1])).'</option>';
        	}
			echo $cb_Areausu;
    	}  
    }
	
	
		public function grabardevolverAction()
    {
		
		
		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){	
			/*
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			*/
			$cn = new Model_DbDatos_Datos();
			$login = new Zend_Session_Namespace('login');
			
			/*
			echo "<pre>";
			print_r($_REQUEST);
			echo "</pre>";
			exit;
			*/
			
			$area_destino = $_REQUEST['txtdvAreades'];
			$usuario_destino = $_REQUEST['txtdvUsuario'];
			$cmb_situacion = $_REQUEST['cmbdvAccion'];
			$observacion = $_REQUEST['txtdvObservaciones'];
			/*
			echo "<pre>";
			print_r($_REQUEST);
			echo "</pre>";
			exit;
			*/
			
	/*		
    [txtAreades] => 3
    [cmbAreades] => 3
    [txtUsuario] => 124
    [cmbUsuario] => 124
    [cmbAccion] => 4
    [inputItem] => 1
    [txtareas] => sadasdsad
	*/		
			
			$parametros[] = array(3);
			$parametros[] = array($login->area);
			$parametros[] = array($area_destino);
			$parametros[] = array($usuario_destino);							
			$parametros[] = array($cmb_situacion);
			$parametros[] = array($observacion);											
			$parametros[] = array($login->id);
			$parametros[] = array('INFORMATICA13');
					
					
			$rowAdqui = $cn->ejecutapgsql('sp_movimiento_recibidos', $parametros);
			
			
			echo $rowAdqui[1][0];
			/**
			echo "<pre>";
			print_r($rowAdqui);
			echo "</pre>";
			*/
			/*
			if(!empty($data->DataValor)){
			
				$dataDetalle = $data->DataValor;			
				foreach($dataDetalle as $dDetalle){
				
					unset($parametros);
					
					$parametros[] = array(1);
					$parametros[] = array(0);
					$parametros[] = array($dDetalle->idvalor);
					$parametros[] = array($login->area);
					$parametros[] = array($area_destino);
					$parametros[] = array($usuario_destino);							
					$parametros[] = array($cmb_situacion);
					$parametros[] = array($observacion);											
					$parametros[] = array($login->id);
					$parametros[] = array('INFORMATICA13');
   				    $parametros[] = array(date("Y-m-d H:i:s", time()));
					
					
					$rowAdqui = $cn->ejecutapgsql('sp_movimiento_valor', $parametros);

				}
				
			}
			*/
    	}    	
    	
    }	
	
	
	
	public function grabarAction()
    {
		
		
		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){	
			/*
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			*/
			$cn = new Model_DbDatos_Datos();
			$login = new Zend_Session_Namespace('login');
			
			/*
			echo "<pre>";
			print_r($_REQUEST);
			echo "</pre>";
			exit;
			*/
			
			$area_destino = $_REQUEST['txtAreades'];
			$usuario_destino = $_REQUEST['txtUsuario'];
			$cmb_situacion = $_REQUEST['cmbAccion'];
			$observacion = $_REQUEST['txtareas'];
			
	/*		
    [txtAreades] => 3
    [cmbAreades] => 3
    [txtUsuario] => 124
    [cmbUsuario] => 124
    [cmbAccion] => 4
    [inputItem] => 1
    [txtareas] => sadasdsad
	*/		
			
			$parametros[] = array("@mquery",1);
			$parametros[] = array("@midareaorigen",$login->area);
			$parametros[] = array("@midareadestino",$area_destino);
			$parametros[] = array("@midusuariodestino",$usuario_destino);							
			$parametros[] = array("@midsituacion",$cmb_situacion);
			$parametros[] = array("@mobservacion",$observacion);											
			$parametros[] = array("@midusuario",$login->id);
			$parametros[] = array("@mestacion",'INFORMATICA13');
					
					
			$rowAdqui = $cn->ejec_store_procedura_sql('Rentas.sp_movimiento_recibidos', $parametros);
			
			
			echo $rowAdqui[0][0];
			/**
			echo "<pre>";
			print_r($rowAdqui);
			echo "</pre>";
			*/
			/*
			if(!empty($data->DataValor)){
			
				$dataDetalle = $data->DataValor;			
				foreach($dataDetalle as $dDetalle){
				
					unset($parametros);
					
					$parametros[] = array(1);
					$parametros[] = array(0);
					$parametros[] = array($dDetalle->idvalor);
					$parametros[] = array($login->area);
					$parametros[] = array($area_destino);
					$parametros[] = array($usuario_destino);							
					$parametros[] = array($cmb_situacion);
					$parametros[] = array($observacion);											
					$parametros[] = array($login->id);
					$parametros[] = array('INFORMATICA13');
   				    $parametros[] = array(date("Y-m-d H:i:s", time()));
					
					
					$rowAdqui = $cn->ejecutapgsql('sp_movimiento_valor', $parametros);

				}
				
			}
			*/
    	}    	
    	
    }	

	public function recepcionarAction(){
		
	    $this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
	/*	
		$val_idruta = $this->_request->getPost('idruta');
		$valor = $this->_request->getPost('valor');
		$desvalor = $this->_request->getPost('desvalor');
		$fechvalor = $this->_request->getPost('fechvalor');
*/
		$cn = new Model_DbDatos_Datos();
		
				$parametros[] = array("@mquery",2);
		//		$parametros[] = array($val_idruta);
				
//		$rowAdqui = $cn->ejecutapgsql('sp_movimiento_valor', $parametros);
		$rowAdqui = $cn->ejec_store_procedura_sql('Rentas.sp_movimiento_recibidos', $parametros);
		

		exit;
		
	}
	
	public function registroexpedientegnAction(){
		
	
		$cn = new Model_DbDatos_Datos();
		
		
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 50;
		
		//$valor = $this->_request->getParam('valor',''); 
    	
		$start = (($page-1) * $limit);
    	$end = $limit;
		
		$SQL_columns=' count(*) ';    
		$SQL_inc = ' where idvalor IS NOT NULL ';  
		$SQL_order=' order by idvalor desc ';
		$SQL_string=' SELECT '.$SQL_columns.' FROM proceso_impresioncoactivo '.$SQL_inc;

		$rowsTotal = $cn->ejecutapgsql_script($SQL_string);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		$SQL_string='';
	

 		$SQL_columns=' * ';
		$SQL_inc = ' where idvalor IS NOT NULL ';  

		$SQL_order=' order by idvalor desc ';
		$SQL_limits = ' LIMIT '.$end.' OFFSET '.$start; 
		
		$SQL_string=' SELECT * FROM ( SELECT '.$SQL_columns.', ROW_NUMBER() OVER ('.$SQL_order.') AS ROW FROM proceso_impresioncoactivo '.$SQL_inc.') AS ALIAS '.$SQL_limits; 
		
		$rows = $cn->ejecutapgsql_script($SQL_string);
		if(count($rows)){
			foreach($rows AS $row){                        
				$entry = array(
					'idvalor'=>utf8_encode($row[1]),					
					'tiponumerovalor'=>$row[5],
					'emision'=>date('d/m/Y',strtotime($row[12])),
					'codigo'=>$row[2], 
					'contribuyente'=>utf8_encode($row[4]),
					'numexpediente'=>utf8_encode($row[3]),
					'emisionexpediente'=>date('d/m/Y',strtotime($row[13])),
					'nrocargo'=>utf8_encode($row[15]),
					'resolucion'=>utf8_encode(strtoupper($row[23])),
					'dias'=>utf8_encode($row[16]),
					'mora'=>utf8_encode($row[17]),
					'total'=>utf8_encode($row[21])
				);
				$jsonData['rows'][] = $entry;
			}
		}
	
		
		$this->view->data = json_encode($jsonData);		
	}

	
	public function registrovalorestmpAction(){
		
	
	
	
	
	
	
		$cn = new Model_DbDatos_Datos();
		
		
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 50;
		
		$valor = $this->_request->getParam('valor',''); 
    	
		$start = (($page-1) * $limit);
    	$end = $limit;
		
		$SQL_columns=' count(*) ';    
		$SQL_inc = ' where id_valor IS NOT NULL ';  
		$SQL_order=' order by id_valor desc ';
		$SQL_string=' SELECT '.$SQL_columns.' FROM tmp_valor_contrib '.$SQL_inc;

		$rowsTotal = $cn->ejec_store_procedura_sql_script($SQL_string);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		$SQL_string='';
	

 		$SQL_columns=' * ';
		$SQL_inc = ' where id_valor IS NOT NULL ';  

		$SQL_order=' order by id_valor desc ';
		$SQL_limits = ' WHERE   ROW BETWEEN '.$start.' AND '.$end; 
		
		$SQL_string=' SELECT * FROM ( SELECT '.$SQL_columns.', ROW_NUMBER() OVER ('.$SQL_order.') AS ROW FROM tmp_valor_contrib '.$SQL_inc.') AS ALIAS '.$SQL_limits; 
		
		$rows = $cn->ejec_store_procedura_sql_script($SQL_string);
		if(count($rows)){
			foreach($rows AS $row){                        
				$entry = array(
					'idvalor'=>utf8_encode($row[0]),
					'numvalor'=>utf8_encode(str_pad($row[1], 7, "0", STR_PAD_LEFT)),
					'desvalor'=>utf8_encode($row[5]),
					'anio'=>utf8_encode($row[2]),
					'tiponumerovalor'=>$row[5].'.- '.str_pad($row[1], 7, "0", STR_PAD_LEFT).'-'.$row[2],
					'emision'=>date('d/m/Y',strtotime($row[6])),
					'codigo'=>str_pad($row[3], 7, "0", STR_PAD_LEFT), 
					'contribuyente'=>utf8_encode($row[4])
					
					
				);
				$jsonData['rows'][] = $entry;
			}
		}
	
		
		$this->view->data = json_encode($jsonData);		
	}
	
	
	public function recibidosvalorestmpAction(){
		
	
	
	
	
	
	
		$cn = new Model_DbDatos_Datos();
		
		
		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 50;
		
		$valor = $this->_request->getParam('valor',''); 
    	
		$start = (($page-1) * $limit);
    	$end = $limit;
		
		$SQL_columns=' count(*) ';    
		$SQL_inc = ' where idvalor IS NOT NULL ';  
		$SQL_order=' order by idvalor desc ';
		$SQL_string=' SELECT '.$SQL_columns.' FROM recibido_valor_contrib '.$SQL_inc;

		$rowsTotal = $cn->ejecutapgsql_script($SQL_string);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		$SQL_string='';
	

 		$SQL_columns=' * ';
		$SQL_inc = ' where idvalor IS NOT NULL ';  

		$SQL_order=' order by idvalor desc ';
		$SQL_limits = ' LIMIT '.$end.' OFFSET '.$start; 
		
		$SQL_string=' SELECT * FROM ( SELECT '.$SQL_columns.', ROW_NUMBER() OVER ('.$SQL_order.') AS ROW FROM recibido_valor_contrib '.$SQL_inc.') AS ALIAS '.$SQL_limits; 
		
		$rows = $cn->ejecutapgsql_script($SQL_string);
		if(count($rows)){
			foreach($rows AS $row){                        
				$entry = array(
					'idvalor'=>utf8_encode($row[0]),
					'numvalor'=>utf8_encode(str_pad($row[1], 7, "0", STR_PAD_LEFT)),
					'desvalor'=>utf8_encode($row[5]),
					'anio'=>utf8_encode($row[2]),
					'tiponumerovalor'=>$row[5].'.- '.str_pad($row[1], 7, "0", STR_PAD_LEFT).'-'.$row[2],
					'emision'=>date('d/m/Y',strtotime($row[6])),
					'codigo'=>str_pad($row[3], 7, "0", STR_PAD_LEFT), 
					'contribuyente'=>utf8_encode($row[4])
					
					
				);
				$jsonData['rows'][] = $entry;
			}
		}
	
		
		$this->view->data = json_encode($jsonData);		
	}
	
	
	public function expedienteAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$SQL_string="select conta from t_conta where rtrim(ltrim(tabla))='EXPEDIENTES'"; 		

		$rows = $cn->ejec_store_procedura_sql_script($SQL_string);
		$val[] = array('#txtNroexpe',utf8_encode(str_pad($rows[0][0],7, "0", STR_PAD_LEFT)),'val');	

		
		$SQL_string2="select * from [Acceso].[TblUsuarios] where area='4004'";
		$rows2 = $cn->ejec_store_procedura_sql_script($SQL_string2);		 				
		
		
		
		
		
		
		//$evt[] = array('#btnGrabaexpe',"click","goToFormulario('frmexpediente');");
		$evt[] = array('#btnSalidaexpe',"click","closePopup('#popupexpediente');");
		//$evt[] = array('#btnImprimirExpediente',"click","imprimeExpediente();");
		//$evt[] = array('#btnImprimirCargo',"click","imprimeCargos();");
		$evt[] = array('#btnImprimirRec01',"click","imprimeResolicion01();");
		$evt[] = array('#btnImprimirCedula',"click","imprimeCedulaNotificacion();");
		
		


		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	
    }
	
	public function expedienteeditarAction(){
		$fn = new Libreria_Pintar();
		
		$numexpe=$this->_request->getParam('numexpe','');
		$anioexpe=$this->_request->getParam('anoexpe','');
		$codigo=$this->_request->getParam('codigo','');

		$val[] = array('#txtNroexpe',$numexpe,'val');
		$val[] = array('#txtAniosexpe',$anioexpe,'val');
		$val[] = array('#txtCodigoexpe',$codigo,'val');

		$evt[] = array('#btnSalidaexpe',"click","closePopup('#popupexpedienteeditar');");		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	}
	
	public function expedientedatosAction()
	{

    	$cn = new Model_DbDatos_Datos();
    	
    	$num_exp=$this->_request->getParam('num_exp','');
		$ano_exp=$this->_request->getParam('ano_exp','');
    	
    	$parametros[] = array('@buscar',3);
    	$parametros[] = array('@num_exp',$num_exp); 
    	$parametros[] = array('@ano_exp',$ano_exp);
    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Coactivo.sp_Expediente', $parametros);
    	
    	$jsonData = array('total'=>count($rowRuta),'rows'=>array());
    	foreach($rowRuta AS $row){
			$entry = array(
					'desvalor'=>utf8_encode($row[1]),
					'idvalor'=>utf8_encode(ucwords($row[0])),
					'numval'=>$row[2],
					'anoval'=>$row[3],
					'numanoval'=>$row[2].'-'.$row[3],
					'monto'=>$row[4],
					'fecha'=>$row[5],
			);
			$jsonData['rows'][] = $entry;
		}
		$this->view->data = json_encode($jsonData);	
		//var_dump($rowRuta);		
	}
	
	public function grabarexpedienteAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
			$cn = new Model_DbDatos_Datos();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){	
			

			$num_exp=(int)$this->_request->getParam('txtNroexpe','');
			$anio_exp=$this->_request->getParam('txtAniosexpe','');
			$id_ejecutor=$this->_request->getParam('txtIdEjecutor','');
			$nombre_ejecutor=$this->_request->getParam('txtNombreEjecutor','');
			$id_auxiliar=$this->_request->getParam('txtIdAuxiliar','');
			$nombre_auxiliar=$this->_request->getParam('txtNombreAuxiliar','');
			$observaciones=$this->_request->getParam('txtObservaciones','');
			$fechaemision=$this->_request->getParam('txtFechaEmision','');
			$costasygastos=$this->_request->getParam('txtcostasygastos','');
			
			
			$parametros[] = array($anio_exp);
			$parametros[] = array($fechaemision);
			$parametros[] = array($id_ejecutor);
			$parametros[] = array($id_auxiliar);
			$parametros[] = array($observaciones);
			$parametros[] = array('ESTACION');
			$parametros[] = array($costasygastos);
			
				
			//$rowAdqui = $cn->ejecutapgsql('sp_movimiento_valor', $parametros);
			$rowAdqui = $cn->ejecutapgsql('sp_generacionexpediente_cargo', $parametros);
		
			exit;

			
			
			$cn = new Model_DbDatos_Datos();
			$login = new Zend_Session_Namespace('login');
			
						
			$txtNroexpe = $data->txtNroexpe;
			$txtAniosexpe = $data->txtAniosexpe;
			$txtCodigoexpe = $data->txtCodigoexpe;
			$txtAuxiliar = $data->txtAuxiliar;
			
			
			
			
    	}    	
    	
    }

	public function devolvervalorAction(){
	    $this->_helper->getHelper('ajaxContext')->initContext();
		/*
		$cn = new Model_DbDatos_Datos();
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	*/
			
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btndvConfirmar',"click","goToFormulario('frmbandejadevolver');");
		$evt[] = array('#btndvSalida',"click","closePopup('#popupdevolvervalor');");

		$SQL_string=' select idarea,nomarea  from area'; 
		$comboarea = $cn->ejecutapgsql_script($SQL_string);
	
		$arArea = $ar->RegistrosCombo($comboarea,0,1);
		$val[] = array('#cmbdvAreades',$fn->ContenidoCombo($arArea,'[Seleccione]',''),'html');


		$SQL_stringTS='select * from t_situacion where flag_situacion=2'; 
		$combotsituacion = $cn->ejecutapgsql_script($SQL_stringTS);
	
		$arTsituacion = $ar->RegistrosCombo($combotsituacion,0,1);
		$val[] = array('#cmbdvAccion',$fn->ContenidoCombo($arTsituacion,'[Seleccione]',''),'html');
		
		
		

		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		
	
	}	
	
	public function exigibilidadAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
		$fn = new Libreria_Pintar();
		
		
		$evt[] = array('#btnGrabaexpe',"click","goToFormulario('frmexigibilidad');");
		$evt[] = array('#btnSalidaexpe',"click","closePopup('#popupexigibilidad');");
		
		
		$fn->PintarEvento($evt);
	
    }
	
	public function grabarexigibilidadAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){	
			
			$json = $this->_request->getPost('json');
			$data = json_decode($json);

			
			
			$cn = new Model_DbDatos_Datos();
			$login = new Zend_Session_Namespace('login');
			
						
			$txtNroexig = $data->txtNroexig;
			$txtAniosexig = $data->txtAniosexig;
						
			
				
			$parametros[] = array('@buscar',1);					
			$parametros[] = array('@numexig',$txtNroexig);
			$parametros[] = array('@anoexig',$txtAniosexig);											
			$parametros[] = array('@vusernm',$login->user);														

			@$rows=$cn->ejec_store_procedura_sql("Coactivo.sp_Exigibilidad",$parametros);
		
			
			
			if(!empty($data->Exigibilidad)){
			
				$dataDetalle = $data->Exigibilidad;			
				foreach($dataDetalle as $dDetalle){
				
					unset($parametros);
						
					$parametros[] = array('@buscar',2);					
					$parametros[] = array('@numexig',$txtNroexig);
					$parametros[] = array('@anoexig',$txtAniosexig);
					$parametros[] = array('@idvalor',$dDetalle->idvalor);
					$parametros[] = array('@numerval',$dDetalle->numval);												
					$parametros[] = array('@annioval',$dDetalle->anoval);														

					@$datos=$cn->ejec_store_procedura_sql("Coactivo.sp_Exigibilidad",$parametros);
				}
				
			}
			
    	}    	
    	
    }
	
	public function exigibilidadeditarAction()
	{
		$fn = new Libreria_Pintar();
		
		$numexig=$this->_request->getParam('numexig','');
		$anoexig=$this->_request->getParam('anoexig','');
		

		$val[] = array('#txtNroexig',$numexig,'val');
		$val[] = array('#txtAniosexig',$anoexig,'val');
		

		$evt[] = array('#btnSalidaexpe',"click","closePopup('#popupexigibilidadeditar');");		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	}
	
	public function exigibilidaddatosAction()
	{

    	$cn = new Model_DbDatos_Datos();
    	
    	$numexig=$this->_request->getParam('numexig','');
		$anoexig=$this->_request->getParam('anoexig','');
    	
    	$parametros[] = array('@buscar',3);
    	$parametros[] = array('@numexig',$numexig); 
    	$parametros[] = array('@anoexig',$anoexig);
    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Coactivo.sp_Exigibilidad', $parametros);
    	
    	$jsonData = array('total'=>count($rowRuta),'rows'=>array());
    	foreach($rowRuta AS $row){
			$entry = array(
					'desvalor'=>utf8_encode($row[1]),
					'idvalor'=>utf8_encode(ucwords($row[0])),
					'numval'=>$row[2],
					'anoval'=>$row[3],
					'numanoval'=>$row[2].'-'.$row[3],
					'monto'=>$row[4],
					'fecha'=>$row[5],
			);
			$jsonData['rows'][] = $entry;
		}
		$this->view->data = json_encode($jsonData);	
			
	}
}
<?php

/**
 * MantpobladoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class RptgeneralppuController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexpredxusoAction() {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "PREDIOS X USO";
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#contentBox',"tabs","");
		
		//COMBO A?O
		$bus_anno="";
		for($i=1992;$i<=date('Y');$i++){
		$bus_anno.="<option value='".$i."'";
			if($i==date('Y'))
			{
				$bus_anno.=" selected ";
			}
		$bus_anno.=">".$i."</option>";
		}
		$this->view->cb_anno=$bus_anno;
		
		$cn=new Model_DbDatos_Datos();
		
		//COMBO USOS
		$nombrestore="rentas.sp_predio";
	    $arraydatos[]=array('@msquery',3);
	    $arraydatos[]=array('@tipo_predi',1);
	    $dato=$cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
	    $cb_usopredio='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato);$i++){
				$cb_usopredio.='<option value="'.$dato[$i][0].'">'.$dato[$i][1].'</option>';	
		}
		$this->view->cb_uso=$cb_usopredio;

		$evt[] = array('#btnGraficoReporteXUso',"click",'ReporteIngresoxGerencia()');
		$evt[] = array('#btnExportarReporteXUso', "click", "exportarPredxuso();" );
		
		$fn->PintarEvento($evt);
		
	}
	
	public function reporte01Action()
    {
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$data_codigo = $data_query[0];
		$data_anio	 = $data_query[1];
		$data_uso	 = $data_query[2];
	
	
		$parametros[] = array('@BUSC','1');
		$parametros[] = array('@CODIGO',$data_codigo);
		$parametros[] = array('@ANNO',$data_anio);
		$parametros[] = array('@USO',$data_uso);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
	
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="850PX">
				<tr>';
		$parte2="<th colspan=5><center><b>CANTIDAD DE PREDIOS X USOS ".$data_anio."</b></center></th>";
		$parte3='</tr>
				<tr>
					<th width="10%"><center><b>ID</b></center></th>
					<th width="50%"><center><b>USO</b></center></th>
    				<th width="15%"><center><b># PREDIOS</b></center></th>
    				<th width="25%"><center><b>CONDICION</b></center></th>
					<th width="10%" align="center"><center><b>Opcion</b></center></th>
			    </tr>';
		$cabecera=$parte1.$parte2.$parte3;	
		
		$cad1.=$cabecera;
		
		$tipo='U';
		
		for($i = 0; $i < count ( $rows ); $i ++) 
        {
			$tipo1=$rows[$i][0];
				
			if($tipo!=$tipo1)
			{
				$tipo=$tipo1;
				
				$cad1.=$cabecera;
				$cad1.='<br><br>';
			}
			
			$cad1.='<tr>';
			$cad1.=	"<td width=10%><center><b>".$rows[$i][0]."</b></center></td>";
			$cad1.=	"<td align=left width=50%><b>".utf8_encode($rows[$i][1])."</b></td>";
			$cad1.=	"<td width=15%><center><b>".$rows[$i][2]."</b></center></td>";
			$cad1.=	"<td width=25%><center><b>".$rows[$i][3]."</b></center></td>";
			$cad1.= "<td align='center'><img src='img/export.gif' title='Exporta Relacion' onclick='imprimeReportexUsos(".$rows[$i][5].",".'"'.$rows[$i][6].'"'.",".'"'.$rows[$i][0].'"'.")'></td>";
			$cad1.='</tr>';
		}
	
		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;
	}
	
	public function reportexusosAction()
	{ 	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		
		$fn = new Libreria_Pintar();		
		
		
		$anno = $this->_request->getParam('anno','');
		$uso = $this->_request->getParam('uso',''); 
		$tipo = $this->_request->getParam('tipo',''); 
		$codigo = $this->_request->getParam('codigo','');
		
		unset($arraydatos);
		$parametros[] = array('@BUSC','9');
		$parametros[] = array('@CODIGO',$codigo);
		$parametros[] = array('@ANNO',$anno);
		$parametros[] = array('@ID_USO',$uso);
		$parametros[] = array('@FLAG',$tipo);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
		
		$val[] = array('#txtAnio',$anno,'val');
		$val[] = array('#txtUso',$uso,'val');
		$val[] = array('#txtFlag',$tipo,'val');
		$val[] = array('#txtCodigos',$codigo,'val');
		
		$evt[] = array('#btnExpDetPPUExcel', "click", "exportarPredioDetalle();" );
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
		$ini='<div align=center style="overflow:auto;height: 90%;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="90%">
				<tr>';
		IF($tipo=='U'){$nom_uso='UNICOS';}
		ELSE {$nom_uso='COOPROPIETARIOS';}
			
		$parte2="<th colspan=8><center><b><H1>DETALLE DE PREDIOS ".$nom_uso." ==> ".$rows[0][6]."</H1></b></center></th>";
		$parte3='</tr>
				<tr>
					<th><center><b>NRO</b></center></th>
					<th><center><b>CODIGO</b></center></th>
					<th><center><b>CODIGO DE PREDIO</b></center></th>
    				<th><center><b>ANEXO - SUBANEXO</b></center></th>
    				<th><center><b>A&Ntilde;O</b></center></th>
					<th><center><b>DIRECCION PREDIO</b><center></th>
					<th><center><b>% CONSTRUCCION</b></center></th>
					<th><center><b>ZONA</b></center></th>
			    </tr>';
		$cabecera=$parte1.$parte2.$parte3;
		
		$cad1.=$cabecera;
		
		if(count($rows))
		{
			foreach($rows as $row){
				$cad1.='<tr>';
				$cad1.=	"<td><center><b>".$row[0]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[1]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[2]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[3]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[4]."</b></center></td>";
				$cad1.=	"<td align=left><b>".$row[5]."</b></td>";
				$cad1.=	"<td><center><b>".$row[7]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[8]."</b></center></td>";				
				
				$cad1.='</tr>';
				
			}
		}	
		
		echo $ini.$cad1;
		
	}
	
	public function exportapreppuAction()
	{
		$cn = new Model_DbDatos_Datos();
		
		$anno = $this->_request->getParam('aniodetp','');
		$uso = $this->_request->getParam('usodetp',''); 
		$tipo = $this->_request->getParam('tipcondidetp',''); 
		$codigo = $this->_request->getParam('codigodetp','');
		
		unset($arraydatos);
		$parametros[] = array('@BUSC','9');
		$parametros[] = array('@CODIGO',$codigo);
		$parametros[] = array('@ANNO',$anno);
		$parametros[] = array('@ID_USO',$uso);
		$parametros[] = array('@FLAG',$tipo);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
				
		$cabecera="";
		
		if(count($rows)){
			$strHtml = "<table border='1'>";					
			$cabecera .= "<tr>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Item</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Codigo</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>CODIGO DE PREDIO</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>ANEXO - SUBANEXO</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>A&Ntilde;O</th>";				
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>DIRECCION PREDIO</th>";		
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>USO</th>";	
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>% CONSTRUCCION</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>ZONA</th>";
			$cabecera .= "</tr>";
			
			$strHtml=$strHtml.$cabecera;
			
			$i=0;
			$j=0;
			
			foreach($rows as $row){
				$i++;
				$j++;
				if($i%2==0)
					$color = "#EBF1DE";
				else
					$color = "#FFFFFF";
				
				$strHtml .= "<tr>";
					$strHtml .= "<td style='background-color:".$color."'>".$j."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[0]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[1]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[2]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[3]."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[4]."</td>";		
					$strHtml .= "<td style='background-color:".$color."'>".$row[5]."</td>";	
					$strHtml .= "<td style='background-color:".$color."'>".$row[6]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[7]."</td>";					
				$strHtml .= "</tr>";
				
			}			
			$strHtml .= "</table>";
			
			$this->view->data = $strHtml;		
		}
		
		
	}
	
	public function graprpt01Action()
	{ 	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
		$anio=$this->_request->getParam('anio','');
		$this->view->anio=$anio;
		$val[] = array('#txtanno',$anio,'val');	
		
		$fn->PintarValor($val);
	}	
	
	public function dataimagenppuAction()
	{
		$cn = new Model_DbDatos_Datos();
		$anio=$this->_request->getParam('anio','');
		
		$condicion=$this->_request->getParam('condicion','');
		
		unset($parametros);
		$parametros[]=array('@BUSC',1);
		$parametros[]=array("@ANNO",$anio);
		$parametros[]=array("@tipo_condicion",$condicion);
		$parametros[]=array("@codigo",'');
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
		
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry[] = array(utf8_encode($row[1]),(int)$row[2]);
			}
		}
		
		$this->view->data = json_encode($entry);

		
	}
	
	public function exportarrpt01Action()
    {
		$cn   	= new Model_DbDatos_Datos();
    
		
		$codigo = $_REQUEST['codigo'];
		$anno = $_REQUEST['anno'];
		$uso = $_REQUEST['uso'];
	
	
		$parametros[] = array('@BUSC',1);
		$parametros[] = array('@CODIGO',$codigo);
		$parametros[] = array('@ANNO',$anno);
		$parametros[] = array('@USO',$uso);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
	
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="850PX">
				<tr>';
		$parte2="<th colspan=4 style='background-color:#4F81BD; color:#FFFFFF'><center><b>CANTIDAD DE PREDIOS X USOS ".$anno."</b></center></th>";
		$parte3='</tr>
				<tr>
					<th width="10%"><center><b>ID</b></center></th>
					<th width="50%"><center><b>USO</b></center></th>
    				<th width="15%"><center><b># PREDIOS</b></center></th>
    				<th width="25%"><center><b>CONDICION</b></center></th>
					
			    </tr>';
		$cabecera=$parte1.$parte2.$parte3;	
		
		$cad1.=$cabecera;
		
		$tipo='U';
		
		for($i = 0; $i < count ( $rows ); $i ++) 
        {
			/*$tipo1=$rows[$i][0];
				
			if($tipo!=$tipo1)
			{
				$tipo=$tipo1;
				
				$cad1.=$cabecera;
				$cad1.='<br><br>';
			}*/
			
			$cad1.='<tr>';
			$cad1.=	"<td width=10%><center><b>".$rows[$i][0]."</b></center></td>";
			$cad1.=	"<td align=left width=50%><b>".utf8_encode($rows[$i][1])."</b></td>";
			$cad1.=	"<td width=15%><center><b>".$rows[$i][2]."</b></center></td>";
			$cad1.=	"<td width=25%><center><b>".$rows[$i][3]."</b></center></td>";
			/*$cad1.= "<td align='center'><img src='img/export.gif' title='Exporta Relacion' ></td>";*/
			$cad1.='</tr>';
		}
	
		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;
	}
	
	
}


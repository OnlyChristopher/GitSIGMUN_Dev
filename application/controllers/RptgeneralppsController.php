<?php

/**
 * MantpobladoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class RptgeneralppsController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	
	public function indexpredxsectorAction()
	{
	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "PREDIOS X SECTOR";
		
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
		
		//COMBO SECTOR
		$nombrestore="Rentas.Rpt_Rentas_General";
	    $arraydatos[]=array('@BUSC',8);
	    $arraydatos[]=array('@SECTOR','');
	    $dato=$cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
	    $cb_usopredio='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato);$i++){
				$cb_usopredio.='<option value="'.$dato[$i][0].'">'.$dato[$i][1].'</option>';	
		}
		$this->view->cb_uso=$cb_usopredio;

		$evt[] = array('#btnGrapPrediosXSector',"click",'GrapIngresoxGerencia()');
		$evt[] = array('#btnExportPrediosXSector', "click", "exportarPredxsector();" );
		
		$fn->PintarEvento($evt);
	
	}
	
	public function reportexsectorAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		
		$fn = new Libreria_Pintar();		
		
		
		$anno = $this->_request->getParam('anno','');
		$id_zona = $this->_request->getParam('id_zona',''); 
		$flag = $this->_request->getParam('flag',''); 
		
		unset($arraydatos);
		$parametros[] = array('@BUSC','10');
		$parametros[] = array('@ANNO',$anno);
		$parametros[] = array('@id_zona',$id_zona);
		$parametros[] = array('@FLAG',$flag);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
		
		$val[] = array('#txtAnio',$anno,'val');
		$val[] = array('#txtSector',$id_zona,'val');
		$val[] = array('#txtFlag',$flag,'val');
		
		$evt[] = array('#btnExpDetPPSExcel', "click", "exportarPredioDetalleSector();" );
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
		$ini='<div align=center style="overflow:auto;height: 90%;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="90%">
				<tr>';
		IF($flag=='U'){$nom_uso='UNICOS';}
		ELSE {$nom_uso='COOPROPIETARIOS';}
			
		$parte2="<th colspan=8><center><b><H1>DETALLE DE PREDIOS ".$nom_uso." ==> ".$rows[0][8]."</H1></b></center></th>";
		$parte3='</tr>
				<tr>
					<th><center><b>NRO</b></center></th>
					<th><center><b>CODIGO</b></center></th>
					<th><center><b>NOMBRE</b></center></th>
					<th><center><b>COD. PREDIO</b></center></th>
    				<th><center><b>ANEXO</b></center></th>
    				<th><center><b>DIR.PREDIO</b></center></th>
					<th><center><b>USO</b><center></th>
					<th><center><b>% CONSTRUCCION</b></center></th>
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
				$cad1.=	"<td><center><b>".$row[6]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[7]."</b></center></td>";
				
				$cad1.='</tr>';
				
			}
		}	
		
		echo $ini.$cad1;
		
	}
	
	public function exportadetpreppsAction()
	{
		$cn = new Model_DbDatos_Datos();
		
		$anio = $this->_request->getParam('anio','');
		$id_sector = $this->_request->getParam('id_sector',''); 
		$flag = $this->_request->getParam('flag',''); 
		
		unset($arraydatos);
		$parametros[] = array('@BUSC','10');
		$parametros[] = array('@ANNO',$anio);
		$parametros[] = array('@id_zona',$id_sector);
		$parametros[] = array('@FLAG',$flag);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
				
		$cabecera="";
		
		if(count($rows)){
			$strHtml = "<table border='1'>";					
			$cabecera .= "<tr>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Item</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Codigo</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Nombre</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>COD. PREDIO</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>ANEXO</th>";			
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>DIR. PREDIO</th>";		
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>USO</th>";	
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>% CONSTRUCCION</th>";
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
					$strHtml .= "<td style='background-color:".$color."'>".$row[1]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[2]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[3]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[4]."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[5]."</td>";		
					$strHtml .= "<td style='background-color:".$color."'>".$row[5]."</td>";	
					$strHtml .= "<td style='background-color:".$color."'>".$row[7]."</td>";					
				$strHtml .= "</tr>";
				
			}			
			$strHtml .= "</table>";
			
			$this->view->data = $strHtml;		
		}
		
		
	}
	
	
	public function reporte02Action()
    {
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$data_codigo = $data_query[0];
		$data_anio	 = $data_query[1];
		$data_sector = $data_query[2];
	
	
		$parametros[] = array('@BUSC','2');
		$parametros[] = array('@CODIGO',$data_codigo);
		$parametros[] = array('@ANNO',$data_anio);
		$parametros[] = array('@SECTOR',$data_sector);
		
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
					<th width="10%"><center><b>Opción</b></center></th>
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
			$cad1.=	"<td align=left width=50%><b>".utf8_encode($rows[$i][2])."</b></td>";
			$cad1.=	"<td width=15%><center><b>".$rows[$i][3]."</b></center></td>";
			$cad1.=	"<td width=25%><center><b>".$rows[$i][4]."</b></center></td>";
			//$cad1.=	"<td align='center'><img src='img/export.gif' title='Exporta Predios' ></td>";
			$cad1.= "<td align='center'><img src='img/export.gif' title='Exporta Predios' onclick='imprimeReportexSector(".'"'.$rows[$i][0].'"'.",".'"'.$rows[$i][1].'"'.",".'"'.$rows[$i][6].'"'.")'></td>";
			$cad1.='</tr>';
		}
	
		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;
	}
	
	public function graprpt02Action()
	{ 	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		//$fn = new Libreria_Pintar();
		
		$anio=$this->_request->getParam('anio','');
		$this->view->anio=$anio;
		$sector=$this->_request->getParam('sector','');
		$this->view->sector=$sector;
		
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
		//$fn->PintarValor($val);
	}

	
	public function exportarrpt02Action()
    {
		$cn   	= new Model_DbDatos_Datos();
    
		
		$codigo =  $_REQUEST['codigo'];
		$anno   = $_REQUEST['anno'];
		$sector = $_REQUEST['sector'];
	
	
		$parametros[] = array('@BUSC','2');
		$parametros[] = array('@CODIGO',$codigo);
		$parametros[] = array('@ANNO',$anno);
		$parametros[] = array('@SECTOR',$sector);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
	
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="850PX">
				<tr>';
		$parte2="<th colspan=4><center><b>CANTIDAD DE PREDIOS X USOS ".$anno."</b></center></th>";
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
			/*
			$tipo1=$rows[$i][0];
				
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
			
			$cad1.='</tr>';
		}
	
		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;

	}
	
}


<?php

/**
 * MantpobladoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class RptgeneralController extends Zend_Controller_Action {
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

		$evt[] = array('#btnGraficoReporteXUso',"click","showPopup('rptgeneral/graprpt01','#popgrap01','800','500','Grafico # Predios X Uso');");
		
		
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
			$cad1.= "<td align='center'><img src='img/export.gif' title='Exporta Relacion' ></td>";
			$cad1.='</tr>';
		}
	
		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;
	}
	
	public function graprpt01Action()
	{ 	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
	
	}
	
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

		$fn->PintarEvento($evt);
	
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
			$cad1.=	"<td align=left width=50%><b>".utf8_encode($rows[$i][1])."</b></td>";
			$cad1.=	"<td width=15%><center><b>".$rows[$i][2]."</b></center></td>";
			$cad1.=	"<td width=25%><center><b>".$rows[$i][3]."</b></center></td>";
			$cad1.=	"<td align='center'><img src='img/export.gif' title='Exporta Predios' ></td>";
			$cad1.='</tr>';
		}
	
		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;
	}
	
	public function indexcontrixsectorAction()
	{
	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "CONTRIBUYENTES POR SECTOR";
		
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
		
		//COMBO DISTRITO
		$nombrestore="Contenedor.SP_TblDistrito";
	    $arraydatos[]=array('@msquery',1);
	    $dato=$cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
	    $cb_sectorpredio='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato);$i++){
				$cb_sectorpredio.='<option value="'.$dato[$i][0].'">'.$dato[$i][1].'</option>';	
		}
		$this->view->cb_distrito=$cb_sectorpredio;

		$fn->PintarEvento($evt);
	
	}
	
	public function muestrasectorAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
	    
	    if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$id_distrito = $this->_request->getPost('id_distrito');
			
			if ($id_distrito=="012")
			{
				$combostore1 ='Rentas.Rpt_Rentas_General';
				$arraydatos[]=array('@BUSC',8);
				$arraydatos[]=array('@SECTOR','');
				$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos);
				
				$cb_Tipocontri='<option value="">[Seleccione]</option>';
				for ($i=0;$i<count($rows1);$i++){
					$cb_Tipocontri.='<option value="'.$rows1[$i][0].'" >'.utf8_encode($rows1[$i][1]).'</option>';
				}
			}
			else
			{
				$cb_Tipocontri='<option value="">[Seleccione]</option>';
			}
			
		
			echo $cb_Tipocontri;
    	}  
    }
	
	public function reporte03Action()
    {
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$data_codigo   = $data_query[0];
		$data_anio	   = $data_query[1];
		$data_distrito = $data_query[2];
		$data_sector   = $data_query[3];
	
	
		$parametros[] = array('@BUSC','3');
		$parametros[] = array('@CODIGO',$data_codigo);
		$parametros[] = array('@ANNO',$data_anio);
		$parametros[] = array('@DISTRITO',$data_distrito);
		$parametros[] = array('@SECTOR',$data_sector);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
		if($data_distrito=="012")
		{
			$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="850PX">
					<tr>';
			$parte2="<th colspan=4><center><b>CONTRIBUYENTES DE INDEPENDENCIA ".$data_anio."</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="10%"><center><b>ID</b></center></th>
						<th width="40%"><center><b>DISTRITO</b></center></th>
						<th width="25%"><center><b>SECTOR</b></center></th>
						<th width="25%"><center><b># CONTRIBUYENTES</b></center></th>
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=10%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=30%><b>".$rows[$i][2]."</b></td>";
				$cad1.=	"<td align=left width=35%><b>".utf8_encode($rows[$i][3])."</b></td>";
				$cad1.=	"<td width=25%><center><b>".$rows[$i][4]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		}
		else
		{
			$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="850PX">
					<tr>';
			$parte2="<th colspan=4><center><b>CONTRIBUYENTES X DISTRITO ".$data_anio."</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="10%"><center><b>ID</b></center></th>
						<th width="70%"><center><b>DISTRITO</b></center></th>
						<th width="20%"><center><b># CONTRIBUYENTES</b></center></th>

					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=10%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=70%><b>".utf8_encode($rows[$i][2])."</b></td>";
				$cad1.=	"<td width=20%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		}
		
	}
	
	public function indexexoneradosAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "EXONERADOS";
		
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
		
		$fn->PintarEvento($evt);
	
	}
	
	public function reporte04Action()
    {
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$data_anio   = $data_query[0];
	
	
		$parametros[] = array('@BUSC','4');     
		$parametros[] = array('@ANNO',$data_anio);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
		
			$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="850PX">
					<tr>';
			$parte2="<th colspan=4><center><b>EXONERADOS DE LA MDI DURANTE EL PERIODO ".$data_anio."</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="10%"><center><b>CODIGO</b></center></th>
						<th width="20%"><center><b>NOMBRE</b></center></th>
						<th width="10%"><center><b>T. PERSONA</b></center></th>
						<th width="5%"><center><b>COD. PRED</b></center></th>
						<th width="30%"><center><b>DIR. PRED</b></center></th>
						<th width="15%"><center><b>USO</b></center></th>
						<th width="10%"><center><b>TOT. AUTOAVALUO</b></center></th>
						
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=10%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=20%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td width=5%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td align=left width=30%><b>".utf8_encode($rows[$i][6])."</b></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][7]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][8]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}
	
	public function indextopdeudoripAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "EXONERADOS";
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#contentBox',"tabs","");
		
		$fn->PintarEvento($evt);
	
	}
	
	public function reporte05Action()
	{
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$data_combo   = $data_query[0];
	
	
		$parametros[] = array('@BUSC','5');     
		$parametros[] = array('@TOP_BUSC',$data_combo);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
		
			$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="850PX">
					<tr>';
			$parte2="<th colspan=4><center><b> DEUDORES IP DEL A?O ".$data_anio."</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="10%"><center><b>CODIGO</b></center></th>
						<th width="20%"><center><b>NOMBRE</b></center></th>
						<th width="10%"><center><b>DIR. FISCA</b></center></th>
						<th width="5%"><center><b>IP ANT</b></center></th>
						<th width="30%"><center><b>IP 2011</b></center></th>
						<th width="15%"><center><b>IP 2012</b></center></th>
						<th width="10%"><center><b>IP 2013</b></center></th>
						<th width="10%"><center><b>IP 2014</b></center></th>
						<th width="10%"><center><b>IP 2015</b></center></th>
						<th width="10%"><center><b>TOT IP</b></center></th>
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=10%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=20%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][2]."</b></center></td>";
				$cad1.=	"<td width=5%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=30%><b>".utf8_encode($rows[$i][4])."</b></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.=	"<td align=left width=30%><b>".utf8_encode($rows[$i][7])."</b></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][8]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][9]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	
	}
	
	public function indexingresectorAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "INGRESOS X SECTOR";
		
		$cn   	= new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		$evt[] = array('#contentBox',"tabs","");
		
		//MUESTRA SECTOR
				$combostore1 ='Rentas.Rpt_Rentas_General';
				$arraydatos[]=array('@BUSC',8);
				$arraydatos[]=array('@SECTOR','');
				$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos);
				
				$cb_sector='<option value="">[Seleccione]</option>';
				for ($i=0;$i<count($rows1);$i++){
					$cb_sector.='<option value="'.$rows1[$i][0].'" >'.utf8_encode($rows1[$i][1]).'</option>';
				}
		
				$this->view->cb_sector=$cb_sector;
		//COMBO A?O
				$bus_anno="";
				for($i=2014;$i<=date('Y');$i++){
				$bus_anno.="<option value='".$i."'";
					if($i==date('Y'))
					{
						$bus_anno.=" selected ";
					}
				$bus_anno.=">".$i."</option>";
				}
				$this->view->cb_anno=$bus_anno;
				
		//MUESTRA FECHAS
		$evt[] = array('#txtfini',"datepicker","");
		$evt[] = array('#txtffin',"datepicker","");
		
		$fecha1 = "01/01/".date("Y");
		$fecha2 = $ar->UltimoDia(date("n"),date("Y"))."/".date("m")."/".date("Y");
		
		$val[] = array('#txtfini',$fecha1,'val');
		
		$val[] = array('#txtffin',$fecha2,'val');
		
		
		
		$fn->PintarEvento($evt);
		
		$fn->PintarValor($val);
	
	}
	
	
	public function reporte06Action()
	{
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$data_anio  = $data_query[0];
		$f_ini   	= $data_query[1];
		$f_fin   	= $data_query[2];
		$sector		= $data_query[3];
	
	
		$parametros[] = array('@BUSC','6');     
		$parametros[] = array('@ANNO',$data_anio);
		$parametros[] = array('@FECH_INI',$f_ini);
		$parametros[] = array('@FECH_FIN',$f_fin);
		$parametros[] = array('@SECTOR',$sector);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
		
			$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="850PX">
					<tr>';
			$parte2="<th colspan=10><center><b> REPORTE DE INGRESOS (".$f_ini." - ".$f_fin.")</b></center></th>";
			$parte3='</tr>
					<tr>
						<th rowspan="2" width="20%"><center><b>SECTOR</b></center></th>
						<th colspan="2" width="20%"><center><b>BV</b></center></th>
						<th colspan="2" width="20%"><center><b>RR</b></center></th>
						<th colspan="2" width="20%"><center><b>PJ</b></center></th>
						<th colspan="2" width="20%"><center><b>SS</b></center></th>
						
						<th rowspan="2" width="10%"><center><b>TOT. RECAUDADO</b></center></th>
					</tr>
					<tr>
						
						<th width="20%"><center><b>IMP_BV</b></center></th>
						<th width="10%"><center><b>MORA_BV</b></center></th>
						<th width="15%"><center><b>IMP_RR</b></center></th>
						<th width="10%"><center><b>MORA_RR</b></center></th>
						<th width="15%"><center><b>IMP_PJ</b></center></th>
						<th width="10%"><center><b>MORA_PJ</b></center></th>
						<th width="15%"><center><b>IMP_SS</b></center></th>
						<th width="10%"><center><b>MORA_SS</b></center></th>
						
					</tr>
					
					';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td align=left width=10%><b>".utf8_encode($rows[$i][0])."</b></td>";
				$cad1.=	"<td align=left width=20%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][2]."</b></center></td>";
				$cad1.=	"<td width=5%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=30%><b>".utf8_encode($rows[$i][4])."</b></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.=	"<td align=left width=30%><b>".utf8_encode($rows[$i][7])."</b></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][8]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][9]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	
	}
	
	
	public function indexsaldoxsectorAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "SALDOS X SECTOR";
		
		$cn   	= new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		$evt[] = array('#contentBox',"tabs","");
		
		//MUESTRA SECTOR
				$combostore1 ='Rentas.Rpt_Rentas_General';
				$arraydatos[]=array('@BUSC',8);
				$arraydatos[]=array('@SECTOR','');
				$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos);
				
				$cb_sector='<option value="">[Seleccione]</option>';
				for ($i=0;$i<count($rows1);$i++){
					$cb_sector.='<option value="'.$rows1[$i][0].'" >'.utf8_encode($rows1[$i][1]).'</option>';
				}
		
				$this->view->cb_sector=$cb_sector;
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
				
				$fn->PintarEvento($evt);
				
	}
	
	
	public function reporte07Action()
	{
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$data_anio  = $data_query[0];
		$sector		= $data_query[1];
		$concepto	= $data_query[2];
	
		$parametros[] = array('@BUSC','7');     
		$parametros[] = array('@ANNO',$data_anio);
		$parametros[] = array('@SECTOR',$sector);
		$parametros[] = array('@TIPO_REC',$concepto);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
		
			$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="850PX">
					<tr>';
			$parte2="<th colspan=10><center><b> REPORTE DE SALDOS (".$data_anio.")</b></center></th>";
			$parte3='</tr>
					<tr>
						<th rowspan="2" width="20%"><center><b>SECTOR</b></center></th>
						<th colspan="4" width="20%"><center><b>CONCEPTO ARBITRIOS</b></center></th>
						<th rowspan="2" width="10%"><center><b>TOT. DEUDA</b></center></th>
					</tr>
					<tr>
						
						<th width="20%"><center><b>IMP_BV</b></center></th>
						<th width="20%"><center><b>IMP_RR</b></center></th>
						<th width="20%"><center><b>IMP_PJ</b></center></th>
						<th width="20%"><center><b>IMP_SS</b></center></th>
						
					</tr>
					
					';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td align=left width=10%><b>".utf8_encode($rows[$i][0])."</b></td>";
				$cad1.=	"<td width=20%><center><b>".$rows[$i][1]."</b></center></td>";
				$cad1.=	"<td width=20%><center><b>".$rows[$i][2]."</b></center></td>";
				$cad1.=	"<td width=20%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td width=20%><center><b>".$rows[$i][4]."</b></center></td>";
				$cad1.=	"<td width=20%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	
	}
	
	----------------------------------------------------------- //casimiro
	
	public function exportaAction() 
	{
	
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		
		$suministro = $_REQUEST['suministro'];
		$ubicacion = $_REQUEST['ubicacion'];
		$cencos = $_REQUEST['cencos'];		
		$area = $_REQUEST['area'];
		
		if(!empty($_REQUEST['req_fech1']))
			$req_fech1 = $_REQUEST['req_fech1'];
		else
			$req_fech1 = "01/01/".date("Y");
			
		if(!empty($_REQUEST['req_fech2']))	
			$req_fech2 = $_REQUEST['req_fech2'];
		else
			$req_fech2 = "31/12/".date("Y");
			
		
		$arraydatos[] = array('@busc',8);
		$arraydatos[]=array("@gastos_desc", $suministro);
		$arraydatos[]=array("@ubicacion", $ubicacion);
		$arraydatos[]=array("@cencos_num", $cencos);
		$arraydatos[]=array("@area", $area);
		$arraydatos[] = array('@fecha_ini',$req_fech1);
		$arraydatos[] = array('@fecha_fin',$req_fech2);
		
		$rows = $cn->ejec_store_procedura_sql('dbo.[MantEdelnor]', $arraydatos);
		
		if(count($rows)){
			$strHtml = "<table border='1'>";	

			$strHtml .= "<tr width='250px'>";
				$strHtml .= "<td colspan='9' align=center style='background-color:white ; color:black; text-decoration: underline;  font-size:150%'>GASTOS DE ENERGIA ELECTRICA</td>";			
			$strHtml .= "</tr>";
			
			$strHtml .= "<tr>";
				$strHtml .= "<th colspan='9' ></th>";			
			$strHtml .= "</tr>";
			
			$strHtml .= "<tr>";
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Item</th>";
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Codigo</th>";
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Suministro</th>";
				$strHtml .= "<th width='250' style='background-color:#4F81BD; color:#FFFFFF'>Ubi. Camara</th>";	
				$strHtml .= "<th width='500' style='background-color:#4F81BD; color:#FFFFFF'>Dependencia</th>";	
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>CENCOS</th>";					
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Meta</th>";
				$strHtml .= "<th width='70' style='background-color:#4F81BD; color:#FFFFFF'>Total</th>";					
				$strHtml .= "<th width='120' align='center' style='background-color:#4F81BD; color:#FFFFFF'>Fecha</th>";			
			$strHtml .= "</tr>";
			
			$i=0;
			$j=0;
			$total=0;
			$puntero=0;
			$new_archi='';
			$archi="";
			
			foreach($rows as $row){
				$i++;
				$j++;
				if($i%2==0)
					$color = "#EBF1AE";
				else
					$color = "#F8FBEF";
					
				if($archi!=$row[3])
				{	
					if ($puntero>0)
					{	
						unset($array);
						$array[] = array('@busc',9);
						$array[]=array("@nom_area", utf8_encode($archi));
						$fila = $cn->ejec_store_procedura_sql('dbo.[MantEdelnor]', $array);
						
						$strHtml .= "<tr align='center'>";
							$strHtml .= "<td colspan='7'  style='background-color:white; color:black'>".'Monto :'.' '."</th>";
							$strHtml .= "<td  align='right' style='background-color:white; color:black'>".$fila[0][0]."</th>";
						$strHtml .= "</tr>";
					}
				
					$archi=$row[3];
					$strHtml .= "<tr align='center'>";
						$strHtml .= "<th colspan='9' style='background-color:#4F81BD; color:#FFFFFF'>".'Area '.' '.$row[3]."</th>";
					$strHtml .= "</tr>";
					
					$new_archi=$archi;
				}
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='background-color:".$color."'>".$j."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[0]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[1]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[2]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[3]."</td>";				
					//$strHtml .= "<td style='mso-number-format:'#0.00';'".$color."'>".$row[4]."</td>";		
					$strHtml .= "<td style='background-color:".$color."'>".$row[5]."</td>";	
					$strHtml .= "<td align='right' style='background-color:".$color."'>".$row[6]."</td>";		
					$strHtml .= "<td align='center'style='background-color:".$color."'>".$row[7]."</td>";						
				$strHtml .= "</tr>";
				
				$puntero++;
				$registros++;	
				
			}
			
			unset($array);
			$array[] = array('@busc',9);
			$array[]=array("@nom_area", utf8_encode($new_archi));
			$fila = $cn->ejec_store_procedura_sql('dbo.[MantEdelnor]', $array);
						
			$strHtml .= "<tr align='center'>";
				$strHtml .= "<td colspan='7' style='background-color:white; color:black'>".'Monto :'.' '."</th>";
				$strHtml .= "<td align='right' style='background-color:white; color:black'>".$fila[0][0]."</th>";
						
			$strHtml .= "</tr>";
			
			$strHtml .= "</table>";
		}
		
		$this->view->data = $strHtml;
	}
	
}


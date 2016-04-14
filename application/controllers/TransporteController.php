<?php

class TransporteController extends Zend_Controller_Action
{
	public function init()
	{
		/* Initialize action controller here */
	}
	public function indexAction()
	{
		$idvehiculo = $this->_request->getParam('idvehiculo','');
		
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore1  = 'Calculo.sp_ListaCombo';
		$parametros1[] = array('@busc','9');
    	$colores = $cn->ejec_store_procedura_sql($nombrestore1, $parametros1);

		$arColores = $ar->RegistrosCombo($colores,0,1);
		$val[] = array('#txtcolor',$fn->ContenidoCombo($arColores,'[ Seleccionar ]',''),'html');
		
    	$nombrestore2  = 'Calculo.sp_ListaCombo';
		$parametros2[] = array('@busc','10');
    	$empresasoat = $cn->ejec_store_procedura_sql($nombrestore2, $parametros2);

		$arEmpresasoat = $ar->RegistrosCombo($empresasoat,0,1);
		$val[] = array('#txtempresa',$fn->ContenidoCombo($arEmpresasoat,'[ Seleccionar ]',''),'html');
		
    	$nombrestore  = 'Transporte.sp_buscador_vehiculos';
		$parametros[] = array('@msquery','2');
		$parametros[] = array('@paramt1',$idvehiculo);
		
		if ($idvehiculo<>''){
		
			$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
			
			$val[] = array('#txtcodigo',$rows[0][0],'val');
			$val[] = array('#txtinscripcion',$rows[0][10],'val');
			$val[] = array('#txtvencimiento',$rows[0][18],'val');
			$val[] = array('#txtsticker',$rows[0][37],'val');
			
			$val[] = array('#txtplaca',$rows[0][4],'val');
			$val[] = array('#txtclase',$rows[0][7],'val');
			$val[] = array('#txtpadron',$rows[0][3],'val');
			$val[] = array('#txtmarca',$rows[0][19],'val');
			$val[] = array('#txtanio',$rows[0][20],'val');
			$val[] = array('#txtmodelo',$rows[0][8],'val');
			$val[] = array('#txtcombustible',$rows[0][21],'val');
			$val[] = array('#txtcarroceria',$rows[0][22],'val');
			$val[] = array('#txteje',$rows[0][23],'val');
			$val[] = array('#txtcolor',$rows[0][11],'val');
			$val[] = array('#txtmotor',$rows[0][5],'val');
			$val[] = array('#txtcilindros',$rows[0][24],'val');
			$val[] = array('#txtserie',$rows[0][6],'val');
			$val[] = array('#txtruedas',$rows[0][25],'val');
			$val[] = array('#txtpasajeros',$rows[0][27],'val');
			$val[] = array('#txtasientos',$rows[0][26],'val');
			$val[] = array('#txtpesoseco',$rows[0][28],'val');
			$val[] = array('#txtpesobruto',$rows[0][29],'val');
			$val[] = array('#txtlongitud',$rows[0][30],'val');
			$val[] = array('#txtaltura',$rows[0][31],'val');
			$val[] = array('#txtancho',$rows[0][32],'val');
			$val[] = array('#txtcargautil',$rows[0][33],'val');
			
			$val[] = array('#txtempresa',$rows[0][12],'val');
			$val[] = array('#txtsoat',$rows[0][13],'val');
			$val[] = array('#txtdesde',$rows[0][14],'val');
			$val[] = array('#txthasta',$rows[0][15],'val');
			
			$val[] = array('#txtpcodigox',$rows[0][9],'val');
			$val[] = array('#txtecodigo',$rows[0][2],'val');
			
			$val[] = array('#txtpnombrex',$rows[0][38],'val');
			$val[] = array('#txtenombre',$rows[0][39],'val');
			
		}
		
		//$evt[] = array('#btngrabar',"click","goToFormulario('frmVehiculo');");
		$evt[] = array('#btncierra',"click","closePopup('#popvehiculo');");
		$evt[] = array('#btngrabar',"click","guardarvehiculo();");
		$evt[] = array('#txtinscripcion',"datepicker","");
		$evt[] = array('#txtvencimiento',"datepicker","");
		$evt[] = array('#txtdesde',"datepicker","");
		$evt[] = array('#txthasta',"datepicker","");
		//$evt[] = array('#btnpbuscar',"click","$('#txtaction').val('1'); showPopup('mantpers/buscar','#popBusPersSol','900','400','Buscador de Personas');");// modificacion casimiro
		//$evt[] = array('#btnebuscar',"click","$('#txtaction').val('2'); showPopup('mantpers/buscar','#popBusPersSol','900','400','Buscador de Personas');");// modificacion casimiro
		
		$mask[] = array("txtpesoseco");
		$mask[] = array("txtpesobruto");
		$mask[] = array("txtlongitud");
		$mask[] = array("txtaltura");
		$mask[] = array("txtancho");
		$mask[] = array("txtcargautil");
		
		$fn->CampoDinero($mask);
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	}
	
	public function buscadorvehiculosAction()
	{
		$fn = new Libreria_Pintar();

		$evt[] = array('#btnSearchVehiculo',"click","buscarVehiculos();");
		$evt[] = array('#btnNewVehiculo',"click","showPopup('transporte/index?idvehiculo=','#popvehiculo','800','600','Nuevo Vehiculo');"); // ventana nuevo contribuyente
		
		$fn->PintarEvento($evt);
	}
	
	public function buscarvehiculosAction()
	{
		$cmbTipos = $this->_request->getParam('cmbTipos','');
		$txtDatos = $this->_request->getParam('txtDatos','');
		
		$cn = new Model_DbDatos_Datos();
    	
    	$nombrestore  = 'Transporte.sp_buscador_vehiculos';
		$parametros[] = array('@msquery',$cmbTipos);
		$parametros[] = array('@paramt1',$txtDatos);
		
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
        
		$jsonData = array('rows'=>array());
		if(count($rows)){
			foreach($rows AS $row){
				$entry = array(
						'idx'=>$row[0],				  
						'idvehiculo'=>utf8_encode($row[1]),
						'idtransporte'=>utf8_encode($row[2]),
						'padron'=>utf8_encode($row[3]),
						'placa'=>utf8_encode($row[4]),
						'propietario'=>utf8_encode($row[5]),
						'empresa'=>utf8_encode($row[6])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
	}

	public function guardarvehiculoAction()
	{
		$idvehiculo = $this->_request->getPost('txtcodigo');
		$msquery 	= ($idvehiculo<>'')? '2' : '1';
		
        $nombrestore='Transporte.sp_Vehiculos';
		
        $arraydatos[] = array("@msquery", $msquery);
        $arraydatos[] = array("@idvehiculo",$idvehiculo);
		$arraydatos[] = array("@fecha_inscrip",$this->_request->getPost('txtinscripcion'));
		$arraydatos[] = array("@vencimiento",$this->_request->getPost('txtvencimiento'));
		$arraydatos[] = array("@vsticke",$this->_request->getPost('txtsticker'));
		$arraydatos[] = array("@placa",$this->_request->getPost('txtplaca'));
		$arraydatos[] = array("@clase",$this->_request->getPost('txtclase'));
		$arraydatos[] = array("@padron",$this->_request->getPost('txtpadron'));
		$arraydatos[] = array("@marca",$this->_request->getPost('txtmarca'));
		$arraydatos[] = array("@aniofab",$this->_request->getPost('txtanio'));
		$arraydatos[] = array("@modelo",$this->_request->getPost('txtmodelo'));
		$arraydatos[] = array("@combustible",$this->_request->getPost('txtcombustible'));
		$arraydatos[] = array("@carroceria",$this->_request->getPost('txtcarroceria'));
		$arraydatos[] = array("@ejes",$this->_request->getPost('txteje'));
		$arraydatos[] = array("@id_color",$this->_request->getPost('txtcolor'));
		$arraydatos[] = array("@motor",$this->_request->getPost('txtmotor'));
		$arraydatos[] = array("@cilindro",$this->_request->getPost('txtcilindros'));
		$arraydatos[] = array("@serie",$this->_request->getPost('txtserie'));
		$arraydatos[] = array("@ruedas",$this->_request->getPost('txtruedas'));
		$arraydatos[] = array("@pasajeros",$this->_request->getPost('txtpasajeros'));
		$arraydatos[] = array("@asientos",$this->_request->getPost('txtasientos'));
		$arraydatos[] = array("@pesoseco",$this->_request->getPost('txtpesoseco'));
		$arraydatos[] = array("@pesobruto",$this->_request->getPost('txtpesobruto'));
		$arraydatos[] = array("@longitud",$this->_request->getPost('txtlongitud'));
		$arraydatos[] = array("@altura",$this->_request->getPost('txtaltura'));
		$arraydatos[] = array("@ancho",$this->_request->getPost('txtancho'));
		$arraydatos[] = array("@carga",$this->_request->getPost('txtcargautil'));
		
		$arraydatos[] = array("@empresa_soat",$this->_request->getPost('txtempresa'));
		$arraydatos[] = array("@soat",$this->_request->getPost('txtsoat'));
		$arraydatos[] = array("@fecha_inicio",$this->_request->getPost('txtdesde'));
		$arraydatos[] = array("@fecha_final",$this->_request->getPost('txthasta'));
		
		$arraydatos[] = array("@propietario",$this->_request->getPost('txtpcodigox'));
		$arraydatos[] = array("@idtransporte",$this->_request->getPost('txtecodigo'));

        $cn = new Model_DbDatos_Datos();
        @$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
        if($this->getRequest()->isXmlHttpRequest()){
    		echo "Se genero correctamente";
    	}
	}
	
	public function buscadorempresasAction()
	{
		$fn = new Libreria_Pintar();

		$evt[] = array('#btnSearchEmpresa',"click","buscarVehiculos();");
		$evt[] = array('#btnNewEmpresa',"click","showPopup('transporte/mantenimientoempresas?idtransporte=0','#popempresa','800','620','Nueva Empresa');"); // ventana nuevo contribuyente
		$evt[] = array('#btnPrintEmpresa',"click","showPopupReport('tipo=pdf&nombrereporte=rptempresas&param=','pouprpttransporte',700,600,'Transporte');");
		$evt[] = array('#btnPrintConductores',"click","showPopupReport('tipo=pdf&nombrereporte=rpt_conductores_x_empresa&param=','pouprpttransporte',700,600,'Transporte');");
		$evt[] = array('#btnPrintVehiculos',"click","showPopupReport('tipo=pdf&nombrereporte=rpt_vehiculos_x_empresa&param=','pouprpttransporte',700,600,'Transporte');");
	
		$fn->PintarEvento($evt);
	}
	
	public function mantenimientoempresasAction()
	{

		$idtransporte = $this->_request->getParam('idtransporte','');
		
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore1  = 'Calculo.sp_ListaCombo';
		$parametros1[] = array('@busc','11');
    	$tipoEmpresa = $cn->ejec_store_procedura_sql($nombrestore1, $parametros1);

		$arTipoEmpresa = $ar->RegistrosCombo($tipoEmpresa,0,1);
		$val[] = array('#txttipo',$fn->ContenidoCombo($arTipoEmpresa,'[ Seleccionar ]',''),'html');

    	$nombrestore2  = 'Calculo.sp_ListaCombo';
		$parametros2[] = array('@busc','12');
    	$txtespec = $cn->ejec_store_procedura_sql($nombrestore2, $parametros2);

		$artxtespec = $ar->RegistrosCombo($txtespec,0,1);
		$val[] = array('#txtespec',$fn->ContenidoCombo($artxtespec,'[ Seleccionar ]',''),'html');
		
    	$nombrestore3  = 'Calculo.sp_ListaCombo';
		$parametros3[] = array('@busc','13');
    	$txtsecvial = $cn->ejec_store_procedura_sql($nombrestore3, $parametros3);

		$artxtsecvial = $ar->RegistrosCombo($txtsecvial,0,1);
		$val[] = array('#txtsecvial',$fn->ContenidoCombo($artxtsecvial,'[ Seleccionar ]',''),'html');
		
    	$nombrestore4  = 'Calculo.sp_ListaCombo';
		$parametros4[] = array('@busc','14');
    	$txtcalzada = $cn->ejec_store_procedura_sql($nombrestore4, $parametros4);

		$artxtcalzada = $ar->RegistrosCombo($txtcalzada,0,1);
		$val[] = array('#txtcalzada',$fn->ContenidoCombo($artxtcalzada,'[ Seleccionar ]',''),'html');
		
    	$nombrestore5  = 'Calculo.sp_ListaCombo';
		$parametros5[] = array('@busc','15');
    	$txttipovia = $cn->ejec_store_procedura_sql($nombrestore5, $parametros5);

		$artxttipovia = $ar->RegistrosCombo($txttipovia,0,1);
		$val[] = array('#txttipovia',$fn->ContenidoCombo($artxttipovia,'[ Seleccionar ]',''),'html');

		$evt[] = array('#txtfecresol',"datepicker","");
		$evt[] = array('#txtfecnot',"datepicker","");
		$evt[] = array('#txtvalidohasta',"datepicker","");
		$evt[] = array('#txtfecingreso',"datepicker","");
		$evt[] = array('#txtfecingresoarea',"datepicker","");
		$evt[] = array('#txtanioexp',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtcapacvehi',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtflota',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#btncierra',"click","closePopup('#popempresa');");
		$evt[] = array('#btngrabar',"click","guardarempresa();");
		$evt[] = array('#btnconductores',"click","openconductores();");
		$evt[] = array('#btnbuscar',"click","showPopup('mantpers/buscar','#popBusPersSol','900','400','Buscador de Personas');");
		
		$mask[] = array("txtarea");
		$mask[] = array("txtanchovia");
		$mask[] = array("txtlongitudvia");
		
		
		
    	$nombrestore  = 'Transporte.Transporte_s';
		$parametros[] = array('@msquery','6');
		$parametros[] = array('@paramt1',$idtransporte);
		
		
		if ($idtransporte<>'0'){
		
			$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
			
			$val[] = array('#txtid',$rows[0][0],'val');
			$val[] = array('#txtsolicitud',$rows[0][1],'val');
			$val[] = array('#txtanio',$rows[0][2],'val');
			$val[] = array('#txttipo',$rows[0][5],'val');
			$val[] = array('#txtcodigo',$rows[0][4],'val');
			$val[] = array('#txtficregis',$rows[0][6],'val');
			$val[] = array('#txtexpediente',$rows[0][10],'val');
			$val[] = array('#txtanioexp',$rows[0][11],'val');
			$val[] = array('#txtfecingreso',$rows[0][12],'val');
			$val[] = array('#txtfecingresoarea',$rows[0][13],'val');
			$val[] = array('#txtarea',$rows[0][15],'val');
			$val[] = array('#txtespec',$rows[0][16],'val');
			$val[] = array('#txtnombre',$rows[0][31],'val');
			$val[] = array('#txtruc',$rows[0][32],'val');
			$val[] = array('#txtinterseccion',$rows[0][17],'val');
			$val[] = array('#txtcapacvehi',$rows[0][18],'val');
			$val[] = array('#txtflota',$rows[0][19],'val');
			$val[] = array('#txtnomenclatura',$rows[0][20],'val');
			$val[] = array('#txtsecvial',$rows[0][21],'val');
			$val[] = array('#txtanchovia',$rows[0][22],'val');
			$val[] = array('#txtlongitudvia',$rows[0][23],'val');
			$val[] = array('#txtcalzada',$rows[0][25],'val');
			$val[] = array('#txttipovia',$rows[0][26],'val');
			$val[] = array('#txtresolucion',$rows[0][7],'val');
			$val[] = array('#txtfecresol',$rows[0][8],'val');
			$val[] = array('#txtfecnot',$rows[0][9],'val');
			$val[] = array('#txtvalidohasta',$rows[0][14],'val');
			
		}
		
		$fn->CampoDinero($mask);
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
	public function guardarempresaAction()
	{
	
		$msquery = ($this->_request->getPost('txtid')=='0')? '3' : '4' ;
		
        $nombrestore='Transporte.Transporte_1';
		
        $arraydatos[] = array("@msquery", $msquery);
		
        $arraydatos[] = array("@mid_solicitud",$this->_request->getPost('txtsolicitud'));
		$arraydatos[] = array("@manio",$this->_request->getPost('txtanio'));
		//$arraydatos[] = array("@mtipo",$this->_request->getPost('txttipo'));
		$arraydatos[] = array("@mcodigo",$this->_request->getPost('txtcodigo'));
		$arraydatos[] = array("@mid_expediente",$this->_request->getPost('txtexpediente'));
		$arraydatos[] = array("@manio_expe",$this->_request->getPost('txtanioexp'));
		$arraydatos[] = array("@mfecha_ingr_td",$this->_request->getPost('txtfecingreso'));
		$arraydatos[] = array("@mfecha_ingr_tras",$this->_request->getPost('txtfecingresoarea'));
		//$arraydatos[] = array("@mmonto",$this->_request->getPost('txtvencimiento'));
		//$arraydatos[] = array("@mestacion",$this->_request->getPost('txtvencimiento'));
		//$arraydatos[] = array("@mlogin",$this->_request->getPost('txtvencimiento'));
		$arraydatos[] = array("@mhasta",$this->_request->getPost('txtvalidohasta'));
		$arraydatos[] = array("@marea",$this->_request->getPost('txtarea'));
		$arraydatos[] = array("@mid_espect",$this->_request->getPost('txtespec'));
		$arraydatos[] = array("@minterseccion",$this->_request->getPost('txtinterseccion'));
		$arraydatos[] = array("@mcapac",$this->_request->getPost('txtcapacvehi'));
		$arraydatos[] = array("@mreten",$this->_request->getPost('txtflota'));
		$arraydatos[] = array("@mnomencla",$this->_request->getPost('txtnomenclatura'));
		$arraydatos[] = array("@mid_secvial",$this->_request->getPost('txtsecvial'));
		$arraydatos[] = array("@mancho",$this->_request->getPost('txtanchovia'));
		$arraydatos[] = array("@mlongitud",$this->_request->getPost('txtlongitudvia'));
		//$arraydatos[] = array("@mestado",$this->_request->getPost('txtvencimiento'));
		$arraydatos[] = array("@mid_calzada",$this->_request->getPost('txtcalzada'));
		$arraydatos[] = array("@mid_tipvia",$this->_request->getPost('txttipovia'));
		$arraydatos[] = array("@mid_empresa",$this->_request->getPost('txttipo'));
		$arraydatos[] = array("@mficha",$this->_request->getPost('txtficregis'));
		$arraydatos[] = array("@mresolucion",$this->_request->getPost('txtresolucion'));
		$arraydatos[] = array("@mfecha_resol",$this->_request->getPost('txtfecresol'));
		$arraydatos[] = array("@mfecha_noti",$this->_request->getPost('txtfecnot'));

        $cn = new Model_DbDatos_Datos();
        @$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
        if($this->getRequest()->isXmlHttpRequest()){
    		echo "Se genero correctamente";
    	}
	}
	
	public function buscarempresasAction()
	{
		$cmbTipos = $this->_request->getParam('cmbTipos','');
		$txtDatos = $this->_request->getParam('txtDatos','');
		
		$cn = new Model_DbDatos_Datos();
    	
    	$nombrestore  = 'Transporte.Transporte_s';
		$parametros[] = array('@msquery',$cmbTipos);
		$parametros[] = array('@paramt1',$txtDatos);
		
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
        
		$jsonData = array('rows'=>array());
		if(count($rows)){
			foreach($rows AS $row){
				$entry = array(
						'idx'=>$row[0],
						'id_solicitud'=>$row[1],
						'anio'=>utf8_encode($row[2]),
						'codigo'=>utf8_encode($row[3]),
						'nombre'=>utf8_encode($row[4]),
						'num_doc'=>utf8_encode($row[5])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
	}
	public function buscadorconductoresAction()
	{
		
		$fn = new Libreria_Pintar();
		
		$txtid = $this->_request->getParam('txtid','');
		$txtcodigo = $this->_request->getParam('txtcodigo','');
		$txtnombre = $this->_request->getParam('txtnombre','');
		$txtruc = $this->_request->getParam('txtruc','');

		$val[] = array('#txtcid',$txtid,'val');
		$val[] = array('#txtccodigo',$txtcodigo,'val');
		$val[] = array('#txtcnombre',$txtnombre,'val');
		$val[] = array('#txtcruc',$txtruc,'val');
		
		$evt[] = array('#btnccierra',"click","closePopup('#popBuscadorconductores');");
		$evt[] = array('#btncnuevo',"click","showPopup('transporte/mantenimientoconductores?txtidx=&txttranid=".$txtid."','#popConductores','700','210','Nuevo Conductor');");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	}
	
	public function mantenimientoconductoresAction()
	{

		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$idtransporte = $this->_request->getParam('txttranid','');
		$idx = $this->_request->getParam('txtidx','');
		
		$val[] = array('#txttranid',$idtransporte,'val');
		$val[] = array('#txtidx',$idx,'val');

		$nombrestore  = 'Transporte.Conductores_1';
		$parametros[] = array('@msquery','2');
		$parametros[] = array('@idx',$idx);
		
		if ($idx<>''){
		
			$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
			
			$val[] = array('#txttranid',$rows[0][1],'val');
			$val[] = array('#txtconcodigo',$rows[0][2],'val');
			$val[] = array('#txtconnombre',$rows[0][3],'val');
			$val[] = array('#txtlicencia',$rows[0][4],'val');
			$val[] = array('#txtfecdesde',$rows[0][5],'val');
			$val[] = array('#txtfechasta',$rows[0][7],'val');
		}
		
		
		$evt[] = array('#btncongrabar',"click","guardarconductores();");
		$evt[] = array('#btnconcierra',"click","closePopup('#popConductores');");
		$evt[] = array('#btnconbuscar',"click","showPopup('mantpers/buscar','#popBusPersSol','900','400','Buscador de Personas');");
		$evt[] = array('#txtfecdesde',"datepicker","");
		$evt[] = array('#txtfechasta',"datepicker","");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
	}
	
	public function guardarconductoresAction()
	{
	
		$msquery = ($this->_request->getPost('txtidx')=='')? '1' : '4' ;
		
        $nombrestore='Transporte.Conductores_1';

        $arraydatos[] = array("@msquery", $msquery);
		$arraydatos[] = array("@idx",$this->_request->getPost('txtidx'));
        $arraydatos[] = array("@idtransporte",$this->_request->getPost('txttranid'));
		$arraydatos[] = array("@conductor",$this->_request->getPost('txtconcodigo'));
		$arraydatos[] = array("@licencia",strtoupper($this->_request->getPost('txtlicencia')));
		$arraydatos[] = array("@desde",$this->_request->getPost('txtfecdesde'));
		$arraydatos[] = array("@hasta",$this->_request->getPost('txtfechasta'));

        $cn = new Model_DbDatos_Datos();
        @$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
        if($this->getRequest()->isXmlHttpRequest()){
    		echo "Se genero correctamente";
    	}
	}
	public function buscarconductoresAction()
	{
		$txtDatos = $this->_request->getParam('txtcid','');
		
		$cn = new Model_DbDatos_Datos();
    	
    	$nombrestore  = 'Transporte.Conductores_2';
		$parametros[] = array('@msquery','6');
		$parametros[] = array('@paramt1',$txtDatos);
		
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
        
		$jsonData = array('rows'=>array());
		if(count($rows)){
			foreach($rows AS $row){
				$entry = array(
						'idx'=>$row[0],
						'conductor'=>$row[3],
						'licencia'=>utf8_encode($row[4]),
						'desde'=>utf8_encode($row[5]),
						'hasta'=>utf8_encode($row[7])
						);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
	}
}
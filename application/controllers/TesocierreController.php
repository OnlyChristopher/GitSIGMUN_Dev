<?php

class TesocierreController extends Zend_Controller_Action
{

    public function init()
    {
			
    }

    public function indexAction()
    {
    	$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		$getlogin = new Zend_Session_Namespace('login');
		$cajero=$getlogin->caja;
		
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);

    	$nombrestore2 = 'Caja.sp_ControlAperturaCierre';
		$parametros2[]=array("@buscar", '2');
		$parametros2[]=array("@caja", $cajero);
		
		@$datosrow = $cn->ejec_store_procedura_sql($nombrestore2, $parametros2);
		
		$cajero=$datosrow[0][0];
		$estado=$datosrow[0][2];
		$fecha=$datosrow[0][3];
		$nombre_caja=$datosrow[0][5];
		$estadocaja=$datosrow[0][6];
		
		
		$val[] = array('#txtfechas',$fecha,'val');
		$val[] = array('#txtusuario',$nombre_caja,'val');
		$val[] = array('#txtcajero',$cajero,'val');
		$val[] = array('#txtestado',$estadocaja,'val');
		$val[] = array('#txtidestado',$estado,'val');
		
		$evt[] = array('#btnapertura',"click","grabarApertura();");
		$evt[] = array('#btncierre',"click","grabarCierre();");

		if($estado=='1'){
			$fun[]=array("enableButton('#btncierre');");
			$fun[]=array("disableButton('#btnapertura');");
		}else{
			$fun[]=array("enableButton('#btnapertura');");
			$fun[]=array("disableButton('#btncierre');");
		}

		$fn->EjecutarFuncion($fun);
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }
	
    public function aperturacierreAction()
    {
	   	
		$txtfechas = $this->_request->getPost('txtfechas');
		$txtaction = $this->_request->getPost('xaction');
		
    	$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$getlogin = new Zend_Session_Namespace('login');
		$cajero=$getlogin->caja;
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
    	$nombrestore2 = 'Caja.sp_MCierreCaja';
		$parametros2[]=array("@busc", $txtaction);
		$parametros2[]=array("@caja", $cajero);
		$parametros2[]=array("@operador", $username);
		$parametros2[]=array("@fecha", $txtfechas);
		$parametros2[]=array("@estacion", $hostname);

		@$datosrow = $cn->ejec_store_procedura_sql($nombrestore2, $parametros2);
		
    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
		$fechahoy=$fecharow[0][0];

    	$nombrestore3 = 'Caja.sp_MCierreCaja';
		$parametros3[]=array("@busc", '5');
		$parametros3[]=array("@caja", $cajero);
		$parametros3[]=array("@fecha", $fechahoy);
		
		@$datosrow3 = $cn->ejec_store_procedura_sql($nombrestore3, $parametros3);
		echo $datosrow3[0][1];

    }
	
    public function cuadrecajaAction()
    {
    	$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		$getlogin = new Zend_Session_Namespace('login');
		$cajero=$getlogin->caja;
		
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
		$fechahoy=$fecharow[0][0];

    	$nombrestore2 = 'Caja.sp_MCierreCaja';
		$parametros2[]=array("@busc", '5');
		$parametros2[]=array("@caja", $cajero);
		$parametros2[]=array("@fecha", $fechahoy);
		
		@$datosrow = $cn->ejec_store_procedura_sql($nombrestore2, $parametros2);
		
		$apertura=$datosrow[0][3];
		$fechaape=$datosrow[0][1];
		
		$val[] = array('#txtfechas',$fechaape,'val');
		$evt[] = array('#btnapertura',"click","grabarMontos();");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
    public function vermontosAction(){

		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$getlogin = new Zend_Session_Namespace('login');
		$cajero=$getlogin->caja;
		
		$nombrestore = 'Caja.sp_ControlAperturaCierre';
		$parametros[]=array("@buscar", '6');
		$parametros[]=array("@caja", $cajero);
		$datosrow = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$val[] = array('#txtestado01',$datosrow[0][6],'val');
		$val[] = array('#txtestado02',$datosrow[0][7],'val');

		$val[] = array('#txtcajeroefectivo',$datosrow[0][2],'val');
		$val[] = array('#txtcajerocheque',$datosrow[0][3],'val');

		$val[] = array('#txtsistemaefectivo',$datosrow[0][4],'val');
		$val[] = array('#txtsistemacheque',$datosrow[0][5],'val');
		
		$fn->PintarValor($val);
	}
	
    public function grabarmontosAction(){
	
		$getlogin = new Zend_Session_Namespace('login');
		$cajero=$getlogin->caja;
		
		$efectivo = $this->_request->getPost('efectivo');
		$cheque = $this->_request->getPost('cheque');
		$txtfechas = $this->_request->getPost('txtfechas');
		
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$nombrestore = 'Caja.sp_ControlAperturaCierre';
		$parametros[]=array("@buscar", '5');
		$parametros[]=array("@caja", $cajero);
		$parametros[]=array("@cajero_efectivo", $efectivo);
		$parametros[]=array("@cajero_cheque", $cheque);
		//$parametros[]=array("@fecha", $txtfechas);

		$datosrow = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
	}
	
	public function consultaaperturaAction(){
		
		$getlogin = new Zend_Session_Namespace('login');
		$cajero=$getlogin->caja;
		
		$cn = new Model_DbDatos_Datos();
		
		$nombrestore = 'Caja.sp_ControlAperturaCierre';
		$parametros[]=array("@buscar", '1');
		$parametros[]=array("@caja", $cajero);
		
		$estadorow = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		echo $estadorow[0][0];
	}
	
    public function aperturaAction(){
	   	
    	$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$getlogin = new Zend_Session_Namespace('login');
		$cajero=$getlogin->caja;

        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
    	$nombrestore2 = 'Caja.sp_ControlAperturaCierre';
		$parametros2[]=array("@buscar", '3');
		$parametros2[]=array("@caja", $cajero);

		@$datosrow = $cn->ejec_store_procedura_sql($nombrestore2, $parametros2);

    }

    public function cierreAction(){
	   	
    	$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$getlogin = new Zend_Session_Namespace('login');
		$cajero=$getlogin->caja;

        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
    	$nombrestore2 = 'Caja.sp_ControlAperturaCierre';
		$parametros2[]=array("@buscar", '4');
		$parametros2[]=array("@caja", $cajero);

		@$datosrow = $cn->ejec_store_procedura_sql($nombrestore2, $parametros2);
		
		echo $datosrow[0][0];

    }	
}
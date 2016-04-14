<?php

class LicendecjuradaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;

		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();


		//Para los tabs
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#contentTabsLeft',"tabs","");
		$evt[] = array('#txtFechaPre',"datepicker","");
        $evt[] = array('#txtFchViDel',"datepicker","");
        $evt[] = array('#txtFchViAl',"datepicker","");
		$evt[] = array('#txtArancel',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtNro',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtPhone',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtExp',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtNroLi',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtNroDocRe',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtNroDoc',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtNroRuc',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtNroTe',"keypress","return validaTeclas(event,'number');");
        //$evt[] = array('#txtInEs',"keypress","return validaTeclas(event,'number');");
        //$evt[] = array('#txtLtEs',"keypress","return validaTeclas(event,'number');");
        //$evt[] = array('#txtMzEs',"keypress","return validaTeclas(event,'number');");
       // $evt[] = array('#txtMod',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtEst',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtnh',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtnm',"keypress","return validaTeclas(event,'number');");

        //$mask[] = array("txtAreaL");
        //$mask[] = array("txtAreaAl");
        $mask[] = array("txtAreaTo");
        $evt[] = array('#cmbTipAnun',"change","getTipoAnuncio()");
        //$evt[] = array('#chkOtro',"checked","getTipoAutorizacion()");
        $mask[] = array("txtlargo");
        $mask[] = array("txtancho");
        $mask[] = array("txtalto");
        $mask[] = array("txtarea");
        //$evt[] = array('#txtNroEs',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtDptoEs',"keypress","return validaTeclas(event,'number');");
        $evt[] = array('#txtcaras',"keypress","return validaTeclas(event,'number');");


		$evt[] = array('#btnBusPerSol',"click","showPopup('mantpers/buscar','#popBusPersSol','900','400','Buscador de Personas');");
		$evt[] = array('#btnPredio',"click","showPopup('mantpred/buscar','#popBusPre','700','400','Buscador de Predios');");
		$evt[] = array('#btnAddDetPago',"click","maxRowPagos();");

		$evt[] = array('#btnSaveSol',"click","goToFormulario('frmlicendecjurada');");
		$evt[] = array('#btnSalirSol',"click","closePopup('#poplicendecjurada');");


		//Para los tabs
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#contentTabsLeft',"tabs","");



		unset($parametros);
		$parametros[] = array('@msquery',1);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipLicencia',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoLice)),'html');

		unset($parametros);
		$parametros[] = array('@msquery',2);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipPredio2',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoDoc)),'html');

		unset($parametros);
		$parametros[] = array('@msquery',3);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipInscrip',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoInscrip)),'html');

		unset($parametros);
		$parametros[] = array('@msquery',4);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipEmpresa',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoEmpr)),'html');

		unset($parametros);
		$parametros[] = array('@msquery',5);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipSector',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoSector)),'html');


		unset($parametros);
		$parametros[] = array('@msquery',2);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbTipPredio6',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoDoc)),'html');


		unset($parametros);
		$parametros[] = array('@msquery',6);
		$documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbzonificacion',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoZonif)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',7);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipAnun',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipo)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',8);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipIlum',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipo)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',9);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipAuto',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipo)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',11);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipRegimen',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idRegimen)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',13);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipUbi',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoUbica)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',14);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipU',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoU)),'html');

        unset($parametros);
        $parametros[] = array('@msquery',15);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbTipUr',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($idTipoUr)),'html');

        /*

        $letras  = array("a","b","c","d","e","f","g","h","i","j","k","l","ll","m","n","ñ","o","p","q","r","s","t","u","v","w","x","y","z");

        $anuncio = '<table><tr><td valign="top"><table>';
        $id = 0;
        foreach($documentos as $documentos){
            $id++;

            $anuncio .= '<tr>';
            $anuncio .= '<td><table style="background:#f2f2f2;" ><tr style="font-size:9px"><td height="50">'.utf8_decode($letras[$id -1 ]).'.</td><td width="110">'.$documentos[1].'</td><td style="padding-right:5px;" bgcolor="#999"><input type="checkbox" id="TipoAnun" name="TipoAnun" value="'.$documentos[0].'" ></td></tr></table></td>';
            $anuncio .= '</tr>';

            if ($id % 5 == 0)
            $anuncio .= '</table></td><td valign="top"><table>';

        }
        $anuncio .= '</table></td></tr></table>';

        $val[] = array('#anuncio',$anuncio,'html');
*/

		$idSolLice = $this->_request->getParam('idSolLice','');
		$this->view->idSolLice=$idSolLice;


        $cod_sol = $this->_request->getParam('cod_sol','');
        $this->view->cod_sol=$cod_sol;

		$readonly = $this->_request->getParam('readonly','');
		$this->view->readonly=$readonly;

		$accion = $this->_request->getParam('accion','');
		$this->view->accion=$accion;

		unset($parametros);
		if(strlen($idSolLice)>0)
		{
			$parametros[] = array('@msql',3);
			$parametros[] = array('@idSolLice',$idSolLice);
            $parametros[] = array('@cod_sol',$cod_sol);
			$rowLicencia = $cn->ejec_store_procedura_sql('wbSpPredio', $parametros);

            $idSol = $rowLicencia[0][1];
            $codpro = $rowLicencia[0][5];
			$nompro = $rowLicencia[0][6];

            $tip_lice = $rowLicencia[0][2];
            $nro_dni= $rowLicencia[0][9];
            $nro_ruc = $rowLicencia[0][10];
            $regimen = $rowLicencia[0][11];
            $direpro = $rowLicencia[0][21];
            $distrito = $rowLicencia[0][14];
            $deno = $rowLicencia[0][19];
            $nomest = $rowLicencia[0][17];
            $direper = $rowLicencia[0][15];
			$fecha = $rowLicencia[0][46];
            $codpre = $rowLicencia[0][7];
            $numEs = $rowLicencia[0][22];
            $depEs = $rowLicencia[0][23];
            $StEs = $rowLicencia[0][24];
            $MaEs = $rowLicencia[0][25];
            $LoEs = $rowLicencia[0][26];
            $puesto = $rowLicencia[0][27];
            $nro_puesto = $rowLicencia[0][28];
            //$areal = $rowLicencia[0][20];
            //$areaal = $rowLicencia[0][21];
            $areatotal = $rowLicencia[0][29];
            $nroh = $rowLicencia[0][30];
            $nrom = $rowLicencia[0][31];
            $nroes = $rowLicencia[0][32];
            $tiposector = $rowLicencia[0][33];
            $tipoinscrip = $rowLicencia[0][34];
            $tipoempre = $rowLicencia[0][35];
            $zonifica = $rowLicencia[0][36];
            $idpredio = $rowLicencia[0][16];
            $observa = $rowLicencia[0][37];
            $telefono = $rowLicencia[0][12];
            $correo = $rowLicencia[0][13];
            $nombreR = $rowLicencia[0][38];
            $tipodocR = $rowLicencia[0][39];
            $nrodocR = $rowLicencia[0][40];
            $telefonoR = $rowLicencia[0][41];
            $partidaR = $rowLicencia[0][42];
            $correoR = $rowLicencia[0][43];
            $tipoU = $rowLicencia[0][18];
            $tipoV = $rowLicencia[0][20];


		}

        $val[] = array('#txtNroLi',$idSol,'val');
        $val[] = array('#txtCodPer',$codpro,'val');
        $val[] = array('#txtNomPer',$nompro,'val');
        $val[] = array('#txtNroDoc',$nro_dni,'val');
        $val[] = array('#txtNroRuc',$nro_ruc,'val');
        $val[] = array('#cmbTipLicencia',$tip_lice,'val');

        unset($parametros);
        $parametros[] = array('@msquery',16);
        $parametros[] = array('@idtipo',$tip_lice);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbCptoLicencia',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($tip_lice)),'html');
        //var_dump($tip_lice);
        $val[] = array('#cmbTipRegimen',$regimen,'val');

        $val[] = array('#txtNroTe',$telefono,'val');
        $val[] = array('#txtCorreo',$correo,'val');
        $val[] = array('#txtDis',$distrito,'val');
        $val[] = array('#txtNomEs',$nomest,'val');
        $val[] = array('#txtUbiEs',$direpro,'val');
        $val[] = array('#cmbTipU',$tipoU,'val');
        $val[] = array('#txtUrb',$deno,'val');
        $val[] = array('#cmbTipUr',$tipoV,'val');
		$val[] = array('#txtDirPer',$direper,'val');
		$val[] = array('#txtFechaPre',$fecha,'val');
        $val[] = array('#txtCodPre',$codpre,'val');
        $val[] = array('#txtpredio',$idpredio,'val');
        $val[] = array('#txtNroEs',$numEs,'val');
        $val[] = array('#txtDptoEs',$depEs,'val');
        $val[] = array('#txtInEs',$StEs,'val');
        $val[] = array('#txtMzEs',$MaEs,'val');
        $val[] = array('#txtLtEs',$LoEs,'val');
        $val[] = array('#cmbTipUbi',$puesto,'val');
        $val[] = array('#txtMod',$nro_puesto,'val');
        //$val[] = array('#txtAreaL',$areal,'val');
        //$val[] = array('#txtAreaAl',$areaal,'val');
        $val[] = array('#txtAreaTo',$areatotal,'val');
        $val[] = array('#txtnh',$nroh,'val');
        $val[] = array('#txtnm',$nrom,'val');
        $val[] = array('#txtEst',$nroes,'val');
        $val[] = array('#cmbTipSector',$tiposector,'val');
        $val[] = array('#cmbTipInscrip',$tipoinscrip,'val');
        $val[] = array('#cmbTipEmpresa',$tipoempre,'val');
        $val[] = array('#cmbzonificacion',$zonifica,'val');
        $val[] = array('#txtobs',$observa,'val');
        $val[] = array('#txtApeNom',$nombreR,'val');
        $val[] = array('#cmbTipPredio2',$tipodocR,'val');
        $val[] = array('#txtNroDocRe',$nrodocR,'val');
        $val[] = array('#txtPhone',$telefonoR,'val');
        $val[] = array('#txtSUNARP',$partidaR,'val');
        $val[] = array('#txtRemail',$correoR,'val');





		$evt[] = array('#btnCloseSol',"click","closePopup('#poplicendecjurada');");

		$fn->CampoDinero($mask);
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);


  }
    public function conslicenAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();

            $id_tipo = $this->_request->getPost('cmbTipLicencia');

            $combostore1 ='WbSTipoLice';
            $arraydatos1[0] = array("@idtipo",$id_tipo);
            $rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);

            $cb_Tipo='<option value="">[Seleccione]</option>';
            for ($i=0;$i<count($rows1);$i++){
                $cb_Tipo.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][1].'</option>';
            }

            echo $cb_Tipo;
        }
    }
    public function griddocumentosAction(){
           $cn = new Model_DbDatos_Datos();
        $parametros[] = array('@msql',3);
        $rows = $cn->ejec_store_procedura_sql('[wbSpLicencia]', $parametros);

        $jsonData = array();
        if(count($rows))
        {
            foreach($rows AS $row){
                $entry = array(
                    'item'=>trim($row[0]),
                    'descrip'=>trim($row[1])
                );
                $jsonData[] = $entry;
            }
        }
        $this->view->data = json_encode($jsonData);
    }
	public function grabarAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
        $idLicencia = $this->_request->getPost('idSolLice');
        //$idAnuncio = $this->_request->getPost('idSolAnun');
        $valor = $this->_request->getPost('accion');
        $persona = $this->_request->getPost('persona');

        $json = $this->_request->getPost('json');
        $data  = json_decode($json);

        $cn = new Model_DbDatos_Datos();

        if($this->getRequest()->isXmlHttpRequest()){



            $año = $this->_request->getPost('txtFechaPre');

            $parametrosL[] = array('@msql',$this->_request->getPost('accion'));
            $parametrosL[] = array('@año',substr($año,6,4));
            $parametrosL[] = array('@idTipoLice',$this->_request->getPost('cmbTipLicencia'));
            $parametrosL[] = array('@idCpto',$this->_request->getPost('cmbCptoLicencia'));
            if($valor == "2" ){
                $parametrosL[] = array('@idSolLice',$idLicencia);
            }
            $parametrosL[] = array('@cod_sol',$this->_request->getPost('txtCodPer'));
            $parametrosL[] = array('@predio',$this->_request->getPost('rbpredio'));
            $parametrosL[] = array('@observa',$this->_request->getPost('txtobs'));
            $parametrosL[] = array('@fechoring',$this->_request->getPost('txtFechaPre'));

            @$rows = $cn->ejec_store_procedura_sql('wbSpLicencia', $parametrosL);
            echo "Licencia ";
        }

        if ($persona == "N"){

        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();

            $parametroSo[] = array('@msql',$this->_request->getPost('accion'));
            $parametroSo[] = array('@cod_sol',trim($this->_request->getPost('txtCodPer')));
            $parametroSo[] = array('@nombre',trim($this->_request->getPost('txtNomPer')));
            $parametroSo[] = array('@tipo',$this->_request->getPost('persona'));
            $parametroSo[] = array('@nro_dni',$this->_request->getPost('txtNroDoc'));
            $parametroSo[] = array('@nro_ruc',$this->_request->getPost('txtNroRuc'));
            $parametroSo[] = array('@regimen',$this->_request->getPost('cmbTipRegimen'));
            $parametroSo[] = array('@denomi',$this->_request->getPost('txtvt'));
            $parametroSo[] = array('@direccion',trim($this->_request->getPost('txtDirPer')));
            $parametroSo[] = array('@numero',$this->_request->getPost('txtcz'));
            $parametroSo[] = array('@depa',$this->_request->getPost('txtcm'));
            $parametroSo[] = array('@manza',$this->_request->getPost('txti1'));
            $parametroSo[] = array('@lote',$this->_request->getPost('txti2'));
            $parametroSo[] = array('@distrito',$this->_request->getPost('txtDis'));
            $parametroSo[] = array('@provincia',$this->_request->getPost('txti4'));
            $parametroSo[] = array('@dpto',$this->_request->getPost('nestado'));
            $parametroSo[] = array('@telefono',$this->_request->getPost('txtNroTe'));
            $parametroSo[] = array('@celular',$this->_request->getPost('txtNroTe'));
            $parametroSo[] = array('@correo',$this->_request->getPost('txtCorreo'));
            $parametroSo[] = array('@fechoring',$this->_request->getPost('txtFechaPre'));
            if($valor == "2" ){
                $parametroSo[] = array('@idSolLice',$idLicencia);
            }

            @$rows = $cn->ejec_store_procedura_sql('wbSpSolicitante', $parametroSo);

            echo "Solicitante ";
        }
        }else{

            if($this->getRequest()->isXmlHttpRequest()){

                $cn = new Model_DbDatos_Datos();
                $parametroSo[] = array('@msql',$this->_request->getPost('accion'));
                $parametroSo[] = array('@cod_sol',trim($this->_request->getPost('txtCodPer')));
                $parametroSo[] = array('@nombre',trim($this->_request->getPost('txtNomPer')));
                $parametroSo[] = array('@tipo',$this->_request->getPost('persona'));
                $parametroSo[] = array('@nro_dni',$this->_request->getPost('txtNroDoc'));
                $parametroSo[] = array('@nro_ruc',$this->_request->getPost('txtNroRuc'));
                $parametroSo[] = array('@regimen',$this->_request->getPost('cmbTipRegimen'));
                $parametroSo[] = array('@denomi',$this->_request->getPost('txtvt'));
                $parametroSo[] = array('@direccion',trim($this->_request->getPost('txtDirPer')));
                $parametroSo[] = array('@numero',$this->_request->getPost('txtcz'));
                $parametroSo[] = array('@depa',$this->_request->getPost('txtcm'));
                $parametroSo[] = array('@manza',$this->_request->getPost('txti1'));
                $parametroSo[] = array('@lote',$this->_request->getPost('txti2'));
                $parametroSo[] = array('@distrito',$this->_request->getPost('txtDis'));
                $parametroSo[] = array('@provincia',$this->_request->getPost('txti4'));
                $parametroSo[] = array('@dpto',$this->_request->getPost('nestado'));
                $parametroSo[] = array('@telefono',$this->_request->getPost('txtNroTe'));
                $parametroSo[] = array('@celular',$this->_request->getPost('txtNroTe'));
                $parametroSo[] = array('@correo',$this->_request->getPost('txtCorreo'));
                $parametroSo[] = array('@fechoring',$this->_request->getPost('txtFechaPre'));
                if($valor == "2" ){
                    $parametroSo[] = array('@idSolLice',$idLicencia);
                }

                @$rows = $cn->ejec_store_procedura_sql('wbSpSolicitante', $parametroSo);
                echo "Solicitante ";


               if($this->getRequest()->isXmlHttpRequest()){

                $cn = new Model_DbDatos_Datos();

                $parametroRe[] = array('@msql',$this->_request->getPost('accion'));
                $parametroRe[] = array('@cod_sol',trim($this->_request->getPost('txtCodPer')));
                //$parametroRe[] = array('@idSolLice',trim($this->_request->getPost('txtNomPer')));
                //$parametroRe[] = array('@idSolAnun',$this->_request->getPost('persona'));
                $parametroRe[] = array('@nombre',strtoupper($this->_request->getPost('txtApeNom')));
                $parametroRe[] = array('@tipo_doc',$this->_request->getPost('cmbTipPredio2'));
                $parametroRe[] = array('@nro_ruc',$this->_request->getPost('txtNroDocRe'));
                $parametroRe[] = array('@telefono',$this->_request->getPost('txtvt'));
                $parametroRe[] = array('@partida',trim($this->_request->getPost('txtSUNARP')));
                $parametroRe[] = array('@correo',$this->_request->getPost('txtRemail'));
                //$parametroRe[] = array('@operador',$this->_request->getPost('txtcm'));
                //$parametroRe[] = array('@estacion',$this->_request->getPost('txti1'));
                $parametroRe[] = array('@fechoring',$this->_request->getPost('txtFechaPre'));
                if($valor == "2" ){
                    $parametroSo[] = array('@idSolLice',$idLicencia);
                }

                @$rows = $cn->ejec_store_procedura_sql('wbSpRepresentante', $parametroRe);

                echo "Representante ";

                }
            }
        }
    	if($this->getRequest()->isXmlHttpRequest()){

			$cn = new Model_DbDatos_Datos();

		    $parametros[] = array('@msql',$this->_request->getPost('accion'));
			$parametros[] = array('@nombre',$this->_request->getPost('txtNomPer'));
			$parametros[] = array('@nom_esta',strtoupper($this->_request->getPost('txtNomEs')));
            $parametros[] = array('@idTipoU',strtoupper($this->_request->getPost('cmbTipU')));
            $parametros[] = array('@deno_pred',strtoupper($this->_request->getPost('txtUrb')));
            $parametros[] = array('@idTipovia',$this->_request->getPost('cmbTipUr'));
			$parametros[] = array('@dire_pred',strtoupper($this->_request->getPost('txtUbiEs')));
			$parametros[] = array('@num_pred',$this->_request->getPost('txtNroEs'));
			$parametros[] = array('@depa_pred',$this->_request->getPost('txtDptoEs'));
            $parametros[] = array('@stand',$this->_request->getPost('txtInEs'));
            $parametros[] = array('@manza_pred',$this->_request->getPost('txtMzEs'));
			$parametros[] = array('@lote_pred',$this->_request->getPost('txtLtEs'));
			$parametros[] = array('@puesto',$this->_request->getPost('cmbTipUbi'));
            $parametros[] = array('@mercado',$this->_request->getPost('txtMod'));
			//$parametros[] = array('@area_local',$this->_request->getPost('txtAreaL'));
			//$parametros[] = array('@area_alma',$this->_request->getPost('txtAreaAl'));
            $parametros[] = array('@area_total',$this->_request->getPost('txtAreaTo'));
            $parametros[] = array('@nro_h',$this->_request->getPost('txtnh'));
            $parametros[] = array('@nro_m',$this->_request->getPost('txtnm'));
            $parametros[] = array('@nro_est',$this->_request->getPost('txtEst'));
            $parametros[] = array('@idTipoInscrip',$this->_request->getPost('cmbTipInscrip'));
            $parametros[] = array('@idTipoSector',$this->_request->getPost('cmbTipSector'));
            $parametros[] = array('@idTipoEmpr',$this->_request->getPost('cmbTipEmpresa'));
            $parametros[] = array('@idTipoZonif',$this->_request->getPost('cmbzonificacion'));
            $parametros[] = array('@cod_sol',$this->_request->getPost('txtCodPer'));
            $parametros[] = array('@idpupred',$this->_request->getPost('txtpredio'));
             //$parametros[] = array('@estacion',$this->_request->getPost('txtAreaTo'));
            $parametros[] = array('@fechoring',$this->_request->getPost('txtFechaPre'));
            if($valor == "2" ){
                $parametros[] = array('@idSolLice',$idLicencia);
            }
            @$rows = $cn->ejec_store_procedura_sql('wbSpPredio', $parametros);

			echo "Establecimiento  ";


        }

        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();
            $fecha = $this->_request->getPost('txtFechaPre');
            $parametrosGiro = $this->_request->getPost('codigoGiro');

            for($i=1;$i<=count($parametrosGiro);$i++){
                unset($parametrosG);
                $parametrosG[] = array('@msql',1);
                $parametrosG[] = array('@fechoring',$fecha);
                $parametrosG[] = array('@idgiroGi',$parametrosGiro[$i][0]);

               $rows = $cn->ejec_store_procedura_sql('wbSpDGiro', $parametrosG);
            }

             echo "Detalle Giro ";
        }

            }


    public function gprediosAction(){

        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $idLicencia = $this->_request->getPost('idSolLice');
        $valor = $this->_request->getPost('accion');


        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();
            $ar = new Libreria_ArraysFunctions();

            $json = $this->_request->getPost('json');
            $data = json_decode($json);

            $path=	new Zend_Session_Namespace('path');


            // /*********DATOS DOCUMENTOS**************/
            if(!empty($data->Doc)){
                $oldDoc = explode('|',$data->oldDocItems);
                $dataDoc = $data->Doc;

                foreach($dataDoc as $dDoc){
                    $descrip = trim($dDoc->descrip);

                    if(strlen($descrip)>0){
                        unset($parametros);
                        $parametros[]=array("@msquery",1);
                        $parametros[]=array('@descDocAnex',strtoupper($descrip));

                        if($valor == "2" ){
                            $parametros[] = array('@idSolLice',$idLicencia);
                        }

                        $dataFicIndIns = $cn->ejec_store_procedura_sql('wbsDetalleDocuAnex', $parametros, true);
                    }
                }

             }
        }
    }
}

















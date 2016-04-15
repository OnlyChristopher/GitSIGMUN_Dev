<?php

class SemanalController extends Zend_Controller_Action
{

    public function indexAction()
    {
			
    }

    public function exportarrptsaldosAction()
    {

        $cn     = new Model_DbDatos_Datos();
    
        $parametros[] = array('@busc','8');
        
        $rows = $cn->ejec_store_procedura_sql('[Rentas].[Reportes_Diversos]', $parametros);



        $ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
        $cad1='<div align="left"><img src="http://10.0.1.60:8080/img/logo.png" ></div>';
        $parte1='<table border="0" class="hovertable" width="850PX">
                <tr>';
        $parte2="<th style='background-color:#FFFFFF' colspan=85><center><b><h3>REPORTE DE SALDOS  :  ".$rows[0][84]."</h3></b></center></th></tr></table>";
        
        $parte3='<table border="1" class="hovertable" width="850PX">
                <tr>
                <th colspan=21>  </th>
                <th colspan=3>IP 2015 TRIM-I</th>
                <th colspan=3>IP 2015 TRIM-II</th>
                <th colspan=3>IP 2015 TRIM-III</th>
                <th colspan=3>IP 2015 TRIM-IV</th>
                <th >EMI.2015</th>
                <th style="background-color:#C7DCC6" colspan=4>ARB ANTERIOR</th>
                <th colspan=4>ARB 2009</th>
                <th style="background-color:#C7DCC6" colspan=4>ARB 2010</th>
                <th colspan=4>ARB 2011</th>
                <th style="background-color:#C7DCC6" colspan=4>ARB 2012</th>
                <th colspan=4>ARB 2013</th>
                <th style="background-color:#C7DCC6" colspan=3>ARB 2014</th>
                <th colspan=6>ARB 2015 TRIM-I</th>
                <th colspan=6>ARB 2015 TRIM-II</th>
                <th colspan=6>ARB 2015 TRIM-III</th>
                <th colspan=6>ARB 2015 TRIM-IV</th>
                </tr>';

        $parte4='<tr>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF" width="10%"><center><b>Nro.</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="10%"><center><b>Codigo</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="50%"><center><b>Nombre</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="15%"><center><b>Dir_Fisca</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="25%"><center><b>Distrito</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="25%"><center><b>Sector</b></center></th>

                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="10%"><center><b>Tipo_via</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="50%"><center><b>Via</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="15%"><center><b>AAHH</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="25%"><center><b>cuadrante</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="25%"><center><b>BASE_IMPONIBLE</b></center></th>

                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="10%"><center><b>tot_autoavaluo</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="50%"><center><b>imp_anual</b></center></th>
                    <th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="15%"><center><b>imp_trime</b></center></th>

                    <th style="background-color:#ABD6A8" width="50%"><center><b>IP_ANT</b></center></th>

                    <th width="25%"><center><b>IP_2009</b></center></th>

                    <th style="background-color:#ABD6A8" width="50%"><center><b>IP_2010</b></center></th>

                    <th width="25%"><center><b>IP_2011</b></center></th>

                     <th style="background-color:#ABD6A8" width="50%"><center><b>IP_2012</b></center></th>

                    <th width="25%"><center><b>IP_2013</b></center></th>

                     <th style="background-color:#ABD6A8" width="50%"><center><b>IP_2014</b></center></th>

                    <th width="15%"><center><b>insoluto</b></center></th>
                    <th width="25%"><center><b>reajuste</b></center></th>
                    <th width="25%"><center><b>mora</b></center></th>

                    <th width="15%"><center><b>insoluto</b></center></th>
                    <th width="25%"><center><b>reajuste</b></center></th>
                    <th width="25%"><center><b>mora</b></center></th>

                    <th width="15%"><center><b>insoluto</b></center></th>
                    <th width="25%"><center><b>reajuste</b></center></th>
                    <th width="25%"><center><b>mora</b></center></th>

                    <th width="15%"><center><b>insoluto</b></center></th>
                    <th width="25%"><center><b>reajuste</b></center></th>
                    <th width="25%"><center><b>mora</b></center></th>

                    <th width="25%"><center><b>costo</b></center></th>

                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_LP_ANT</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_PJ_ANT</b></center></th>                    
                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_SS_ANT</b></center></th>                                        
                    <th style="background-color:#ABD6A8" width="25%"><center><b>EMISION</b></center></th>

                    <th width="25%"><center><b>ARB_LP_2009</b></center></th>
                    <th width="25%"><center><b>ARB_PJ_2009</b></center></th>
                    <th width="25%"><center><b>ARB_SS_2009</b></center></th>
                    <th width="25%"><center><b>EMISION</b></center></th>

                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_LP_2010</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_PJ_2010</b></center></th>                    
                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_SS_2010</b></center></th>                                        
                    <th style="background-color:#ABD6A8" width="25%"><center><b>EMISION</b></center></th>

                    <th width="25%"><center><b>ARB_LP_2011</b></center></th>
                    <th width="25%"><center><b>ARB_PJ_2011</b></center></th>
                    <th width="25%"><center><b>ARB_SS_2011</b></center></th>
                    <th width="25%"><center><b>EMISION</b></center></th>

                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_LP_2012</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_PJ_2012</b></center></th>                    
                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_SS_2012</b></center></th>                                        
                    <th style="background-color:#ABD6A8" width="25%"><center><b>EMISION</b></center></th>

                    <th width="25%"><center><b>ARB_LP_2013</b></center></th>
                    <th width="25%"><center><b>ARB_PJ_2013</b></center></th>
                    <th width="25%"><center><b>ARB_SS_2013</b></center></th>
                    <th width="25%"><center><b>EMISION</b></center></th>


                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_LP_2014</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_PJ_2014</b></center></th>                    
                    <th style="background-color:#ABD6A8" width="25%"><center><b>ARB_SS_2014</b></center></th>                                        


                    <th width="15%"><center><b>INS_LP</b></center></th>
                    <th width="25%"><center><b>MORA_LP</b></center></th>
                    <th width="25%"><center><b>INS_PJ</b></center></th>
                    <th width="25%"><center><b>MORA_PJ</b></center></th>
                    <th width="25%"><center><b>INS_SS</b></center></th>
                    <th width="25%"><center><b>MORA_SS</b></center></th>

                    <th style="background-color:#ABD6A8" width="15%"><center><b>INS_LP</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>MORA_LP</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>INS_PJ</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>MORA_PJ</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>INS_SS</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>MORA_SS</b></center></th>

                    <th width="15%"><center><b>INS_LP</b></center></th>
                    <th width="25%"><center><b>MORA_LP</b></center></th>
                    <th width="25%"><center><b>INS_PJ</b></center></th>
                    <th width="25%"><center><b>MORA_PJ</b></center></th>
                    <th width="25%"><center><b>INS_SS</b></center></th>
                    <th width="25%"><center><b>MORA_SS</b></center></th>

                    <th style="background-color:#ABD6A8" width="15%"><center><b>INS_LP</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>MORA_LP</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>INS_PJ</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>MORA_PJ</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>INS_SS</b></center></th>
                    <th style="background-color:#ABD6A8" width="25%"><center><b>MORA_SS</b></center></th>

                </tr>';
        $cabecera=$parte1.$parte2.$parte3.$parte4;  
        
        $cad1.=$cabecera;
        $j=0;
        
        for($i = 0; $i < count ( $rows ); $i ++) 
        {
            $j=$i+1;
            
            $cad1.='<tr>';
            $cad1.= "<td width=10%><center><b>".$j."</b></center></td>";
            $cad1.= "<td align=center ><b>".utf8_encode($rows[$i][0])."</b></td>";
            $cad1.= "<td width=10%><b>".utf8_encode($rows[$i][1])."</b></td>";
            $cad1.= "<td width=10%><b>".utf8_encode($rows[$i][2])."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][3]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][4]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][5]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][6]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][7]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][8]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][9]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][10]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][11]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][12]."</b></td>";

            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][13]."</b></td>";

            $cad1.= "<td width=10%><b>".$rows[$i][14]."</b></td>";

            $cad1.= "<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][15]."</b></td>";

            $cad1.= "<td width=10%><b>".$rows[$i][16]."</b></td>";

            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][17]."</b></td>";

            $cad1.= "<td width=10% ><b>".$rows[$i][18]."</b></td>";

            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][19]."</b></td>";

            $cad1.= "<td width=10%><b>".$rows[$i][20]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][21]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][22]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][23]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][24]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][25]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][26]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][27]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][28]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][29]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][30]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][31]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][32]."</b></td>";

            //ARB

            $cad1.= "<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][33]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][34]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][35]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][36]."</b></td>";

            $cad1.= "<td width=10%><b>".$rows[$i][37]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][38]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][39]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][40]."</b></td>";

            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][41]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][42]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][43]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][44]."</b></td>";

            $cad1.= "<td width=10% ><b>".$rows[$i][45]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][46]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][47]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][48]."</b></td>";

            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][49]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][50]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][51]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][52]."</b></td>";

            $cad1.= "<td width=10%><b>".$rows[$i][53]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][54]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][55]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][56]."</b></td>";

            $cad1.= "<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][57]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][58]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][59]."</b></td>";



            $cad1.= "<td width=10% ><b>".$rows[$i][60]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][61]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][62]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][63]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][64]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][65]."</b></td>";

            $cad1.= "<td style='background-color:#EDF8D2'  width=10% ><b>".$rows[$i][66]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][67]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][68]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][69]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][70]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][71]."</b></td>";

            $cad1.= "<td width=10% ><b>".$rows[$i][72]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][73]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][74]."</b></td>";
            $cad1.= "<td width=10% ><b>".$rows[$i][75]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][76]."</b></td>";
            $cad1.= "<td width=10%><b>".$rows[$i][77]."</b></td>";

            $cad1.= "<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][78]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][79]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][80]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][81]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][82]."</b></td>";
            $cad1.= "<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][83]."</b></td>";

            $cad1.='</tr>';
        }
    
        echo $ini.$cad1;
    }

    public function exportarrptsaldos1Action()
    {
    	$cn   	= new Model_DbDatos_Datos();
	
		$parametros[] = array('@busc','2');
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Reportes_Diversos]', $parametros);
	
	
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="850PX">
				<tr>';
		$parte2="<th style='background-color:#7C9FD8' colspan=128><center><b>REPORTE DE SALDOS  ".$data_anio."</b></center></th></tr>";
		
		$parte3='<tr>
				<th colspan=14>  </th>
				<th style="background-color:#C7DCC6" colspan=4>IP ANTERIOR</th>
				<th colspan=4>IP 2009</th>
				<th style="background-color:#C7DCC6" colspan=4>IP 2010</th>
				<th colspan=4>IP 2011</th>
				<th style="background-color:#C7DCC6" colspan=4>IP 2012</th>
				<th colspan=4>IP 2013</th>
				<th style="background-color:#C7DCC6" colspan=4>IP 2014</th>
				<th colspan=3>IP 2015 TRIM-I</th>
				<th colspan=3>IP 2015 TRIM-II</th>
				<th colspan=3>IP 2015 TRIM-III</th>
				<th colspan=3>IP 2015 TRIM-IV</th>
				<th >EMI.2015</th>
				<th style="background-color:#C7DCC6" colspan=7>ARB ANTERIOR</th>
				<th colspan=7>ARB 2009</th>
				<th style="background-color:#C7DCC6" colspan=7>ARB 2010</th>
				<th colspan=7>ARB 2011</th>
				<th style="background-color:#C7DCC6" colspan=7>ARB 2012</th>
				<th colspan=7>ARB 2013</th>
				<th style="background-color:#C7DCC6" colspan=6>ARB 2014</th>
				<th colspan=6>ARB 2015 TRIM-I</th>
				<th colspan=6>ARB 2015 TRIM-II</th>
				<th colspan=6>ARB 2015 TRIM-III</th>
				<th colspan=6>ARB 2015 TRIM-IV</th>
				</tr>';

		$parte4='<tr>
					<th style="background-color:#9D9A9A ;color:#FFFFFF" width="10%"><center><b>Nro.</b></center></th>
					<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="10%"><center><b>Codigo</b></center></th>
					<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="50%"><center><b>Nombre</b></center></th>
    				<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="15%"><center><b>Dir_Fisca</b></center></th>
    				<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="25%"><center><b>Distrito</b></center></th>
    				<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="25%"><center><b>Sector</b></center></th>

    				<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="10%"><center><b>Tipo_via</b></center></th>
					<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="50%"><center><b>Via</b></center></th>
    				<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="15%"><center><b>AAHH</b></center></th>
    				<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="25%"><center><b>cuadrante</b></center></th>
    				<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="25%"><center><b>BASE_IMPONIBLE</b></center></th>

    				<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="10%"><center><b>tot_autoavaluo</b></center></th>
					<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="50%"><center><b>imp_anual</b></center></th>
    				<th style="background-color:#9D9A9A ;color:#FFFFFF " width="10%" width="15%"><center><b>imp_trime</b></center></th>

    				<th style="background-color:#ABD6A8" width="25%"><center><b>insoluto</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>emision</b></center></th>
    				<th style="background-color:#ABD6A8" width="10%"><center><b>reajuste</b></center></th>
					<th style="background-color:#ABD6A8" width="50%"><center><b>mora</b></center></th>

    				<th width="15%"><center><b>insoluto</b></center></th>
    				<th width="25%"><center><b>emision</b></center></th>
    				<th width="25%"><center><b>reajuste</b></center></th>
    				<th width="25%"><center><b>mora</b></center></th>

    				<th style="background-color:#ABD6A8" width="15%"><center><b>insoluto</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>emision</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>reajuste</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>mora</b></center></th>

    				<th width="15%"><center><b>insoluto</b></center></th>
    				<th width="25%"><center><b>emision</b></center></th>
    				<th width="25%"><center><b>reajuste</b></center></th>
    				<th width="25%"><center><b>mora</b></center></th>

    				<th style="background-color:#ABD6A8" width="15%"><center><b>insoluto</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>emision</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>reajuste</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>mora</b></center></th>

    				<th width="15%"><center><b>insoluto</b></center></th>
    				<th width="25%"><center><b>emision</b></center></th>
    				<th width="25%"><center><b>reajuste</b></center></th>
    				<th width="25%"><center><b>mora</b></center></th>

    				<th style="background-color:#ABD6A8" width="15%"><center><b>insoluto</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>emision</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>reajuste</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>mora</b></center></th>

    				<th width="15%"><center><b>insoluto</b></center></th>
    				<th width="25%"><center><b>reajuste</b></center></th>
    				<th width="25%"><center><b>mora</b></center></th>

    				<th width="15%"><center><b>insoluto</b></center></th>
    				<th width="25%"><center><b>reajuste</b></center></th>
    				<th width="25%"><center><b>mora</b></center></th>

    				<th width="15%"><center><b>insoluto</b></center></th>
    				<th width="25%"><center><b>reajuste</b></center></th>
    				<th width="25%"><center><b>mora</b></center></th>

    				<th width="15%"><center><b>insoluto</b></center></th>
    				<th width="25%"><center><b>reajuste</b></center></th>
    				<th width="25%"><center><b>mora</b></center></th>

    				<th width="25%"><center><b>costo</b></center></th>


    				<th style="background-color:#ABD6A8" width="15%"><center><b>INS_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_SS</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_SS</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>EMISION</b></center></th>


    				<th width="15%"><center><b>INS_LP</b></center></th>
    				<th width="25%"><center><b>MORA_LP</b></center></th>
    				<th width="25%"><center><b>INS_PJ</b></center></th>
    				<th width="25%"><center><b>MORA_PJ</b></center></th>
    				<th width="25%"><center><b>INS_SS</b></center></th>
    				<th width="25%"><center><b>MORA_SS</b></center></th>
    				<th width="25%"><center><b>EMISION</b></center></th>

    				<th style="background-color:#ABD6A8" width="15%"><center><b>INS_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_SS</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_SS</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>EMISION</b></center></th>

    				<th width="15%"><center><b>INS_LP</b></center></th>
    				<th width="25%"><center><b>MORA_LP</b></center></th>
    				<th width="25%"><center><b>INS_PJ</b></center></th>
    				<th width="25%"><center><b>MORA_PJ</b></center></th>
    				<th width="25%"><center><b>INS_SS</b></center></th>
    				<th width="25%"><center><b>MORA_SS</b></center></th>
    				<th width="25%"><center><b>EMISION</b></center></th>

    				<th style="background-color:#ABD6A8" width="15%"><center><b>INS_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_SS</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_SS</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>EMISION</b></center></th>

    				<th width="15%"><center><b>INS_LP</b></center></th>
    				<th width="25%"><center><b>MORA_LP</b></center></th>
    				<th width="25%"><center><b>INS_PJ</b></center></th>
    				<th width="25%"><center><b>MORA_PJ</b></center></th>
    				<th width="25%"><center><b>INS_SS</b></center></th>
    				<th width="25%"><center><b>MORA_SS</b></center></th>
    				<th width="25%"><center><b>EMISION</b></center></th>

    				<th style="background-color:#ABD6A8" width="15%"><center><b>INS_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_SS</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_SS</b></center></th>

    				<th width="15%"><center><b>INS_LP</b></center></th>
    				<th width="25%"><center><b>MORA_LP</b></center></th>
    				<th width="25%"><center><b>INS_PJ</b></center></th>
    				<th width="25%"><center><b>MORA_PJ</b></center></th>
    				<th width="25%"><center><b>INS_SS</b></center></th>
    				<th width="25%"><center><b>MORA_SS</b></center></th>

    				<th style="background-color:#ABD6A8" width="15%"><center><b>INS_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_SS</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_SS</b></center></th>

    				<th width="15%"><center><b>INS_LP</b></center></th>
    				<th width="25%"><center><b>MORA_LP</b></center></th>
    				<th width="25%"><center><b>INS_PJ</b></center></th>
    				<th width="25%"><center><b>MORA_PJ</b></center></th>
    				<th width="25%"><center><b>INS_SS</b></center></th>
    				<th width="25%"><center><b>MORA_SS</b></center></th>

    				<th style="background-color:#ABD6A8" width="15%"><center><b>INS_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_LP</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_PJ</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>INS_SS</b></center></th>
    				<th style="background-color:#ABD6A8" width="25%"><center><b>MORA_SS</b></center></th>

    				<th width="25%"><center><b>F. EJECUCION</b></center></th>


			    </tr>';
		$cabecera=$parte1.$parte2.$parte3.$parte4;	
		
		$cad1.=$cabecera;
		$j=0;
		
		for($i = 0; $i < count ( $rows ); $i ++) 
        {
			$j=$i+1;
			
			$cad1.='<tr>';
			$cad1.=	"<td width=10%><center><b>".$j."</b></center></td>";
			$cad1.=	"<td align=center ><b>".utf8_encode($rows[$i][0])."</b></td>";
			$cad1.=	"<td width=10%><b>".utf8_encode($rows[$i][1])."</b></td>";
			$cad1.=	"<td width=10%><b>".utf8_encode($rows[$i][2])."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][3]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][4]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][5]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][6]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][7]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][8]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][9]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][10]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][11]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][12]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][13]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][14]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][15]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][16]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][17]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][18]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][19]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][20]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][21]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][22]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][23]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][24]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][25]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][26]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][27]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][28]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][29]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][30]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][31]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][32]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][33]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][34]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][35]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][36]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][37]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][38]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][39]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][40]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][41]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][42]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][43]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][44]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][45]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][46]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][47]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][48]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][49]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][50]."</b></td>";

			$cad1.=	"<td width=10% ><b>".$rows[$i][51]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][52]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][53]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][54]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][55]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][56]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][57]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][58]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][59]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][60]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][61]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][62]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][63]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][64]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][65]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][66]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][67]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][68]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][69]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][70]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][71]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][72]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][73]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][74]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][75]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][76]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][77]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][78]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][79]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][80]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][81]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][82]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][83]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][84]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][85]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][86]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][87]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][88]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][89]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][90]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][91]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][92]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][93]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][94]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][95]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][96]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][97]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][98]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][99]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][100]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][101]."</b></td>";

			$cad1.=	"<td width=10% ><b>".$rows[$i][102]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][103]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][104]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][105]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][106]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][107]."</b></td>";

			$cad1.=	"<td style='background-color:#EDF8D2'  width=10% ><b>".$rows[$i][108]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][109]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][110]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][111]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][112]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][113]."</b></td>";

			$cad1.=	"<td width=10% ><b>".$rows[$i][114]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][115]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][116]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][117]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][118]."</b></td>";
			$cad1.=	"<td width=10%><b>".$rows[$i][119]."</b></td>";

			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][120]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][121]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][122]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10% ><b>".$rows[$i][123]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][124]."</b></td>";
			$cad1.=	"<td style='background-color:#EDF8D2' width=10%><b>".$rows[$i][125]."</b></td>";
			$cad1.=	"<td width=10% ><b>".$rows[$i][126]."</b></td>";

			$cad1.='</tr>';
		}
	
		echo $ini.$cad1;
    }

    public function exportarrptfraccAction()
    {

    	$cn   	= new Model_DbDatos_Datos();
	
		$parametros[] = array('@busc','7');
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Reportes_Diversos]', $parametros);
	
	
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
		$cad1='<div align="left"><img src="http://10.0.1.60:8080/img/logo.png" ></div>';
		$parte1='<table border="0">
				<tr>';
		$parte2="<th style='background-color:#FFFFFF; color:#000000' colspan=18><center><H3><b> SITUACION FRACCIONAMIENTOS  :  ".$rows[0][17]."</b></H3></center></th></tr></table>";
		$parte3="<table border=1 class=hovertable width=850PX></tr>
				<tr>
					<th style='background-color:#3784AB; color:#FFFFFF' width='10%'><center><b>ID</b></center></th>
					<th style='background-color:#3784AB; color:#FFFFFF' width='50%'><center><b>CODIGO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='15%'><center><b>NOMBRE</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>DIR_FISCA</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>ANIO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>NUM_DOCU</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>F. CONVENIO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>DEUDA</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>CUOTA INICIAL</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b># CUOTAS</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>PERIODOS PENDIENTES</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>CUOTAS VENCIDAS</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>INSOLUTO VENCIDO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>MORA VENCIDO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>PERIODO</b></center></th>

    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>INSOLUTO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>MORA</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>F. VENCIMIENTO</b></center></th>
			    </tr>";

		$cabecera=$parte1.$parte2.$parte3;	
		
		$cad1.=$cabecera;
		
		$codigo='0000000';
		$num_docu='00000000000';
		$j=0;
		for($i = 0; $i < count ( $rows ); $i ++) 
        {


				$j=$j+1;
				$cad1.='<tr>';

				$cad1.=	"<td  width=10%><center><b>".$j."</b></center></td>";
				$cad1.=	"<td  width=10%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td  align=left width=50%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td  align=left width=50%><b>".utf8_encode($rows[$i][2])."</b></td>";
				$cad1.=	"<td  width=15%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][4]."</b></center></td>";
				$cad1.=	"<td  width=15%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.=	"<td  width=15%><center><b>".$rows[$i][7]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][8]."</b></center></td>";
				$cad1.=	"<td  width=15%><center><b>".$rows[$i][9]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][10]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][11]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][12]."</b></center></td>";

				$cad1.=	"<td width=15%><center><b>".$rows[$i][13]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][14]."</b></center></td>"; //INSOLUTO
				$cad1.=	"<td width=25%><center><b>".$rows[$i][15]."</b></center></td>";
				$cad1.=	"<td width=25%><center><b>".$rows[$i][16]."</b></center></td>";
				$cad1.='</tr>';
			
		}

		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;
    }


public function exportarrptfracc1Action()
    {

        $cn     = new Model_DbDatos_Datos();
    
        $parametros[] = array('@busc','6');
        
        $rows = $cn->ejec_store_procedura_sql('[Rentas].[Reportes_Diversos]', $parametros);
    
    
        $ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
        $cad1='';
        $parte1='<table border="1" class="hovertable" width="850PX">
                <tr>';
        $parte2="<th style='background-color:#4F81BD; color:#FFFFFF' colspan=24><center><b> SITUACION FRACCIONAMIENTOS </b></center></th>";
        $parte3="</tr>
                <tr>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='10%'><center><b>ID</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='50%'><center><b>CODIGO</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='15%'><center><b>NOMBRE</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>DIR_FISCA</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>ANIO</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>NUM_DOCU</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>F. CONVENIO</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>DEUDA</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>CUOTA INICIAL</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b># CUOTAS</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>PERIODOS PENDIENTES</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>CUOTAS VENCIDAS</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>INSOLUTO VENCIDO</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>MORA VENCIDO</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>PERIODO</b></center></th>

                    <th style='background-color:#E6D87C; color:#000000' width='25%'><center><b>MONTO IP</b></center></th>
                    <th style='background-color:#E6D87C; color:#000000' width='25%'><center><b>MONTO ARB BV</b></center></th>
                    <th style='background-color:#E6D87C; color:#000000' width='25%'><center><b>MONTO ARB RR</b></center></th>
                    <th style='background-color:#E6D87C; color:#000000' width='25%'><center><b>MONTO ARB PJ</b></center></th>
                    <th style='background-color:#E6D87C; color:#000000' width='25%'><center><b>MONTO ARB SS</b></center></th>
                    <th style='background-color:#E6D87C; color:#000000' width='25%'><center><b>MONTO MULTA</b></center></th>

                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>INSOLUTO</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>MORA</b></center></th>
                    <th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>F. VENCIMIENTO</b></center></th>
                </tr>";

        $cabecera=$parte1.$parte2.$parte3;  
        
        $cad1.=$cabecera;
        
        $codigo='0000000';
        $num_docu='00000000000';
        $j=0;
        for($i = 0; $i < count ( $rows ); $i ++) 
        {
            $codigo1=$rows[$i][0];
            $num_docu1=$rows[$i][4];
                
            if($codigo!=$codigo1 || $num_docu!=$num_docu1)
            {
                $codigo=$codigo1;
                $num_docu=$num_docu1;

                $col=$rows[$i][9];

                $j=$j+1;
                $cad1.='<tr>';

                $cad1.= "<td  rowspan=".$col." width=10%><center><b>".$j."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=10%><center><b>".$rows[$i][0]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." align=left width=50%><b>".utf8_encode($rows[$i][1])."</b></td>";
                $cad1.= "<td  rowspan=".$col." align=left width=50%><b>".utf8_encode($rows[$i][2])."</b></td>";
                $cad1.= "<td  rowspan=".$col." width=15%><center><b>".$rows[$i][3]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=25%><center><b>".$rows[$i][4]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=15%><center><b>".$rows[$i][5]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=25%><center><b>".$rows[$i][6]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=15%><center><b>".$rows[$i][7]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=25%><center><b>".$rows[$i][8]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=15%><center><b>".$rows[$i][9]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=25%><center><b>".$rows[$i][10]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=25%><center><b>".$rows[$i][11]."</b></center></td>";
                $cad1.= "<td  rowspan=".$col." width=25%><center><b>".$rows[$i][12]."</b></center></td>";

                $cad1.= "<td width=15%><center><b>".$rows[$i][13]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][15]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][16]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][17]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][18]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][19]."</b></center></td>";
                $cad1.= "<td width=25%><center><b>".$rows[$i][20]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][14]."</b></center></td>"; //INSOLUTO
                $cad1.= "<td width=25%><center><b>".$rows[$i][21]."</b></center></td>";
                $cad1.= "<td width=25%><center><b>".$rows[$i][22]."</b></center></td>";
                $cad1.='</tr>';

            }
            
            else{
            
                $cad1.='<tr>';
                $cad1.= "<td width=15%><center><b>".$rows[$i][13]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][15]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][16]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][17]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][18]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][19]."</b></center></td>";
                $cad1.= "<td width=25%><center><b>".$rows[$i][20]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][14]."</b></center></td>"; //INSOLUTO
                $cad1.= "<td width=25%><center><b>".$rows[$i][21]."</b></center></td>";
                $cad1.= "<td width=25%><center><b>".$rows[$i][22]."</b></center></td>";

                $cad1.='</tr>';
            }
        }
    
        //$cad1.=$data_codigo."</br>";
        echo $ini.$cad1;
    }
	public function menureporteingresosAction(){

		$fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);

		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];

		$this->view->title = "Reporte de Ingresos por Rango de Fechas";

		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");

		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "semanal/index","Reportes Gerenciales")');
		$evt[] = array("#btnaceptar", "click", 'exportaringresosxrango()');

		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}



	public function exportarrptingresosAction(){

    	$cn   	= new Model_DbDatos_Datos();
	


		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');

		$parametros[] = array('@busc','4');
		$parametros[] = array('@fecha_ini',$desde);
		$parametros[] = array('@fecha_fin',$hasta);

		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Reportes_Diversos]', $parametros);
	
	
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="850PX">
				<tr>';
		$parte2="<th style='background-color:#4F81BD; color:#FFFFFF' colspan=15><center><b> REPORTE INGRESOS </b></center></th>";
		$parte3="</tr>
				<tr>
					<th style='background-color:#3784AB; color:#FFFFFF' width='10%'><center><b>ID</b></center></th>
					<th style='background-color:#3784AB; color:#FFFFFF' width='50%'><center><b>NUM. INGRESO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='15%'><center><b>CODIGO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>NOMBRE</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>ANIO OBLI.</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>CONCEPTO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>SUB CONCEPTO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>PERIODO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>MONTOTAL</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>INSOLUTO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>REAJUSTE</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>EMISION</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>MORA</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>F. INGRESO</b></center></th>
    				<th style='background-color:#3784AB; color:#FFFFFF' width='25%'><center><b>MES</b></center></th>
			    </tr>";

		$cabecera=$parte1.$parte2.$parte3;	
		
		$cad1.=$cabecera;

		for($i = 0; $i < count ( $rows ); $i ++) 
        {

				$j=$i+1;

				$cad1.='<tr>';

				$cad1.=	"<td  width=10%><center><b>".$j."</b></center></td>";
				$cad1.=	"<td  width=10%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td  align=left width=50%><b>".$rows[$i][1]."</b></td>";
				$cad1.=	"<td  align=left width=50%><b>".utf8_encode($rows[$i][2])."</b></td>";
				$cad1.=	"<td  width=15%><center><b>".utf8_encode($rows[$i][3])."</b></center></td>";
				//$cad1.=	"<td  width=25%><center><b>".$rows[$i][4]."</b></center></td>";
				$cad1.=	"<td  width=15%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.=	"<td  width=15%><center><b>".$rows[$i][7]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][8]."</b></center></td>";
				$cad1.=	"<td  width=15%><center><b>".$rows[$i][9]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][10]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][11]."</b></center></td>";
				$cad1.=	"<td  width=25%><center><b>".$rows[$i][12]."</b></center></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][13]."</b></center></td>";
                $cad1.= "<td width=15%><center><b>".$rows[$i][15]."</b></center></td>";

				$cad1.='</tr>';

		}
			
	
		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;

    }

	
}




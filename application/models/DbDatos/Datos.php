<?php
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_DbDatos_Datos
{
	public function ejec_store_procedura_sql($nombrestore,$arraydatos)
	{		
		$cn = mssql_connect("10.0.1.60","sa","Sistemas25");
		//$cn = mssql_connect("10.0.1.19","sa","123456");

		if(!$cn){
			echo 'error en base de datos';
		}else{			
			mssql_select_db("SIGMUN",$cn);			
			$caddatos = '';
			if($arraydatos != '' || $arraydatos != null){
				if(count($arraydatos) > 0){
					for($i=0;$i<count($arraydatos);$i++){
							$nomvar = $arraydatos[$i][0];
							$valvar = $arraydatos[$i][1];
						$caddatos.= $nomvar."='".$valvar."',";
					}
					$caddatos = substr($caddatos,0,strlen($caddatos)-1);
				}
			}
			$cadins = 'exec '.$nombrestore.' '.$caddatos;
			
			//echo '<textarea>'.$cadins.'</textarea>';
			//$result='';
                        //$retorno=htmlentities($cadins, ENT_QUOTES,'UTF-8');
			$result=mssql_query(utf8_decode($cadins)) or die("Error al ejecutar: <textarea>".$cadins."</textarea>");
			$contador=0;
			$datos = null;
			while ($row = mssql_fetch_row($result)) {
				$c = count($row);
						for($i = 0 ; $i<$c;$i++){
								$cadreplace = $row[$i];
								//$cadreplace = htmlentities($row[$i]);
								$cadreplace = str_replace('&amp;','&',$cadreplace);
								//$cadreplace = str_replace("'",' ',$cadreplace);
								$cadreplace = str_replace('"',' ',$cadreplace);
								$cadreplace = str_replace('|','',$cadreplace);
								$cadreplace = str_replace('?','',$cadreplace);
								$cadreplace = str_replace('\\','',$cadreplace);	
								//$cadreplace = str_replace('&Ntilde;',htmlentities('?'),$cadreplace);
								$arraydata[$i] = $cadreplace; 
				 			}      	
				 		$datos[$contador] = $arraydata;
			   $contador++;
			} 
			
			mssql_close($cn);
			return $datos;
		}
	}
	
	
		public function ejec_store_procedura_sql_manuel($nombrestore,$arraydatos)
	{		
		$cn = mssql_connect("10.0.5.9","sa","123456");
		//$cn = mssql_connect("10.0.1.19","sa","123456");

		if(!$cn){
			echo 'error en base de datos';
		}else{			
			mssql_select_db("SIGMUN_ANT_LOCAL",$cn);			
			$caddatos = '';
			if($arraydatos != '' || $arraydatos != null){
				if(count($arraydatos) > 0){
					for($i=0;$i<count($arraydatos);$i++){
							$nomvar = $arraydatos[$i][0];
							$valvar = $arraydatos[$i][1];
						$caddatos.= $nomvar."='".$valvar."',";
					}
					$caddatos = substr($caddatos,0,strlen($caddatos)-1);
				}
			}
			$cadins = 'exec '.$nombrestore.' '.$caddatos;
			
			//echo '<textarea>'.$cadins.'</textarea>';
			//$result='';
                        //$retorno=htmlentities($cadins, ENT_QUOTES,'UTF-8');
			$result=mssql_query(utf8_decode($cadins)) or die("Error al ejecutar: <textarea>".$cadins."</textarea>");
			$contador=0;
			$datos = null;
			while ($row = mssql_fetch_row($result)) {
				$c = count($row);
						for($i = 0 ; $i<$c;$i++){
								$cadreplace = $row[$i];
								//$cadreplace = htmlentities($row[$i]);
								$cadreplace = str_replace('&amp;','&',$cadreplace);
								//$cadreplace = str_replace("'",' ',$cadreplace);
								$cadreplace = str_replace('"',' ',$cadreplace);
								$cadreplace = str_replace('|','',$cadreplace);
								$cadreplace = str_replace('?','',$cadreplace);
								$cadreplace = str_replace('\\','',$cadreplace);	
								//$cadreplace = str_replace('&Ntilde;',htmlentities('?'),$cadreplace);
								$arraydata[$i] = $cadreplace; 
				 			}      	
				 		$datos[$contador] = $arraydata;
			   $contador++;
			} 
			
			mssql_close($cn);
			return $datos;
		}
	}
	
	
	
	
	public function ejec_store_procedura_sql_noparamatro($nombrestore,$arraydatos)
	{		
		$cn = mssql_connect("10.0.1.60","sa","Sistemas25");
		//$cn = mssql_connect("10.0.1.19","sa","123456");

		if(!$cn){
			echo 'error en base de datos';
		}else{			
			mssql_select_db("SIGMUN",$cn);			
			$caddatos = '';
			if($arraydatos != '' || $arraydatos != null){
				if(count($arraydatos) > 0){
					for($i=0;$i<count($arraydatos);$i++){
							$nomvar = $arraydatos[$i][0];
							$valvar = $arraydatos[$i][1];
						$caddatos.= $nomvar."='".$valvar."',";
					}
					$caddatos = substr($caddatos,0,strlen($caddatos)-1);
				}
			}
			$cadins = 'exec '.$nombrestore.' '.$caddatos;
			
			//echo '<textarea>'.$cadins.'</textarea>';
			//$result='';
                        //$retorno=htmlentities($cadins, ENT_QUOTES,'UTF-8');
			$result=mssql_query(utf8_decode($cadins)) or die("Error al ejecutar: <textarea>".$cadins."</textarea>");
						
			mssql_close($cn);
			return $result;
		}
	}

	public function ejec_store_procedura_sql_noparamatro2($nombrestore,$arraydatos)
	{		
		/*$cn = mssql_connect("localhost","sa","123456");
		//$cn = mssql_connect("10.0.1.19","sa","123456");

		if(!$cn){
			echo 'error en base de datos';
		}else{			
			mssql_select_db("SIGMUN",$cn);			
			$caddatos = '';
			if($arraydatos != '' || $arraydatos != null){
				if(count($arraydatos) > 0){
					for($i=0;$i<count($arraydatos);$i++){
							$nomvar = $arraydatos[$i][0];
							$valvar = $arraydatos[$i][1];
						$caddatos.= $nomvar."='".$valvar."',";
					}
					$caddatos = substr($caddatos,0,strlen($caddatos)-1);
				}
			}
			$cadins = 'exec '.$nombrestore.' '.$caddatos;
			
			//echo '<textarea>'.$cadins.'</textarea>';
			//$result='';
                        //$retorno=htmlentities($cadins, ENT_QUOTES,'UTF-8');
			$result=mssql_query(utf8_decode($cadins)) or die("Error al ejecutar: <textarea>".$cadins."</textarea>");
						
			mssql_close($cn);
			return $result;
		}*/
		$cn = mssql_connect("10.0.100.10","sa","Sistemas25");
		//$cn = mssql_connect("10.0.1.19","sa","123456");

		if(!$cn){	
			echo 'error en base de datos';
		}else{			
			mssql_select_db("SIGMUN_ANT",$cn);			
			$caddatos = '';
			if($arraydatos != '' || $arraydatos != null){
				if(count($arraydatos) > 0){
					for($i=0;$i<count($arraydatos);$i++){
							$nomvar = $arraydatos[$i][0];
							$valvar = $arraydatos[$i][1];
						$caddatos.= $nomvar."='".$valvar."',";
					}
					$caddatos = substr($caddatos,0,strlen($caddatos)-1);
				}
			}
			$cadins = 'exec '.$nombrestore.' '.$caddatos;
			
		
			//echo '<textarea>'.$cadins.'</textarea>';
			//$result='';
                        //$retorno=htmlentities($cadins, ENT_QUOTES,'UTF-8');
			$result=mssql_query(utf8_decode($cadins)); //or die("Error al ejecutar: <textarea>".$cadins."</textarea>");

			if(!$result){
				$retorno[0]=false;
				$retorno[1]=mssql_get_last_message();
			}
			else{
				$retorno[0]=true;
				$contador=0;
				$datos = null;
				while ($row = mssql_fetch_row($result)) {
					$c = count($row);
							for($i = 0 ; $i<$c;$i++){
									$cadreplace = $row[$i];
									//$cadreplace = htmlentities($row[$i]);
									$cadreplace = str_replace('&amp;','&',$cadreplace);
									//$cadreplace = str_replace("'",' ',$cadreplace);
									$cadreplace = str_replace('"',' ',$cadreplace);
									$cadreplace = str_replace('|','',$cadreplace);
									$cadreplace = str_replace('?','',$cadreplace);
									$cadreplace = str_replace('\\','',$cadreplace);	
									//$cadreplace = str_replace('&Ntilde;',htmlentities('?'),$cadreplace);
									$arraydata[$i] = $cadreplace; 
					 			}      	
					 		$datos[$contador] = $arraydata;
				   $contador++;
				}
				$retorno[1]=$datos;
			}
						
			mssql_close($cn);
			return $retorno;
		}
	}

	public function ejec_store_procedura_sqlc($nombrestore,$arraydatos)
	{		
		//$cn = mssql_connect("10.0.100.10","sa","Sistemas25");
		$cn = mssql_connect("10.0.1.60","sa","Sistemas25");
		
		if(!$cn){
			echo 'error en base de datos';
		}else{			
			mssql_select_db("catastro",$cn);			
			$caddatos = '';
			if($arraydatos != '' || $arraydatos != null){
				if(count($arraydatos) > 0){
					for($i=0;$i<count($arraydatos);$i++){
							$nomvar = $arraydatos[$i][0];
							$valvar = $arraydatos[$i][1];
						$caddatos.= $nomvar."='".$valvar."',";
					}
					$caddatos = substr($caddatos,0,strlen($caddatos)-1);
				}
			}
			$cadins = 'exec '.$nombrestore.' '.$caddatos;
			
			$result = mssql_query(utf8_decode($cadins)) or die("Error al ejecutar: <textarea>".$cadins."</textarea>");
			$contador=0;
			$datos = null;
			while ($row = mssql_fetch_row($result)) {
				$c = count($row);
						for($i = 0 ; $i<$c;$i++){
							$cadreplace = $row[$i];
							//$cadreplace = htmlentities($row[$i]);
							$cadreplace = str_replace('&amp;','&',$cadreplace);
							$cadreplace = str_replace("'",' ',$cadreplace);
							$cadreplace = str_replace('"',' ',$cadreplace);
							$cadreplace = str_replace('|','',$cadreplace);
							$cadreplace = str_replace('?','',$cadreplace);
							$cadreplace = str_replace('\\','',$cadreplace);	
							//$cadreplace = str_replace('&Ntilde;',htmlentities('?'),$cadreplace);
							$arraydata[$i] = $cadreplace;
				 		}      	
				 		$datos[$contador] = $arraydata;
			   $contador++;
			} 
			
			mssql_close($cn);
			return $datos;
		}
	}
	
	public function ejecutasql($nombrestore,$parametros,$flagOut){
		$cn = $this->conecta();
		
		if (!$cn){
			echo 'Error de Coneccion BD';
		}else{
			mysql_select_db("prueba", $cn);
			
			$cadenadatos = '';
			if($parametros != '' || $parametros != null){
				$cparametros = count($parametros);
				if($cparametros > 0){
					for($i=0; $i < $cparametros; ++$i){
						$nompar = $parametros[$i][0];
						$valpar = utf8_decode($parametros[$i][1]);
						$valpar = str_replace("'", "''", $valpar);
						if($valpar!='OUT')
						{
							$cadenadatos.= $nompar.":='".$valpar."',";
						}
						else
						{
							$parOut = $nompar;
							$cadenadatos.= $nompar.",";
						}
					}
					$cadenadatos = substr($cadenadatos,0,strlen($cadenadatos)-1);
				}
			}
			$instruccion = 'call '.$nombrestore.' ('.$cadenadatos.')';
			//print($instruccion);

			$result = mysql_query($instruccion);						
			
			if (!$result) 
				print "MYSQL Query failed: " . htmlentities(mysql_error()) . "<br />".$instruccion."\n";
			
			$datos = null;
			
			if($flagOut)
			{
				$query = "SELECT $parOut";
				$result = mysql_query($query);				
				$numrows = mysql_num_rows($result);

				if($numrows>0)
				{
					for ($i=0; $i<$numrows; $i++)
					{
						$rows = mysql_fetch_array($result);			
						$datos[$i][0] = $rows[0];
					}
					//print_r($datos);
				}				
			}
			else
			{
				$contador = 0;
	
				while(($row = mysql_fetch_array($result)) != false){
					$c = count($row)/2;
					for($i = 0; $i < $c; ++$i){
						$cadreemp = ($wutf8encode)?utf8_encode($row[$i]):$row[$i];
						$arraydatos[$i] = $cadreemp;
					}
					$datos[$contador] = $arraydatos;
					//print_r($datos);
					++$contador;
				}
			}
			
			mysql_close($cn);
			return $datos;
		}
	}
	
	public function ejecutafuncsql($nombrefunc,$parametros){ 
		$cn = $this->conecta();
	
		if (!$cn){
			echo 'Error de Coneccion BD';
		}else{
			mysql_select_db("prueba", $cn);
			
			$cparametros = count($parametros);
			$cadenadatos = '';
			if($cparametros > 0){
				for($i = 0; $i < $cparametros; ++$i){
					$cadenadatos .= "'".$parametros[$i]."',";	
				}
				$cadenadatos = substr($cadenadatos,0,strlen($cadenadatos)-1);
			}

			$instruccion = 'select '.$nombrefunc.' ('.$cadenadatos.')';

			$result = mysql_query($instruccion);
			$valor = mysql_fetch_array($result);
	
			mysql_close($cn);
			return $valor;
		} 
	}
	
}

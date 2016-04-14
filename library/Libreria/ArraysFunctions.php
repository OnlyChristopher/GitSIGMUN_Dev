<?php
class Libreria_ArraysFunctions {
	
	public function RegistrosCombo($arraydatos, $ind_val, $ind_text) {
		$valor = array();
		
		$numfilas = count($arraydatos);
		
		if($numfilas)
		{
			for ($i=0; $i<$numfilas; $i++)
				$valor[$arraydatos[$i][$ind_val]] = $arraydatos[$i][$ind_text];
		}
		
		return $valor;
	}
	
	public function RegistrosComboc($arraydatos, $ind_val, $ind_text, $ind_tip) {
		$valor = array();
		
		$numfilas = count($arraydatos);
		
		if($numfilas)
		{
			for ($i=0; $i<$numfilas; $i++){
				if($ind_tip=='number')
					$valor[trim($arraydatos[$i][$ind_val])] = $arraydatos[$i][$ind_text];
				else
					$valor[$arraydatos[$i][$ind_val]] = $arraydatos[$i][$ind_text];
			}
		}
		
		return $valor;
	}
	
	public function AniosTributos() {
		$valor = array();
		
		for ($i=date('Y'); $i>=1992; $i--){
			$valor[$i] = $i;
			}
		
		return $valor;
	}
	
	public function EspecialChars($str)
	{
		$tildes = array('á','é','í','ó','ú','ñ','Á','É','Í','Ó','Ú','Ñ','°');
		$vocales = array('&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&ntilde;','&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;','&Ntilde;','&deg;');
		$str = str_replace($tildes,$vocales,$str);
		
		return $str;
	}

	public function GroupArrayMulti($Arr, $g1, $g2, $g3, $g4) {
		$output = Array();
		
		foreach($Arr as $value) {
			$str = "";
			if(!empty($g2))
				$str .= "_".$value[$g2];
			if(!empty($g3))
				$str .= "_".$value[$g3];
			if(!empty($g4))
				$str .= "_".$value[$g4];
				
			$output_element = &$output[$value[$g1] . $str];
			$output_element[$g1] = $value[$g1];
			$output_element[$g2] = $value[$g2];
			$output_element[$g3] = $value[$g3];
			$output_element[$g4] = $value[$g4];
		}
		
		return array_values($output);
	}
	
	public function isDate($str) 
	{ 
	  $stamp = split("/",$str);
	  
	  if(!empty($stamp[0]) && !empty($stamp[1]) && !empty($stamp[2]))
	  {
		  $day   = $stamp[0]; 
		  $month = $stamp[1];   
		  $year  = $stamp[2]; 
		  
		  if (checkdate($month, $day, $year)) 
			return TRUE; 
		  else
			return FALSE;
	  }
	  else
		return FALSE;       
	}
	
	public function toDate($str) 
	{ 
		$date = strtotime($str);
		if ($date === false)
			return '';
		else{
			$new_date = date('d/m/Y', $date);
			if($this->isDate($new_date))
				return $new_date;
			else
				return '';
		}
	}
	
	public function EstadosUsuario() {
		$valor = array();

		$valor['1'] = "ACTIVADO";
		$valor['0'] = "DESACTIVADO";
				
		return $valor;
	}
	
	public function TipoObjeto() {
		$valor = array();

		$valor['M'] = "MENÚ";
		$valor['O'] = "OBJETO";
				
		return $valor;
	}
	
	public function EstadoRegistro() {
		$valor = array();

		$valor['8'] = "PENDIENTE";
		$valor['14'] = "ACUMULADO";
		$valor['7'] = "FRACCIONADO";
				
		return $valor;
	}
	
	public function AddZeros($numero,$ceros) {
		if($numero>0)
		{
			$order_diez = explode(".",$numero); 
			$dif_diez = $ceros - strlen($order_diez[0]); 
			for($m = 0 ; $m < $dif_diez; $m++)
			{ 
				@$insertar_ceros .= 0;
			}
			return $insertar_ceros .= $numero; 
		}
		else
			return '';		
	}
	
}
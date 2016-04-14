<?php

class Libreria_Pintar {
	
	var $script = true;
	
	public function IniciaScript() {
		echo "<script type='text/javascript'>";
		$this->script = false;
	}
	
	public function FinScript() {
		echo "</script>";
		$this->script = true;
	}
	
	public function PintarLibrerias(){
		 
		$surl = new Zend_Session_Namespace('path');
		$url = $surl->data;
		
	}
	
	/**
	 * Escribe valores dentro de los componentes
	 * tiene como parametro un arreglo que tiene
	 * la siguiente estructura
	 * 		Col 0 = Id del Objecto
	 * 		Col 1 = Valor del Objecto
	 * 		Col 2 = Tipo de Propiedad (val, html)
	 * @param array $valores
	 */
	public function PintarValor($valores) {
		$script = "Parametro invalido.";

		if(is_array($valores)){
		$valores = array_values($valores);
			$script = ($this->script) ? "\n<script type='text/javascript'>\n" : "";
			for($i = 0; $i < count($valores); $i++) {
				$valores[$i][1] = str_replace("'", "\'", $valores[$i][1]);
				$script .= "\t$('".$valores[$i][0]."').".$valores[$i][2]."('".$valores[$i][1]."');\n";
			}
			$script .= ($this->script) ? "</script>\n" : "";
		}
		
		echo $script;
	}
	
	//Para búsqueda en formulario flexigrid
	public function PintarValorSearch($valores) {
		$script = "Parametro invalido.";

		if(is_array($valores)){
		$valores = array_values($valores);
			$script = ($this->script) ? "\n<script type='text/javascript'>\n" : "";
			$script .= "\n $(document).ready(function(){";
			for($i = 0; $i < count($valores); $i++) {
				$valores[$i][1] = str_replace("'", "\'", $valores[$i][1]);
				$script .= "\t$('".$valores[$i][0]."').".$valores[$i][2]."('".$valores[$i][1]."');\n";
			}
			$script .= "});";
			$script .= ($this->script) ? "</script>\n" : "";
		}
		
		echo $script;
	}
	
	/**
	 * Asigna los eventos a los componentes
	 * tiene como parametro un arreglo que tiene
	 * la siguiente estructura
	 * 		Col 0 = Id del Objecto
	 * 		Col 1 = Evento
	 * 		Col 2 = Funcion
	 * @param array $valores
	 */
	public function PintarEvento($valores) {
		$script = "Parametro invalido.";

		if(is_array($valores)){
			$valores = array_values($valores);
			$script = ($this->script) ? "\n<script type='text/javascript'>\n" : "";
			for($i = 0; $i < count($valores); $i++) {
				if(!empty($valores[$i][2]))
					switch($valores[$i][1]){
						case 'autotab':
							$script .= "\t$('".$valores[$i][0]."').".$valores[$i][1]."(".$valores[$i][2].");\n";
						break;
						case 'checked':
							$script .= "\t$('".$valores[$i][0]."').attr('".$valores[$i][1]."','".$valores[$i][2]."');\n";
						break;
						default:
							$script .= "\t$('".$valores[$i][0]."').".$valores[$i][1]."(function(event){ ".$valores[$i][2]." });\n";
						break;
					}										
				else
					$script .= "\t$('".$valores[$i][0]."').".$valores[$i][1]."();\n";
			}
			$script .= ($this->script) ? "</script>\n" : "";
		}
		
		echo $script;
	}
	
	/**
	 * Habilita o deshabilita los componentes
	 * tiene como parametro un arreglo que tiene
	 * la siguiente estructura
	 * 		Col 0 = Id del Objecto
	 * 		Col 1 = Valor ( true, false )
	 * @param array $valores
	 */
	public function HabilitarComponente($valores) {
		$script = "Parametro invalido.";
		if(is_array($valores)){
		$valores = array_values($valores);			
			$script = ($this->script) ? "\n<script type='text/javascript'>\n" : "";
			for($i = 0; $i < count($valores); $i++) {
				if($valores[$i][1] == true){
					$script .= '$("'.$valores[$i][0].'").attr(\'disabled\',\'\'); ';	
				}else if($valores[$i][1] == false){
					$script .= '$("'.$valores[$i][0].'").attr(\'disabled\',\'disabled\'); ';
				}				
			}
			$script .= ($this->script) ? "</script>\n" : "";
		}
		
		echo $script;
	}
	
	/**
	 * Componentes de solo lectura tiene como 
	 * parametro un arreglo que tiene la siguiente estructura
	 * 		Col 0 = Id del Objecto
	 * 		Col 1 = Valor ( true, false )
	 * @param array $valores
	 */
	public function ComponenteSoloLectura($valores) {
		$script = "Parametro invalido.";
		if(is_array($valores)){
		$valores = array_values($valores);
			$script = ($this->script) ? "\n<script type='text/javascript'>\n" : "";
			for($i = 0; $i < count($valores); $i++) {
				if($valores[$i][1] == true){
					$script .= '$("'.$valores[$i][0].'").attr(\'readonly\', true);';	
					$script .= '$("'.$valores[$i][0].'").css(\'background-color\', \'#fffbc3\');';
				}else if($valores[$i][1] == false){
					$script .= '$("'.$valores[$i][0].'").attr(\'readonly\', false); ';
				}				
			}
			$script .= ($this->script) ? "</script>\n" : "";
		}
		
		echo $script;
	}

	/**
	 * Añade un atributo al componente
	 * Tiene como parametro un arreglo 
	 * que tiene la siguiente estructura
	 * 		Col 0 = Id del Objecto
	 * 		Col 1 = Atributo
	 *		Col 2 = Valor
	 * @param array $valores
	 */
	public function AtributoComponente($valores) {
		$script = "Parametro invalido.";
		
		if(is_array($valores)){
			$valores = array_values($valores);
			$script = ($this->script) ? "\n<script type='text/javascript'>\n" : "";
			for($i = 0; $i < count($valores); $i++) {
				if($valores[$i][1]=='checked' || $valores[$i][1]=='readonly' || $valores[$i][1]=='disabled')
					$script .= '$("'.$valores[$i][0].'").attr(\''.$valores[$i][1].'\', '.$valores[$i][2].');';
				else
					$script .= '$("'.$valores[$i][0].'").attr(\''.$valores[$i][1].'\', \''.$valores[$i][2].'\');';
			}
			$script .= ($this->script) ? "</script>\n" : "";
		}
		
		echo $script;
	}
	
	/**
	 * Genera unca cadena html con el contenido
	 * de un ComboBox
	 * @param array $opciones 
	 * 		Arreglo bidemensional con los valores a usar
	 * 		Indice 0: Se asigna a la propiedad value
	 * 		Indice 1: Valor que se muestra
	 * @param Object $valor_defecto Valor por defecto a seleccionar
	 * return
	 */
	public function ContenidoCombo($opciones, $titulo, $valor_defecto) {
		$html = "";
		if(is_array($opciones)) {
			if(!empty($titulo))
				$html .= "<option value=''>".$titulo."</option>";
			foreach($opciones as $k=>$v)
			{
				$html .= "<option value='".$k."'";
				
				if(is_numeric($k))
				{
					if($k==$valor_defecto && is_numeric($valor_defecto)) 
						$html .= " selected"; 
				}
				else
				{
					if($k==$valor_defecto) 
						$html .= " selected";
				}
				
				$html .= ">".$v."</option>";
			}
		} else {
			$html = "<option value=\"\">Parametros Invalidos</option>";
		}
		return $html;
	}
	
	public function ContenidoCombo2($opciones, $valor_defecto) {
		$html = "";
		if(is_array($opciones)) {
		$opciones = array_values($opciones);
//			$html .= "<option value=\"9999999999\">Seleccionar...</option>";
			for($i = 0; $i < count($opciones); $i++) {
				if($opciones[$i][0] == $valor_defecto){
					$html .= "<option value=\"".$opciones[$i][0]."\" selected=\"selected\">".$opciones[$i][1]."</option>";
				}else{
					$html .= "<option value=\"".$opciones[$i][0]."\">".$opciones[$i][1]."</option>";	
				}				
			}
		} else {
			$html = "<option value='-1'>Parametros Invalidos</option>";
		}
		return $html;
	}
	
	/**
	 * Ejecuta funciones JavaScript
	 * Tiene como parametro un arreglo
	 * 		Col 0 = Funcion
	 * @param array $valores
	 */
	public function EjecutarFuncion($valores) {
		$script = "Parametro invalido.";
		if(is_array($valores)){
			$valores = array_values($valores);
			$script = ($this->script) ? "\n<script type='text/javascript'>\n" : "";
			$script .= "\n $(document).ready(function(){";
			for($i = 0; $i < count($valores); $i++) {
				$script .= "\t" . $valores[$i][0] . "\n";
			}
			$script .= "});";
			$script .= ($this->script) ? "</script>\n" : "";
		}
		echo $script;
	}
	public function CampoDinero($valores) {
		$script = "Parametro invalido Dinero.";
		if(is_array($valores)){
		$valores = array_values($valores);
		$script = "\n<script>\n
						jQuery(function($) {\n
						";
			for($i = 0; $i < count($valores); $i++) {
				$script .= '$("#'.$valores[$i][0].'").autoNumeric(); ';
			}
			$script .= "});\n
				</script>\n";
			
		}
		//echo "<textarea>".$script."</textarea>";
		echo $script;
	}
	function add_ceros($numero,$ceros) {
	$order_diez = explode(".",$numero); 
	$dif_diez = $ceros - strlen($order_diez[0]); 
	for($m = 0 ; 
	$m < $dif_diez;
	$m++) 
	{ 
	@$insertar_ceros .= 0;
	} 
	return $insertar_ceros .= $numero; 
	}
}
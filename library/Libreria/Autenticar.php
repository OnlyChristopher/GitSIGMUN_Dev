<?php
class Libreria_Autenticar implements Zend_Auth_Adapter_Interface {
	
	private $_username;
	private $_password;
	
	public function __construct($username, $password) {
		$this->_username = $username;
		$this->_password = $password;
	}
	
	/*
	 * @return Zend_Auth_Result|Zend_Auth_Result
	 */
	
	public function authenticate() {
		$parametros[] = array('@buscar',1);
		$parametros[] = array('@parametro',$this->_username);
		$parametros[] = array('@password',$this->_password);
		
		$cn = new Model_DbDatos_Datos();
		$acceso = $cn->ejec_store_procedura_sql('Acceso.sp_LogOut', $parametros);
		
		if (count($acceso)>0){
			if((int) $acceso[0][8] == 1){
			 	
				$login = new Zend_Session_Namespace('login');
				$login->user = $acceso[0][1];	/** Usuario **/
				$login->area = $acceso[0][2];	/** Tipo    **/
				$login->name = $acceso[0][6];	/** Nombre  **/				
                $login->caja = $acceso[0][7];	/** Nro de caja  **/
				$login->perfil = $acceso[0][9];	/** Id Perfil  **/
                $login->narea = $acceso[0][11]; /** Area */
                $login->encargado = $acceso[0][12]; /** Encargado */
				
				$result = new Zend_Auth_Result ( Zend_Auth_Result::SUCCESS, $acceso[0], array ("Ok" ) );
			} else{
				$result = new Zend_Auth_Result ( Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null, array ("Usuario Inhabilitado." ) );
			}
		} else{
			$result = new Zend_Auth_Result ( Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null, array ("Nombre de usuario y/o clave incorrectos." ) );
		}
		
		return $result;
	}
}
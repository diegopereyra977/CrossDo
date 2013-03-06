<?php
//para log en firePHP
require_once("FirePHP.class.php");
require_once("fb.php");
// ------------------

// Script para levantar la notificacion (singleton).
class SessionAttr{
	private static $instancia;
	
	private $contador;
    private $_data = array();
	
	
	private function __construct(){
		if(file_exists("sessionAttr.txt")){
			$this->_data = self::_updateOfFile();
		}else {
			$this->_data;
		}
		Fb::log($this);
	}

	public static function getInstance(){
		
		if (!self::$instancia instanceof self){
			
				self::$instancia = new self;
			
		}
		
		return self::$instancia;
	}
   
	public function setData($data){
		$this->_data = $data;
		self::_saveToFile($data);
	}
	
	public function getData(){
		$this->data = self::_updateOfFile();
		return $this->_data;
	}
	
	private static function _saveToFile($objectToSave){
		file_put_contents("sessionAttr.txt", serialize($objectToSave));
	}
	
	private static function _updateOfFile(){
		$sesFile = file("sessionAttr.txt");
		return unserialize($sesFile[0]);
	}
   
}

?>
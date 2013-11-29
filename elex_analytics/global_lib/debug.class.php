<?php
class debug{
	private $open;
	private $type;
	function __construct(){
		$this->open=false;
		$this->type="html";
	}
	public function setOpen(){
		$this->open=true;
	}
	public function setType($type){
		$this->type=$type;
	}
	function echoDebug($msg){
		if(defined('XA_LOG_RETURN_TYPE') && XA_LOG_RETURN_TYPE=="img") return;
		if($this->open ) {
			if(is_array($msg))var_dump($msg);
			else echo $msg;
			if($this->type=="html")echo "</br>";
			else if($this->type=="file") echo "\n";	
		}
	}
	static private $instance = null;
	static public function singleton(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}
	static public function debug($msg){
		$debug=debug::singleton();
		$debug->echoDebug($msg);
	}
	static public function open(){
		$debug=debug::singleton();
		$debug->open=true;
	}
	static public function close(){
		$debug=debug::singleton();
		$debug->open=false;
	}
}
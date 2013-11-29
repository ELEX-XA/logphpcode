<?php
class LogManager{
	private $ea_start_time;
	function __construct(){
		$this->ea_start_time=microtime(true);
	}
	
//	const $_zlib_encode = "application/x-www-form-urlencoded";
	static private $instance = null;
	static public function singleton(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/*
	 * check all param
	 */
	function checkAllParams(){
		if(!isset($_REQUEST['json'])){
			throw new Exception('json error',10001);
		} 
		$_REQUEST['appid']=strtolower($_REQUEST['appid']);
	}
	function logEnter(){
		$this->checkAllParams();
	}
	function logLeave($type,$msg){
		switch($type){
			case 'php':
				header("xa-api-version:v3");
				$t=sprintf('%.2f ms',(microtime(true) - $this->ea_start_time) * 1000);
				$re['stats']="ok";
				$re['time']=$t;
				$re['message']="";
				echo json_encode ($re);
				break;
			case 'img':
				header("Content-type: image/gif");
				header("Transfer-Encoding:identity");
				header("xa-api-version:v3");
				$str=pack("H*", "4749463839610100010080ff00ffffff0000002c00000000010001000002024401003b");
				$length=strlen($str);
				header("Content-Length:$length");
				echo $str;
				break;
			case 'error':
				header("xa-api-version:v3");
				$t=sprintf('%.2f ms',(microtime(true) - $this->ea_start_time) * 1000);
				$re['stats']="error";
				$re['time']=$t;
				$re['message']=$msg;
				die (json_encode ($re));
				break;
		}
	}
	
	
	/*
	 * use for appid map and check params 
	 */
	static public function enter(){
		$log=LogManager::singleton();
		debug::debug("LogManager enter");
		$log->logEnter();
	}
	static public function leave($type,$msg){
		debug::debug("LogManager leave");
		$log=LogManager::singleton();
		$log->logLeave($type,$msg);
		
	}
	
	
}
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
		if(!isset($_REQUEST['appid']) || $_REQUEST['appid']=="") throw new Exception("appid is not set");
		if(!isset($_REQUEST['uid']) || $_REQUEST['uid']=="" ) throw new Exception("uid is not set");  
		$_REQUEST['appid']=strtolower($_REQUEST['appid']);  
	}
	function logEnter(){
		$this->checkAllParams();
	}
	function buildJson($type,$msg){
		$t=sprintf('%.2f ms',(microtime(true) - $this->ea_start_time) * 1000);
		switch($type){
			case 'php':
				$re['stats']="ok";
				$re['time']=$t;
				$re['message']=$msg;
				break;
			case 'error':
				$re['stats']="error";
				$re['time']=$t;
				$re['message']=$msg;
				break;
		}	
		return $re;
	}
	function changeType($type){
		if(isset($_REQUEST['callback']) && $_REQUEST['callback']!="" && $type!='error'){
			$type="jsonp";
		}
		if(isset($_SERVER['HTTP_ACCEPT']) && start_with($_SERVER['HTTP_ACCEPT'], "image") && $type!='error') {
			$type='img';	
		}
		if(isset($_REQUEST['img'])){
			$type='img';
		}
		return $type;
	}
	function logLeave($type,$msg){
		$re=$this->buildJson($type,$msg);
		$type=$this->changeType($type);
		/**
		 * 不同的返回类型，对应不同的客户端请求的情况。
		 * php 类型：适用于浏览器窗口直接测试；
		 * img 类型：适用于以 <img> 标签形式发起请求，类似google的方式；
		 * jsonp 类型：适用于以jsonp方式调用。
		 */
		switch($type){
			case 'php':
				//header("Content-type: application/json");
				header("Content-type: text/html");
				header("xa-api-version:v4");
				echo json_encode ($re);
				break;
			case 'img':
				header("Content-type: image/gif");
				header("Transfer-Encoding:identity");
				header("xa-api-version:v4");
				$str=pack("H*", "4749463839610100010080ff00ffffff0000002c00000000010001000002024401003b");
				$length=strlen($str);
				header("Content-Length:$length");
				echo $str;
				break;
			case 'error':
				header("xa-api-version:v4");
				
				echo json_encode ($re);
				break;
			case 'jsonp':
				header("xa-api-version:v4");
				header("Content-type: application/javascript");
				echo $_REQUEST['callback']."(".json_encode($re).");";
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
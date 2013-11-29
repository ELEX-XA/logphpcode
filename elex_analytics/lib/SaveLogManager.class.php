<?php
class SaveLogManager{
	
	private $need_check;
	
	function __construct(){
		require_once GLOBAL_LIB_ROOT."/common.php";
		require_once LIB_ROOT_V2."/Expand.class.php";
		require_once LIB_ROOT_V2."/LogContainer.class.php";
		$this->need_check = false;
	}
	static private $instance = null;
	static public function singleton(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	function setNeedCheck($is_need_check){
		$this->need_check=$is_need_check;
	}
	function getNeedCheck(){
		return $this->need_check;
	}
	static public function openCheck(){
		$save=SaveLogManager::singleton();
		$save->setNeedCheck(true);
	}
	/*
	 * params need dir appid 
	 * return the save file
	 */
	function getSaveFile($params){
		if(!isset($params['appid'],$params['dir']))
			throw $e=new Exception("open file params is not enough");
		if(!AppidChecker::checkV3($params['appid']))
			throw $e=new Exception("get file with worng appid");
		//echo $params['site_id'].$params['Y'].$params['m'].$params['d'].$params['index'];
		$hour=intval(date('H'));
		$minute=intval(date('i'));
		$index=$hour*12+(int)($minute/5);
		
		$temp=sprintf("%s/%s/%04s/%02s/ea_data_%02s/%s.log",
			$params['dir'],$params['appid'],date('Y'),date('m'),date('d'),$index);
		$filepath=APP_ROOT.'/'.$temp;
		return $filepath;
	}
	/*
	 * get the base log msg from 6 filed
	 */
	function getBaseLog(){		
		if(!isset($_REQUEST['appid'])
			||!isset($_REQUEST['event'])
			||!isset($_REQUEST['uid'])) throw new Exception("no appid or event or uid");
		if(isset($_REQUEST['timestamp']))
			$timestamp = $_REQUEST['timestamp'];
		else 
			$timestamp = $_SERVER['REQUEST_TIME'];
		$log=$_REQUEST['appid']."\t".$_REQUEST['uid']."\t".$_REQUEST['ref'].
			"\t".$_REQUEST['event']."\t".$_REQUEST['json_var']."\t".$timestamp."\n";
		
		if ($_REQUEST ['event'] === "user.visit") {
			$ip = getIP ();
			if ($ip != false) {
				$log = $log . $_REQUEST ['appid'] . "\t" . $_REQUEST ['uid'] . "\t" . '' . "\t" . "user.update" . "\t" . '{"geoip":"' . $ip . '"}' . "\t" . $timestamp . "\n";
			}
		}
		return $log;
	}
	function getBasePackageLog($log_ht){
		$uid = $log_ht['uid'];
		$event = $log_ht['event'];
		$json_ht = $log_ht['json_var'];
		$ref = $log_ht['ref'];
		if(!$log_ht['timestamp']){
			$timestamp = $_SERVER['REQUEST_TIME'];
		}
		else{
			$timestamp = $log_ht['timestamp'];
		}
		if($json_ht == null)
			$json_var = "";
		else	
			$json_var = json_encode($json_ht);
		$log=$_REQUEST['appid']."\t".$uid."\t".$ref.
			"\t".$event."\t".$json_var."\t".$timestamp."\n";
		return $log;
	}
	/*
	 * get the store log from log filed
	 */
	function getStoreLog(){
		if(!isset($_REQUEST['appid']))throw new Exception("no appid");
		if(!isset($_REQUEST['log']))throw new Exception("no log");
		$log = $this->addAppidForStoreLog($_REQUEST['log']);
		return $log."\n";
	}
	
	/*
	 * save base 6 filed log
	 * index 计算为小时数*12+分钟数/5,就是指出在一天中的第几个5分钟时段，从0开始
	 * dir structure: yyyy/mm/ea_data_day/index.log
	 */
	function saveBaseLog(){
		debug::debug("enter saveBaseLog");

		$logmsg = $this->getBaseLog();
		$file_params=array('appid'=>$_REQUEST['appid'],'dir'=>"site_data");
		$log_file=$this->getSaveFile($file_params);
		ea_write_log($log_file,$logmsg);
		
		
		//expand the pay complete
		//when revice the pay compete write the count log for vamonut
		
		$file_params=array('appid'=>$_REQUEST['appid'],'dir'=>"store_log");
		$log_file=$this->getSaveFile($file_params);
		Expand::expandPay($log_file);
	
		
	}
	function saveBasePackageLog(){
		debug::debug("enter saveBasePackageLog");

		$appid = $_REQUEST['appid'];
		if(isset($_REQUEST['logs'])){
			$_REQUEST['log']=$_REQUEST['logs'];
		}
		$logs_str = $_REQUEST['log'];
		$logs_array = json_decode($logs_str,true);
		if($logs_array==null){
			throw new Exception("json format is illegal"); 
		}
		foreach ($logs_array as $log_ht){
			$file_params=array('appid'=>$_REQUEST['appid'],'dir'=>"site_data");
			$log_file=$this->getSaveFile($file_params);
			$logmsg = $this->getBasePackageLog($log_ht);
			ea_write_log($log_file,$logmsg);
//			if(strcmp($log_ht['event'],"user.visit") == 0 || strcmp($log_ht['event'],"user.update") == 0)
//				$this->checkVersion();
		}		
	}
	function addAppidForStoreLog($log) {
		$log_ht = json_decode($log, true);
		if($log_ht==null){
			throw new Exception("json format is illegal"); 
		}
		$signedParams = $log_ht['signedParams'];
		if($signedParams['appid'] == null)
			$signedParams['appid'] = $_REQUEST['appid'];
		if(array_key_exists('sns_id', $signedParams))
			unset($signedParams['sns_id']);
		if(array_key_exists('sign', $signedParams))
			unset($signedParams['sign']);	
		if(array_key_exists('uid', $signedParams))
			unset($signedParams['uid']);
		$log_ht['signedParams'] = $signedParams;
		return json_encode($log_ht);
	}
	
	/*
	 * save store log
	 * index 计算为小时数*12+分钟数/5,就是指出在一天中的第几个5分钟时段，从0开始
	 * dir structure: yyyy/mm/ea_data_day/index.log
	 */
	function saveStoreLog(){

		$logmsg = $this->getStoreLog();
		$file_params=array('appid'=>$_REQUEST['appid'],'dir'=>"store_log");
		$log_file=$this->getSaveFile($file_params);
		ea_write_log($log_file,$logmsg);
	}
	//*************************************************************************************
	
	
	/*
	 * read from json and assign base log and store log
	 */
	function saveAllLog(){
		

		$log = json_decode($_REQUEST['json'], true);
		$logContainer=new LogContainer($log);
		
		$file_params['appid']=$logContainer->getAppid();
		$file_params['dir']='site_data';
		$msg=$logContainer->getBaseLog();
		if($msg!=null){
			$log_file=$this->getSaveFile($file_params);
			ea_write_log($log_file,$msg);
		}
		$file_params['dir']='store_log';
		$msg=$logContainer->getStoreLog();
		if($msg!=null){
			$log_file=$this->getSaveFile($file_params);
			ea_write_log($log_file,$msg);
		}
	}
	/*
	 * save the log
	 */
	static public function saveLog($type){
		debug::debug("saveLog ".$type);
		$save=SaveLogManager::singleton();
		switch($type){
			case 'base':
				if($save->getNeedCheck()) {
					$save->checkBaseLog();
				}
				else $save->saveBaseLog();
				break;
			case 'base_package':
				$save->saveBasePackageLog();
				break;
			case 'store':
				if($save->getNeedCheck()) {
					$save->checkStoreLog();
				}
				else $save->saveStoreLog();
				break;
			case 'all':
				if($save->getNeedCheck()) {
					$save->checkAllLog();
				}
				else $save->saveAllLog();
				break;
				
		}
	}
	
	function checkBaseLog() {
		if(!isset($_REQUEST['uid']) || $_REQUEST['uid'] == '' || !isset($_REQUEST['appid']) || $_REQUEST['appid'] == '' 
			|| !isset($_REQUEST['event']) || $_REQUEST['event'] == '') {
			throw  new Exception("appid uid or event is missing !");
		}
		$event = $_REQUEST['event'];
		if(!in_array($event, LogManager::$base_event)){
			echo "this event may not allow !";
		}
		if($_REQUEST['json_var'] != null) {
			$log_ht = json_decode($_REQUEST['json_var'], true);
			if($log_ht == null){
				new Exception("json_var can't match json format!");
			}
		}
		echo "ok";
		return true;
	}
	
	function checkStoreLog() {
		if(!isset($_REQUEST['appid']) || $_REQUEST['appid'] == '' || !isset($_REQUEST['log']) || $_REQUEST['log'] == ''){
			throw  new Exception("appid or log is missing !");
		}
		$log_str = $_REQUEST['log'];
		$logs_ht = json_decode($log_str,true);
		if($logs_ht == null){
			throw  new Exception("log can't match json format!");
		}
		if(!isset($logs_ht['signedParams']['sns_uid'])){
			throw  new Exception("sns_uid in signedParams is missing!");
		}
		if(!isset($logs_ht['signedParams']['appid'])){
			throw  new Exception("appid in signedParams is missing!");
		}
		if(!isset($logs_ht['stats'])){
			throw  new Exception("stats field is missing!");
		}
	    $stats_array = $logs_ht['stats'];
	    foreach($stats_array as $stat) {
	    	$statfunction = $stat['statfunction'];
	    	if(!isset($stat['statfunction'])){
	    		throw  new Exception("the statfunction is missing");
	    	}
	    	if(!in_array($statfunction, LogManager::$store_event)){
	    		echo "the statfunction may not allow!";
	    	}
	    }
	    echo "ok";
	    return true;
	}
	function checkAllLog(){
		$logs_ht = json_decode($_REQUEST['json'],true);
		if($logs_ht == null){
			throw  new Exception("json field can't match json format!");
		}
		if(!isset($logs_ht['signedParams']['uid'])){
			throw  new Exception("uid in signedParams is missing!");
		}
		if(!isset($logs_ht['signedParams']['appid'])){
			throw  new Exception("appid in signedParams is missing!");
		}
		if(!AppidChecker::check($logs_ht['signedParams']['appid'])){
			throw  new Exception("appid is illegal!");
		}
		if(!isset($logs_ht['stats'])){
			throw  new Exception("stats field is missing!");
		}
	    $stats_array = $logs_ht['stats'];
	    foreach($stats_array as $stat) {
	    	if(!isset($stat['eventName'])){
	    		throw  new Exception("the eventName in stats is missing");
	    	}
	    	if(isset($stat['eventName'])) $event = $stat['eventName'];
	    	if(!in_array($event, LogManager::$store_event) && !in_array($event, LogManager::$base_event)){
	    		echo "the eventName ".$event." may not allow! ";
	    	}
	    }
	    echo "ok";
	    return true;
	}
	
}
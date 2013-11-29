<?php
class SaveLogManager{
	
	function __construct(){
		require_once GLOBAL_LIB_ROOT. '/common.php';
		require_once LIB_ROOT_V4. '/LogContainer.class.php';
	}
	static private $instance = null;
	static public function singleton(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/*
	 * params need dir appid 
	 * return the save file
	 */
	function getSaveFile($params){
		if(!isset($params['appid'],$params['dir']))
			throw $e=new Exception("open file params is not enough");
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
	 * read from json and assign base log and store log
	 */
	function writeLog(){
		$logContainer=new LogContainer();
		$ret = $this->writeLogFromLogContainer($logContainer);
		//special delivery for v9
		$old_appid=$logContainer->getAppid();
		if(substr($old_appid, 0,3)=="v9-" && !($old_appid == "v9-v9")){
			require_once APP_ROOT."/v9_transforms.php";
			$logContainer->setAppid("v9-v9");
			$ret = $this->writeLogFromLogContainer($logContainer);
		}
		return $ret;
	}
	
	function writeLogFromLogContainer($logContainer){
		$file_params['appid']=$logContainer->getAppid();
		$file_params['dir']='site_data';
		$logNum=0;$updateNum=0;
		$msg=$logContainer->getBaseLog($logNum,$updateNum);
		if($msg!=null){
			$log_file=$this->getSaveFile($file_params);
			ea_write_log($log_file,$msg);
		}
		$file_params['dir']='store_log';
		$msg=$logContainer->getStoreLog($logNum,$updateNum);
		if($msg!=null){
			$log_file=$this->getSaveFile($file_params);
			ea_write_log($log_file,$msg);
		}	
		return "store ".$logNum." action and ".$updateNum." update ";
	}
	/*
	 * save the log
	 */
	static public function saveLog(){
		debug::debug("saveLog ");
		
		$save=SaveLogManager::singleton();
		return $save->writeLog();
	}
	
}
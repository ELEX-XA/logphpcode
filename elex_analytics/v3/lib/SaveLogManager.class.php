<?php
class SaveLogManager{
	
	private $need_check;
	
	function __construct(){
		require_once GLOBAL_LIB_ROOT. '/common.php';
		require_once LIB_ROOT_V3. '/LogContainer.class.php';
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
		
		$log = json_decode($_REQUEST['json'], true);
		if($log==null) throw new Exception("json format is error");
		$logContainer=new LogContainer($log);
		
		$this->writeLogFromLogContainer($logContainer);
		$old_appid=$logContainer->getAppid();
		if(substr($old_appid, 0,3)=="v9-" && !($old_appid == "v9-v9")){
			require_once APP_ROOT."/v9_transforms.php";
			$ref=substr($old_appid,3);
			
			$logContainer->setAppid("v9-v9");
			$log['eventName']="update";
			$log['params']['ref']=$ref;
			$logContainer->addLog($log);
			$this->writeLogFromLogContainer($logContainer);
			
		}
	}
	
	function writeLogFromLogContainer($logContainer){
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
	static public function saveLog(){
		debug::debug("saveLog ");
		$save=SaveLogManager::singleton();
		
		if($save->getNeedCheck()) {
			$save->checkLog();
		}
		else $save->writeLog();

	}
	
	function checkLog(){
	    return true;
	}
	
}
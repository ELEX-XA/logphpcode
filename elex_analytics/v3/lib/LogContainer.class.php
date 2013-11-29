<?php
class LogContainer{
	private $baseMsg;
	private $stats;
	private $appid;
	private $uid;
	private $send_time;
	public static $base_event = array(
	'visit'=>"user.visit",
	'pay'=>"pay.complete",
	'heartbeat'=>"user.heartbeat",
	'quit'=>"user.quit",
	'update'=>"user.update",
	);
	private function getType($event){
		if(isset(LogContainer::$base_event[$event])){
			return 0;
		}
		return 1;
	}
	
	public function __construct($log){
		require_once GLOBAL_LIB_ROOT."/AppidChecker.class.php";
		if($log==null){
			throw new Exception("save all json format is illegal");
		}
        $this->appid=$log['signedParams']['appid']; 
        $this->appid = strtolower($this->appid); 
		if(!AppidChecker::checkV3($this->appid)){
			throw new Exception("appid is illegal");
		}
		//add gdp hook
		
		$this->uid=gdpUidHook($log['signedParams']['uid']);
		
		if(isset($log['signedParams']['timestamp'])){
			$this->send_time=$log['signedParams']['timestamp'];
		}
		else{
			$this->send_time=millisec();
		}
		to_millisec($this->send_time);
		foreach($log['stats'] as $slog){
			$this->addLog($slog);
		}
	}
	public function addLog($slog){
		$eventName=explode(".",$slog['eventName']);
		if(isset(LogContainer::$base_event[$slog['eventName']])){
			//save base log
			$event=LogContainer::$base_event[$slog['eventName']];
			//get json_var
			if(isset($slog['params'])){
				$json_var=$slog['params'];
			}
			else $json_var=array();
			
			//get ref
			if(isset($json_var['ref'])){
				$ref=$json_var['ref'];
			}
			else $ref=null;
			
			//get timestamp
			if(isset($slog['timestamp'])){
				to_millisec($slog['timestamp']);
				$nowtime=millisec()-($this->send_time-$slog['timestamp']);
			}
			else{
				$nowtime=millisec();
			}
			
			//pay add the gross gcurrency
			if($event==='pay.complete'){
//				$json_var['gcurrency']=$eventName[1];
				$json_var['gross']=$slog['value'];
			}
			else if($event==='user.quit'){
				$json_var['duration_time']=$slog['value'];
			}
			
			
			if($json_var!=null && count($json_var)!=0) $json_var=json_encode($json_var);
			else $json_var=null;
			
			$this->baseMsg[]=sprintf("%s\t%s\t%s\t%s\t%s",$this->uid,$ref,$event,$json_var,$nowtime);
			if($event === 'user.visit'){
				$ip = getIp() ;
				if($ip != false)
					$this->baseMsg[] = sprintf("%s\t%s\t%s\t%s\t%s",$this->uid,$ref,"user.update",'{"geoip":"'.$ip.'"}',$nowtime);
			}
		}
		else{
			if(isset($slog['timestamp'])){
				to_millisec($slog['timestamp']);
				$nowtime=millisec()-($this->send_time-$slog['timestamp']);
			}
			else{
				$nowtime=millisec();
			}
			$newlog['timestamp']=$nowtime;
			$add=false;
			for($i=0;$i<=5;$i++){
				if(isset($eventName[$i])){
					$add=true;
					$newlog['data'][$i]=$eventName[$i];
				}	
				else $newlog['data'][$i]="";
			}
			if(isset($slog['value'])){
				$newlog['data'][6]=$slog['value'];	
			}
			else $newlog['data'][6]=0;
			$newlog['statfunction']='count';
			
			// 丢弃 xa.geoip
			if($newlog['data'][0] == "xa" && $newlog['data'][1] == "geoip")
				$add = false;
			
			if($add)$this->stats[]=$newlog;
			if($newlog['data'][0] == "visit" || ($newlog['data'][0] == "xa" && $newlog['data'][1] == "geoip")){
				$ip = getIp() ;
				if($ip != false)
					$this->baseMsg[] = sprintf("%s\t%s\t%s\t%s\t%s",$this->uid,$ref,"user.update",'{"geoip":"'.$ip.'"}',$nowtime);
			}
		}
	}
	public function getBaseLog(){
		$re="";
		foreach($this->baseMsg as $msg ){
			$re=$re.$this->appid."\t".$msg."\n";	
		}
		return $re;
	}
	public function getStoreLog(){
		require_once GLOBAL_LIB_ROOT."/AppidMatch.class.php";
		if($this->stats==null) return null;
		$log['signedParams']['appid']=AppidMatch::match($this->appid);
		$log['signedParams']['uid']=$this->uid;
		$log['stats']=$this->stats;
		return json_encode($log)."\n";
	}
	public function getAppid(){
		return $this->appid;
	}
	public function setAppid($appid){
		$this->appid=$appid;
	}
	public function getUid(){
		return $this->uid;
	}
}
<?php
class LogContainer{
	private $baseMsg;
	private $stats;
	private $appid;
	private $uid;
	private $send_time;
	public function __construct($log){
		if($log==null){
			throw new Exception("save all json format is illegal");
		}
		$this->appid=$log['signedParams']['appid'];
		if(!AppidChecker::check($this->appid)){
			throw new Exception("appid is illegal");
		}
		$this->uid=$log['signedParams']['uid'];
		if(isset($log['signedParams']['timestamp'])){
			$this->send_time=$log['signedParams']['timestamp'];
		}
		else{
			$this->send_time=millisec();
		}
		to_millisec($this->send_time);
		foreach($log['stats'] as $slog){
			if(isset($slog['name'])){
				$slog['eventName']=$slog['name'];
				unset($slog['name']);	
			}
			if(isset($slog['data'])){
				$slog['params']=$slog['data'];
				unset($slog['data']);	
			}
			
			$slog['eventName']=$this->resloveAllEventName($slog['eventName']);
			
			$type=EventType::getType($slog['eventName']);
			if($type==0){
				$this->addBaseLog($slog);
			}
			else if($type==1){
				$this->addStoreLog($slog);
			}	
		}
	}
	
	private static $match=array("buy.item"=>"buyitem");
	private function resloveAllEventName($eventName){
		if(isset(LogContainer::$match[$eventName])){
			return LogContainer::$match[$eventName];
		}	
		else return $eventName;
	}
	
	private function addBaseLog($slog){
		$json_var=$slog['params'];
		if(isset($json_var['ref'])){
			$ref=$json_var['ref'];
			unset($json_var['ref']);
		}
		if(isset($slog['timestamp'])){
			to_millisec($slog['timestamp']);
			$nowtime=millisec()-($this->send_time-$slog['timestamp']);
		}
		else{
			$nowtime=millisec();
		}
		if($json_var!=null && count($json_var)!=0) $json_var=json_encode($json_var);
		else $json_var=null;
		$this->baseMsg.=sprintf("%s\t%s\t%s\t%s\t%s\t%s\n",
			$this->appid,$this->uid,$ref,$slog['eventName'],$json_var,$nowtime);
		if($slog['eventName'] === "user.visit"){
			$ip = getIP() ;
			if($ip != false)
				$this->baseMsg.= sprintf("%s\t%s\t%s\t%s\t%s",$this->uid,$ref,"user.update",'{"geoip":"'.$ip.'"}',$nowtime);
		}
	}
	
	private function addStoreLog($slog){
		if(isset($slog['timestamp'])){
			to_millisec($slog['timestamp']);
			$nowtime=millisec()-($this->send_time-$slog['timestamp']);
		}
		else{
			$nowtime=millisec();
		}
		$newlog['timestamp']=$nowtime;
		$newlog['data']=$slog['params'];
		$newlog['statfunction']=$slog['eventName'];	
		// 丢弃 xa.geoip
		if($slog['params'][0] != 'xa' || $slog['params'][1] != 'geoip')
			$this->stats[]=$newlog;
		// visit & xa.geoip 更新 geoip
		if($slog['params'][0] === "visit" || ( $slog['params'][0] === "xa" && $slog['params'][1] === "geoip" )){
			$ip = getIP() ;
			if($ip != false)
				$this->baseMsg.= sprintf("%s\t%s\t%s\t%s\t%s",$this->uid,$ref,"user.update",'{"geoip":"'.$ip.'"}',$nowtime);
		}
	}
	public function getBaseLog(){
		return $this->baseMsg;
	}
	public function getStoreLog(){
		if($this->stats==null) return null;
		$log['signedParams']['appid']=$this->appid;
		$log['signedParams']['uid']=$this->uid;
		$log['stats']=$this->stats;
		return json_encode($log)."\n";
	}
	public function getAppid(){
		return $this->appid;
	}
	public function getUid(){
		return $this->uid;
	}
}
class EventType{
	static public $indexlog_event=array(
	"user.visit"=>1,
	"user.update"=>1,
	"user.increment"=>1,
	"user.login"=>1,
	"page.view"=>1,
	);
	static public $storelog_event=array(
	"count"=>1,
	"buy.item"=>1,
	"milestone"=>1,
	"tutorial"=>1,
	"buyitem"=>1,
	);
	
	static public function getType($name){
		if(isset(self::$storelog_event[$name])){
			return 1;
		}
		else return 0;
	}
}
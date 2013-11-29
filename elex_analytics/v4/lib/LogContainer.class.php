<?php
class LogContainer{
	private $baseMsg;
	private $visitMsg;
	private $stats;
	private $update;
	private $appid;
	private $uid;
	//all timestamp represented in milliseconds
	private $send_time;
	private $abs_time;//absolute timestamp, bypass $send_time and timestamps in individual events
	private $ref;
	public static $base_event = array(
	'visit'=>"user.visit",
	'pay'=>"pay.complete",
	'heartbeat'=>"user.heartbeat",
	'quit'=>"user.quit",
	'update'=>"user.update",
	);
	
	public function __construct($log){
		require_once GLOBAL_LIB_ROOT."/AppidChecker.class.php";   
	     
		$this->appid=$_REQUEST['appid'];
		if(!AppidChecker::checkV3($this->appid)){
			throw new Exception("appid is illegal");
		}
		
		//add gdp hook
		
		$this->uid=gdpUidHook($_REQUEST['uid']);
		
		if(isset($_REQUEST['timestamp'])){
			$this->send_time=$_REQUEST['timestamp'];
		}
		else{
			$this->send_time=0;
		}
		if(isset($_REQUEST['abs_ts'])){
			$this->abs_time = $_REQUEST['abs_ts'];
		}else{
			$this->abs_time = null;
		}
		to_millisec($this->abs_time);
		to_millisec($this->send_time);
		foreach($_REQUEST as $key=>$value){
			if(start_with($key, "action")){
				$this->addAction($value);			
			}
			if(start_with($key, "update")){
				$this->addUpdate($value);
			}
		}
	}
	public function addUpdate($triple){
		$t=explode(",",$triple,2);
		$property=$t[0];
		$val=$t[1];
		$this->update[$property]=$val;	
		//add ref field for ad system
		//format:appid uid ref user.visit {} timestamp
		if($property=="ref"){
			$this->ref=$val;		
		}
	}
	
	/**
	 * 获得log最终的发生时间。若果有abs_ts参数，则按照绝对时间；
	 * 如果是：
	 * 	http://xa.xingcloud.com/v4/proj/uid?action=a,,xxx
	 * 则按照xxx算每个action的时间。
	 * 如果是：
	 * 	http://xa.xingcloud.com/v4/proj/uid?timestamp=xxx&action1=a&action2=b
	 * 则每个action都按照timestamp的时间。
	 * 如果是：
	 * 	http://xa.xingcloud.com/v4/proj/uid?timestamp=xxx&action=a,,yyy
	 * 即有对一个action有两个时间戳，则视timestamp为客户端认为的当前时间，以此决定action的实际发生时间。
	 * 
	 * @param number $timestamp 
	 * @return number 
	 */
	private function getLogTime($timestamp){
		//get timestamp
		if($this->abs_time){// abs_time superb
			$nowtime = $this->abs_time;
		}else if($timestamp != 0){
				
			if($this->send_time != 0){ // ?timestamp=xxx&action=a,,yyy
				$nowtime = millisec() - ($this->send_time-$timestamp);
			}else{//	?action=a,,xxx
				$nowtime = $timestamp;
			}
		}else{
			if($this->send_time != 0){	// ?timestamp=xxx&action=a
				$nowtime = $this->send_time;
			}else{	// ?action=a
				$nowtime = millisec();
			}
		}
		return $nowtime;		
	}
	
	public function addAction($triple){
		$t=explode(",",$triple);
		$event=$t[0];
		$value=$t[1];
		$timestamp=$t[2];
		//normalize time representation
		if($timestamp != 0){
			to_millisec($timestamp);
		}
		$nowtime = $this->getLogTime($timestamp);
		//第四个参数，days
		if(count($t) >=4){
			$days = intval($t[3]);
		}else{
			$days = 1;
		}
		if($days>1 && $days <= 100){
			$value=intval($t[1]);
			$day_value = intval($value / $days);
			$first_day_value = $day_value + intval($value % $days);
			$this->addAction("$event,$first_day_value,$nowtime");
			for($i=1;$i<$days;$i++){
				$nowtime += 86400000;
				$this->addAction("$event,$day_value,$nowtime");
			}
			return;
		}
		if(isset(LogContainer::$base_event[$event])){
			
			//pay add the gross gcurrency
			if($event==='pay'){
				$json_var['gcurrency']="USD";
				$json_var['gross']=$value;
			}
			else if($event==='quit'){
				$json_var['duration_time']=$value;
			}
			if(count($json_var)!=0)$json_var=json_encode($json_var);
			else $json_var="";
			
			//add ref field for ad system
			if(LogContainer::$base_event[$event]!="user.visit"){
				$this->baseMsg[]=sprintf("%s\t%s\t%s\t%s\t%s",$this->uid,$ref,LogContainer::$base_event[$event],$json_var,$nowtime);				
			}
			else{
				$visitRecord[uid]=$this->uid;
				$visitRecord[timestamp]=$nowtime;
				$this->visitMsg[]=$visitRecord;
				$ip = getIP();
				if($ip != false)
					$this->baseMsg[]=sprintf("%s\t%s\t%s\t%s\t%s",$this->uid,$ref,"user.update",'{"geoip":"'.$ip.'"}',$nowtime);
			}
			
		}
		else{
						
			$newlog['timestamp']=$nowtime;
			$eventArray=explode(".", $event);
			for($i=0;$i<=5;$i++){
				if(isset($eventArray[$i]))$newlog['data'][$i]=$eventArray[$i];	
				else $newlog['data'][$i]="";
			}
			$newlog['data'][6]=intval($value);
			$newlog['statfunction']='count';
			// 丢弃xa.geoip
			if($newlog['data'][0] != "xa" || $newlog['data'][1] != "geoip")
				$this->stats[]=$newlog;
			
			if($newlog['data'][0] == "visit" || ($newlog['data'][0] == "xa" && $newlog['data'][1] == "geoip" )){
				$ip = getIP() ;
				if($ip != false)
					$this->baseMsg[]=sprintf("%s\t%s\t%s\t%s\t%s",$this->uid,$ref,"user.update",'{"geoip":"'.$ip.'"}',$nowtime);
			}
		}
	}
	
	public function getUpdateLog(&$re,&$updateNum){
		$updateNum+=count($this->update);
		if($updateNum!=0){
			$re=$re.sprintf("%s\t%s\t%s\t%s\t%s\t%s",$this->appid,$this->uid,"","user.update",json_encode($this->update),time())."\n";	
		}
	}
	
	public function getBaseLog(&$logNum,&$updateNum){
		$re="";
		foreach($this->visitMsg as $visitRecord ){
			$msg=sprintf("%s\t%s\t%s\t%s\t%s\t%s\n",
				$this->appid,$visitRecord[uid],$this->ref,"user.visit","",$visitRecord[timestamp]);
			$re.=$msg;
			$logNum++;	
		}
		foreach($this->baseMsg as $msg ){
			$re=$re.$this->appid."\t".$msg."\n";
			$logNum++;	
		}
		$this->getUpdateLog($re,$updateNum);
		return $re;
	}
	public function getStoreLog(&$logNum){
		require_once GLOBAL_LIB_ROOT."/AppidMatch.class.php";
		if($this->stats==null) return null;
		$log['signedParams']['appid']=AppidMatch::match($this->appid);
		$log['signedParams']['uid']=$this->uid;
		$log['stats']=$this->stats;
		$logNum+=count($this->stats);
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

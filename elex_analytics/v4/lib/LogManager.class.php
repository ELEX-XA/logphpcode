<?php
class LogManager{
	private $ea_start_time;
	private $appidArr;
	private $internet1;
	/*
     * 项目wuzijing作测试用
     */
	function __construct(){
		$this->ea_start_time=microtime(true);
		$this->appidArr = array(
		    "xa_damo","22apple","22find","ttsgames","aartemis","awesomehp","cok_337","delta-homes","sof-dp","sof-dsk","l","dosearches","sof-hpprotect","sof-everything","fishao",
		    "gdp","sof-gdp","sof-seed","sof-ss","v9-gp","hot-finder","ie-lightning-speed","sof-ient","sof-isafe","isearch123","istart123","istartpageing","istartsurf",
		    "key-find","lightning-newtab","lightning-speedial","lightningnt","lightning-speed-dial","luckybeginning","luckysearches","sof-macinstaller","myoivu",
		    "mystartsearch","myv9","nationzoom","newgag","newgdppop","newtab-all","newtab2","internet-3","ordt","omiga-plus","omniboxes","oursurfing","sof-picexa-dl",
		    "sof-px","portaldosites","qone8","qone8search","qvo6","raydownload","safehomepage","sof-pbd-dl","sof-wzp-dl","sof-yacbndl","sof-zbd-dl","sweet-page","v9",
		    "v9m","v9search","vi-view","wartune-en","web337","webssearches","sof-zip","sof-wzpdl","sof-wpm","sof-wxz","www-337-com","lightningnewtab","yac-error-page",
		    "xa-xbb","yac-newdl","sof-yacnvd","yoursearching","websupport","shenqu","maomaomei","kjsg","xzqz","livepoolpro","desertoperations","wargame1942","generalsofwar",
		    "monkeyking","darkorbit","loa","myfreezoo","mlf","farmerama","drakensang","piratestorm","guardiaoonline","dragon-pals","hog","cuponkit","cuponkit-ext","unnamedsoft",
		    "unsoftnvd","internet-1","internet-2","internet","chhp-unistallmaster","chhp-myoivu","prote-ff-extension","sof-installer","sof-newgdppop","qtype","qtyper",
		    "quick-sidebar","quick-start","searchprotect","usv9","jiggybonga","xlfc","xlfc-cbnc","yzzt","csbhtw","kszl","ddt","gcld","gcld","gs","age","age2","agei","agei2",
		    "aoerts","ram","ba2","cok","cokfb","happyfarm","coktw","cokmi","thor","rafo","firefox-searchengine","gggggg","do-search","wuzijing","elex337","minigames337",
		    "pay337","337admin","ddten","web337vip","unextnvd","sof-mcassist","ggggggsite","mysites123","webpageing","yoursites123","istartpage123","surfpageing","mysurfing123",
		    "sof-filecheck","ffffff","chroomium","crxbro","datazip","gggggg2"
		);
		$this->internet1 = array(
		    "webssearches","key-find","awesomehp","sweet-page","v9","do-search","aartemis","omiga-plus","qone8","dosearches","delta-homes","22apple","22find","qvo6","portaldosites",
		    "usv9","nationzoom","istart123","vi-view","istartsurf","mystartsearch","omniboxes","luckysearches","oursurfing","isearch123"
		);
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
		if(!in_array(strtolower($_REQUEST['appid']),$this->appidArr)) throw new Exception("appid invalid");
		if(!isset($_REQUEST['uid']) || $_REQUEST['uid']=="" ) throw new Exception("uid is not set");
		if(in_array(strtolower($_REQUEST['appid']),$this->internet1) && preg_match('/^([0-9]+_[0-9]+_)|(^[0-9]+$)/',$_REQUEST['uid'],$b) ) throw new Exception("uid is not right");
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
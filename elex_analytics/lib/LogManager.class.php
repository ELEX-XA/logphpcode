<?php
class LogManager{
	private $ea_start_time;
	function __construct(){
		$this->ea_start_time=microtime(true);
		require_once GLOBAL_LIB_ROOT. '/AppidChecker.class.php';
	}
	private $appid_map_array=array(
	'rome0@elex337_pt_1'=>'rome0@337_pt_1',
	'rome0@orkupt_pt_2'=>'rome0@elex337_pt_2',
	'rome0@orkupt_pt_1'=>'rome0@337_pt_1',
	'hl@elex337_pt_1'=>'hl@337_pt_1',
	'kszl@elex337_de_1'=>'kszl@337_de_s1',
	'kszl@elex337_pt_4'=>'kszl@337_pt_s4',
	'kszl@elex337_tr_1'=>'kszl@337_tr_s1',
	'tencent-18894@facebook_tw'=>'tencent-18894',
	'age@337_en_andriod.global.s2'=>'age@337_en_android.global.s2',
	);

	public static $base_event = array(
	'user.visit',
	'user.update',
	'user.inc',
	'user.login',
	'user.heartbeat',
	'user.error',
	'user.quit',
	'pay.visit',
	'pay.visitc',
	'pay.complete',
	'page.visit'
	);
	
	public static $store_event = array(
	'count',
	'milestone',
	'tutorial',
	'buyitem',
	);
	
	static private $instance = null;
	static public function singleton(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}
	function appidmap(){
		require_once GLOBAL_LIB_ROOT."/AppidMatch.class.php";
		$_REQUEST['appid']=AppidMatch::match($_REQUEST['appid']);
	}
	
	/*
	 * check one param
	 */
	function check($filed,$param){
	        if($filed=="appid"){
	                if(!AppidChecker::checkV3($param)) return false;	                
	        }
	        return true;
	}
	/*
	 * check all param
	 */
	function checkAllParams(){
		if(isset($_REQUEST['appid'][100]) || isset($ref[1000]) || isset($_REQUEST['uid'][200]) ){
			throw new Exception('paramater length error',10001);
		}
		if(isset($_REQUEST['ref']))$_REQUEST['ref']=substr($_REQUEST['ref'], 0,990);
		
		if(!$this->check("appid",$_REQUEST['appid']) && !isset($_REQUEST['json'])){
			//ea_write_log(APP_ROOT."/error_log/".date('Y-m-d').".log", "error_appid=".$_REQUEST['appid']." ".$_REQUEST['event']." ".$_REQUEST['logs']." \n");
			throw new Exception('appid error',10001);
		}
		
		$_REQUEST['appid']=strtolower($_REQUEST['appid']);
		$this->appidmap();
	}
	
	function logAnalytics(){
		if($_REQUEST['ref']=="xafrom=br;ddt;g;s;Search-Ddtank;ddtank2.3"
		){
			ea_write_log(APP_ROOT."/error_log/".$_REQUEST['ref'].date('Y-m-d').".log", 
				$_REQUEST['appid']."\t".$_REQUEST['uid']."\t".$_REQUEST['ref']."\t".$_REQUEST['event']." \n");	
		}
		if( start_with($_REQUEST['ref'],"xafrom=th;citylife@facebook;f;elex2") )	
		{
			ea_write_log(APP_ROOT."/error_log/citylife".date('Y-m-d').".log", 
				$_REQUEST['appid']." ".$_REQUEST['uid']." ".$_REQUEST['event']." ".$_REQUEST['ref']." \n");	
		}
		
	}
	function logEnter(){
//		$this->checkZlibStr();
		$this->logAnalytics();
		$this->checkAllParams();
	}
	function logLeave($type,$msg){
		switch($type){
			case 'php':
				header("xa-api-version:v2");
				if($msg !=null && $msg!=""){
					echo $msg;
					die();
				}
				else printf('/* %.1f ms*/',(microtime(true) - $this->ea_start_time) * 1000);
				break;
			case 'img':
				header("Content-type: image/gif");
				header("Transfer-Encoding:identity");
				header("xa-api-version:v2");
				$str=pack("H*", "4749463839610100010080ff00ffffff0000002c00000000010001000002024401003b");
				$length=strlen($str);
				header("Content-Length:$length");
				echo $str;
				break;
			case 'new_return':
				
				header("xa-api-version:v3");
				$t=sprintf('%.2f ms',(microtime(true) - $this->ea_start_time) * 1000);
				if($msg !=null && $msg!=""){
					$re['stats']="error";
					$re['time']=$t;
					$re['message']=$msg;
					echo json_encode ($re);	
					die();
				}
				else {
					$re['stats']="ok";
					$re['time']=$t;
					$re['message']="";
					echo json_encode ($re);	
				}			
				break;
				
		}
	}
	
	/*
	 * umcompress zlib content into $_REQUEST system variable
	 */
	protected function checkZlibStr(){
		
		// $rawInput format : appid=xx&uid=xx&json_var=xx	
		if( isset($_SERVER['HTTP_CONTENT_ENCODINGg']) && $_SERVER['HTTP_CONTENT_ENCODING'] == 'zlib' )
		{
			$urlZlibStirng = file_get_contents('php://input');
			$inflated = gzuncompress($urlZlibStirng,2048);
			
			$zlibArr = explode( '&', $inflated);
			foreach ( $zlibArr as $field ){										
				// require 2 elements for each field
				$pos = strpos($field, '=' );	
					
				if( $pos === FALSE )
					continue;
				if( $pos >= 1 ) 
					$a = substr( $field, 0, $pos );
				else 
					continue;
				
				if(  $pos < (strlen($field)-1) )
					$b = substr( $field, $pos+1 );
				else 
					continue;
					
				$_REQUEST[$a] = $b;	
			}			
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
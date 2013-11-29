<?php
/**
 * 往一个文件里面写log
 * @param string $file
 * @param string $msg
 */
//include_once '../config.inc.php';

function ea_write_log($file,$msg){
	$re = file_put_contents($file,$msg,FILE_APPEND);
	if($re === false){
		if(!is_dir(dirname($file))){
			mkdir(dirname($file),0777,true);
			file_put_contents($file,$msg,FILE_APPEND);;
		}
	}
}

function exit_with_error($msg){
	die ("$msg");
}

function start_with($str,$prefix){
	$length = strlen($prefix);
	return (substr($str,0,$length)===$prefix);
}

/**
 * 获取milli-second单位的时间戳
 * @return number milli-second 精度的时间戳
 */
function millisec(){
	return round(microtime(true) * 1000);
}

/**
 * 将一个timestamp标准化为13位的毫秒timestamp
 * @param number $timestamp 输入的timestamp
 * @return number 输出的timestamp，或者null
 */
function to_millisec(&$timestamp){
	if($timestamp == null){
		return null;
	}
	$length=strlen(strval($timestamp));
	if($length==10){
		$timestamp = intval(strval($timestamp)."000");
	}
	else if($length==13){
		$timestamp = intval($timestamp);
	}
	else {
		$timestamp = millisec();
	}
	return $timestamp;
}


function change_timestamp(&$timestamp){
	if($timestamp == null){
		return null;
	}
	$length=count(strval($timestamp));
	if($length==10){
		return intval(strval($timestamp)."000");
	}
	else if($length==13){
		return intval($timestamp);
	}
	else return millisec();
}

/*
 * 使脚本支持命令行获取参数
 */
function get_params($argc,$argv,$_REQUEST)
{
	if ($argc > 0)
	{
	  for ($i=1;$i < $argc;$i++)
	  {
	  	// for debug
	    parse_str($argv[$i],$tmp);
	    $_REQUEST = array_merge($_REQUEST, $tmp);
	  }
	}
	return $_REQUEST;
}

/**
 * 获取用户真实 IP
 */
function getIP()
{
        static $realip;
        if (isset($_SERVER)){
                if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"],"unkown") != 0){
                	$proxy_ips = split(',', $_SERVER["HTTP_X_FORWARDED_FOR"]);
                        $realip = trim($proxy_ips[0]);
                } else if (isset($_SERVER["HTTP_CLIENT_IP"]) && strcasecmp($_SERVER["HTTP_CLIENT_IP"],"unkown")!= 0) {
                        $realip = $_SERVER["HTTP_CLIENT_IP"];
                } else {
                        $realip = $_SERVER["REMOTE_ADDR"];
                }
        } else {
                if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"),"unkown") != 0){
                	$proxy_ips = split(',', getenv("HTTP_X_FORWARDED_FOR"));
                	$realip = trim($proxy_ips[0]);                      
                } else if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unkown") != 0) {
                        $realip = getenv("HTTP_CLIENT_IP");
                } else {
                        $realip = getenv("REMOTE_ADDR");
                }
        }

		return ip2long($realip) ;        
}






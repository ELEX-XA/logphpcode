<?php
class Signature {
	/*
	 * some bug for mapping appid 's validate
	 */
	static private $key="Xya_bwXcNnfOxLPkriAKHBbMRpGsSb7hiZjCTtsK9JvtJC1Mp1ooh3KtJy4FknNsK3hXPqqN4Q3ohnStSS05MqMl9Ucojx5rkardRuYwfc38NDDbnJgjGZ3IBWhIGQjw";
	static public function validate($_REQUEST){
		if(!isset($_REQUEST['timestamp']) || !isset($_REQUEST["token"]))
		{echo "error:no timestamp or no token";return false;}
		$nowtime=time();
		if($nowtime-intval($_REQUEST['timestamp'])>300) {echo "error:overtime";return false;}
		ksort($_REQUEST);
		foreach($_REQUEST as $key =>$value){
			if($key!="token") $allstring=$allstring.$value;
		}
		$allstring=$allstring.Signature::$key;
		if(md5($allstring)==$_REQUEST['token']) return true;
		else {echo "error:token is worng";return false;}
	}
	
	static public function create_validate($params){
		foreach($params as $key =>$value){
			if($key!="token") $allstring=$allstring.$value;
		}
		$allstring=$allstring.Signature::$key;
		$params['token']=md5($allstring);
		return $params;
	}
}

?>
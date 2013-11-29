<?php
class NetManager{
	static public function post_http_request($url,$params){
		$context=array();
		if(is_array($params)){
			ksort($params);
			$context['http']=array(
			'timeout'=>'60',
			'method'=>'POST',
			'content'=>http_build_query($params,'','&'),
			);
		}
		return file_get_contents($url,false,stream_context_create($context));
	}
	
	static public function get_http_request($url,$params){
		$context=array();
		$context['http']=array(
		'timeout'=>'60',
		'method'=>'GET',
		'header'=>"haha:abcd\r\r"
		);
		$url=$url.'?'.http_build_query($params,'','&');
		echo $url." ";
		return file_get_contents($url,false,stream_context_create($context));
	}
	static public function socket_post_http_request($url,$params,&$return){
		self::socket_http_request($url, 'POST', $params,$return);
	}
	static public function socket_get_http_request($url,$params,&$return){
		self::socket_http_request($url, 'GET', $params,$return);
	}
	static public function socket_http_request($url,$method,$params,&$return){
		$re=parse_url($url);
		$params=http_build_query($params,'',"&");
		if(!isset($re['port'])){
			$re['port']=80;
		}
		$fp=fsockopen($re['host'],$re['port'],$errno,$errstr,30);
		if(!$fp){
			return null;
		}
		$out=$method." ".$re['path']." HTTP/1.1\r\n";
		$out.="Host:".$re['host']."\r\n";
		$out.="Connection:Close\r\n";
		$out.="Content-Length:".strlen($params)."\r\n";
		
		$out.="\r\n".$params."\r\n";
		fwrite($fp, $out);
		
		
		if($return!=null){
		 	while (!feof($fp)){
		  		$return.=fgets($fp,128);
		  	}
		}
		fclose($fp);

	}
}

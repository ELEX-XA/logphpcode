<?php
function gdpUidHook($uid){
	if(strlen($uid)!=32){
		return $uid;
	}
	if(preg_match('/^0*(FB|AA)(\d+)$/', $uid,$matches)){
		$pf = $matches[1];
		$id = $matches[2];
		
		switch ($pf){
			case 'FB':
				return $id;
			case 'AA':
				return $id;
			default:
				return $uid;
		}
	}else{
		return $uid;
	}
}

function test(){
	//true
	echo "<br>\n".(gdpUidHook('00000000000000000000FB1766662133'));
	echo "<br>\n".(gdpUidHook('00000000000000000000FB17666621331'));
	echo "<br>\n".(gdpUidHook('000000000000000000FB17666621331'));
	echo "<br>\n".(gdpUidHook('10000000000000000000FB1766662133'));
	echo "<br>\n".(gdpUidHook('00000000000000000001FB1766662133'));
	//true
	echo "<br>\n".(gdpUidHook('00000000000000000000AA1766662133'));
	echo "<br>\n".(gdpUidHook('00000000000000000000XX1766662133'));
}
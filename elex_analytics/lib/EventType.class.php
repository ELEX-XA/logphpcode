<?php
class EventType{
	static public $indexlog_event=array(
	"user.visit"=>1,
	"user.update"=>1,
	"user.increment"=>1,
	"user.login"=>1,//...
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
<?php
class DateManager{
	static public function dis(&$date1,&$date2){
		$t1=strtotime($date1);
		$t2=strtotime($date2);
		return round(($t1-$t2)/(24*3600));
	}
	static public function dayCal($date,$i){
		return date("Y-m-d",strtotime("$i day $date"));
	}	
	static public function listAllDate($start_date,$end_date){
		$re=array();
		$nowdate=$start_date;
		do{
			$re[$nowdate]=1;
		}while(DateManager::dis($nowdate, $end_date)<=0);
		return $re;
	}
	static private function getWeek($date){
		$i=date("w",strtotime($date));
		$s=self::dayCal($date, -$i);
		$e=self::dayCal($date, 6-$i);
		return $s." to ".$e;
	}
	static private function getMonth($date){
		list($year,$month,$day)=explode("-", $date);
		return $year."-".$month;
	}
	static public function getPeriod($date,$period){
		if($period=="weekly") return DateManager::getWeek($date);
		else if($period=="monthly") return DateManager::getMonth($date);
	}
	static public function compare($a,$b){
		return strcmp($a,$b);
	}
	static public function nextPeriod($now,$period){
		if($period=='weekly'){
			$date=substr($now, 0,10);
			return self::getWeek(self::dayCal($date, 7));
		}
		else if($period=='monthly'){
			$date=$now."-02";
			$nowtime=strtotime($date);
			$newtime=strtotime("+1 month ",$nowtime);
			$newdate=date("Y-m-d",$newtime);
			return self::getMonth($newdate);
		}
	}
	static public function getIndex($timestamp=null){
		if($timestamp==null)$timestamp=time();
		$hour=date('H');
		$minute=intval(date('i'));
		$index=intval($hour)*12+(int)($minute/5);
		return $index;
	}
	static public function getDay($i){
		$today=date("Y-m-d");
		return date("Y-m-d",strtotime("$i day $today"));
	}
}
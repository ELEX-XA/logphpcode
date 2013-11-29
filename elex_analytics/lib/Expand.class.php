<?php
class Expand{
	static public function expandPay($log_file){
		if($_REQUEST['event']=="pay.complete"){
//			echo "into expand";
			$json=json_decode($_REQUEST['json_var'],true);
			if(isset($json['vamount'])){
//				echo "into write";
				$logmsg["signedParams"]['sns_uid']=$_REQUEST['uid'];
				$logmsg["signedParams"]['appid']=$_REQUEST['appid'];
				$logmsg["stats"][0]['statfunction']="count";
				$logmsg["stats"][0]['timestamp']=time();
				$logmsg["stats"][0]['data'][0]="xapay";
				$logmsg["stats"][0]['data'][1]=$json['vamount'];
				$logmsg["stats"][0]['data'][2]="";
				$logmsg["stats"][0]['data'][3]="";
				$logmsg["stats"][0]['data'][4]="";
				$logmsg["stats"][0]['data'][5]="";
				$logmsg["stats"][0]['data'][6]=1;
//				echo json_encode($logmsg);
				ea_write_log($log_file,json_encode($logmsg)."\n");
			}
		}
	}
}
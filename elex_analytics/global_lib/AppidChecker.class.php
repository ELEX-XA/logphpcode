<?php 
/**
 * 对Appid合法性的检查。
 * 
 * @author immars
 */
class AppidChecker{
	
	/**
	 * @deprecated
	 * v1接口的Appid的合法性。
	 * 所谓Appid的合法性：即除了 $white_map 中所列出来的白名单外，
	 * 符合xxxx@yyy_zzz的形式。这个形式是来自于GDP发布的要求：
	 * xxx代表平台ID，yyy代表平台ID（如facebook），zzz代表语言和地区
	 */
	/*[^a-zA-Z0-9_@.\-]*/
	static function check($appid){
		if(isset(self::$white_map[$appid])) return true;
		else if(substr($appid,0,11)=="xingyuntest") return true;
		else if(preg_match("/(^[a-zA-Z0-9])([a-zA-Z0-9_.\-]*)@([a-zA-Z0-9_.\-]+)_([a-zA-Z0-9_.\-]+$)/", $appid))return true;
		else return false;	
	}
	
	/**
	 * v3,v4接口的appid的合法性。
	 * 只要不包含奇怪的字符就是合法的。
	 * @param string $appid
	 */
	public static function checkV3(&$appid) {
		if(strpos($appid, "hayday")===0){
			$appid="happyfarm@elex337_en_1.0";
			return true;
		}else if(isset(self::$white_map[$appid])) return true;
		else if(substr($appid,0,11)=="xingyuntest") return true;
		else if(preg_match("/(^[a-zA-Z0-9])([a-zA-Z0-9_.\-@]*$)/", $appid)){
			return true;	
		}
		else return false;
	}
	
	static private $white_map=array(	
		"ranchfacebook"=>1,
		"pigorkut"=>1,
		"farmorkut_en"=>1,
		"farmfacebook_ml"=>1,
		"farmsonico_1"=>1,
		"farmfacebook_Arab"=>1,
		"farmmeinvz"=>1,
		"myislandmeinvz"=>1,
		"farmfacebook_de"=>1,
		"farmhyves_1"=>1,
		"pigmeinvz"=>1,
		"farmfacebook_pt"=>1,
		"mhd"=>1,
		"farmnk"=>1,
		"hospital_meinvz"=>1,
		"football_orkut"=>1,
		"dzpk_orkut"=>1,
		"dzpk_english"=>1,
		"dzpk_fanti"=>1,
		"farm_facebook_tw"=>1,
		"farmfacebook_tr"=>1,
		"farm3_meinvz"=>1,
		"football_meinvz"=>1,
		"mhc_meinvz"=>1,
		"mdxd_meinvz"=>1,
		"kldz_meinvz"=>1,
		"farmlokalisten"=>1,
		"hospital_orkut"=>1,
		"farm3_facebook_de"=>1,
		"football_facebook_de"=>1,
		"farm3_facebook_tw_test"=>1,
		"fish_nk"=>1,
		"farmfacebook_th_1"=>1,
		"farmfacebook_th_test"=>1,
		"dzpk_meinvz"=>1,
		"casino_meinvz"=>1,
		"casino_facebook"=>1,
		"plantmeinvz"=>1,
		"myisland_lokalisten"=>1,
		"mhc_lokalisten"=>1,
		"football_lokalisten"=>1,
		"pig_lokalisten"=>1,
		"farm3_lokalisten"=>1,
		"pandora_test"=>1,
		"pignl"=>1,
		"pignk"=>1,
		"football_orkut_pt"=>1,
		"hospital_lokalisten"=>1,
		"mhc_orkut_pt"=>1,
		"farm3_orkut_pt"=>1,
		"plant_facebook"=>1,
		"cafelife_orkut_pt"=>1,
		"swjy_orkut"=>1,
		"friendtd"=>1,
		"cafelife"=>1,
		"farmfacebook_nl"=>1,
		"cafelife_meinvz_de"=>1,
		"mhc_yahoo"=>1,
		"zooworld"=>1,
		"magicflower_vz"=>1,
		"hitower"=>1,
		"zzzw_orkut_pt"=>1,
		"elex337"=>1,
		"ibibo"=>1,
		"test"=>1,
		"farmfacebook_arab"=>1,
		"farmmixi"=>1,
		"farmzingme"=>1,
		"qq"=>1,
	);
}

//$file=fopen("/home/qus/newappid", "r");
//while($line=fgets($file)){
//	$appid=substr($line, 0,strlen($line)-1);
//	if(AppidChecker::check($appid)) {//echo "ok".$appid."\n";
//	}
//	else echo $appid."\n";
//}




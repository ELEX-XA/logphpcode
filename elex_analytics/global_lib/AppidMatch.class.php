<?php
/**
 * 特殊处理一些Appid：
 * 将 'rome0@elex337_pt_1' 转化为 'rome0@337_pt_1'。
 * 历史遗留问题。
 * @author immars
 *
 */
class AppidMatch{
	static private $appid_map=array(
	'rome0@elex337_pt_1'=>'rome0@337_pt_1',
	'rome0@orkupt_pt_2'=>'rome0@elex337_pt_2',
	'rome0@orkupt_pt_1'=>'rome0@337_pt_1',
	'hl@elex337_pt_1'=>'hl@337_pt_1',
	'kszl@elex337_de_1'=>'kszl@337_de_s1',
	'kszl@elex337_pt_4'=>'kszl@337_pt_s4',
	'kszl@elex337_tr_1'=>'kszl@337_tr_s1',
	'tencent-18894@facebook_tw'=>'tencent-18894',
	'age@337_en_andriod.global.s2'=>'age@337_en_android.global.s2',
	
	'age@337_en_andriod.global.s1'=>'age@337_en_android.global.s1',
	'age@337_en_android.s1'=>'age@337_en_andriod.s1',
	'age@337_en_android.s2'=>'age@337_en_andriod.s2',
	'age@337_en_android.s3'=>'age@337_en_andriod.s3',
	'age@337_en_android.s4'=>'age@337_en_andriod.s4',
	'age@337_en_android.s5'=>'age@337_en_andriod.s5',
	'age@337_en_android.s6'=>'age@337_en_andriod.s6',
	'age@337_en_android.s7'=>'age@337_en_andriod.s7',
	'age@337_en_android.s8'=>'age@337_en_andriod.s8',
	'age@337_en_android.s9'=>'age@337_en_andriod.s9',
	'age@337_en_android.s10'=>'age@337_en_andriod.s10',
	);
	static public function match($appid){
		if(isset(AppidMatch::$appid_map[$appid]))return AppidMatch::$appid_map[$appid];
		else return $appid;
	}
}
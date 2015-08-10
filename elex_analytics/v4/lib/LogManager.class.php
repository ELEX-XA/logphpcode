<?php
class LogManager{
	private $ea_start_time;
	private $appidArr;
	function __construct(){
		$this->ea_start_time=microtime(true);
		$this->appidArr = array(
		    "newtabv3-bg","newtabv1-bg","extended-protection","focalpricetest1","sof-hpprotect",
            "v9-sof","v9-mdd-zs","sof-gl","qone8search","sof-hpnt","citylife","fishman","v9-fft-2","v9-fft",
            "picatowntest","zoom","qone8-search","lollygame","sa2-single","sof-app-wallpaper","sof-pc","v9-fft-1",
            "v9-bnd","v9-ins","defender","goe","v9-gzg","v9-iob","v9-gdp","snsnations","v9-vei","v9-slb","wushen-ko",
            "foreststory-local","v9-idg","reputations","sof-dealply","twpoker","tw","v9-ss","v9-mlv","tencent-100619972",
            "sof-asc","sof-fxt","sof-mb","v9-mdd-jl","v9-referral","v9-vlt","appstudo","ep-404pages","justfortest","v9-wnf",
            "safev9","ramweb","pls","m-appstudo","search-switch","softmgr","bomcelular","lea","sof-youtv","omiga-search",
            "v9-gp","leawo","delta","tj","v9-ind","mobilewallpaper","v9-avc-1","v9-avc","v9-v9tb","searchwidget","v9-ism",
            "dinokingdom","v9-smk","v9-net","v9-umz-2","v9-nps","priceangels2","elexmarket","v9-v9speed","v9-mdd-lh","v9-trnd",
            "newtab-iframe","bookmark-v9","msdk-aoe","hdzdy","xiaoxiaoyedian","zhuguan","v9-iob-1","v9-gls","pirateking","mhc",
            "tencent-16488","zzzw","crazytribe","boyapoker","rxzq","v9-dvi","efunfunsm4","coalaatexaspoker","yhzg","v9-klt",
            "ponyland","ks","v9-muh","v9-ssrks","v9-imb","v9-jet","pmxy","v9-idd","v9-v9sc","v9-atr","civwar-integrate","rs",
            "mohuanhuayuan","magic-gardener","hl","v9-bdl","longjiang","v9-wbp","v9-ascbxk","v9-js","v9-imm","haidao",
            "pirates-defender","sydhold","v9-rek","v9-afp","v9-kw","v9-sssmz","v9-lgn","v9-kin","limingdiguo","v9-mdg",
            "v9-umz","chuanshuo","casino","card","qlj","v9-sfp","cafelife","ddtschool","dzpk","nindou","gotgp","mdd-search-widget",
            "v9-vlt2","v9-slbnew","apptoolsserver","gs","v9-see","v9-stk","v9-hanp","farmerama","yitien","apptools-appstudo","darkorbit",
            "sof-plushd","v9-dsk","v9-edep","tencent-30209","yulong","v9-nsk","v9-mdd-sk","nizhuantianxiakrad","sof-costmin","v9-birp",
            "v9-sstbf","v9-kb","v9-amt","v9-kts","v9-lwaviguanwang","xiyouqzhuan","v9-fog","v9-dnk","v9-v9sm","sof-yy","feiquanqiu",
            "v9-tti","soft-alo","sof-50onred","tradetang2","happytreasure","v9-gtl","tencent-30050","ofm","v9-durp","v9-fxtbxk","sftx",
            "sof-getsavin","ddtschoolorkut","sof-gc","romemanor","v9-vip","fragorial1","v9-smt","sof-cut","mhdxd","v9-adk","v9-rks",
            "v9-vkfp","ecyber-ph","sof-fsab","nizhuantianxiakr","battledawn","v9-mdd-gtl","dtzl2","rummikub","v9-test2","v9-ssfds","sa2-online",
            "v9-mrm","v9-veo","sof-ls","doom","kaku","v9-vdl","sinohotel","onmylike-mdg","lc","v9-ishp","v9-adc","v9-lgb","rifthunter",
            "footballidentity","v9-ish","v9-umz-1","find-sof","v9-ktl","v9-rdlgb","v9-asca5","v9-dsk4sh","v9-atv","zx","cloudunion","r","v9-utt",
            "v9-ssybc","moshi","v9-adwordsgoplayer","v9-hitb","sof-kp","yiqifei","sof-yys","v9-indprt","camelgames","mw2","sa2-test","v9-vsb","v9-mdd-aoe",
            "v9-rdops","sof-fil","yyptsite","ecyber-lanucherwnd","chaos-age","v9-8th","v9-ssrjc","tencent-100630156","v9-abc","v9-mdd-searchapk",
            "fb-get-test","dhgate1","v9-w3i","jdbbx","v9-hipp","v9-vtt","v9-ascadt2","v9-ascadt3","v9-hipb","v9-mdd-91","baby","sof-msn","v9-idg2new",
            "tencent-100623395","onmylike-jdl","msnshell3","dinodirect","v9-rjc","tencent-19089","v9-ssnet","lumosity-demo","xtreeme","skg","focalprice",
            "cuteplant","foxit1","v9-asdguanwang","v9-mcguanwang","sof-mel","fish-hero","lj","qudongrensheng","v9-fxtzig","v9-asc","v9-asq","tag-zhou",
            "v9-vltnew","hop2","livemall","v9-asclgn","shangwang","fish","mdd","milan","v9-tek","v9-retp","v9-epo","kingnetshushan","test0005","manortest",
            "tidebuy","v9-mti","cufflinks","v9-ssf46","sof-js","longjiang-dajiangjun","madeinchina","v9-ascutd","ewin88","v9-ascspg","v9-avs","sof-ckt",
            "minilyrics","xc-rsc","elongdemo","tencent-100635540","v9-ascc02","v9-rdrd","eachbuyer","v9-test1","xlfc-3","xlfc-4","v9-mizp","v9-ssmrm",
            "sof-llq","longjiangnn","v9-melzig","v9-melguanwang","v9-vcs","tradetang","v9-ascbxe","v9-kpguanwang","sof-hpp-asc","v9-sinp","test-common-ml",
            "v9-sszig","v9-utd","yoybuy1","yellowearth","sof-itl","xlfc-2","v9-mel","v9-test3","ppt-assistant","v9-ascstp","v9-ascvlt","v9-idgnew","ddtest",
            "sof-hpp","v9-ssrek","yourlydia","ems360","v9-sssmk","v9-ssfdl","v9-ade","v9-sssmt","v9-ssb10","testing","tencent-33983","v9-ssbxe",
            "qiudechao-site","pirate","v9-ascsmk","jojo207","v9-ssa1","v9-imi","v9-vizp","v9-aschai","ecyber-tr","v9-ismc","db-monitor","smzt","v9-ssedl",
            "studentuniverse","esupin","asdf","bowenshangcheng","v9-ilk","v9-mdd-webpage","v9-bsh","v9-mrl","tupianzhuanhuan","v9-ssfid","qqv3doc",
            "find-find","cetetek","ec21","v9-sshfd","v9-ascc10","v9-ssgls","sof-mc","v9-ascpzg","v9-ioy","v9-maip","efunfuntest","v9-dskguanwang","fluege",
            "v9-kdl","v9-asczig","yy-helpcenter","tencent-18271","everytide","v9-adwordsnurse","nowec","tencent-100624412","smartreversi","dreammail",
            "tencent-34650","elexv9nurse","v9-ssb7","v9-ssb6","v9-ssb5","test2","v9-ssb3","v9-ssb1","easyhadoop","v9-ssb9","v9-ssb8","ctrip-demo",
            "v9-mdd-ajzs","v9-fxtguanwang","bnb2b","cloud-cloud","v9-ascnur","v9-ssb4","ucenter","mltest1","pokerstarswebsite","jike","dealextreme",
            "v9-asc4sh","dssynctest","v9-4sh","v9-sskts","v9-rdguanwang","v9-ascidd","software","tencent-10152","longjiangn","yoygou1","swjy","v9-sdg",
            "sof-asd","v9-fxta1","v9-fxta3","v9-fxta5","landing","dresses1","v9-ibr","v9-ssadn","v9-ascedl","jcsportline","onmylike-onmylike","ecyber",
            "mokredit","en-ec21","qqdownload","v9-dtn","v9-ssctv","v9-sshai","markettime","mltest","ku123","v9-fxtxzz","v9-ascnpg","susinobag","v9-ascnps",
            "v9-mdd","piratestorm","v9-search","v9-edeb","sof-ftchrome","savetome","sof-pcsm","s337","xiaomi","gt","mxhzw","sof-yontoo","hot-finder","mozca",
            "sof-iminent","fishao-de","candymahjong","letsfarm-dev","kkpoke21","yahoo","v9-ttinew","search-savetome","tw1","sof-yandex","dragonkokaoadreally",
            "arcadecenter","battlealertatapple","letsfarm","northeuropekr","v9-ssvyk","longzhizhaohuankoad","soft-twitter-assist","sof-jwallet","v9-mhc",
            "uf-pay","sof-ftchrome1","yes-test","solitaireduels","sof-lksav","raydownload","v9-vtnet","sof-iapps","hdtexaspoker","happ_1ch","nztx",
            "kd","sof-addlyrics","farm3_meinvz","iobit","fiwhman","sof-pcsuzk","s","v9-mtix","v9-rdm","yac-gdpdl","pt","sof-lol","iappyfarmer",
            "boyojoy","tcg","tibia","default-newtab","mhsj","kongregatetest","xing","mahjongduels","sof-pcf","hk","krlong","yac-updl","pgzs","zhongqinglv",
            "atest","ns","drakensang","menghuan","sof","sof-bp","xlfc-cbnc","monster","kongfu","xlfcmobile","longzhizhaohuan","minigarden","sz-eng","v9-tug",
            "govome","globososo","sof-newhpnt","lp","v9m","gbanner","ddt-ff"
		);
	}
	
//	const $_zlib_encode = "application/x-www-form-urlencoded";
	static private $instance = null;
	static public function singleton(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/*
	 * check all param
	 */
	function checkAllParams(){
		if(!isset($_REQUEST['appid']) || $_REQUEST['appid']=="") throw new Exception("appid is not set");
		if(in_array(strtolower($_REQUEST['appid']),$this->appidArr)) throw new Exception("appid invalid");
		if(!isset($_REQUEST['uid']) || $_REQUEST['uid']=="" ) throw new Exception("uid is not set");  
		$_REQUEST['appid']=strtolower($_REQUEST['appid']);  
	}
	function logEnter(){
		$this->checkAllParams();
	}
	function buildJson($type,$msg){
		$t=sprintf('%.2f ms',(microtime(true) - $this->ea_start_time) * 1000);
		switch($type){
			case 'php':
				$re['stats']="ok";
				$re['time']=$t;
				$re['message']=$msg;
				break;
			case 'error':
				$re['stats']="error";
				$re['time']=$t;
				$re['message']=$msg;
				break;
		}	
		return $re;
	}
	function changeType($type){
		if(isset($_REQUEST['callback']) && $_REQUEST['callback']!="" && $type!='error'){
			$type="jsonp";
		}
		if(isset($_SERVER['HTTP_ACCEPT']) && start_with($_SERVER['HTTP_ACCEPT'], "image") && $type!='error') {
			$type='img';	
		}
		if(isset($_REQUEST['img'])){
			$type='img';
		}
		return $type;
	}
	function logLeave($type,$msg){
		$re=$this->buildJson($type,$msg);
		$type=$this->changeType($type);
		/**
		 * 不同的返回类型，对应不同的客户端请求的情况。
		 * php 类型：适用于浏览器窗口直接测试；
		 * img 类型：适用于以 <img> 标签形式发起请求，类似google的方式；
		 * jsonp 类型：适用于以jsonp方式调用。
		 */
		switch($type){
			case 'php':
				//header("Content-type: application/json");
				header("Content-type: text/html");
				header("xa-api-version:v4");
				echo json_encode ($re);
				break;
			case 'img':
				header("Content-type: image/gif");
				header("Transfer-Encoding:identity");
				header("xa-api-version:v4");
				$str=pack("H*", "4749463839610100010080ff00ffffff0000002c00000000010001000002024401003b");
				$length=strlen($str);
				header("Content-Length:$length");
				echo $str;
				break;
			case 'error':
				header("xa-api-version:v4");
				
				echo json_encode ($re);
				break;
			case 'jsonp':
				header("xa-api-version:v4");
				header("Content-type: application/javascript");
				echo $_REQUEST['callback']."(".json_encode($re).");";
				break;
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
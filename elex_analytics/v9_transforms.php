<?php
function v9_transforms($appid,$ref){
	if($ref!=null){
		$temp=explode(";", $ref);
		if($temp[3]!=null) return "v9-".$temp[3];	 
	}
	return $appid;
}

function is_v9_ref($ref){
	$white_v9=array(	
		"sof"=>1,
		"tam"=>1,
		"fft"=>1,
		"gzg"=>1,
		"ins"=>1,
		"iob"=>1,
		"idg"=>1,
		"slb"=>1,
		"opc"=>1,
		"avc"=>1,
		"cor"=>1,
		"trnd"=>1,
		"ind"=>1,
		"vei"=>1,
		"ism"=>1,
		"v9tb"=>1,
		"pbr"=>1,
		"vlt"=>1,
		"gls"=>1,
		"bnd"=>1,
		"prs"=>1,
		"pbc"=>1,
		"imb"=>1,
		"wnf"=>1,
		"js"=>1,
		"dvi"=>1,
		"oop"=>1,
		"idd"=>1,
		"nps"=>1,
		"sfp"=>1,
		"kb"=>1,
		"kw"=>1,
		"lgn"=>1,
		"nsk"=>1,
		"wbp"=>1,
		"oyi"=>1,
		"afp"=>1,
		"ktl"=>1,
		"W3i"=>1,
		"cda"=>1,
		"ishp"=>1,
		"fxt"=>1,
		"indprt"=>1,
		"v9sm"=>1,
		"bdl"=>1,
		"ish"=>1,
		"sdg"=>1,
		"ioy"=>1,
	//	"v9"=>1,
		"mdg"=>1
	);
	if(isset($white_v9[$ref])) return true;
	else return false;
}





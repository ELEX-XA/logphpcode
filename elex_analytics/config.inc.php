<?php
define('APP_ROOT',dirname(realpath(__FILE__)));
define('GLOBAL_LIB_ROOT',APP_ROOT.'/global_lib/');
define('LIB_ROOT_V2',APP_ROOT.'/lib/');
define('LIB_ROOT_V3',APP_ROOT.'/v3/lib');
define('LIB_ROOT_V4',APP_ROOT.'/v4/lib');
define('ROOT_V2',APP_ROOT.'/');
define('ROOT_V3',APP_ROOT.'/v3');
define('ROOT_V4',APP_ROOT.'/v4');
date_default_timezone_set("Asia/Shanghai");

require_once GLOBAL_LIB_ROOT . '/debug.class.php';
debug::close();
error_reporting(0);




<?php
/*analytic.337.com/123456/index.gif?appid=XX&&log={xxx}
 * 
 * or
 * 
 *analytic.337.com/123456/static.gif?appid=XX&&log={xxx}
 * 
 *save base log
 *set the XA_LOG_RETURN_TYPE
 *and run the index.php
 */
define('XA_LOG_SAVE_TYPE', "index");
define('XA_LOG_RETURN_TYPE', "img");

//echo "v2";
include_once './config.inc.php';
require_once ROOT_V2.'/main.php';
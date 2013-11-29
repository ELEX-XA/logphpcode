<?php
/*analytic.337.com/storelog.php?appid=XX&&log={xxx}
 * save storelog
 *
 *set the XA_LOG_RETURN_TYPE and XA_LOG_SAVE_TYPE
 *and run the main.php
 */
define('XA_LOG_SAVE_TYPE', "storelog");
define('XA_LOG_RETURN_TYPE', "php");

include_once './config.inc.php';
require_once ROOT_V2.'/main.php';